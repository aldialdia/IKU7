<div class="sidebar p-3">
    <h4 class="text-center mb-4">Metode-Belajar</h4>
    <ul class="nav nav-pills flex-column">

        @if (auth()->user()->role == 'rektorat')
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Manajemen Fakultas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Manajemen Departemen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Manajemen User</a>
            </li>
        @endif

        @if (auth()->user()->role == 'fakultas')
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Manajemen Mata Kuliah</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Manajemen Dosen</a>
            </li>
        @endif

        @if (auth()->user()->role == 'dosen')
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ url('/admin/dosen') }}">Dashboard Dosen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('dosen.matkul_saya') }}">Mata Kuliah Saya</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('dosen.input_metode.index') }}">Input Mata Kuliah</a>
            </li>
        @endif

    </ul>
</div>
