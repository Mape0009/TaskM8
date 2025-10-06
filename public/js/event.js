// Built file placeholder for environments not using Vite hot build
// This script mirrors resources/js/event.js functionality if copied during build
function initCountdown(){var e=document.getElementById("countdown");if(!e)return;var t=e.getAttribute("data-start");if(!t)return;var n=new Date(t).getTime();function i(){var e=Date.now(),t=Math.max(0,n-e),i=Math.floor(t/864e5),o=Math.floor(t/36e5%24),d=Math.floor(t/6e4%60),a=Math.floor(t/1e3%60);!function(e,t){var n=document.getElementById(e);n&&(n.textContent=String(t).padStart(2,"0"))}("cd-days",i),!function(e,t){var n=document.getElementById(e);n&&(n.textContent=String(t).padStart(2,"0"))}("cd-hours",o),!function(e,t){var n=document.getElementById(e);n&&(n.textContent=String(t).padStart(2,"0"))}("cd-mins",d),!function(e,t){var n=document.getElementById(e);n&&(n.textContent=String(t).padStart(2,"0"))}("cd-secs",a)}i(),setInterval(i,1e3)}
function initShare(){var e=document.getElementById("share-btn");e&&e.addEventListener("click",function(){var t={title:document.title,text:document.title,url:window.location.href};navigator.share?navigator.share(t).catch(function(){}):navigator.clipboard&&navigator.clipboard.writeText(window.location.href).then(function(){e.textContent="Link kopieret",setTimeout(function(){e.textContent="Del"},1500)})})}
document.addEventListener("DOMContentLoaded",function(){initShare()});


