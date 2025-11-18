<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="ms-auto"></div>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }} </a>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownProfile">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-fill me-2"></i>Profil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.tentang') }}">
                            <i class="bi bi-info-circle-fill me-2"></i>Tentang Aplikasi
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="/logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
                </li>
        </ul>
    </div>
</nav>