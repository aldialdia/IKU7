<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <title>Login - SIIKU7</title>

    <style>
        /* Memberi background abu-abu muda pada body */
        body {
            background-color: #f0f2f5;
        }
        /* Mengatur kartu login agar di tengah (vertikal & horizontal) */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Mengatur style kartu */
        .login-card {
            max-width: 900px; /* Lebar maksimal kartu */
            border: none; /* Hilangkan border default */
            border-radius: 1rem; /* Buat lebih melengkung */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1); /* Efek shadow */
            overflow: hidden; /* Agar gambar tidak keluar dari radius */
        }
        /* Kolom gambar di kiri */
        .login-image-col {
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .login-image-col img {
            max-width: 100%;
            height: auto;
        }
        /* Kolom form di kanan (padding dihapus, kita pakai class Bootstrap) */
        .login-form-col {
            background-color: #ffffff;
        }
        
        /* Atur input-group (untuk ikon) */
        .input-group-text {
            background-color: transparent;
            border-right: 0;
        }
        .form-control {
            border-left: 0;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container-fluid login-container">
        <div class="card login-card w-100">
            <div class="row g-0">
                
                <div class="col-md-6 d-none d-md-flex login-image-col">
                    <img src="{{ asset('images/login-image.png') }}" alt="Login Image">
                    </div>

                <div class="col-md-6 login-form-col p-4 p-md-5"> 
                    
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-1">Selamat Datang</h2>
                        <p class="text-muted">Sistem Informasi IKU 7 (SIIKU7)</p>
                    </div>

                    @if ($errors->any())
                    <div class="alert alert-danger" style="font-size: 0.9rem;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <form action="" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" value="{{ old('email')}}" name="email" class="form-control" placeholder="Masukan Email Anda" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Masukan Password Anda" required>
                            </div>
                        </div>

                        <div class="mb-3 d-grid">
                            <button name="submit" type="submit" class="btn btn-primary btn-lg">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>