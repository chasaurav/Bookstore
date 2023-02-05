<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();
        $query = $client->get('https://fakerapi.it/api/v1/books?_quantity=100');
        $books = $query->getBody();

        if ($books) {
            $books = json_decode($books, true);

            $authorCollection = collect();
            $genreCollection = collect();
            $publiserCollection = collect();

            foreach ($books['data'] as $book) {
                if (!isset($authorCollection[$book['author']])) {
                    $author = Author::create([
                        'name' => $book['author']
                    ]);

                    $authorCollection[$book['author']] = $author->id;
                }

                if (!isset($genreCollection[$book['genre']])) {
                    $genre = Genre::create([
                        'name' => $book['genre']
                    ]);

                    $genreCollection[$book['genre']] = $genre->id;
                }

                if (!isset($publiserCollection[$book['publisher']])) {
                    $publisher = Publisher::create([
                        'name' => $book['publisher']
                    ]);

                    $publiserCollection[$book['publisher']] = $publisher->id;
                }

                Book::create([
                    'title' => $book['title'],
                    'author_id' => isset($authorCollection[$book['author']]) ? $authorCollection[$book['author']] : null,
                    'genre_id' => isset($genreCollection[$book['genre']]) ? $genreCollection[$book['genre']] : null,
                    'description' => $book['description'],
                    'isbn' => $book['isbn'],
                    'image' => $book['image'],
                    'published' => $book['published'],
                    'publisher_id' => isset($publiserCollection[$book['publisher']]) ? $publiserCollection[$book['publisher']] : null
                ]);
            }
        }
    }
}
