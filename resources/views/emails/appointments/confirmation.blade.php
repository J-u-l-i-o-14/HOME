@component('mail::message')
# Confirmation de votre rendez-vous

Bonjour {{ $donor->name }},

Votre rendez-vous de don de sang a bien été **confirmé**.

**Date :** {{ $appointment->appointment_date->format('d/m/Y à H:i') }}

@if($appointment->campaign)
**Lieu :** {{ $appointment->campaign->location }}
**Campagne :** {{ $appointment->campaign->name }}
@else
**Centre :** {{ $appointment->center->name ?? 'Centre principal' }}
@endif

Merci pour votre engagement solidaire !

@component('mail::button', ['url' => url('/')])
Voir mon espace donneur
@endcomponent

Cordialement,
L’équipe LifeSaver
@endcomponent
