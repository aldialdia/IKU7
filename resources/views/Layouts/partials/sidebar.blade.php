<div class="sidebar">
    <a class="sidebar-brand">
        SIIKU7
    </a>
    
    <div class="sidebar-menu">
        <ul class="nav nav-pills flex-column">
            
            @if (auth()->user()->role == 'rektorat')
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->routeIs('rektorat.dashboard*') ? 'active' : '' }}" 
                       href="{{ route('rektorat.dashboard') }}">
                       <i class="bi bi-pie-chart-fill"></i>
                       <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->routeIs('rektorat.penilaian*') ? 'active' : '' }}" 
                       href="{{ route('rektorat.penilaian.list') }}">
                       <i class="bi bi-mortarboard-fill"></i>
                       <span>Penilaian Mahasiswa</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->routeIs('rektorat.manajemen-fakultas*') ? 'active' : '' }}" 
                       href="{{ route('rektorat.manajemen-fakultas.index') }}">
                       <i class="bi bi-building"></i>
                       <span>Manajemen Akun Fakultas</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->role == 'fakultas')
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->routeIs('fakultas.dashboard*') ? 'active' : '' }}" 
                       href="{{ route('fakultas.dashboard') }}">
                       <i class="bi bi-speedometer2"></i>
                       <span>Dashboard Fakultas</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->routeIs('fakultas.verifikasi*') ? 'active' : '' }}" 
                       href="{{ route('fakultas.verifikasi.index') }}">
                       <i class="bi bi-patch-check-fill"></i>
                       <span>Verifikasi Mata Kuliah</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->routeIs('fakultas.manajemen-dosen*') ? 'active' : '' }}" 
                       href="{{ route('fakultas.manajemen-dosen.index') }}">
                       <i class="bi bi-person-video3"></i>
                       <span>Manajemen Dosen</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->role == 'dosen')
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->routeIs('dosen.dashboard*') ? 'active' : '' }}" 
                       href="{{ route('dosen.dashboard') }}">
                       <i class="bi bi-speedometer2"></i>
                       <span>Dashboard Dosen</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->routeIs('dosen.matkul_saya*') ? 'active' : '' }}" 
                       href="{{ route('dosen.matkul_saya') }}">
                       <i class="bi bi-journal-bookmark-fill"></i>
                       <span>Mata Kuliah Saya</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->routeIs('dosen.input_metode*') ? 'active' : '' }}" 
                       href="{{ route('dosen.input_metode.index') }}">
                       <i class="bi bi-pencil-square"></i>
                       <span>Input Mata Kuliah</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</div>