@php
    use Illuminate\Support\Facades\Auth;
@endphp
<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand d-md-none" href="#">Library Management</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <li><button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button></li>
                        </form>
                        {{-- <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i></a></li> --}}
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
