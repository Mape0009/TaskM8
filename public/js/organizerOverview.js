(function(){
    var input = document.getElementById('participant-search');
    var roleFilter = document.getElementById('role-filter');
    var list = document.getElementById('participants');
    
    function filterParticipants() {
        var q = (input.value || '').toLowerCase().trim();
        var role = (roleFilter.value || '');
        var items = list.querySelectorAll('.participant-card');
        items.forEach(function(item){
            var name = (item.getAttribute('data-name') || '');
            var email = (item.getAttribute('data-email') || '');
            var itemRole = (item.getAttribute('data-role') || '');
            
            var matchesSearch = !q || name.indexOf(q) !== -1 || email.indexOf(q) !== -1;
            var matchesRole = !role || itemRole === role;
            var show = matchesSearch && matchesRole;
            item.style.display = show ? '' : 'none';
        });
    }
    
    if (input) {
        input.addEventListener('input', filterParticipants);
    }
    if (roleFilter) {
        roleFilter.addEventListener('change', filterParticipants);
    }

    var modal = document.getElementById('coowner-confirm-modal');
    var confirmBtn = document.getElementById('coowner-confirm-btn');
    var cancelBtn = document.getElementById('coowner-cancel-btn');
    var pendingSubmitForm = null;

    function openModal() { if (modal) modal.style.display = 'flex'; }
    function closeModal() { if (modal) modal.style.display = 'none'; }

    if (cancelBtn) cancelBtn.addEventListener('click', function(){ closeModal(); pendingSubmitForm = null; });
    if (modal) modal.addEventListener('click', function(e){ if (e.target === modal) { closeModal(); pendingSubmitForm = null; } });
    if (confirmBtn) confirmBtn.addEventListener('click', function(){ if (pendingSubmitForm) { pendingSubmitForm.submit(); pendingSubmitForm = null; closeModal(); } });

    document.querySelectorAll('.role-form').forEach(function(form){
        form.addEventListener('submit', function(e){
            var select = form.querySelector('select[name="eventRole"]');
            if (!select) return;
            var value = select.value;
            if (value === 'coOwner') {
                e.preventDefault();
                pendingSubmitForm = form;
                openModal();
            }
        });
    });
})();

