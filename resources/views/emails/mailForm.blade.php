<!DOCTYPE html>
<html lang="da">
<head>
<meta charset="UTF-8">
<title>TaskM8 Begivenhedsinvitation</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    /* RESET & GLOBAL */
    body {
      margin: 0;
      padding: 0;
      background: #f2f4f8;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      color: #202124;
    }
 
    .container {
      max-width: 680px;
      margin: 40px auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 40px rgba(0, 0, 0, 0.07);
      overflow: hidden;
    }
 
    .header {
      background: linear-gradient(90deg, #1a73e8, #4a8df0);
      color: white;
      padding: 40px 30px 30px 30px;
      text-align: center;
    }
 
    .header h1 {
      font-size: 26px;
      margin: 0;
    }
 
    .header p {
      font-size: 16px;
      margin: 8px 0 0;
      opacity: 0.9;
    }
 
    .section {
      padding: 32px;
    }
 
    .section h2 {
      font-size: 20px;
      margin-bottom: 12px;
      color: #1a73e8;
    }
 
    .event-card {
      background: #f9fbfe;
      border: 1px solid #d2e3fc;
      border-left: 6px solid #1a73e8;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 24px;
    }
 
    .event-card p {
      margin: 6px 0;
      font-size: 15px;
    }
 
    .event-card p strong {
      color: #1a73e8;
    }
 
    .pin-block {
      background: #e8f0fe;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      color: #1a73e8;
      letter-spacing: 4px;
      box-shadow: inset 0 0 0 1px #c3d3f5;
      margin-bottom: 24px;
    }
 
    .btn {
      display: inline-block;
      background: linear-gradient(to right, #1a73e8, #3b82f6);
      color: white;
      padding: 14px 32px;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      font-size: 16px;
      box-shadow: 0 3px 10px rgba(26, 115, 232, 0.3);
      transition: background 0.2s ease, transform 0.2s ease;
    }
 
    .btn:hover {
      background: linear-gradient(to right, #3b82f6, #1a73e8);
      transform: scale(1.02);
    }

.btn,
.btn:link,
.btn:visited,
.btn:hover,
.btn:active {
  color: white !important;
  text-decoration: none;
}
 
    .info-box {
      background: #f1f3f4;
      padding: 16px;
      border-radius: 10px;
      font-size: 14px;
      color: #444;
      border: 1px solid #dadce0;
      margin-top: 20px;
    }
 
    .footer {
      background: #f9fafa;
      padding: 24px;
      font-size: 13px;
      color: #888;
      text-align: center;
      border-top: 1px solid #e0e0e0;
    }
 
    @media (max-width: 680px) {
      .section { padding: 24px; }
    }
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Du er inviteret!</h1>
            <p>via TaskM8 – platformen til nem planlægning</p>
        </div>
    
        <div class="section">
            <h2>Begivenhedsdetaljer</h2>
    
      <div class="event-card">
        <p><strong>Titel:</strong> {{ $event['title'] ?? '' }}</p>
        <p><strong>Tid:</strong> 
          @if(!empty($event['time']))
            {{ \Carbon\Carbon::parse($event['time'])->format('d. F Y · H:i') }}
            @if(!empty($event['end_time']))
              – {{ \Carbon\Carbon::parse($event['end_time'])->format('H:i') }}
            @endif
          @endif
        </p>
        <p><strong>Lokation:</strong> {{ $event['location'] ?? '' }}</p>
        <p><strong>Beskrivelse:</strong> {{ $event['description'] ?? '' }}</p>
        <p><strong>Inviteret af:</strong> {{ $event['inviter_email'] ?? '' }}</p>
      </div>
    
        <h2>Bekræft din deltagelse</h2>
    
        <p>For at deltage, skal du oprette en konto og bekræfte din identitet med den midlertidige PIN-kode nedenfor:</p>
    
        <div class="pin-block">
            493872
    </div>
    
        <p style="text-align:center;">
    <a href="https://taskm8.dk/opret" class="btn">➡️ Opret konto og deltag</a>
    </p>
    
        <div class="info-box">
            Du modtager denne e-mail, fordi en person har inviteret dig til en begivenhed via TaskM8.  
            Hvis du ikke genkender invitationen, kan du trygt ignorere denne besked.
    </div>
    </div>
    
        <div class="footer">
        TaskM8 · taskm8.socdata.dk
        </div>
    </div>
</body>
</html>