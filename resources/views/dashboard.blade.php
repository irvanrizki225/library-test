@php
    use Illuminate\Support\Facades\Auth;
@endphp
@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Dashboard</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        {{-- <li class="breadcrumb-item"><a href="#">Dashboard</a></li> --}}
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            @if (Auth::user()->role == 'admin')
                <div class="col-md-4">
                    <div class="card-counter bg-books">
                        <i class="fas fa-book float-start"></i>
                        <span class="count-numbers">{{ $countBooks }}</span>
                        <span class="count-name">Total Books</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-counter bg-members">
                        <i class="fas fa-users float-start"></i>
                        <span class="count-numbers">{{ $countCustomers }}</span>
                        <span class="count-name">Members</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-counter bg-borrowed">
                        <i class="fas fa-exchange-alt float-start"></i>
                        <span class="count-numbers">{{ $countTransactions }}</span>
                        <span class="count-name">Borrowed</span>
                    </div>
                </div>
            @else
                <div class="col-md-6">
                    <div class="card-counter bg-books">
                        <i class="fas fa-book float-start"></i>
                        <span class="count-numbers">{{ $countBooks }}</span>
                        <span class="count-name">Total Books</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-counter bg-borrowed">
                        <i class="fas fa-exchange-alt float-start"></i>
                        <span class="count-numbers">{{ $countTransactions }}</span>
                        <span class="count-name">Borrowed</span>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
