<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard()
    {
        $title = 'Dashboard - ' . config('app.name');
        $countBooks = DB::table('books')->count();
        $countCustomers = DB::table('customers')->count();
        $countTransactions = DB::table('transactions')->count();
        return view('dashboard' , compact('title', 'countBooks', 'countCustomers', 'countTransactions'));
    }
}
