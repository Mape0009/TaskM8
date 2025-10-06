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


