<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $books = Book::limit(5)->get();

        return view('landing')->with(compact('books'));
    }
}
