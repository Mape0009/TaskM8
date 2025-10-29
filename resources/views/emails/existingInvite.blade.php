<!DOCTYPE html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invitation</title>
</head>
<body style="margin:0; padding:0; background:#f2f4f8; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; color:#202124;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f2f4f8; padding:0; margin:0;">
    <tr>
      <td align="center">
        <table width="680" cellpadding="0" cellspacing="0" border="0" style="max-width:680px; margin:40px auto; background:#fff; border-radius:16px; box-shadow:0 4px 40px rgba(0,0,0,0.07); overflow:hidden;">
          <tr>
            <td style="background:linear-gradient(90deg,#1a73e8,#4a8df0); color:#fff; padding:32px; text-align:center; border-radius:16px 16px 0 0;">
              <h1 style="font-size:26px; margin:0;">Du er inviteret til en begivenhed</h1>
              <p style="font-size:16px; margin:8px 0 0; opacity:0.9;">via TaskM8</p>
            </td>
          </tr>
          <tr>
            <td style="padding:28px;">
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f9fbfe; border:1px solid #d2e3fc; border-left:6px solid #1a73e8; border-radius:12px; margin-bottom:18px;">
                <tr><td style="padding:18px;">
                  <p style="margin:6px 0; font-size:15px;"><strong style="color:#1a73e8;">Titel:</strong> {{ $event['title'] ?? '' }}</p>
@if(!empty($event['time']))
  <p style="margin:6px 0; font-size:15px;">
    <strong style="color:#1a73e8;">Starttidspunkt:</strong> 
    {{ \Carbon\Carbon::parse($event['time'])->format('d. F Y · H:i') }}
  </p>
@endif

@if(!empty($event['end_time']))
  <p style="margin:6px 0; font-size:15px;">
    <strong style="color:#1a73e8;">Sluttidspunkt:</strong> 
    {{ \Carbon\Carbon::parse($event['end_time'])->format('d. F Y · H:i') }}
  </p>
@endif

                  </p>
                  <p style="margin:6px 0; font-size:15px;"><strong style="color:#1a73e8;">Lokation:</strong> {{ $event['location'] ?? '' }}</p>
                  <p style="margin:6px 0; font-size:15px;"><strong style="color:#1a73e8;">Beskrivelse:</strong> {{ $event['description'] ?? '' }}</p>
                </td></tr>
              </table>
              <p style="margin-bottom:16px;">Du er allerede oprettet i TaskM8, og er blevet tilføjet til begivenheden.<br>Du kan se og opdatere din deltagelse her:</p>
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center">
                    <a href="{{ url('/events/' . ($event['id'] ?? '')) }}" style="display:inline-block; background:linear-gradient(90deg,#1a73e8,#3b82f6); color:#fff !important; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600; font-size:16px;">Åbn begivenhed</a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>


