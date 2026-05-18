<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva solicitud de contacto - Kusay.pe</title>
</head>
<body style="margin:0;padding:24px;background:#f2f7f4;font-family:Arial,sans-serif;color:#183f30;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;margin:0 auto;background:#ffffff;border:1px solid #d5e3db;border-radius:14px;overflow:hidden;">
        <tr>
            <td style="padding:20px 22px;border-bottom:1px solid #e4eee8;background:linear-gradient(140deg,#eff7f2,#ffffff);">
                <a href="{{ config('app.url') }}" style="display:inline-block;text-decoration:none;">
                    <img src="{{ $logo_url }}" alt="Logo Kusay.pe" style="max-height:74px;width:auto;display:block;">
                </a>
            </td>
        </tr>

        <tr>
            <td style="padding:22px;">
                <h2 style="margin:0 0 12px;color:#163f30;font-size:22px;">Nueva solicitud de contacto</h2>
                <p style="margin:0 0 16px;color:#557667;line-height:1.55;">
                    Recibiste una nueva solicitud para una de tus propiedades publicadas.
                </p>

                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;background:#f8fcfa;font-weight:700;">Propiedad</td>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;">{{ $propiedad_titulo }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;background:#f8fcfa;font-weight:700;">Ubicacion</td>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;">{{ $ubicacion }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;background:#f8fcfa;font-weight:700;">Precio</td>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;">{{ $precio }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;background:#f8fcfa;font-weight:700;">Nombre</td>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;">{{ $contacto_nombre }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;background:#f8fcfa;font-weight:700;">Correo</td>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;">{{ $contacto_email }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;background:#f8fcfa;font-weight:700;">Telefono</td>
                        <td style="padding:10px 12px;border:1px solid #dbe7e1;">{{ $contacto_telefono }}</td>
                    </tr>
                </table>

                <div style="margin-top:16px;padding:12px 14px;border:1px solid #dbe7e1;border-radius:10px;background:#fbfefd;">
                    <p style="margin:0 0 8px;font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#648477;font-weight:700;">Mensaje</p>
                    <p style="margin:0;line-height:1.6;color:#254d3c;">{{ $contacto_mensaje }}</p>
                </div>

                <p style="margin:16px 0 0;color:#5f7f71;font-size:13px;">
                    Puedes responder directamente a este correo para contactar al interesado.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
