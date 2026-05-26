document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('eventEditWizard');
  if (!form) return;

  var steps = Array.prototype.slice.call(form.querySelectorAll('.form-step'));
  var current = 0;

  var description = document.getElementById('event-edit-description');
  var counter = document.getElementById('event-edit-description-counter');
  var startDate = document.getElementById('startDate');
  var endDate = document.getElementById('endDate');

  var tipText = document.getElementById('event-edit-tip-text');
  var tipLabel = document.getElementById('event-edit-tip-label');
  var tipValue = document.getElementById('event-edit-tip-value');

  var tips = window.eventEditTips || [];

  function updateCounter() {
    if (!description || !counter) return;
    counter.textContent = (description.value || '').length + ' / 800';
  }

  function updateTip(stepIndex) {
    var tip = tips[stepIndex] || tips[0] || {};
    if (tipText) tipText.textContent = tip.text || '';
    if (tipLabel) tipLabel.textContent = tip.label || '';
    if (tipValue) tipValue.textContent = tip.value || '';
  }

  function formatDateTime(value) {
    if (!value) return '-';
    var dt = new Date(value);
    if (isNaN(dt.getTime())) return value;
    return dt.toLocaleString(undefined, {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  }

  function updateReview() {
    var setText = function (id, value) {
      var el = document.getElementById(id);
      if (!el) return;
      el.textContent = (value || '').trim() || '-';
    };

    setText('reviewEventName', form.querySelector('#eventName') ? form.querySelector('#eventName').value : '');
    setText('reviewLocation', form.querySelector('#location') ? form.querySelector('#location').value : '');
    setText(
      'reviewSchedule',
      formatDateTime(startDate ? startDate.value : '') + ' → ' + formatDateTime(endDate ? endDate.value : '')
    );
    setText('reviewDescription', description ? description.value : '');
  }

  function validateStep(index) {
    var required = steps[index].querySelectorAll('[required]');
    for (var i = 0; i < required.length; i++) {
      if (!required[i].value) {
        required[i].focus();
        return false;
      }
    }

    if (index === 1 && startDate && endDate && startDate.value && endDate.value) {
      if (new Date(endDate.value) <= new Date(startDate.value)) {
        endDate.focus();
        return false;
      }
    }

    return true;
  }

  function showStep(index) {
    steps.forEach(function (step, idx) {
      step.hidden = idx !== index;
    });
    current = index;

    document.querySelectorAll('[data-step-indicator]').forEach(function (el, idx) {
      el.classList.toggle('is-active', idx === index);
      el.classList.toggle('is-done', idx < index);
    });

    updateTip(index);
    updateReview();
  }

  form.addEventListener('click', function (e) {
    var next = e.target.closest('[data-next]');
    var prev = e.target.closest('[data-prev]');

    if (next) {
      if (!validateStep(current)) return;
      if (current < steps.length - 1) showStep(current + 1);
    }

    if (prev) {
      if (current > 0) showStep(current - 1);
    }
  });

  if (description) {
    description.addEventListener('input', function () {
      if (description.value.length > 800) {
        description.value = description.value.slice(0, 800);
      }
      updateCounter();
      updateReview();
    });
  }

  if (startDate) {
    startDate.addEventListener('change', function () {
      var dt = new Date(this.value);
      if (!(dt instanceof Date) || isNaN(dt)) return;
      var plus1 = new Date(dt.getTime() + 60 * 60 * 1000);
      var pad = function (n) { return String(n).padStart(2, '0'); };
      var local =
        plus1.getFullYear() +
        '-' +
        pad(plus1.getMonth() + 1) +
        '-' +
        pad(plus1.getDate()) +
        'T' +
        pad(plus1.getHours()) +
        ':' +
        pad(plus1.getMinutes());
      if (!endDate.value || new Date(endDate.value) <= dt) {
        endDate.value = local;
      }
      updateReview();
    });
  }

  form.addEventListener('input', updateReview);
  form.addEventListener('change', updateReview);

  updateCounter();
  showStep(0);
});
