document.addEventListener('DOMContentLoaded', function(){
  var desc=document.getElementById('description');
  var counter=document.getElementById('desc-counter');
  function updateCounter(){ if(!desc||!counter) return; var len=(desc.value||'').length; counter.textContent=len+'/500'; }
  if(desc){ desc.addEventListener('input', updateCounter); updateCounter(); }

  var start=document.getElementById('startDate');
  var end=document.getElementById('endDate');
  function syncMin(){ if(!start||!end) return; if(start.value){ end.min=start.value; } }
  function validateOrder(){ if(!start||!end) return; if(start.value && end.value && end.value<start.value){ end.setCustomValidity('Slut skal være efter start'); } else { end.setCustomValidity(''); } }
  if(start){ start.addEventListener('change', function(){ syncMin(); validateOrder(); }); }
  if(end){ end.addEventListener('change', validateOrder); }

  var search=document.getElementById('user_search');
  var list=document.getElementById('user_list');
  var selected=document.getElementById('assignee-selected');
  function renderSelected(){ if(!selected||!list) return; selected.innerHTML=''; list.querySelectorAll('input[type="checkbox"]').forEach(function(cb){ if(cb.checked){ var label=cb.closest('label'); var name=label?label.querySelector('.assignee-name')||label.querySelector('span:last-child'):null; var tag=document.createElement('span'); tag.className='tag'; tag.textContent=(name?name.textContent:'Bruger'); var rm=document.createElement('span'); rm.className='rm'; rm.textContent='×'; rm.addEventListener('click', function(){ cb.checked=false; renderSelected(); }); tag.appendChild(rm); selected.appendChild(tag); } }); }
  if(list){ list.addEventListener('change', renderSelected); renderSelected(); }
  function filter(){ if(!search||!list) return; var q=(search.value||'').toLowerCase(); list.querySelectorAll('.assignee-item').forEach(function(el){ var n=el.getAttribute('data-name'); el.style.display=!q|| (n&&n.indexOf(q)!==-1)?'flex':'none'; }); }
  if(search){ search.addEventListener('input', filter); }
});

// Wizard controls and live review for Task create
document.addEventListener('DOMContentLoaded', function(){
  var form=document.getElementById('taskWizard');
  if(!form) return;
  var steps=Array.prototype.slice.call(form.querySelectorAll('.form-step'));
  var current=0;

  function showStep(i){ steps.forEach(function(s,idx){ s.hidden = idx!==i; }); current=i; updateReview(); }
  function updateReview(){
    var name=form.querySelector('#taskName');
    var desc=form.querySelector('#description');
    var set=function(id, val){ var el=document.getElementById(id); if(el) el.textContent = (val||'').trim()||'-'; };
    set('reviewTaskName', name?name.value:'');
    set('reviewDescription', desc?desc.value:'');
  }

  form.addEventListener('click', function(e){
    var next=e.target.closest('[data-next]');
    var prev=e.target.closest('[data-prev]');
    if(next){
      var required=steps[current].querySelectorAll('[required]');
      for(var i=0;i<required.length;i++){ if(!required[i].value){ required[i].focus(); return; } }
      if(current < steps.length-1) showStep(current+1);
    }
    if(prev){ if(current>0) showStep(current-1); }
  });

  ['input','change'].forEach(function(ev){ form.addEventListener(ev, updateReview); });

  // Press Enter on step 1 to go next instead of submitting
  form.addEventListener('keydown', function(e){
    var key=e.key||e.keyCode;
    if(key==='Enter' || key===13){
      var active = steps[current];
      var isTextInput = document.activeElement && ['INPUT','TEXTAREA'].includes(document.activeElement.tagName);
      if(active && active.getAttribute('data-step')==='1' && isTextInput){
        e.preventDefault();
        var required=active.querySelectorAll('[required]');
        for(var i=0;i<required.length;i++){ if(!required[i].value){ required[i].focus(); return; } }
        if(current < steps.length-1) showStep(current+1);
      }
    }
  });
  showStep(0);
});
