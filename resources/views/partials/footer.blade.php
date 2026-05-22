<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-brand">
                <a href="{{ url('/om-os') }}" class="footer-about-link">{{ __('ui.footer_about_link') }}</a>
                <p class="footer-tagline">{{ __('ui.footer_tagline') }}</p>
            </div>
            <nav class="footer-nav" aria-label="Politikker">
                <a href="{{ url('/privatlivspolitik') }}" class="footer-link">{{ __('ui.footer_privacy') }}</a>
                <a href="{{ url('/cookiepolitik') }}" class="footer-link">{{ __('ui.footer_cookie') }}</a>
                <a href="{{ url('/vilkar') }}" class="footer-link">{{ __('ui.footer_terms') }}</a>
            </nav>
        </div>
    </div>
</footer>

<div id="tm8-page-loader" class="tm8-page-loader" aria-hidden="true">
    <div class="tm8-page-loader__card" role="status" aria-live="polite" aria-label="{{ __('ui.footer_loader_label') }}">
        <div class="loading-wave" aria-hidden="true">
            <div class="loading-bar"></div>
            <div class="loading-bar"></div>
            <div class="loading-bar"></div>
            <div class="loading-bar"></div>
        </div>
        <h2 class="tm8-page-loader__title">{{ __('ui.footer_loader_title') }}</h2>
        <p class="tm8-page-loader__text">{{ __('ui.footer_loader_text') }}</p>
    </div>
</div>

<style>
    .site-footer {
        border-top: 1px solid var(--footer-border, #dbe1ea);
        background: #f5f7fb; 
        padding: 14px 0;
        margin-top: 28px;
    }

    html.dark-mode .site-footer,
    body.dark-mode .site-footer {
        background: radial-gradient(120% 120% at 0% 0%, rgba(30, 64, 175, 0.2) 0%, rgba(15, 23, 42, 0.9) 55%, rgba(2, 6, 23, 0.94) 100%);
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



@php
    $hideCookieConsent = request()->is('cookiepolitik') || request()->is('privatlivspolitik') || request()->is('vilkar');
@endphp

@unless($hideCookieConsent)
<!-- Cookie Consent Modal (centered, professional, accept-only) -->
<div id="cookie-consent" class="cookie-consent" aria-live="polite" aria-hidden="true" style="display:none;">
    <div class="cookie-overlay" id="cookie-overlay" aria-hidden="true"></div>
    <div class="card cookie-modal" role="dialog" aria-modal="true" aria-labelledby="cookie-title" aria-describedby="cookie-desc" tabindex="-1">
        <!-- Decorative cookie SVG (improved icon) -->
        <svg xml:space="preserve" viewBox="0 0 122.88 122.25" y="0px" x="0px" id="cookieSvg" width="84" height="84" aria-hidden="true"><g><path d="M101.77,49.38c2.09,3.1,4.37,5.11,6.86,5.78c2.45,0.66,5.32,0.06,8.7-2.01c1.36-0.84,3.14-0.41,3.97,0.95 c0.28,0.46,0.42,0.96,0.43,1.47c0.13,1.4,0.21,2.82,0.24,4.26c0.03,1.46,0.02,2.91-0.05,4.35h0v0c0,0.13-0.01,0.26-0.03,0.38 c-0.91,16.72-8.47,31.51-20,41.93c-11.55,10.44-27.06,16.49-43.82,15.69v0.01h0c-0.13,0-0.26-0.01-0.38-0.03 c-16.72-0.91-31.51-8.47-41.93-20C5.31,90.61-0.73,75.1,0.07,58.34H0.07v0c0-0.13,0.01-0.26,0.03-0.38 C1,41.22,8.81,26.35,20.57,15.87C32.34,5.37,48.09-0.73,64.85,0.07V0.07h0c1.6,0,2.89,1.29,2.89,2.89c0,0.4-0.08,0.78-0.23,1.12 c-1.17,3.81-1.25,7.34-0.27,10.14c0.89,2.54,2.7,4.51,5.41,5.52c1.44,0.54,2.2,2.1,1.74,3.55l0.01,0 c-1.83,5.89-1.87,11.08-0.52,15.26c0.82,2.53,2.14,4.69,3.88,6.4c1.74,1.72,3.9,3,6.39,3.78c4.04,1.26,8.94,1.18,14.31-0.55 C99.73,47.78,101.08,48.3,101.77,49.38L101.77,49.38z M59.28,57.86c2.77,0,5.01,2.24,5.01,5.01c0,2.77-2.24,5.01-5.01,5.01 c-2.77,0-5.01-2.24-5.01-5.01C54.27,60.1,56.52,57.86,59.28,57.86L59.28,57.86z M37.56,78.49c3.37,0,6.11,2.73,6.11,6.11 s-2.73,6.11-6.11,6.11s-6.11-2.73-6.11-6.11S34.18,78.49,37.56,78.49L37.56,78.49z M50.72,31.75c2.65,0,4.79,2.14,4.79,4.79 c0,2.65-2.14,4.79-4.79,4.79c-2.65,0-4.79-2.14-4.79-4.79C45.93,33.89,48.08,31.75,50.72,31.75L50.72,31.75z M119.3,32.4 c1.98,0,3.58,1.6,3.58,3.58c0,1.98-1.6,3.58-3.58,3.58s-3.58-1.6-3.58-3.58C115.71,34.01,117.32,32.4,119.3,32.4L119.3,32.4z M93.62,22.91c2.98,0,5.39,2.41,5.39,5.39c0,2.98-2.41,5.39-5.39,5.39c-2.98,0-5.39-2.41-5.39-5.39 C88.23,25.33,90.64,22.91,93.62,22.91L93.62,22.91z M97.79,0.59c3.19,0,5.78,2.59,5.78,5.78c0,3.19-2.59,5.78-5.78,5.78 c-3.19,0-5.78-2.59-5.78-5.78C92.02,3.17,94.6,0.59,97.79,0.59L97.79,0.59z M76.73,80.63c4.43,0,8.03,3.59,8.03,8.03 c0,4.43-3.59,8.03-8.03,8.03s-8.03-3.59-8.03-8.03C68.7,84.22,72.29,80.63,76.73,80.63L76.73,80.63z M31.91,46.78 c4.8,0,8.69,3.89,8.69,8.69c0,4.8-3.89,8.69-8.69,8.69s-8.69-3.89-8.69-8.69C23.22,50.68,27.11,46.78,31.91,46.78L31.91,46.78z M107.13,60.74c-3.39-0.91-6.35-3.14-8.95-6.48c-5.78,1.52-11.16,1.41-15.76-0.02c-3.37-1.05-6.32-2.81-8.71-5.18 c-2.39-2.37-4.21-5.32-5.32-8.75c-1.51-4.66-1.69-10.2-0.18-16.32c-3.1-1.8-5.25-4.53-6.42-7.88c-1.06-3.05-1.28-6.59-0.61-10.35 C47.27,5.95,34.3,11.36,24.41,20.18C13.74,29.69,6.66,43.15,5.84,58.29l0,0.05v0h0l-0.01,0.13v0C5.07,73.72,10.55,87.82,20.02,98.3 c9.44,10.44,22.84,17.29,38,18.10l0.05,0h0v0l0.13,0.01h0c15.24,0.77,29.35-4.71,39.83-14.19c10.44-9.44,17.29-22.84,18.10-38l0-0.05 v0h0l0.01-0.13v0c0.07-1.34,0.09-2.64,0.06-3.91C112.98,61.34,109.96,61.51,107.13,60.74L107.13,60.74z M116.15,64.04L116.15,64.04 L116.15,64.04L116.15,64.04z M58.21,116.42L58.21,116.42L58.21,116.42L58.21,116.42z"></path></g></svg>
        <div style="text-align:left; margin-left:6px; flex:1;">
            <h3 id="cookie-title" class="cookie-title">{{ __('ui.cookie_title') }}</h3>
            <p id="cookie-desc" class="cookie-desc">{!! str_replace(':policy', '<a href="'.url('/cookiepolitik').'">'.e(__('ui.footer_cookie')).'</a>', __('ui.cookie_desc')) !!}</p>
        </div>
        <div style="display:flex; flex-direction:column; gap:10px; align-items:center; margin-left:12px;">
            <button id="cookie-accept" class="accept-button" aria-label="{{ __('ui.cookie_accept') }}">{{ __('ui.cookie_accept') }}</button>
            <a href="{{ url('/cookiepolitik') }}" class="cookie-link" style="font-size:0.88rem; color:var(--accent,#6b46ff);">{{ __('ui.cookie_read_policy') }}</a>
        </div>
    </div>
</div>

<style>
/* Centered modal styles inspired by provided design, accept-only */
.cookie-consent { position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; z-index: 99999; }
.cookie-consent[aria-hidden="false"] { pointer-events: auto; }
.cookie-overlay { display: none; position: fixed; inset: 0; background: rgba(6,10,22,0.55); backdrop-filter: blur(4px); z-index: 99998; }
.cookie-consent[aria-hidden="false"] .cookie-overlay { display:block; }
.cookie-modal { position: relative; z-index: 99999; width: 360px; max-width: calc(100% - 32px); background: linear-gradient(180deg,#ffffff 0%, #fbfbff 100%); border-radius:12px; padding:22px; display:flex; align-items:center; gap:14px; box-shadow: 0 20px 50px rgba(2,6,23,0.28); border: 1px solid rgba(10,10,30,0.06); pointer-events: auto; transform: translateY(-8px) scale(.98); opacity:0; transition: transform .28s cubic-bezier(.22,1,.36,1), opacity .22s ease; }
.cookie-modal.show { transform: translateY(0) scale(1); opacity:1; }
.cookie-modal svg { flex-shrink:0; }
.accept-button { background: linear-gradient(180deg,#7b57ff 0%,#5b3df0 100%); color:#fff; border:none; padding:12px 20px; border-radius:999px; font-weight:800; cursor:pointer; box-shadow: 0 10px 30px rgba(91,61,240,0.2); transition: box-shadow .12s ease; }
.accept-button:hover { box-shadow: 0 10px 30px rgba(91,61,240,0.2); }
.cookie-link { color: #6b46ff; text-decoration:none; font-weight:700; }
.cookie-title { margin:0 0 6px 0; font-size:1.1rem; font-weight:800; color:#0f172a; }
.cookie-desc { margin:0; color:#475569; line-height:1.45; }
html.dark-mode .cookie-title,
body.dark-mode .cookie-title { color:#000000; }
html.dark-mode .cookie-desc,
body.dark-mode .cookie-desc { color:#000000; }
html.dark-mode .cookie-link,
body.dark-mode .cookie-link { color:#c4b5fd !important; }
@media (max-width:420px){
    .cookie-modal{
        width: calc(100% - 6px);
        max-width: calc(100% - 6px);
        margin: 0 22px;
        padding:16px;
        gap:10px;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .cookie-modal svg{
        width: 66px;
        height: 66px;
    }

    .cookie-modal > div[style*="flex:1"]{
        margin-left: 0 !important;
        width: 100%;
        text-align: center;
    }

    .cookie-modal > div[style*="flex-direction:column"]{
        margin-left: 0 !important;
        width: 100%;
        align-items: center;
    }

    .accept-button{
        width:100%;
    }

    .cookie-link{
        align-self: center;
    }
}
</style>

<script>
(function(){
    const STORAGE_KEY = 'tm8_cookie_consent_v1';
    const el = document.getElementById('cookie-consent');
    const btn = document.getElementById('cookie-accept');
    const overlay = document.getElementById('cookie-overlay');

    function hasConsent() { try { return localStorage.getItem(STORAGE_KEY) === 'accepted'; } catch(e){ return document.cookie.includes('tm8_cc=1'); } }
    function setConsent(){ try{ localStorage.setItem(STORAGE_KEY,'accepted'); }catch(e){} try{ document.cookie = 'tm8_cc=1; path=/; max-age=' + (60*60*24*365*2) + '; SameSite=Lax'; }catch(e){} }

    function show(){ if(!el) return; el.style.display='flex'; el.setAttribute('aria-hidden','false'); if(overlay) overlay.setAttribute('aria-hidden','false'); try{ document.body.style.overflow='hidden'; }catch(e){} const modal = el.querySelector('.cookie-modal'); requestAnimationFrame(()=>{ if(modal){ modal.classList.add('show'); modal.focus(); } }); }
    function hide(){ if(!el) return; const modal = el.querySelector('.cookie-modal'); if(modal){ modal.classList.remove('show'); } if(overlay) overlay.setAttribute('aria-hidden','true'); try{ document.body.style.overflow=''; }catch(e){} setTimeout(()=>{ el.setAttribute('aria-hidden','true'); el.style.display='none'; },220); }

    // DO NOT allow clicking overlay to dismiss
    if(overlay){ overlay.addEventListener('click', function(e){ e.stopPropagation(); }); }

    if(!hasConsent()){ show(); }

    if(btn){ btn.addEventListener('click', function(){ setConsent(); hide(); }); }

    // keyboard accessibility: allow Enter/Space to activate accept button when focused
    document.addEventListener('keydown', function(e){ if(el && el.getAttribute('aria-hidden')==='false'){ if((e.key==='Enter'||e.key===' ') && document.activeElement === btn){ e.preventDefault(); btn.click(); } if(e.key==='Escape'){ e.preventDefault(); } } });
})();
</script>
@endunless
