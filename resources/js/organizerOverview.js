(function(){
    var input = document.getElementById('participant-search');
    var list = document.getElementById('participants');
    if (input && list) {
        input.addEventListener('input', function(){
            var q = (input.value || '').toLowerCase().trim();
            var items = list.querySelectorAll('.participant-card');
            items.forEach(function(item){
                var name = (item.getAttribute('data-name') || '');
                var email = (item.getAttribute('data-email') || '');
                var show = !q || name.indexOf(q) !== -1 || email.indexOf(q) !== -1;
                item.style.display = show ? '' : 'none';
            });
        });
    }

    document.querySelectorAll('.role-form').forEach(function(form){
        form.addEventListener('submit', function(e){
            var select = form.querySelector('select[name="eventRole"]');
            if (!select) return;
            var value = select.value;
            if (value === 'coOwner') {
                var ok = confirm('Er du sikker på, at du vil give Med-ejer-rollen? Med-ejere har udvidede rettigheder.');
                if (!ok) {
                    e.preventDefault();
                }
            }
        });
    });
})();