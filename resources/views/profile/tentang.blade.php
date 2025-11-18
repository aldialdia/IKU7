@extends('layouts.app')

@section('title', 'Tentang Aplikasi')

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-info-circle-fill text-primary me-3" style="font-size: 2rem;"></i>
                <div>
                    <h4 class="mb-0">Sistem Informasi IKU 7</h4>
                    <p class="mb-0 text-muted">Informasi sistem dan tim pengembang</p>
                </div>
            </div>
            <hr>
            
            <p class="mb-0">Versi 1.0.0</p>
            <p class="mt-2">Sistem informasi terintegrasi untuk membantu Dosen menentukan metode pembelajaran (PjBL, CBM, Biasa) dan memfasilitasi proses verifikasi oleh Admin Fakultas serta monitoring oleh Rektorat.</p>
            
            <h6 class="mt-4">Fitur Utama</h6>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-pencil-square me-2 text-primary"></i> Input Metode Pembelajaran (PjBL/CBM)</li>
                        <li class="mb-2"><i class="bi bi-ui-checks me-2 text-primary"></i> Manajemen Komponen Penilaian</li>
                        <li class="mb-2"><i class="bi bi-file-earmark-arrow-up-fill me-2 text-primary"></i> Upload Dokumen Pendukung</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-patch-check-fill me-2 text-primary"></i> Verifikasi Mata Kuliah (Oleh Fakultas)</li>
                        <li class="mb-2"><i class="bi bi-pie-chart-fill me-2 text-primary"></i> Dashboard Monitoring (Oleh Rektorat)</li>
                        <li class="mb-2"><i class="bi bi-people-fill me-2 text-primary"></i> Manajemen Akun (Dosen & Fakultas)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-4">
            <h4 class="mb-3">Tim Pengembang</h4>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/profil-pic.png') }}" class="rounded-circle mb-3" alt="Aldi" width="100" height="100">
                            <h5 class="card-title">Aldi</h5>
                            <p class="text-muted small">Sistem Informasi</p>
                            <blockquote class="blockquote fst-italic text-success-emphasis bg-success-subtle border-start border-success border-5 p-3 small">
                                "quote"
                            </blockquote>
                            <p class="text-muted small mt-2"><i class="bi bi-envelope me-1"></i> email</p>
                            <div>
                                <a href="#" class="text-decoration-none me-2"><i class="bi bi-linkedin" style="font-size: 1.2rem;"></i></a>
                                <a href="#" class="text-decoration-none me-2"><i class="bi bi-github" style="font-size: 1.2rem;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/profil-pic.png') }}" class="rounded-circle mb-3" alt="Aldi" width="100" height="100">
                            <h5 class="card-title">Aldi</h5>
                            <p class="text-muted small">Sistem Informasi</p>
                            <blockquote class="blockquote fst-italic text-success-emphasis bg-success-subtle border-start border-success border-5 p-3 small">
                                "quote"
                            </blockquote>
                            <p class="text-muted small mt-2"><i class="bi bi-envelope me-1"></i> email</p>
                            <div>
                                <a href="#" class="text-decoration-none me-2"><i class="bi bi-linkedin" style="font-size: 1.2rem;"></i></a>
                                <a href="#" class="text-decoration-none me-2"><i class="bi bi-github" style="font-size: 1.2rem;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/profil-pic.png') }}" class="rounded-circle mb-3" alt="Aldi" width="100" height="100">
                            <h5 class="card-title">Aldi</h5>
                            <p class="text-muted small">Sistem Informasi</p>
                            <blockquote class="blockquote fst-italic text-success-emphasis bg-success-subtle border-start border-success border-5 p-3 small">
                                "quote"
                            </blockquote>
                            <p class="text-muted small mt-2"><i class="bi bi-envelope me-1"></i> email</p>
                            <div>
                                <a href="#" class="text-decoration-none me-2"><i class="bi bi-linkedin" style="font-size: 1.2rem;"></i></a>
                                <a href="#" class="text-decoration-none me-2"><i class="bi bi-github" style="font-size: 1.2rem;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <div class="row justify-content-center"> 
                <div class="col-md-6 mb-3"> <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/profil-pic.png') }}" class="rounded-circle mb-3" alt="Aldi" width="100" height="100">
                            <h5 class="card-title">Aldi</h5>
                            <p class="text-muted small">Sistem Informasi</p>
                            <blockquote class="blockquote fst-italic text-success-emphasis bg-success-subtle border-start border-success border-5 p-3 small">
                                "quote"
                            </blockquote>
                            <p class="text-muted small mt-2"><i class="bi bi-envelope me-1"></i> email</p>
                            <div>
                                <a href="#" class="text-decoration-none me-2"><i class="bi bi-linkedin" style="font-size: 1.2rem;"></i></a>
                                <a href="#" class="text-decoration-none me-2"><i class="bi bi-github" style="font-size: 1.2rem;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3"> <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/profil-pic.png') }}" class="rounded-circle mb-3" alt="Aldi" width="100" height="100">
                            <h5 class="card-title">Aldi</h5>
                            <p class="text-muted small">Sistem Informasi</p>
                            <blockquote class="blockquote fst-italic text-success-emphasis bg-success-subtle border-start border-success border-5 p-3 small">
                                "quote"
                            </blockquote>
                            <p class="text-muted small mt-2"><i class="bi bi-envelope me-1"></i> email</p>
                            <div>
                                <a href="#" class="text-decoration-none me-2"><i class="bi bi-linkedin" style="font-size: 1.2rem;"></i></a>
                                <a href="#" class="text-decoration-none me-2"><i class="bi bi-github" style="font-size: 1.2rem;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-body p-4">
            <h4 class="mb-3">Kontak & Dukungan</h4>
            <div class="row">
                <div class="col-md-6">
                    <strong>Email</strong>
                    <p class="text-muted"><i class="bi bi-envelope me-2"></i> aldiedoank1208@gmail.com</p>
                </div>
                <div class="col-md-6">
                    <strong>Telepon</strong>
                    <p class="text-muted"><i class="bi bi-telephone me-2"></i> +628 </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection