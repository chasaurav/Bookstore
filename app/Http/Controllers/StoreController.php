<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $authors = Author::distinct()->get();
        $genres = Genre::distinct()->get();
        $isbns = Book::select('isbn')->without(['author', 'genre', 'publisher'])->distinct()->get();

        return view('landing')->with(compact('authors', 'genres', 'isbns'));
    }

    public function searchBooks(Request $request)
    {
        $query = empty(trim($request->get('query')))
            ? Book::search()
            : Book::search($request->get('query'))->where('title', $request->get('query'));

        if ($request->has('authorId')) {
            $query->where('author_id', $request->get('authorId'));
        }

        if ($request->has('genreId')) {
            $query->where('genre_id', $request->get('genreId'));
        }

        if ($request->has('isbn')) {
            $query->where('isbn', $request->get('isbn'));
        }

        if ($request->has('dateFrom') && $request->has('dateTo')) {
            $query->query(function ($query) use ($request) {
                $query->whereBetween('published', [$request->get('dateFrom'), $request->get('dateTo')]);
            });
        }

        $books = $query->paginate(8);

        return response()->json($books, 200);
    }
}
