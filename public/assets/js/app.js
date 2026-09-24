(() => {
  const toFa = (value) => String(value).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
  const format = (value) => toFa(new Intl.NumberFormat('en-US').format(Math.round(value)));
  let factor = 15400000;
  document.querySelectorAll('.calc-tabs button').forEach(btn => btn.addEventListener('click', () => {
    document.querySelectorAll('.calc-tabs button').forEach(x => x.classList.remove('active'));
    btn.classList.add('active'); factor = Number(btn.dataset.factor || factor);
  }));
  document.getElementById('calculate-project')?.addEventListener('click', () => {
    const area = Math.max(1, Number(document.getElementById('project-area')?.value || 1));
    const out = document.getElementById('estimate-value');
    if (out) out.textContent = format(area * factor) + ' ریال';
  });
  const modal = document.getElementById('quote-modal');
  document.querySelectorAll('[data-open-quote]').forEach(x => x.addEventListener('click', () => modal?.removeAttribute('hidden')));
  document.querySelectorAll('[data-close-quote]').forEach(x => x.addEventListener('click', () => modal?.setAttribute('hidden','hidden')));
  modal?.addEventListener('click', e => { if (e.target === modal) modal.setAttribute('hidden','hidden'); });
  document.querySelectorAll('.flash').forEach(el => setTimeout(() => el.remove(), 3500));
})();