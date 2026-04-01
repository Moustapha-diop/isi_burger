<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:30px 0">
    <tr><td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.1)">

            {{-- Header --}}
            <tr>
                <td style="background:#1d1d1d;padding:30px;text-align:center">
                    <h1 style="color:#e63946;margin:0;font-size:28px">ISI BURGER</h1>
                    <p style="color:#aaa;margin:5px 0 0">Confirmation de commande</p>
                </td>
            </tr>

            {{-- Body --}}
            <tr>
                <td style="padding:30px">
                    <h2 style="color:#1d1d1d;margin-top:0">Bonjour {{ $commande->client->user->name }} </h2>
                    <p style="color:#555;line-height:1.6">
                        Nous avons bien reçu votre commande <strong>#{{ $commande->id }}</strong>.
                        Notre équipe la prépare avec soin. Vous recevrez une notification dès qu'elle sera prête.
                    </p>

                    {{-- Récapitulatif --}}
                    <table width="100%" style="border-collapse:collapse;margin:20px 0">
                        <thead>
                            <tr style="background:#1d1d1d;color:#fff">
                                <th style="padding:10px;text-align:left">Article</th>
                                <th style="padding:10px;text-align:center">Qté</th>
                                <th style="padding:10px;text-align:right">Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commande->ligneCommandes as $ligne)
                            <tr style="border-bottom:1px solid #eee">
                                <td style="padding:10px">{{ $ligne->burger->nom }}</td>
                                <td style="padding:10px;text-align:center">{{ $ligne->quantite }}</td>
                                <td style="padding:10px;text-align:right">{{ number_format($ligne->getSousTotal(), 0, ',', ' ') }} FCFA</td>
                            </tr>
                            @endforeach
                            <tr style="background:#fff3f3">
                                <td colspan="2" style="padding:12px;text-align:right;font-weight:bold">TOTAL</td>
                                <td style="padding:12px;text-align:right;font-weight:bold;color:#e63946;font-size:16px">
                                    {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="color:#777;font-size:13px">
                        Commande passée le : <strong>{{ $commande->date->format('d/m/Y à H:i') }}</strong>
                    </p>
                </td>
            </tr>

            {{-- Footer --}}
            <tr>
                <td style="background:#f8f8f8;padding:20px;text-align:center;color:#999;font-size:12px">
                    Merci de votre confiance ! ISI BURGER – Dakar, Sénégal
                </td>
            </tr>
        </table>
    </td></tr>
</table>
</body>
</html>
