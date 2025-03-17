@extends('layouts.app')

@section('title', 'Register')

@section('content')
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
@endsection

@section('scripts')
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
@endsection