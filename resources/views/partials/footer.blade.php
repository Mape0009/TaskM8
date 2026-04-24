<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-brand">
                <a href="{{ url('/om-os') }}" class="footer-about-link">Om os hos Mercantec</a>
                <p class="footer-tagline">TaskM8 planlægning og koordinering</p>
            </div>
            <nav class="footer-nav" aria-label="Politikker">
                <a href="{{ url('/privatlivspolitik') }}" class="footer-link">Privatlivspolitik</a>
                <a href="{{ url('/cookiepolitik') }}" class="footer-link">Cookiepolitik</a>
                <a href="{{ url('/vilkar') }}" class="footer-link">Vilkår</a>
            </nav>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        border-top: 1px solid var(--footer-border, #dbe1ea);
        background: var(--footer-bg, #f8fafc);
        padding: 14px 0;
        margin-top: 28px;
    }

    .footer-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 18px;
    }

    .footer-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .footer-brand {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .footer-about-link {
        color: var(--footer-link-hover, #1f2937);
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .footer-about-link:hover {
        text-decoration: none;
        opacity: 0.86;
    }

    .footer-tagline {
        margin: 0;
        color: var(--footer-link, #334155);
        font-size: 12px;
        font-weight: 500;
    }

    .footer-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .footer-link {
        color: var(--footer-link, #334155);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
        border: 1px solid var(--footer-chip-border, #dbe1ea);
        border-radius: 999px;
        padding: 5px 10px;
        line-height: 1.2;
        background: var(--footer-chip-bg, #ffffff);
    }

    .footer-link:hover {
        color: var(--footer-link-hover, #0f172a);
        text-decoration: none;
        border-color: var(--footer-chip-hover-border, #b8c4d9);
        background: var(--footer-chip-hover-bg, #f1f5f9);
    }

    body:not(.dark-mode),
    html:not(.dark-mode) {
        --footer-bg: #f8fafc;
        --footer-border: #dbe1ea;
        --footer-link: #334155;
        --footer-link-hover: #0f172a;
        --footer-chip-bg: #ffffff;
        --footer-chip-border: #dbe1ea;
        --footer-chip-hover-bg: #f1f5f9;
        --footer-chip-hover-border: #cbd5e1;
    }

    html.dark-mode .site-footer,
    body.dark-mode .site-footer {
        --footer-bg: #121722;
        --footer-border: #273043;
        --footer-link: #dbe2ef;
        --footer-link-hover: #ffffff;
        --footer-chip-bg: #182132;
        --footer-chip-border: #2a3853;
        --footer-chip-hover-bg: #1f2d47;
        --footer-chip-hover-border: #3a4d73;
    }

    @media (max-width: 640px) {
        .site-footer {
            padding: 8px 0;
            margin-top: 16px;
        }

        .footer-container {
            padding: 0 8px;
        }

        .footer-content {
            align-items: center;
            justify-content: space-between;
            gap: 6px;
        }

        .footer-brand {
            gap: 0;
        }

        .footer-about-link {
            font-size: 12px;
        }

        .footer-tagline {
            display: none;
        }

        .footer-nav {
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
            gap: 4px;
            width: auto;
        }

        .footer-link {
            font-size: 11.5px;
            display: inline-flex;
            width: auto;
            border-radius: 999px;
            padding: 4px 8px;
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
    background: #ffffff;
    border: 1px solid #dbe1ea;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.16);
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
    background: #eff6ff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1d4ed8;
    box-shadow: inset 0 0 0 1px #dbeafe;
}
.cookie-content { flex: 1; }
.cookie-content h3 { margin: 0 0 6px 0; font-size: 16px; color: #0f172a; letter-spacing: .2px; }
.cookie-content p { margin: 0; color: #334155; line-height: 1.55; font-size: 14px; }
.cookie-content a { color: #1d4ed8; text-decoration: underline; }
.cookie-actions { display: flex; gap: 12px; align-items: center; }
.cookie-btn {
    appearance: none;
    border: 1px solid #1d4ed8;
    border-radius: 10px;
    padding: 10px 16px;
    background: #1d4ed8;
    color: #fff;
    font-weight: 700;
    cursor: pointer;
    transition: transform .15s ease, box-shadow .15s ease;
}
.cookie-btn:hover { box-shadow: 0 8px 18px rgba(29, 78, 216, 0.32); }
.cookie-btn:active { }
.btn-shimmer { display: none; }
html.dark-mode .cookie-card,
body.dark-mode .cookie-card {
    background: #101828;
    border-color: #273043;
}
html.dark-mode .cookie-content h3,
body.dark-mode .cookie-content h3 {
    color: #f8fafc;
}
html.dark-mode .cookie-content p,
body.dark-mode .cookie-content p {
    color: #dbe2ef;
}
html.dark-mode .cookie-content a,
body.dark-mode .cookie-content a {
    color: #93c5fd;
}
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
