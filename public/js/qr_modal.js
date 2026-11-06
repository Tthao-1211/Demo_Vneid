// Kiểm tra thư viện QR
if (typeof qrcode === 'undefined') {
    console.error('Thư viện qrcode-generator chưa được tải!');
}

// Hiển thị mã QR
function hienThiQR(loai) {
    const modal = document.getElementById('modalQR');
    const container = document.getElementById('qrcode-box');
    const title = document.getElementById('qrTitle');
    const desc = document.getElementById('qrDesc');

    if (typeof qrcode === 'undefined') {
        alert('Thư viện tạo QR chưa được tải!');
        return;
    }

    let duLieu = '';

    if (loai === 'cccd') {
        duLieu = window.qrData.cccd;
        title.innerText = 'Mã QR Thẻ CCCD';
        desc.innerText = 'Thông tin căn cước công dân';
    } else if (loai === 'dinhdanh') {
        duLieu = window.qrData.dinhdanh;
        title.innerText = 'Mã QR Định Danh Điện Tử';
        desc.innerText = 'Thông tin định danh điện tử';
    }

    if (!duLieu || !duLieu.trim()) {
        alert('Không có dữ liệu để tạo mã QR!');
        return;
    }

    container.innerHTML = '';

    try {
        // Hỗ trợ tiếng Việt UTF-8
        qrcode.stringToBytes = qrcode.stringToBytesFuncs['UTF-8'];

        // Tự động chọn phiên bản QR phù hợp
        const qr = qrcode(0, 'L');

        qr.addData(duLieu, 'Byte');
        qr.make();

        container.innerHTML = qr.createSvgTag(6, 4);

        modal.style.display = 'flex';

    } catch (error) {
        console.error('Lỗi tạo QR:', error);
        alert('Không thể tạo QR: ' + error.message);
    }
}

// Đóng modal QR
function dongQR() {
    document.getElementById('modalQR').style.display = 'none';
}

// Nhấn bên ngoài modal để đóng
window.addEventListener('click', function (event) {
    const modal = document.getElementById('modalQR');

    if (event.target === modal) {
        dongQR();
    }
});