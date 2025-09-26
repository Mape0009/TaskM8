<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-left">
            <span>© Mercantec 2025</span>
        </div>
        <nav class="footer-nav" aria-label="Footer">
            <a href="{{ url('/privatlivspolitik') }}">Privatlivspolitik</a>
            <a href="{{ url('/cookiepolitik') }}">Cookiepolitik</a>
            <a href="{{ url('/vilkar') }}">Vilkår</a>
        </nav>
    </div>
    <style>
        .site-footer{border-top:1px solid rgba(255,255,255,0.08);background:var(--color-bg, #0b1220);color:var(--color-text, #e5e7eb);padding:20px 24px;margin-top:40px;transition:padding-bottom .25s ease}
        .footer-container{max-width:1100px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:16px}
        .footer-nav a{color:var(--color-text-secondary, #9ca3af);text-decoration:none;margin-left:16px;transition:color .2s ease}
        .footer-nav a:hover{color:var(--color-text, #e5e7eb)}
        @media (max-width:640px){.footer-container{flex-direction:column;align-items:flex-start}.footer-nav a{margin:8px 12px 0 0}}
        .cookie-open .site-footer{padding-bottom:84px}
    </style>
</footer>

{{-- Cookie Consent Popup --}}
<div id="cookie-consent" class="cookie-consent" aria-live="polite" aria-hidden="true">
    <div class="cookie-card" role="dialog" aria-modal="true" aria-labelledby="cookie-title" aria-describedby="cookie-desc" tabindex="-1">
        <div class="cookie-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M15 3a6 6 0 0 1-6 6 6 6 0 1 0 6-6zM7 14a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm4 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm5-3a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
        </div>
        <div class="cookie-content">
            <h3 id="cookie-title">Cookies</h3>
            <p id="cookie-desc">Vi bruger nødvendige cookies for at få siden til at fungere stabilt og sikkert. Klikker du "Accepter", gemmer vi et samtykke. Læs mere i vores <a href="{{ url('/cookiepolitik') }}">cookiepolitik</a>.</p>
        </div>
        <div class="cookie-actions">
            <button id="cookie-accept" class="cookie-btn" aria-label="Accepter cookies">
                <span class="btn-label">Accepter</span>
                <span class="btn-shimmer" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</div>
<style>
    .cookie-consent{position:fixed;inset:auto 0 20px 0;display:flex;justify-content:center;pointer-events:none;z-index:2147483647}
    .cookie-card{width:min(720px,92%);background:rgba(17,24,39,0.9);backdrop-filter:saturate(140%) blur(8px);border:1px solid rgba(255,255,255,0.08);border-radius:14px;box-shadow:0 10px 30px rgba(0,0,0,0.35);padding:16px 16px;display:flex;align-items:center;gap:16px;transform:translateY(24px) scale(0.98);opacity:0;transition:transform .45s cubic-bezier(.22,1,.36,1),opacity .35s ease;pointer-events:auto}
    .cookie-card.show{transform:translateY(0) scale(1);opacity:1}
    .cookie-icon{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,rgba(59,130,246,.25),rgba(124,58,237,.25));display:flex;align-items:center;justify-content:center;color:#93c5fd;box-shadow:inset 0 0 0 1px rgba(255,255,255,0.08)}
    .cookie-content{flex:1}
    .cookie-content h3{margin:0 0 6px 0;font-size:16px;color:#fff;letter-spacing:.2px}
    .cookie-content p{margin:0;color:#cbd5e1;line-height:1.55;font-size:14px}
    .cookie-content a{color:#93c5fd;text-decoration:underline}
    .cookie-actions{display:flex;gap:12px;align-items:center}
    .cookie-btn{position:relative;overflow:hidden;appearance:none;border:0;border-radius:10px;padding:10px 16px;background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;font-weight:700;cursor:pointer;box-shadow:0 6px 16px rgba(37,99,235,.35);transform:translateY(0);transition:transform .15s ease, box-shadow .15s ease}
    .cookie-btn:hover{transform:translateY(-1px);box-shadow:0 10px 22px rgba(37,99,235,.45)}
    .cookie-btn:active{transform:translateY(0)}
    .btn-shimmer{position:absolute;inset:0;background:linear-gradient(120deg,transparent,rgba(255,255,255,.25),transparent);transform:translateX(-100%);animation:shimmer 3.6s infinite}
    @keyframes shimmer{0%{transform:translateX(-100%)}60%{transform:translateX(200%)}100%{transform:translateX(200%)}}
    @media (max-width:640px){.cookie-card{flex-direction:column;align-items:flex-start}.cookie-actions{width:100%;justify-content:flex-end}}
</style>
<script>
    (function(){
        var STORAGE_KEY = 'tm8_cookie_consent_v1';
        var el = document.getElementById('cookie-consent');
        var btn = document.getElementById('cookie-accept');
        function hasConsent(){ try { return localStorage.getItem(STORAGE_KEY) === 'accepted'; } catch(e){ return document.cookie.indexOf('tm8_cc=1') !== -1; } }
        function setConsent(){
            try { localStorage.setItem(STORAGE_KEY,'accepted'); } catch(e){}
            try { document.cookie = 'tm8_cc=1; path=/; max-age=' + (60*60*24*365*2) + '; SameSite=Lax'; } catch(e){}
        }
        function show(){ if(!el) return; el.setAttribute('aria-hidden','false'); document.documentElement.classList.add('cookie-open'); requestAnimationFrame(function(){ var c = el.querySelector('.cookie-card'); if(c){ c.classList.add('show'); c.focus(); }}); }
        function hide(){ if(!el) return; var c = el.querySelector('.cookie-card'); if(c){ c.classList.remove('show'); } setTimeout(function(){ el.setAttribute('aria-hidden','true'); document.documentElement.classList.remove('cookie-open'); }, 300); }
        if(!hasConsent()) { show(); }
        if(btn){ btn.addEventListener('click', function(){ setConsent(); hide(); }); }
        document.addEventListener('keydown', function(e){ if(el && el.getAttribute('aria-hidden')==='false' && (e.key==='Enter' || e.key===' ')){ if(document.activeElement===btn){ btn.click(); } } });
    })();
</script>

