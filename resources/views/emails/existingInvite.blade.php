<!DOCTYPE html>
<html lang="da">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invitation</title>
<style>
  body{margin:0;padding:0;background:#f2f4f8;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;color:#202124}
  .container{max-width:680px;margin:40px auto;background:#fff;border-radius:16px;box-shadow:0 4px 40px rgba(0,0,0,.07);overflow:hidden}
  .header{background:linear-gradient(90deg,#1a73e8,#4a8df0);color:#fff;padding:32px;text-align:center}
  .section{padding:28px}
  .event-card{background:#f9fbfe;border:1px solid #d2e3fc;border-left:6px solid #1a73e8;border-radius:12px;padding:18px;margin-bottom:18px}
  .btn{display:inline-block;background:linear-gradient(90deg,#1a73e8,#3b82f6);color:#fff !important;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600}
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Du er inviteret til en begivenhed</h1>
      <p>via TaskM8</p>
    </div>
    <div class="section">
      <div class="event-card">
        <p><strong>Titel:</strong> {{ $event['title'] ?? '' }}</p>
        <p><strong>Tid:</strong>
          @if(!empty($event['time']))
            {{ \Carbon\Carbon::parse($event['time'])->format('d. F Y · H:i') }}
            @if(!empty($event['end_time']))
              – {{ \Carbon\Carbon::parse($event['end_time'])->format('d. F Y · H:i') }}
            @endif
          @endif
        </p>
        <p><strong>Lokation:</strong> {{ $event['location'] ?? '' }}</p>
        <p><strong>Beskrivelse:</strong> {{ $event['description'] ?? '' }}</p>
        <p><strong>Inviteret af:</strong> {{ $event['inviter_email'] ?? '' }}</p>
      </div>
      <p>Du er allerede oprettet i TaskM8, og er blevet tilføjet til begivenheden.
         Du kan se og opdatere din deltagelse her:</p>
      <p style="text-align:center;">
        <a class="btn" href="{{ url('/events/' . ($event['id'] ?? '')) }}">Åbn begivenhed</a>
      </p>
    </div>
  </div>
</body>
</html>


