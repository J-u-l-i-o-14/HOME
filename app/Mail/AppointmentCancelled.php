<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $donor;
    public $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, $reason = null)
    {
        $this->appointment = $appointment;
        $this->donor = $appointment->donor;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Annulation de votre rendez-vous de don de sang')
            ->markdown('emails.appointments.cancelled')
            ->with([
                'appointment' => $this->appointment,
                'donor' => $this->donor,
                'reason' => $this->reason,
            ]);
    }
}
