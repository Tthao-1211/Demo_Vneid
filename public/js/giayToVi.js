// Form thêm giấy tờ: đổi các ô nhập riêng theo loại giấy tờ được chọn
(function () {
    var oChonLoai = document.getElementById('ma_loai');
    var vung = document.getElementById('cac-truong-rieng');
    if (!oChonLoai || !vung) return;

    var dsLoai = JSON.parse(vung.getAttribute('data-loai') || '{}');
    var giaTriCu = JSON.parse(vung.getAttribute('data-gia-tri') || '{}');

    function veLai() {
        vung.textContent = '';
        var cacTruong = dsLoai[oChonLoai.value] || [];
        cacTruong.forEach(function (truong) {
            var nhom = document.createElement('div');
            nhom.className = 'nhom-nhap';
            var nhan = document.createElement('label');
            nhan.textContent = truong.nhan;
            nhan.setAttribute('for', 'thong_tin_' + truong.ten);
            var o = document.createElement('input');
            o.type = 'text';
            o.id = 'thong_tin_' + truong.ten;
            o.name = 'thong_tin[' + truong.ten + ']';
            o.maxLength = 100;
            o.value = giaTriCu[truong.ten] || '';
            nhom.appendChild(nhan);
            nhom.appendChild(o);
            vung.appendChild(nhom);
        });
    }

    oChonLoai.addEventListener('change', function () { giaTriCu = {}; veLai(); });
    veLai();
})();
