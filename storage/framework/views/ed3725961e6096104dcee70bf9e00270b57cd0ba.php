

<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Login</h2>
    
    <!-- Alert untuk error -->
    <div id="login-alert" class="alert alert-danger d-none"></div>

    <form id="login-form">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" id="login-email" name="email" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" class="form-control" id="login-password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.getElementById("login-form").addEventListener("submit", function (e) {
    e.preventDefault();

    let email = document.getElementById("login-email").value;
    let password = document.getElementById("login-password").value;
    let alertBox = document.getElementById("login-alert");

    fetch("/api/login", {
        method: "POST",
        body: JSON.stringify({ email, password }),
        headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => {
        if (data.token) {
            // Simpan token ke localStorage
            localStorage.setItem("token", data.token);
            // Redirect ke halaman produk
            window.location.href = "/products";
        } else {
            // Tampilkan pesan error di alert
            alertBox.textContent = data.message || "Login gagal!";
            alertBox.classList.remove("d-none");
        }
    })
    .catch(error => {
        alertBox.textContent = "Terjadi kesalahan, coba lagi!";
        alertBox.classList.remove("d-none");
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\crud8\resources\views/auth/login.blade.php ENDPATH**/ ?>