

<?php $__env->startSection('title', 'Register'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Registrasi</h2>
    <div id="register-alert" class="alert alert-danger d-none"></div>
    <form id="register-form">
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" class="form-control" id="register-name" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" id="register-email" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" class="form-control" id="register-password" required>
        </div>
        <button type="submit" class="btn btn-success">Daftar</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.getElementById("register-form").addEventListener("submit", function(e) {
    e.preventDefault();
    fetch('/api/register', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            name: document.getElementById("register-name").value,
            email: document.getElementById("register-email").value,
            password: document.getElementById("register-password").value
        })
    }).then(response => response.json())
      .then(data => {
          if (data.message) {
              window.location.href = "/login";
          } else {
              document.getElementById("register-alert").innerText = "Registrasi gagal!";
              document.getElementById("register-alert").classList.remove("d-none");
          }
      });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\crud8\resources\views/auth/register.blade.php ENDPATH**/ ?>