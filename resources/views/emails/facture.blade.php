<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:30px 0">
    <tr><td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.1)">

            <tr>
                <td style="background:#1d1d1d;padding:30px;text-align:center">
                    <h1 style="color:#e63946;margin:0;font-size:28px"> ISI BURGER</h1>
                    <p style="color:#aaa;margin:5px 0 0">Votre facture est disponible</p>
                </td>
            </tr>

            <tr>
                <td style="padding:30px">
                    <h2 style="color:#1d1d1d;margin-top:0">
                        Facture N° {{ $facture->numero }}
                    </h2>
                    <p style="color:#555;line-height:1.6">
                        Bonjour <strong>{{ $facture->paiement->commande->client->user->name }}</strong>,<br><br>
                        Votre commande <strong>#{{ $facture->paiement->commande->id }}</strong> a été payée avec succès.
                        Veuillez trouver votre facture en pièce jointe (PDF).
                    </p>

                    <div style="background:#f0fff4;border-left:4px solid #38a169;padding:15px;border-radius:4px;margin:20px 0">
                        <strong style="color:#2f855a">✅ Paiement confirmé</strong><br>
                        <span style="color:#555">
                            Montant : <strong>{{ number_format($facture->paiement->montant, 0, ',', ' ') }} FCFA</strong><br>
                            Date : {{ $facture->paiement->date_paiement->format('d/m/Y à H:i') }}
                        </span>
                    </div>
                </td>
            </tr>

            <tr>
                <td style="background:#f8f8f8;padding:20px;text-align:center;color:#999;font-size:12px">
                    Merci pour votre commande !  ISI BURGER – Dakar, Sénégal
                </td>
            </tr>
        </table>
    </td></tr>
</table>
</body>
</html>
