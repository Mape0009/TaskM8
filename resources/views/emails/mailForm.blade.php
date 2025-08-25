
<!DOCTYPE html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <title>TaskM8 Begivenhedsinvitation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background:#f2f4f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#202124;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f2f4f8; padding:0; margin:0;">
    <tr>
      <td align="center">
        <table width="680" cellpadding="0" cellspacing="0" border="0" style="max-width:680px; margin:40px auto; background:#fff; border-radius:16px; box-shadow:0 4px 40px rgba(0,0,0,0.07); overflow:hidden;">
          <tr>
            <td style="background:linear-gradient(90deg,#1a73e8,#4a8df0); color:#fff; padding:40px 30px 30px 30px; text-align:center; border-radius:16px 16px 0 0;">
              <h1 style="font-size:26px; margin:0;">Du er inviteret!</h1>
              <p style="font-size:16px; margin:8px 0 0; opacity:0.9;">via TaskM8 – platformen til nem planlægning</p>
            </td>
          </tr>
          <tr>
            <td style="padding:32px;">
              <h2 style="font-size:20px; margin-bottom:12px; color:#1a73e8;">Begivenhedsdetaljer</h2>
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f9fbfe; border:1px solid #d2e3fc; border-left:6px solid #1a73e8; border-radius:12px; padding:20px; margin-bottom:24px;">
                <tr><td style="padding:20px;">
                  <p style="margin:6px 0; font-size:15px;"><strong style="color:#1a73e8;">Titel:</strong> {{ $event['title'] ?? '' }}</p>
                  <p style="margin:6px 0; font-size:15px;"><strong style="color:#1a73e8;">Tid:</strong> 
                    @if(!empty($event['time']))
                      {{ \Carbon\Carbon::parse($event['time'])->format('d. F Y · H:i') }}
                      @if(!empty($event['end_time']))
                        – {{ \Carbon\Carbon::parse($event['end_time'])->format('d. F Y · H:i') }}
                      @endif
                    @endif
                  </p>
                  <p style="margin:6px 0; font-size:15px;"><strong style="color:#1a73e8;">Lokation:</strong> {{ $event['location'] ?? '' }}</p>
                  <p style="margin:6px 0; font-size:15px;"><strong style="color:#1a73e8;">Beskrivelse:</strong> {{ $event['description'] ?? '' }}</p>
                  <p style="margin:6px 0; font-size:15px;"><strong style="color:#1a73e8;">Inviteret af:</strong> {{ $event['inviter_email'] ?? '' }}</p>
                </td></tr>
              </table>
              <h2 style="font-size:20px; margin-bottom:12px; color:#1a73e8;">Bekræft din deltagelse</h2>
              <p style="margin-bottom:16px;">For at deltage, skal du oprette en konto og bekræfte din identitet med den midlertidige PIN-kode nedenfor:</p>
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                <tr>
                  <td align="center" style="background:#e8f0fe; border-radius:10px; padding:20px; text-align:center; font-size:24px; font-weight:bold; color:#1a73e8; letter-spacing:4px; box-shadow:inset 0 0 0 1px #c3d3f5;">
                    {{ $event['pin_code'] ?? '' }}
                  </td>
                </tr>
              </table>
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center">
                    <a href="{{ $event['invite_url'] ?? (url('/signup') . '?email=' . urlencode($event['invite_email'] ?? '') . '&pin=' . urlencode($event['pin_code'] ?? '') . '&event=' . urlencode($event['id'] ?? '')) }}" style="display:inline-block; background:linear-gradient(to right,#1a73e8,#3b82f6); color:#fff !important; padding:14px 32px; border-radius:8px; font-weight:600; text-decoration:none; font-size:16px; box-shadow:0 3px 10px rgba(26,115,232,0.3); transition:background 0.2s ease, transform 0.2s ease;">➡️ Opret konto og deltag</a>
                  </td>
                </tr>
              </table>
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:20px;">
                <tr>
                  <td style="background:#f1f3f4; padding:16px; border-radius:10px; font-size:14px; color:#444; border:1px solid #dadce0;">
                    Du modtager denne e-mail, fordi en person har inviteret dig til en begivenhed via TaskM8.<br>
                    Hvis du ikke genkender invitationen, kan du trygt ignorere denne besked.
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="background:#f9fafa; padding:24px; font-size:13px; color:#888; text-align:center; border-top:1px solid #e0e0e0; border-radius:0 0 16px 16px;">
              TaskM8 · taskm8.socdata.dk
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>