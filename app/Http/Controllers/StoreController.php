<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function searchBooks(Request $request)
    {
        $books = Book::search($request->get('query'))->paginate(8);
        return response()->json($books, 200);
    }
}
