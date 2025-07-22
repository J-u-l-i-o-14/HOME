<div style="font-family: Arial, sans-serif; font-size: 16px; color: #222;">
    <h2 style="color: #b91c1c;">Confirmation de votre réservation</h2>
    <p>Bonjour <strong>{{ $data['client_name'] }}</strong>,</p>
    <p>Votre réservation de sang a bien été enregistrée.</p>
    <ul>
        <li><strong>Centre :</strong> {{ $data['center'] }}</li>
        <li><strong>Groupe sanguin :</strong> {{ $data['blood_type'] }}</li>
        <li><strong>Quantité :</strong> {{ $data['quantity'] }} poche(s)</li>
        <li><strong>Montant total :</strong> {{ number_format($data['total'], 0, ',', ' ') }} F CFA</li>
        <li><strong>Montant payé :</strong> {{ number_format($data['paid'], 0, ',', ' ') }} F CFA</li>
        <li><strong>Moyen de paiement :</strong> {{ $data['payment_method'] }}</li>
    </ul>
    <p>Veuillez passez dans le plus bref delai (avant 72h).</p>
    <p>Merci pour votre confiance.<br>L'équipe du centre de sang</p>
</div> 