function errorServer() {
    $.notify({
    icon: "error",
    message: "Kết nối mạng bị lỗi. Vui lòng kiểm tra lại."

    }, {
    type: 'danger',
    timer: 3000,
        placement: {
            from: 'bottom',
            align: 'center'
        }
    });
}

function loadCustomerError() {
    $.notify({
    icon: "error",
    message: "Lỗi lấy dữ liệu từ server"

    }, {
    type: 'danger',
    timer: 3000,
        placement: {
            from: 'bottom',
            align: 'center'
        }
    });
}

function loginErr() {
    $.notify({
        icon: "error",
        message: "Tên đăng nhập hoặc mật khẩu không đúng"

    }, {
        type: 'danger',
        timer: 3000,
        placement: {
            from: 'bottom',
            align: 'center'
        }
    });
}

function expiredErr() {
    $.notify({
        icon: "error",
        message: "Chương trình đã hết hạn dùng vui lòng liên hệ: 035 260 8118"

    }, {
        type: 'danger',
        timer: 3000,
        placement: {
            from: 'bottom',
            align: 'center'
        }
    });
}

function updateErr() {
    $.notify({
        icon: "error",
        message: "Lỗi cập nhật thông tin khách hàng"

    }, {
        type: 'danger',
        timer: 3000,
        placement: {
            from: 'bottom',
            align: 'center'
        }
    });
}

function updateSuccess() {
    $.notify({
        icon: "success",
        message: "Cập nhật thông tin khách hàng thành công"

    }, {
        type: 'success',
        timer: 3000,
        placement: {
            from: 'bottom',
            align: 'center'
        }
    });
}

function addSuccess() {
    $.notify({
        icon: "success",
        message: "Thêm thông tin khách hàng thành công"

    }, {
        type: 'success',
        timer: 3000,
        placement: {
            from: 'bottom',
            align: 'center'
        }
    });
}

function waitRun() {
    $.notify({
        icon: "backup",
        message: "Vui lòng đợi xử lý..."

    }, {
        type: 'info',
        timer: 20000,
        placement: {
            from: 'bottom',
            align: 'center'
        }
    });
}