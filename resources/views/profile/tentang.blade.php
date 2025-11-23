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
                            <img src="{{ asset('images/rahil1.jpg') }}" class="rounded-circle mb-3" alt="Rahil Akram Hammad" width="100" height="100">
                            <h5 class="card-title">Rahil Akram Hammad</h5>
                            <p class="text-muted small">Sistem Informasi</p>
                            <blockquote class="blockquote fst-italic text-success-emphasis bg-success-subtle border-start border-success border-5 p-3 small">
                                "Stay Focus"
                            </blockquote>
                            <p class="text-muted small mt-2"> Programmer</p>
                            <div>
                                <a href="https://www.linkedin.com/in/rahil-akram-hammad-784aba243" class="text-decoration-none me-2" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin" style="font-size: 1.2rem;"></i></a>
                                <a href="https://github.com/watdhill" class="text-decoration-none me-2" target="_blank" rel="noopener noreferrer"><i class="bi bi-github" style="font-size: 1.2rem;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/revin.jpg') }}" class="rounded-circle mb-3" alt="Revin Pahlevi" width="100" height="100">
                            <h5 class="card-title">Revin Pahlevi</h5>
                            <p class="text-muted small">Sistem Informasi</p>
                            <blockquote class="blockquote fst-italic text-success-emphasis bg-success-subtle border-start border-success border-5 p-3 small">
                                "Tidak semua hal sesuai harapan, maka bawa santai saja 😏"
                            </blockquote>
                            <p class="text-muted small mt-2"> Project Manager</p>
                            <div>
                                <!-- Ganti URL LinkedIn / GitHub berikut untuk Revin -->
                                <a href="http://www.linkedin.com/in/revin-pahlevi-615778337" class="text-decoration-none me-2" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin" style="font-size: 1.2rem;"></i></a>
                                <a href="https://github.com/RevinPahlevi" class="text-decoration-none me-2" target="_blank" rel="noopener noreferrer"><i class="bi bi-github" style="font-size: 1.2rem;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/aldi.jpg') }}" class="rounded-circle mb-3" alt="Aldi" width="100" height="100">
                            <h5 class="card-title">Aldi</h5>
                            <p class="text-muted small">Sistem Informasi</p>
                            <blockquote class="blockquote fst-italic text-success-emphasis bg-success-subtle border-start border-success border-5 p-3 small">
                                "Stay hungry, stay foolish"
                            </blockquote>
                            <p class="text-muted small mt-2"> Programmer</p>
                            <div>
                                <!-- Ganti URL LinkedIn / GitHub berikut untuk Aldi -->
                                <a href="https://www.linkedin.com/in/your-linkedin" class="text-decoration-none me-2" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin" style="font-size: 1.2rem;"></i></a>
                                <a href="https://github.com/aldialdia" class="text-decoration-none me-2" target="_blank" rel="noopener noreferrer"><i class="bi bi-github" style="font-size: 1.2rem;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <div class="row justify-content-center"> 
                <div class="col-md-6 mb-3"> <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/zhahra.jpg') }}" class="rounded-circle mb-3" alt="Zhahra Idya Astwoti" width="100" height="100">
                            <h5 class="card-title">Zhahra Idya Astwoti</h5>
                            <p class="text-muted small">Sistem Informasi</p>
                            <blockquote class="blockquote fst-italic text-success-emphasis bg-success-subtle border-start border-success border-5 p-3 small">
                                "let them"
                            </blockquote>
                            <p class="text-muted small mt-2"> System Analyst</p>
                            <div>
                                <!-- Ganti URL LinkedIn / GitHub berikut untuk Zhahra -->
                                <a href="https://id.linkedin.com/in/zhahra-idhya-astwoti-024317207" class="text-decoration-none me-2" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin" style="font-size: 1.2rem;"></i></a>
                                <a href="https://github.com/zhhraid" class="text-decoration-none me-2" target="_blank" rel="noopener noreferrer"><i class="bi bi-github" style="font-size: 1.2rem;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3"> <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/loly.jpg') }}" class="rounded-circle mb-3" alt="Loly Amelia Nurza" width="100" height="100">
                            <h5 class="card-title">Loly Amelia Nurza</h5>
                            <p class="text-muted small">Sistem Informasi</p>
                            <blockquote class="blockquote fst-italic text-success-emphasis bg-success-subtle border-start border-success border-5 p-3 small">
                                "it will pass"
                            </blockquote>
                            <p class="text-muted small mt-2"> UI/UX Design</p>
                            <div>
                                <!-- Ganti URL LinkedIn / GitHub berikut untuk Loly -->
                                <a href="https://www.linkedin.com/in/loly-amelia-nurza-7609a6287/" class="text-decoration-none me-2" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin" style="font-size: 1.2rem;"></i></a>
                                <a href="https://github.com/lolyameliaa" class="text-decoration-none me-2" target="_blank" rel="noopener noreferrer"><i class="bi bi-github" style="font-size: 1.2rem;"></i></a>
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
                    <p class="text-muted"><i class="bi bi-envelope me-2"></i> revinpahlevi@gmail.com</p>
                    <p class="text-muted"><i class="bi bi-envelope me-2"></i> aldiedoank1208@gmail.com</p>
                    <p class="text-muted"><i class="bi bi-envelope me-2"></i> rahilakram766@gmail.com</p>
                    <p class="text-muted"><i class="bi bi-envelope me-2"></i> zhahraidhya221@gmail.com</p>
                    <p class="text-muted"><i class="bi bi-envelope me-2"></i> lolyamelian45@gmail.com</p>
                </div>
                <div class="col-md-6">
                    <strong>Telepon</strong>
                    <p class="text-muted"><i class="bi bi-telephone me-2"></i> +6281378133145 </p>
                    <p class="text-muted"><i class="bi bi-telephone me-2"></i> +6282383490316 </p>
                    <p class="text-muted"><i class="bi bi-telephone me-2"></i> +6285212619866 </p>
                    <p class="text-muted"><i class="bi bi-telephone me-2"></i> +6285264676041 </p>
                    <p class="text-muted"><i class="bi bi-telephone me-2"></i> +6282268251708 </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection