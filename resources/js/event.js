// Count down to start time
function initCountdown() {
    var el = document.getElementById('countdown');
    if (!el) return;
    var iso = el.getAttribute('data-start');
    if (!iso) return;
    var target = new Date(iso).getTime();
    function update() {
        var now = Date.now();
        var diff = Math.max(0, target - now);
        var d = Math.floor(diff / (1000 * 60 * 60 * 24));
        var h = Math.floor((diff / (1000 * 60 * 60)) % 24);
        var m = Math.floor((diff / (1000 * 60)) % 60);
        var s = Math.floor((diff / 1000) % 60);
        var set = function(id, v){ var n = document.getElementById(id); if (n) n.textContent = String(v).padStart(2,'0'); };
        set('cd-days', d); set('cd-hours', h); set('cd-mins', m); set('cd-secs', s);
    }
    update();
    setInterval(update, 1000);
}

// Share button using Web Share API with fallback
function initShare() {
    var btn = document.getElementById('share-btn');
    if (!btn) return;
    btn.addEventListener('click', function(){
        var data = { title: document.title, text: document.title, url: window.location.href };
        if (navigator.share) {
            navigator.share(data).catch(function(){});
        } else if (navigator.clipboard) {
            navigator.clipboard.writeText(window.location.href).then(function(){
                btn.textContent = 'Link kopieret';
                setTimeout(function(){ btn.textContent = 'Del'; }, 1500);
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function(){
    initCountdown();
    initShare();
});


