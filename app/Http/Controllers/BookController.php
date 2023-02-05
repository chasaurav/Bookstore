<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use App\Models\Publisher;
use App\Http\Requests\BooksRequest;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function getBooksForDatatable()
    {
        $query = Book::orderBy('published', 'desc')->get([
            'id',
            'title',
            'author_id',
            'genre_id',
            'description',
            'isbn',
            'image',
            'published',
            'publisher_id'
        ]);

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $btn = '<a
                    href="' . url("books/" . $row->id . "/edit") . '"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i>
                </a>';

                $btn .= '&nbsp<a 
                    href="javascript:void(0)"
                    data-id="' . $row->id . '"
                    class="btn btn-danger btn-sm delete-book-btn">
                    <i class="fas fa-trash"></i>
                </a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create()
    {
        $authors = Author::all();
        $genres = Genre::all();
        $publishers = Publisher::all();

        return view('create')->with(compact('authors', 'genres', 'publishers'));
    }

    public function store(BooksRequest $request)
    {
        if ($request->has('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public', $imageName);
        }

        $book = Book::create([
            'title' => $request->get('title'),
            'isbn' => $request->get('isbn'),
            'author_id' => $request->get('author'),
            'genre_id' => $request->get('genre'),
            'publisher_id' => $request->get('publisher'),
            'published' => $request->get('published'),
            'description' => $request->get('description'),
            'image' => $request->has('image') ? Storage::url($imageName) : null
        ]);

        return response()->json($book, 201);
    }

    public function edit(Book $book)
    {
        $authors = Author::all();
        $genres = Genre::all();
        $publishers = Publisher::all();

        return view('edit')->with(compact('book', 'authors', 'genres', 'publishers'));
    }

    public function update(BooksRequest $request, Book $book)
    {
        $book->title = $request->get('title');
        $book->isbn = $request->get('isbn');
        $book->author_id = $request->get('author');
        $book->genre_id = $request->get('genre');
        $book->publisher_id = $request->get('publisher');
        $book->published = $request->get('published');
        $book->description = $request->get('description');

        if ($request->has('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public', $imageName);

            $book->image = Storage::url($imageName);
        }

        $book->save();

        return response()->json($book, 200);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([], 200);
    }
}
