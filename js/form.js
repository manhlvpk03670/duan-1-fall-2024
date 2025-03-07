function validateForm(event) {
    let isValid = true;

    // Clear previous error messages
    document.querySelectorAll('.error-message').forEach(el => el.remove());

    // Get input values
    const name = document.getElementById("name");
    const email = document.getElementById("email");
    const address = document.getElementById("address");
    const phone = document.getElementById("phone");
    const paymentMethod = document.getElementById("payment-method");

    // Validate each field
    if (!name.value.trim()) {
        showError(name, "Vui lòng nhập họ và tên.");
        isValid = false;
    }
    if (!email.value.trim()) {
        showError(email, "Vui lòng nhập đúng email.");
        isValid = false;
    }
    if (!address.value.trim()) {
        showError(address, "Vui lòng nhập địa chỉ.");
        isValid = false;
    }
    if (!phone.value.trim()) {
        showError(phone, "Vui lòng nhập số điện thoại.");
        isValid = false;
    }
    if (!paymentMethod.value) {
        showError(paymentMethod, "Vui lòng chọn phương thức thanh toán.");
        isValid = false;
    }

    // Prevent form submission if not valid
    if (!isValid) {
        event.preventDefault();
    }
}

function showError(inputElement, message) {
    const error = document.createElement('div');
    error.className = 'error-message';
    error.innerText = message;
    inputElement.parentNode.appendChild(error);
}

function handlePayment(event) {
    event.preventDefault(); // Ngăn chặn form submit mặc định

    const paymentMethod = document.getElementById("payment-method").value;

    if (paymentMethod === "banking") {
        window.location.href = "banking_payment.php"; // Chuyển hướng đến trang thanh toán trực tuyến
    } else if (paymentMethod === "cod") {
        window.location.href = "cod_payment.php"; // Chuyển hướng đến trang thanh toán khi giao hàng
    } else {
        alert("Vui lòng chọn phương thức thanh toán.");
    }
}
