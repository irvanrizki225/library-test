@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
@endphp

<div class="col-md-2 p-0 sidebar">
    <div class="text-center py-4">
        <h5 class="text-white">Library Management</h5>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item ">
            <a class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" href="/dashboard">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>

        @if (Auth::user()->role == 'admin')
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'categories.index' ? 'active' : '' }}" href="/categories">
                    <i class="fas fa-tags"></i> Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'books.index' ? 'active' : '' }}" href="/books">
                    <i class="fas fa-book"></i> Books
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'customers.index' ? 'active' : '' }}" href="/customers">
                    <i class="fas fa-users"></i> Members
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'transactions.index' ? 'active' : '' }}" href="/transactions">
                    <i class="fas fa-exchange-alt"></i> Transactions
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'books.index' ? 'active' : '' }}" href="/books">
                    <i class="fas fa-book"></i> Books
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'transactions.index' ? 'active' : '' }}" href="/transactions">
                    <i class="fas fa-exchange-alt"></i> Transactions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="/profile">
                    <i class="fas fa-user"></i> Detail Profile
                </a>
            </li>
        @endif
    </ul>
</div>
