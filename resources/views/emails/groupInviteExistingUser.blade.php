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
      .button-link { width: 100% !important; box-sizing: border-box !important; text-align: center !important; }
    }

    @media (prefers-color-scheme: dark) {
      body, .email-bg { background: #101621 !important; }
      .card { background: #171f2d !important; border-color: #2f3e59 !important; }
      .hero-title, .hero-subtitle, .body-text, .group-title, .group-line, .notice { color: #e8eefb !important; -webkit-text-fill-color: #e8eefb !important; }
      .section-label { color: #8ce0d5 !important; -webkit-text-fill-color: #8ce0d5 !important; }
      .group-box { background: #1c2739 !important; border-color: #42597f !important; }
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
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:linear-gradient(130deg,#0d8a7d 0%,#19a89b 58%,#31c2b4 100%);">
                <tr>
                  <td align="center" class="hero-pad" style="padding:34px 30px 30px;">
                    <div style="width:90px; height:56px; line-height:56px; border-radius:14px; text-align:center; font-size:16px; font-weight:800; color:#ffffff; background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.34);">TASKM8</div>
                    <div class="hero-title title" style="margin-top:14px; font-size:30px; line-height:1.05; font-weight:650; color:#ffffff; -webkit-text-fill-color:#ffffff; letter-spacing:-0.8px;">Du er inviteret</div>
                    <div class="hero-subtitle subtitle" style="margin-top:10px; font-size:22px; line-height:1.35; color:#e9fffc; -webkit-text-fill-color:#e9fffc;">til en gruppe via TaskM8</div>
                  </td>
                </tr>
              </table>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="content-pad" style="padding:26px 30px 28px;">
                    <div class="section-label" style="margin:0 0 12px; font-size:12px; font-weight:800; letter-spacing:1.1px; text-transform:uppercase; color:#148f82; -webkit-text-fill-color:#148f82;">Gruppedetaljer</div>

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="group-box" style="background:#eefcf9; border:1px solid #ccebe5; border-left:4px solid #148f82; border-radius:14px;">
                      <tr>
                        <td style="padding:18px 18px 14px;">
                          <div class="group-title" style="margin:0 0 10px; font-size:25px; line-height:1.12; font-weight:650; color:#162233; -webkit-text-fill-color:#162233; letter-spacing:-0.5px;">{{ $group['name'] ?? 'Ukendt gruppe' }}</div>

                          @if(!empty($group['description']))
                            <p class="group-line" style="margin:0; font-size:19px; line-height:1.35; color:#24344a; -webkit-text-fill-color:#24344a;">
                              <strong class="section-label" style="color:#148f82; -webkit-text-fill-color:#148f82;">Beskrivelse:</strong>
                              {{ $group['description'] ?? '' }}
                            </p>
                          @endif
                        </td>
                      </tr>
                    </table>

                    <p class="body-text" style="margin:18px 0 18px; font-size:17px; line-height:1.55; color:#4b5d78; -webkit-text-fill-color:#4b5d78;">
                      Du er allerede registreret i TaskM8. Åbn gruppen for at se medlemmer og samarbejde med holdet med det samme.
                    </p>

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                      <tr>
                        <td align="center">
                          <a href="{{ $group['group_url'] ?? url('/groups/overview') }}" class="button-link" style="display:inline-block; text-decoration:none; background:#148f82; color:#ffffff; -webkit-text-fill-color:#ffffff; font-size:18px; font-weight:800; padding:13px 26px; border-radius:999px;">Åbn gruppen</a>
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
