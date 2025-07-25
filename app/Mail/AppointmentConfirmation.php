<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $donor;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->donor = $appointment->donor;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmation de votre rendez-vous de don de sang')
            ->markdown('emails.appointments.confirmation')
            ->with([
                'appointment' => $this->appointment,
                'donor' => $this->donor,
            ]);
    }
}
