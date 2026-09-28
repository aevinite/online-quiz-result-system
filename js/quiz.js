/* =========================================================
   Quiz interaction (JavaScript)
   - Countdown timer with auto-submit
   - One-question-at-a-time navigation
   - Progress bar + selected-option highlight
   ========================================================= */
(function () {
  const form      = document.getElementById('quizForm');
  const blocks    = Array.from(document.querySelectorAll('.question-block'));
  const prevBtn   = document.getElementById('prevBtn');
  const nextBtn   = document.getElementById('nextBtn');
  const submitBtn = document.getElementById('submitBtn');
  const progress  = document.getElementById('progress');
  const timerEl   = document.getElementById('timer');

  if (!form || blocks.length === 0) return;

  let current = 0;
  const total = blocks.length;

  // ---- Show a specific question ----
  function showQuestion(index) {
    blocks.forEach((b, i) => b.classList.toggle('active', i === index));
    current = index;

    prevBtn.style.display   = index === 0 ? 'none' : 'inline-block';
    nextBtn.style.display   = index === total - 1 ? 'none' : 'inline-block';
    submitBtn.style.display = index === total - 1 ? 'inline-block' : 'none';

    progress.style.width = (((index + 1) / total) * 100) + '%';
  }

  nextBtn.addEventListener('click', () => {
    if (current < total - 1) showQuestion(current + 1);
  });
  prevBtn.addEventListener('click', () => {
    if (current > 0) showQuestion(current - 1);
  });

  // ---- Highlight the selected option ----
  form.addEventListener('change', (e) => {
    if (e.target.type === 'radio') {
      const opts = e.target.closest('.options').querySelectorAll('.option');
      opts.forEach(o => o.classList.remove('selected'));
      e.target.closest('.option').classList.add('selected');
    }
  });

  // ---- Confirm before manual submit (validation prompt) ----
  form.addEventListener('submit', (e) => {
    const answered = form.querySelectorAll('input[type="radio"]:checked').length;
    if (!autoSubmitting) {
      if (answered < total) {
        const ok = confirm(
          'You answered ' + answered + ' of ' + total +
          ' questions. Unanswered ones are marked wrong. Submit anyway?'
        );
        if (!ok) { e.preventDefault(); return; }
      }
    }
    clearInterval(countdown);
  });

  // ---- Countdown timer ----
  let timeLeft = window.QUIZ_TIME || 300;
  let autoSubmitting = false;

  function renderTime() {
    const m = String(Math.floor(timeLeft / 60)).padStart(2, '0');
    const s = String(timeLeft % 60).padStart(2, '0');
    timerEl.textContent = m + ':' + s;
    if (timeLeft <= 30) timerEl.classList.add('warning');
  }
  renderTime();

  const countdown = setInterval(() => {
    timeLeft--;
    renderTime();
    if (timeLeft <= 0) {
      clearInterval(countdown);
      autoSubmitting = true;
      alert('Time is up! Your quiz will be submitted automatically.');
      form.submit();
    }
  }, 1000);

  showQuestion(0);
})();
