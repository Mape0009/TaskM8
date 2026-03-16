<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-content">
            <nav class="footer-nav" aria-label="Footer Navigation">
                <a href="{{ url('/privatlivspolitik') }}" class="footer-link">Privatlivspolitik</a>
                <span class="footer-divider">·</span>
                <a href="{{ url('/cookiepolitik') }}" class="footer-link">Cookiepolitik</a>
                <span class="footer-divider">·</span>
                <a href="{{ url('/vilkar') }}" class="footer-link">Vilkår</a>
            </nav>
            <p class="footer-credit">© Mercantec 2026</p>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        border-top: 1px solid var(--footer-border, rgba(148,163,184,0.14));
        background: var(--footer-bg, rgba(15,23,42,0.3));
        padding: 16px 0;
        margin-top: 40px;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 32px;
    }

    .footer-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .footer-nav {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .footer-link {
        color: var(--footer-link, rgba(226,232,240,0.78));
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .footer-link:hover {
        color: var(--footer-link-hover, rgba(226,232,240,1));
    }

    .footer-divider {
        color: rgba(148,163,184,0.4);
        font-size: 12px;
    }

    .footer-credit {
        margin: 0;
        font-size: 13px;
        color: rgba(148,163,184,0.7);
        font-weight: 500;
    }

    /* Light Mode */
    body:not(.dark-mode),
    html:not(.dark-mode) {
        --footer-bg: rgba(245,247,251,0.5);
        --footer-border: rgba(15,23,42,0.08);
        --footer-link: rgba(15,23,42,0.72);
        --footer-link-hover: rgba(15,23,42,0.95);
    }

    body:not(.dark-mode) .footer-credit,
    html:not(.dark-mode) .footer-credit {
        color: rgba(15,23,42,0.56);
    }

    body:not(.dark-mode) .footer-divider,
    html:not(.dark-mode) .footer-divider {
        color: rgba(15,23,42,0.3);
    }

    /* Responsive */
    @media (max-width: 640px) {
        .footer-container {
            padding: 0 16px;
        }

        .footer-content {
            flex-direction: column;
            gap: 12px;
        }

        .footer-nav {
            gap: 8px;
        }

        .footer-link {
            font-size: 12px;
        }

        .footer-credit {
            font-size: 12px;
        }
    }

    .cookie-open .site-footer {
        padding-bottom: 20px;
    }
</style>



<!-- Cookie Consent Popup -->
<div id="cookie-consent" class="cookie-consent" aria-live="polite" aria-hidden="true" style="display:none;">
    <div class="cookie-card" role="dialog" aria-modal="true" aria-labelledby="cookie-title" aria-describedby="cookie-desc" tabindex="-1">
        <div class="cookie-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                <path d="M15 3a6 6 0 0 1-6 6 6 6 0 1 0 6-6zM7 14a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm4 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm5-3a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </svg>
        </div>
        <div class="cookie-content">
            <h3 id="cookie-title">Cookies</h3>
            <p id="cookie-desc">
                Vi bruger nødvendige cookies for at få siden til at fungere stabilt og sikkert.
                Klikker du "Accepter", gemmer vi et samtykke. Læs mere i vores
                <a href="{{ url('/cookiepolitik') }}">cookiepolitik</a>.
            </p>
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
.cookie-consent {
    position: fixed;
    inset: auto 0 20px 0;
    display: flex;
    justify-content: center;
    pointer-events: none;
    z-index: 700; /* notification level */
}
.cookie-consent[aria-hidden="false"] { pointer-events: auto; display: flex; }

.cookie-card {
    width: min(720px, 92%);
    background: rgba(17,24,39,0.9);
    backdrop-filter: saturate(140%) blur(8px);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    transform: translateY(24px) scale(0.98);
    opacity: 0;
    transition: transform .45s cubic-bezier(.22,1,.36,1), opacity .35s ease;
}
.cookie-card.show { transform: translateY(0) scale(1); opacity: 1; }

.cookie-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(59,130,246,.25), rgba(124,58,237,.25));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #93c5fd;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08);
}
.cookie-content { flex: 1; }
.cookie-content h3 { margin: 0 0 6px 0; font-size: 16px; color: #fff; letter-spacing: .2px; }
.cookie-content p { margin: 0; color: #cbd5e1; line-height: 1.55; font-size: 14px; }
.cookie-content a { color: #93c5fd; text-decoration: underline; }
.cookie-actions { display: flex; gap: 12px; align-items: center; }
.cookie-btn {
    position: relative; overflow: hidden; appearance: none; border: 0; border-radius: 10px;
    padding: 10px 16px; background: linear-gradient(135deg,#2563eb,#7c3aed); color: #fff; font-weight: 700;
    cursor: pointer; box-shadow: 0 6px 16px rgba(37,99,235,.35); transform: translateY(0);
    transition: transform .15s ease, box-shadow .15s ease;
}
.cookie-btn:hover { box-shadow: 0 10px 22px rgba(37,99,235,.45); }
.cookie-btn:active { }
.btn-shimmer { position: absolute; inset: 0; background: linear-gradient(120deg,transparent,rgba(255,255,255,.25),transparent); transform: translateX(-100%); animation: shimmer 3.6s infinite; }
@keyframes shimmer { 0%{transform:translateX(-100%)}60%{transform:translateX(200%)}100%{transform:translateX(200%)} }
@media (max-width:640px){ .cookie-card{flex-direction:column;align-items:flex-start} .cookie-actions{width:100%;justify-content:flex-end} }
</style>

<script>
(function(){
    const STORAGE_KEY = 'tm8_cookie_consent_v1';
    const el = document.getElementById('cookie-consent');
    const btn = document.getElementById('cookie-accept');

    function hasConsent() {
        try { return localStorage.getItem(STORAGE_KEY) === 'accepted'; } 
        catch(e) { return document.cookie.includes('tm8_cc=1'); }
    }

    function setConsent() {
        try { localStorage.setItem(STORAGE_KEY,'accepted'); } catch(e){}
        try { document.cookie = 'tm8_cc=1; path=/; max-age=' + (60*60*24*365*2) + '; SameSite=Lax'; } catch(e){}
    }

    function show() {
        if(!el) return;
        el.style.display = 'flex';
        el.setAttribute('aria-hidden','false');
        document.documentElement.classList.add('cookie-open');
        requestAnimationFrame(() => {
            const c = el.querySelector('.cookie-card');
            if(c){ c.classList.add('show'); c.focus(); }
        });
    }

    function hide() {
        if(!el) return;
        const c = el.querySelector('.cookie-card');
        if(c){ c.classList.remove('show'); }
        el.setAttribute('aria-hidden','true');
        el.style.display = 'none';
        document.documentElement.classList.remove('cookie-open');
    }

    if(!hasConsent()) { show(); }

    if(btn){
        btn.addEventListener('click', function(){
            setConsent();
            hide();
        });
    }

    document.addEventListener('keydown', function(e){
        if(el && el.getAttribute('aria-hidden')==='false' && (e.key==='Enter' || e.key===' ')){
            if(document.activeElement===btn){ btn.click(); }
        }
    });
})();
</script>
