<!DOCTYPE html>
<html lang="da" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">
  <title>Gruppeinvitation</title>
  <style>
    @media only screen and (max-width: 640px) {
      .outer-pad { padding: 12px !important; }
      .hero-pad { padding: 26px 20px 22px !important; }
      .content-pad { padding: 20px 16px 22px !important; }
      .title { font-size: 36px !important; line-height: 1.08 !important; }
      .subtitle { font-size: 20px !important; }
      .group-title { font-size: 30px !important; }
      .group-line { font-size: 18px !important; }
      .pin-code { font-size: 38px !important; letter-spacing: 4px !important; }
      .button-link { width: 100% !important; box-sizing: border-box !important; text-align: center !important; }
    }

    @media (prefers-color-scheme: dark) {
      body, .email-bg { background: #101621 !important; }
      .card { background: #171f2d !important; border-color: #2f3e59 !important; }
      .hero-title, .hero-subtitle, .body-text, .group-title, .group-line, .notice { color: #e8eefb !important; -webkit-text-fill-color: #e8eefb !important; }
      .section-label { color: #b8a7ff !important; -webkit-text-fill-color: #b8a7ff !important; }
      .group-box, .pin-box { background: #1c2739 !important; border-color: #42597f !important; }
      .footer { background: #141d2a !important; color: #aebdd8 !important; }
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
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:linear-gradient(130deg,#5f3dc4 0%,#7a57e6 60%,#9a79ff 100%);">
                <tr>
                  <td align="center" class="hero-pad" style="padding:34px 30px 30px;">
                    <div style="width:90px; height:56px; line-height:56px; border-radius:14px; text-align:center; font-size:16px; font-weight:800; color:#ffffff; background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.34);">TASKM8</div>
                    <div class="hero-title title" style="margin-top:14px; font-size:30px; line-height:1.05; font-weight:650; color:#ffffff; -webkit-text-fill-color:#ffffff; letter-spacing:-0.8px;">Du er inviteret</div>
                    <div class="hero-subtitle subtitle" style="margin-top:10px; font-size:22px; line-height:1.35; color:#f1ecff; -webkit-text-fill-color:#f1ecff;">til en gruppe via TaskM8</div>
                  </td>
                </tr>
              </table>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="content-pad" style="padding:26px 30px 28px;">
                    <div class="section-label" style="margin:0 0 12px; font-size:12px; font-weight:800; letter-spacing:1.1px; text-transform:uppercase; color:#6b46d2; -webkit-text-fill-color:#6b46d2;">Gruppedetaljer</div>

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="group-box" style="background:#f5f1ff; border:1px solid #ddd3ff; border-left:4px solid #6b46d2; border-radius:14px;">
                      <tr>
                        <td style="padding:18px 18px 14px;">
                          <div class="group-title" style="margin:0 0 10px; font-size:25px; line-height:1.12; font-weight:650; color:#162233; -webkit-text-fill-color:#162233; letter-spacing:-0.5px;">{{ $group['name'] ?? 'Ukendt gruppe' }}</div>

                          @if(!empty($group['description']))
                            <p class="group-line" style="margin:0; font-size:19px; line-height:1.35; color:#24344a; -webkit-text-fill-color:#24344a;">
                              <strong class="section-label" style="color:#6b46d2; -webkit-text-fill-color:#6b46d2;">Beskrivelse:</strong>
                              {{ $group['description'] ?? '' }}
                            </p>
                          @endif
                        </td>
                      </tr>
                    </table>

                    <div class="section-label" style="margin:18px 0 8px; font-size:12px; font-weight:800; letter-spacing:1.1px; text-transform:uppercase; color:#6b46d2; -webkit-text-fill-color:#6b46d2;">Opret konto og deltag</div>
                    <p class="body-text" style="margin:0 0 14px; font-size:17px; line-height:1.55; color:#4b5d78; -webkit-text-fill-color:#4b5d78;">
                      For at deltage i gruppen skal du oprette en TaskM8-konto. Brug pinkoden nedenfor under tilmelding.
                    </p>

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="pin-box" style="background:#f5f1ff; border:1px solid #ddd3ff; border-radius:14px; margin-bottom:18px;">
                      <tr>
                        <td align="center" style="padding:14px 16px 16px;">
                          <div class="section-label" style="font-size:12px; font-weight:800; letter-spacing:1px; text-transform:uppercase; color:#6b46d2; -webkit-text-fill-color:#6b46d2;">Din pinkode</div>
                          <div class="pin-code" style="margin-top:6px; font-size:50px; line-height:1; letter-spacing:6px; font-family:Consolas,'Courier New',monospace; font-weight:800; color:#5f3dc4; -webkit-text-fill-color:#5f3dc4;">{{ $group['pin_code'] ?? '0000' }}</div>
                          <div class="body-text" style="margin-top:8px; font-size:13px; color:#566a87; -webkit-text-fill-color:#566a87;">Indtast koden under tilmelding</div>
                        </td>
                      </tr>
                    </table>

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                      <tr>
                        <td align="center">
                          <a href="{{ $group['invite_url'] ?? url('/signup') }}" class="button-link" style="display:inline-block; text-decoration:none; background:#6b46d2; color:#ffffff; -webkit-text-fill-color:#ffffff; font-size:18px; font-weight:800; padding:13px 26px; border-radius:999px;">Opret konto og deltag i gruppen</a>
                        </td>
                      </tr>
                    </table>

                    <p class="notice" style="margin:0; font-size:13px; line-height:1.55; color:#5f718d; -webkit-text-fill-color:#5f718d;">
                      Du modtager denne e-mail, fordi en person har inviteret dig til en gruppe via TaskM8.
                    </p>
                  </td>
                </tr>
              </table>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="footer" align="center" style="padding:14px 20px 18px; font-size:13px; color:#697b96; border-top:1px solid #dde5f1; background:#f9fbff;">TaskM8 | Group Collaboration</td>
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
