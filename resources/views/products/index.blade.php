@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Produk</h1>

    <!-- Notifikasi Hapus Produk -->
    <div id="delete-product-alert" class="alert alert-success d-none"></div>

    <div class="d-flex justify-content-between mb-3">
    <button class="btn btn-danger" id="logout-button">Logout</button>
    </div>
    
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Tambah Produk</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="product-list">
            <!-- Data dari API akan dimuat di sini -->
        </tbody>
    </table>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <div id="add-product-alert" class="alert alert-success d-none"></div>
                <form id="add-product-form">
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" class="form-control" name="price" required>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Produk -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <div id="edit-product-alert" class="alert alert-success d-none"></div>
                <form id="edit-product-form">
                    <input type="hidden" id="edit-product-id">
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control" id="edit-name" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea class="form-control" id="edit-description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" class="form-control" id="edit-price" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    fetchProducts();

    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/login"; // Redirect ke halaman login jika tidak ada token
    }

    // Fungsi Logout
    document.getElementById("logout-button").addEventListener("click", function () {
        fetch("/api/logout", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            localStorage.removeItem("token"); // Hapus token dari localStorage
            window.location.href = "/login"; // Redirect ke halaman login
        })
        .catch(error => console.error("Error:", error));
    });

    // Tambah Produk
    document.getElementById("add-product-form").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/api/products', {
            method: "POST",
            body: JSON.stringify(Object.fromEntries(formData)),
            headers: { "Content-Type": "application/json" }
        }).then(response => response.json())
          .then(data => {
              if (data.id) { // Pastikan produk berhasil dibuat
                  fetchProducts();
                  document.getElementById("add-product-alert").innerText = "Produk berhasil ditambahkan!";
                  document.getElementById("add-product-alert").classList.remove("d-none");

                  // Sembunyikan alert setelah 3 detik
                  setTimeout(() => {
                      document.getElementById("add-product-alert").classList.add("d-none");
                      new bootstrap.Modal(document.getElementById('addProductModal')).hide();
                  }, 3000);

                  this.reset();
              } else {
                  alert("Gagal menambahkan produk. Periksa kembali!");
              }
          }).catch(error => console.error("Error:", error));
    });

    // Edit Produk
    document.getElementById("edit-product-form").addEventListener("submit", function (e) {
        e.preventDefault();
        const id = document.getElementById("edit-product-id").value;

        const data = {
            name: document.getElementById("edit-name").value,
            description: document.getElementById("edit-description").value,
            price: document.getElementById("edit-price").value
        };

        console.log("Mengirim update untuk produk ID:", id, "dengan data:", data); // Debugging

        fetch(`/api/products/${id}`, {
            method: "PUT",
            body: JSON.stringify(data),
            headers: { 
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest" // Pastikan AJAX request
            }
        }).then(response => response.json())
          .then(data => {
              if (data.id) { // Pastikan produk berhasil diperbarui
                  fetchProducts();
                  document.getElementById("edit-product-alert").innerText = "Produk berhasil diperbarui!";
                  document.getElementById("edit-product-alert").classList.remove("d-none");

                  // Sembunyikan alert setelah 3 detik
                  setTimeout(() => {
                      document.getElementById("edit-product-alert").classList.add("d-none");
                      new bootstrap.Modal(document.getElementById('editProductModal')).hide();
                  }, 3000);
              } else {
                  alert("Gagal memperbarui produk. Pastikan data benar!");
              }
          }).catch(error => console.error("Error:", error));
    });
});

// Ambil Data dari API
function fetchProducts() {
    fetch('/api/products')
        .then(response => response.json())
        .then(products => {
            const list = document.getElementById("product-list");
            list.innerHTML = "";
            products.forEach((product, index) => {
                list.innerHTML += `
                    <tr id="product-row-${product.id}">
                        <td>${index + 1}</td>
                        <td>${product.name}</td>
                        <td>${product.description}</td>
                        <td>Rp ${new Intl.NumberFormat().format(product.price)}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editProduct(${product.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Hapus</button>
                        </td>
                    </tr>
                `;
            });
        });
}

// Isi Data ke Form Edit
function editProduct(id) {
    console.log("Edit produk dengan ID:", id); // Tambahkan ini untuk debug
    fetch(`/api/products/${id}`)
        .then(response => response.json())
        .then(product => {
            console.log("Data produk:", product); // Cek apakah data produk benar

            document.getElementById("edit-product-id").value = product.id;
            document.getElementById("edit-name").value = product.name;
            document.getElementById("edit-description").value = product.description;
            document.getElementById("edit-price").value = product.price;
            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        });
}

// Hapus Produk dengan Notifikasi
function deleteProduct(id) {
    if (confirm("Yakin ingin menghapus produk ini?")) {
        fetch(`/api/products/${id}`, { method: "DELETE" })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Gagal menghapus produk");
                }
                return response.text(); // Mengambil teks mentah terlebih dahulu
            })
            .then(text => {
                console.log("Response text:", text); // Debugging: Cek isi respons
                return text ? JSON.parse(text) : {}; // Jika kosong, return objek kosong
            })
            .then(() => {
                // Hapus produk dari tampilan tanpa refresh
                const productRow = document.querySelector(`#product-row-${id}`);
                if (productRow) {
                    productRow.remove();
                }

                // Tampilkan notifikasi sukses
                const alertBox = document.getElementById("delete-product-alert");
                alertBox.innerText = "Produk berhasil dihapus!";
                alertBox.classList.remove("d-none");

                // Sembunyikan alert setelah 3 detik
                setTimeout(() => {
                    alertBox.classList.add("d-none");
                }, 3000);
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat menghapus produk.");
            });
    }
}
</script>
@endsection