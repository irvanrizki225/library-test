<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\models\Book;
use App\models\Transaction;
use App\models\Customer;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $title = 'Borrowed - ' . config('app.name');

        if (Auth::user()->role == 'admin') {
            $transactions = Transaction::with('book', 'customer')->get();
        } else {
            $transactions = Transaction::with('book', 'customer')
                ->whereHas('customer', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->get();
        }


        $books = Book::all();
        $customers = Customer::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'user');
            })
            ->get();

        if (request()->ajax()) {
            $dataTable = DataTables::of($transactions)
            ->addIndexColumn()
            ->addColumn('book', function ($row) {
                return $row->book->title;
            })
            ->addColumn('customer', function ($row) {
                return $row->customer->name;
            });

            if (Auth::user()->role == 'admin') {
                $dataTable->addColumn('status', function ($row) {
                    if ($row->status == 'returned') {
                        $btn = '<button class="btn btn-success btn-sm" disabled>Returned</button> &nbsp;';
                    } else {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-danger btn-sm Change">
                                Borrowed</a> &nbsp;';
                    }
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm Edit">
                            <i class="fas fa-edit" aria-hidden="true"></i></a> &nbsp;';
                    $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm Delete">
                            <i class="fas fa-trash" aria-hidden="true"></i></a>';
                    return $btn;
                });
            } else {
                $dataTable->addColumn('status', function ($row) {
                    return $row->status == 'returned' ? 'Returned' : 'Borrowed';
                });
            }

            // Tentukan rawColumns berdasarkan role
            $rawColumns = Auth::user()->role == 'admin' ? ['action', 'status'] : [];

            return $dataTable->rawColumns($rawColumns)->make(true);
        }

        return view('transaction.index' , compact('title', 'books', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required',
            'customer_id' => $request->auth ? 'required' : 'nullable',
            'transaction_date' => 'required',
            'return_date' => 'required',
        ]);

        DB::beginTransaction();

        if ($request->auth == 'admin') {
            $customer_id = $request->customer_id;
        } else {
            $customer = Customer::where('user_id', Auth::user()->id)->first();
            $customer_id = $customer->id;
        }

        $transactionBook = Transaction::where('book_id', $request->book_id)->where('return_date', null)->first();

        if ($transactionBook) {
            DB::rollBack();
            return response()->json(['error' => 'Book is already borrowed.']);
        }

        if ($request->id == null) {
            $transaction = Transaction::create([
                'book_id' => $request->book_id,
                'customer_id' => $customer_id,
                'transaction_date' => $request->transaction_date,
                'return_date' => $request->return_date,
            ]);

            $book = Book::find($request->book_id);
            $book->update([
                'stock' => $book->stock - 1,
            ]);
        } else {
            $transaction = Transaction::find($request->id);
            $transaction->update([
                'book_id' => $request->book_id,
                'customer_id' => $customer_id,
                'transaction_date' => $request->transaction_date,
                'return_date' => $request->return_date,
            ]);
        }

        if (!$transaction) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to save transaction.']);
        }

        DB::commit();
        return response()->json(['success' => 'Transaction saved successfully.']);
    }

    public function edit($id)
    {
        $transaction = Transaction::find($id);
        return response()->json($transaction);
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
        return response()->json(['success' => 'Transaction deleted successfully.']);
    }

    public function returned($id)
    {
        $transaction = Transaction::find($id);

        $transaction->update([
            'status' => 'returned',
        ]);

        $book = Book::find($transaction->book_id);
        $book->update([
            'stock' => $book->stock + 1,
        ]);

        return response()->json(['success' => 'Book returned successfully.']);
    }
}
