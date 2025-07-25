<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Campaign;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AppointmentTaken;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->is_donor) {
            $appointmentsQuery = $user->appointments()->with('campaign')->latest();
        } else if (in_array($user->role, ['admin', 'manager'])) {
            $appointmentsQuery = Appointment::with(['donor', 'campaign'])
                ->where('center_id', $user->center_id)
                ->latest();
        } else {
            $appointmentsQuery = Appointment::with(['donor', 'campaign'])->latest();
        }

        // Filtres dynamiques (exemple)
        if ($dateFrom = request('date_from')) {
            $appointmentsQuery->whereDate('scheduled_at', '>=', $dateFrom);
        }
        if ($dateTo = request('date_to')) {
            $appointmentsQuery->whereDate('scheduled_at', '<=', $dateTo);
        }
        if ($status = request('status')) {
            $appointmentsQuery->where('status', $status);
        }
        if ($type = request('type')) {
            $appointmentsQuery->where('type', $type);
        }

        $appointments = $appointmentsQuery->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $campaigns = Campaign::upcoming()->get();
        return view('appointments.create', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'appointment_date' => 'required|date|after:now',
            'type' => 'required|in:centre,campagne',
            'campaign_id' => 'required_if:type,campagne|exists:campaigns,id',
            'notes' => 'nullable|string|max:500',
        ]);
        $centerId = $user->center_id;
        $appointment = Appointment::create([
            'donor_id' => $user->id,
            'center_id' => $centerId,
            'appointment_date' => $request->appointment_date,
            'type' => $request->type,
            'campaign_id' => $request->type === 'campagne' ? $request->campaign_id : null,
            'notes' => $request->notes,
            'status' => 'planifie',
        ]);

        // Envoyer email de confirmation
        try {
            Mail::to(auth()->user()->email)->send(new AppointmentConfirmation($appointment));
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la création
            \Log::error('Erreur envoi email: ' . $e->getMessage());
        }

        // Notifier les managers du centre
        $managers = \App\Models\User::where('role', 'manager')
            ->where('center_id', $appointment->center_id)
            ->get();
        foreach ($managers as $manager) {
            \App\Models\Notification::create([
                'user_id' => $manager->id,
                'type' => 'appointment',
                'message' => 'Nouveau rendez-vous pris par ' . $user->name . ' pour le ' . $appointment->appointment_date . '.',
                'read' => false,
            ]);
        }
        return redirect()->route('appointments.index')
            ->with('success', 'Rendez-vous créé avec succès. Un email de confirmation vous a été envoyé.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $campaigns = Campaign::upcoming()->get();
        return view('appointments.edit', compact('appointment', 'campaigns'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $user = auth()->user();
        $request->validate([
            'appointment_date' => 'required|date|after:now',
            'type' => 'required|in:centre,campagne',
            'campaign_id' => 'required_if:type,campagne|exists:campaigns,id',
            'notes' => 'nullable|string|max:500',
            'status' => 'sometimes|in:planifie,confirme,complete,annule',
        ]);
        $centerId = $user->center_id;
        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'type' => $request->type,
            'campaign_id' => $request->type === 'campagne' ? $request->campaign_id : null,
            'notes' => $request->notes,
            'status' => $request->status ?? $appointment->status,
            'center_id' => $centerId,
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Rendez-vous mis à jour avec succès.');
    }

    public function destroy(Request $request, Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        
        $reason = $request->input('cancel_reason');
        $appointment->update(['status' => 'annule']);

        // Envoi de l'email d'annulation au donneur
        if ($appointment->donor && $appointment->donor->email) {
            \Mail::to($appointment->donor->email)
                ->send(new \App\Mail\AppointmentCancelled($appointment, $reason));
        }

        if(auth()->user() && in_array(auth()->user()->role, ['manager','admin','donor','donneur'])){
            return redirect()->route('appointments.index')->with('success', "Rendez-vous annulé avec succès. Un email d'annulation a été envoyé au donneur.");
        } else {
            return redirect('/')->with('success', "Rendez-vous annulé avec succès. Un email d'annulation a été envoyé au donneur.");
        }
    }

    public function confirm(Appointment $appointment)
    {
        $this->authorize('confirm', $appointment);

        $appointment->update([
            'status' => 'confirme',
            'confirmed_at' => now(),
        ]);

        // Envoi de l'email de confirmation au donneur
        if ($appointment->donor && $appointment->donor->email) {
            \Mail::to($appointment->donor->email)
                ->send(new \App\Mail\AppointmentConfirmation($appointment));
        }

        return redirect()->back()
            ->with('success', 'Rendez-vous confirmé avec succès. Un email de confirmation a été envoyé au donneur.');
    }

    // Formulaire public de prise de rendez-vous
    public function publicForm()
    {
        $campaigns = Campaign::all();
        return view('appointments.public', compact('campaigns'));
    }

    // Traitement du formulaire public
    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'campaign_id' => 'required|exists:campaigns,id',
            'appointment_date' => 'required|date|after:now',
        ]);

        // Création ou récupération de l'utilisateur
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            ['name' => $validated['name'], 'password' => bcrypt(Str::random(12))]
        );

        // Création du rendez-vous
        $appointment = Appointment::create([
            'donor_id' => $user->id,
            'appointment_date' => $validated['appointment_date'],
            'type' => 'campagne',
            'campaign_id' => $validated['campaign_id'],
            'status' => 'planifie',
        ]);

        // Notification des admins/managers
        Notification::send(User::role(['admin', 'manager'])->get(), new AppointmentTaken($appointment));

        return redirect()->route('login')->with('success', 'Votre rendez-vous est pris. Un compte a été créé, veuillez vérifier votre email.');
    }
}