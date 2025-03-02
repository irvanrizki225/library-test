<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index()
    {
        $title = 'Categories - ' . config('app.name');
        $categories = Category::all();

        if (request()->ajax()) {
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm Edit">
                            <i class="fas fa-edit" aria-hidden="true"></i></a> &nbsp;';
                    $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm Delete">
                            <i class="fas fa-trash" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('category.index' , compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        DB::beginTransaction();

        $category = Category::updateOrCreate(
            ['id' => $request->id],
            ['name' => $request->name]
        );

        if (! $category) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to save category.']);
        }

        DB::commit();

        return response()->json(['success' => 'Category saved successfully.']);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return response()->json($category);
    }

    public function destroy($id)
    {
        Category::where('id', $id)->delete();
        return response()->json(['success' => 'Category deleted successfully.']);
    }
}
