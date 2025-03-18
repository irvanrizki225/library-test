<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Book;
use App\Models\Category;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class BookController extends Controller
{
    public function index()
    {
        $title = 'Books - ' . config('app.name');
        $books = Book::with('category')->get();
        $categories = Category::all();

        if (request()->ajax()) {
            return DataTables::of($books)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category->name;
                })
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

        return view('book.index' , compact('title', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'year' => 'required',
            'isbn' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required',
        ]);

        DB::beginTransaction();

        $filename = $this->handleProfilePhotoUpload($request);

        $book = Book::updateOrCreate(
            ['id' => $request->id],
            [
                'title' => $request->title,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'author' => $request->author,
                'publisher' => $request->publisher,
                'year' => $request->year,
                'isbn' => $request->isbn,
                'image' => $filename,
                'stock' => $request->stock,
            ]
        );

        if (! $book) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to save book.']);
        }

        DB::commit();
        return response()->json(['success' => 'Book saved successfully.']);
    }

    protected function handleProfilePhotoUpload($request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $request->title . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('image_book'), $filename);
            return $filename;
        }
        return null;
    }

    public function edit($id)
    {
        $book = Book::find($id);
        return response()->json($book);
    }

    public function destroy($id)
    {
        Book::where('id', $id)->delete();
        return response()->json(['success' => 'Book deleted successfully.']);
    }
}
