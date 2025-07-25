@component('mail::message')
# Annulation de votre rendez-vous

Bonjour {{ $donor->name }},

Nous vous informons que votre rendez-vous de don de sang prévu le **{{ $appointment->appointment_date->format('d/m/Y à H:i') }}** a été **annulé**.

@if($reason)
> **Raison de l'annulation :**<br>
> {{ $reason }}
@endif

@if($appointment->campaign)
**Lieu :** {{ $appointment->campaign->location }}
**Campagne :** {{ $appointment->campaign->name }}
@else
**Centre :** {{ $appointment->center->name ?? 'Centre principal' }}
@endif

N’hésitez pas à reprendre rendez-vous sur notre plateforme dès que possible.

@component('mail::button', ['url' => url('/')])
Prendre un nouveau rendez-vous
@endcomponent

Merci de votre compréhension,
L’équipe LifeSaver
@endcomponent
