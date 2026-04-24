document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('taskEditWizard');
  if (!form) return;

  var steps = Array.prototype.slice.call(form.querySelectorAll('.form-step'));
  var current = 0;

  var description = document.getElementById('description');
  var counter = document.getElementById('task-description-counter');

  var tipText = document.getElementById('task-edit-tip-text');
  var tipLabel = document.getElementById('task-edit-tip-label');
  var tipValue = document.getElementById('task-edit-tip-value');

  var tips = {
    0: {
      text: 'Et stærkt navn gør opgaven lettere at skimme i en travl liste.',
      label: 'Navn',
      value: 'Brug et konkret navn med handling og område'
    },
    1: {
      text: 'En god beskrivelse sparer spørgsmål senere i planlægningen.',
      label: 'Detaljer',
      value: 'Skriv forventet resultat og hvornår opgaven er løst'
    },
    2: {
      text: 'Tjek helheden én gang til, før du gemmer ændringerne.',
      label: 'Kvalitetstjek',
      value: 'Navn og beskrivelse skal passe sammen'
    }
  };

  function updateCounter() {
    if (!description || !counter) return;
    counter.textContent = (description.value || '').length + ' / 800';
  }

  function updateTip(stepIndex) {
    var tip = tips[stepIndex] || tips[0];
    if (tipText) tipText.textContent = tip.text;
    if (tipLabel) tipLabel.textContent = tip.label;
    if (tipValue) tipValue.textContent = tip.value;
  }

  function updateReview() {
    var setText = function (id, value) {
      var el = document.getElementById(id);
      if (!el) return;
      el.textContent = (value || '').trim() || '-';
    };

    setText('reviewTaskName', form.querySelector('#taskName') ? form.querySelector('#taskName').value : '');
    setText('reviewDescription', description ? description.value : '');
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
      var required = steps[current].querySelectorAll('[required]');
      for (var i = 0; i < required.length; i++) {
        if (!required[i].value) {
          required[i].focus();
          return;
        }
      }
      if (current < steps.length - 1) showStep(current + 1);
    }

    if (prev) {
      if (current > 0) showStep(current - 1);
    }
  });

  if (description) {
    description.addEventListener('input', function () {
      updateCounter();
      updateReview();
    });
  }

  form.addEventListener('input', updateReview);
  form.addEventListener('change', updateReview);

  updateCounter();
  showStep(0);
});
