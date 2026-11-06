// Hiện/ẩn mật khẩu
document.querySelectorAll('[data-hien-mk]').forEach(b => b.addEventListener('click', () => {
  const i = document.getElementById(b.dataset.hienMk); i.type = i.type === 'password' ? 'text' : 'password';
}));
// Hộp thoại
document.querySelectorAll('[data-mo]').forEach(b => b.addEventListener('click', () => {
  const hop = document.getElementById(b.dataset.mo);
  if (b.dataset.loai) napLoai(hop, JSON.parse(b.dataset.loai));
  hop.classList.add('mo-ra');
}));
document.querySelectorAll('.hop-thoai').forEach(h => h.addEventListener('click', e => {
  if (e.target === h || e.target.hasAttribute('data-dong')) h.classList.remove('mo-ra');
}));
// Form loại giấy tờ: các trường động
function dongTruong(f = { khoa: '', nhan: '' }) {
  const d = document.createElement('div'); d.className = 'dong-truong';
  d.innerHTML = '<input name="nhan[]" placeholder="Nhãn (vd: Biển số)"><input name="khoa[]" placeholder="khóa JSON"><button type="button" class="nut-chu">✕</button>';
  d.children[0].value = f.nhan; d.children[1].value = f.khoa;
  d.children[2].onclick = () => d.remove();
  document.getElementById('ds-truong').appendChild(d);
}
function napLoai(hop, l) {
  hop.querySelector('[name=ma_loai]').value = l.ma_loai; hop.querySelector('[name=ten_loai]').value = l.ten_loai;
  document.getElementById('ds-truong').innerHTML = '';
  (l.cac_truong.length ? l.cac_truong : [undefined]).forEach(dongTruong);
}
document.getElementById('them-truong')?.addEventListener('click', () => dongTruong());
