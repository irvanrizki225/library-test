<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Customer;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $title = 'Customers - ' . config('app.name');
        $customers = Customer::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'user');
            })
            ->get();

        if (request()->ajax()) {
            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->user?->status == 'active') {
                        $status = 'inactive';

                        return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-success btn-sm Change">
                    Active</a> &nbsp;';
                    } else {
                        $status = 'active';
                        return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-danger btn-sm Change">
                    Inactive</a> &nbsp;';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm Edit">
                    <i class="fas fa-edit" aria-hidden="true"></i></a> &nbsp;';
                    $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm Delete">
                            <i class="fas fa-trash" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('customer.index', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nik' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'password' => $request->id ? 'nullable|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]+$/|confirmed' : 'required|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]+$/|confirmed',
        ],[
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number',
            'password.confirmed' => 'Password and confirmation password do not match',
        ]);

        DB::beginTransaction();

        if ($request->id == null) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $customer = Customer::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'nik' => $request->nik,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);
        } else {
            $customer = Customer::find($request->id);
            $customer->name = $request->name;
            $customer->nik = $request->nik;
            $customer->address = $request->address;
            $customer->phone = $request->phone;
            $customer->email = $request->email;
            $customer->save();

            $user = User::find($customer->user_id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password != null) {
                $user->password = bcrypt($request->password);
            }
            $user->save();
        }

        if (! $customer || ! $user) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to save customer.']);
        }

        DB::commit();
        return response()->json(['success' => 'Customer saved successfully.']);
    }

    public function edit($id)
    {
        $customer = Customer::with('user')->find($id);
        return response()->json($customer);
    }

    public function destroy($id)
    {
        $customer = Customer::with('user')->find($id);
        User::where('id', $customer->user_id)->delete();
        $customer->delete();
        return response()->json(['success' => 'Customer deleted successfully.']);
    }

    public function changeStatus(Request $request, $id)
    {
        $customer = Customer::with('user')->find($id);

        if ($customer->user->status == 'active') {
            User::where('id', $customer->user_id)->update(['status' => 'inactive']);
        } else {
            User::where('id', $customer->user_id)->update(['status' => 'active']);
        }
        return response()->json(['success' => 'Customer activated successfully.']);
    }

    public function profile()
    {
        $title = 'Profile - ' . config('app.name');

        if (request()->ajax()) {
            $customer = Customer::with('user')->where('user_id', Auth::user()->id)->first();
            return response()->json($customer);
        }

        return view('customer.profile', compact('title'));
    }
}
