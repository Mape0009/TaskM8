<!DOCTYPE html>
<html lang="da" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">
  <title>Begivenhedsinvitation</title>
  <style>
    @media only screen and (max-width: 640px) {
      .outer-pad { padding: 12px !important; }
      .hero-pad { padding: 26px 20px 22px !important; }
      .content-pad { padding: 20px 16px 22px !important; }
      .title { font-size: 38px !important; line-height: 1.08 !important; }
      .subtitle { font-size: 20px !important; }
      .event-title { font-size: 30px !important; }
      .event-line { font-size: 18px !important; }
      .button-link { width: 100% !important; box-sizing: border-box !important; text-align: center !important; }
    }

    @media (prefers-color-scheme: dark) {
      body, .email-bg { background: #101621 !important; }
      .card { background: #171f2d !important; border-color: #2f3e59 !important; }
      .hero-title, .hero-subtitle, .body-text, .event-title, .event-line { color: #e8eefb !important; -webkit-text-fill-color: #e8eefb !important; }
      .section-label { color: #9bc0ff !important; -webkit-text-fill-color: #9bc0ff !important; }
      .event-box { background: #1c2739 !important; border-color: #42597f !important; }
      .footer { background: #141d2a !important; color: #aebdd8 !important; }
    }

    [data-ogsc] .event-box,
    [data-ogsb] .event-box {
      background: #1c2739 !important;
      border-color: #42597f !important;
    }

    [data-ogsc] .event-title,
    [data-ogsc] .event-line,
    [data-ogsc] .body-text,
    [data-ogsb] .event-title,
    [data-ogsb] .event-line,
    [data-ogsb] .body-text {
      color: #e8eefb !important;
      -webkit-text-fill-color: #e8eefb !important;
    }

    [data-ogsc] .section-label,
    [data-ogsb] .section-label {
      color: #9bc0ff !important;
      -webkit-text-fill-color: #9bc0ff !important;
    }
  </style>
</head>
<body class="email-bg" style="margin:0; padding:0; background:#ebeff5; font-family:'Segoe UI','Helvetica Neue',Arial,sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="email-bg" style="background:#ebeff5;">
    <tr>
      <td align="center" class="outer-pad" style="padding:22px;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:700px;">
          <tr>
            <td class="card" style="background:#ffffff; border:1px solid #d8e1ef; border-radius:18px; overflow:hidden;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:linear-gradient(130deg,#1f67d8 0%,#4a90ee 62%,#70b0ff 100%);">
                <tr>
                  <td align="center" class="hero-pad" style="padding:34px 30px 30px;">
                    <div style="width:90px; height:56px; line-height:56px; border-radius:14px; text-align:center; font-size:16px; font-weight:800; color:#ffffff; background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.34);">TASKM8</div>
                    <div class="hero-title title" style="margin-top:14px; font-size:30px; line-height:1.05; font-weight:650; color:#ffffff; -webkit-text-fill-color:#ffffff; letter-spacing:-0.8px;">Du er inviteret</div>
                    <div class="hero-subtitle subtitle" style="margin-top:10px; font-size:22px; line-height:1.35; color:#eef5ff; -webkit-text-fill-color:#eef5ff;">til en begivenhed via TaskM8</div>
                  </td>
                </tr>
              </table>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="content-pad" style="padding:26px 30px 28px;">
                    <div class="section-label" style="margin:0 0 12px; font-size:12px; font-weight:800; letter-spacing:1.1px; text-transform:uppercase; color:#2f71d9; -webkit-text-fill-color:#2f71d9;">Begivenhedsdetaljer</div>

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="event-box" style="background:#f3f7ff; border:1px solid #ccd9ef; border-left:4px solid #2f71d9; border-radius:14px;">
                      <tr>
                        <td style="padding:18px 18px 14px;">
                          <div class="event-title" style="margin:0 0 12px; font-size:25px; line-height:1.12; font-weight:650; color:#162233; -webkit-text-fill-color:#162233; letter-spacing:-0.5px;">{{ $event['title'] ?? 'Uden titel' }}</div>

                          @if(!empty($event['time']))
                            <p class="event-line" style="margin:0 0 10px; font-size:20px; line-height:1.35; color:#24344a; -webkit-text-fill-color:#24344a;">
                              <strong class="section-label" style="color:#2f71d9; -webkit-text-fill-color:#2f71d9;">Start:</strong>
                              {{ \Carbon\Carbon::parse($event['time'])->locale('da')->translatedFormat('d. F Y') }} kl. {{ \Carbon\Carbon::parse($event['time'])->format('H:i') }}
                            </p>
                          @endif

                          @if(!empty($event['end_time']))
                            <p class="event-line" style="margin:0 0 10px; font-size:20px; line-height:1.35; color:#24344a; -webkit-text-fill-color:#24344a;">
                              <strong class="section-label" style="color:#2f71d9; -webkit-text-fill-color:#2f71d9;">Slut:</strong>
                              {{ \Carbon\Carbon::parse($event['end_time'])->locale('da')->translatedFormat('d. F Y') }} kl. {{ \Carbon\Carbon::parse($event['end_time'])->format('H:i') }}
                            </p>
                          @endif

                          @if(!empty($event['location']))
                            <p class="event-line" style="margin:0 0 10px; font-size:20px; line-height:1.35; color:#24344a; -webkit-text-fill-color:#24344a;"><strong class="section-label" style="color:#2f71d9; -webkit-text-fill-color:#2f71d9;">Lokation:</strong> {{ $event['location'] ?? '' }}</p>
                          @endif

                          @if(!empty($event['description']))
                            <p class="event-line" style="margin:0; font-size:20px; line-height:1.35; color:#24344a; -webkit-text-fill-color:#24344a;"><strong class="section-label" style="color:#2f71d9; -webkit-text-fill-color:#2f71d9;">Beskrivelse:</strong> {{ $event['description'] ?? '' }}</p>
                          @endif
                        </td>
                      </tr>
                    </table>

                    <p class="body-text" style="margin:18px 0 18px; font-size:17px; line-height:1.55; color:#4b5d78; -webkit-text-fill-color:#4b5d78;">
                      Du er allerede registreret i TaskM8 og er blevet tilføjet til denne begivenhed. Du kan se detaljer og opdatere din deltagelse med det samme.
                    </p>

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td align="center">
                          <a href="{{ url('/events/' . ($event['id'] ?? '')) }}" class="button-link" style="display:inline-block; text-decoration:none; background:#2f71d9; color:#ffffff; -webkit-text-fill-color:#ffffff; font-size:18px; font-weight:800; padding:13px 26px; border-radius:999px;">Åbn begivenheden</a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="footer" align="center" style="padding:14px 20px 18px; font-size:13px; color:#697b96; border-top:1px solid #dde5f1; background:#f9fbff;">TaskM8 | Event Management Platform</td>
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
