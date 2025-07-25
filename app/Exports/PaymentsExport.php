<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Payment::with('reservationRequest.user')
            ->get()
            ->map(function ($payment) {
                return [
                    'ID' => $payment->id,
                    'Réservation' => $payment->reservation_id,
                    'Utilisateur' => optional($payment->reservationRequest->user)->name,
                    'Montant' => $payment->amount,
                    'Méthode' => $payment->method,
                    'Transaction' => $payment->transaction_id,
                    'Statut' => $payment->status,
                    'Payé le' => optional($payment->paid_at)->format('Y-m-d H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Réservation',
            'Utilisateur',
            'Montant',
            'Méthode',
            'Transaction',
            'Statut',
            'Payé le',
        ];
    }
}
