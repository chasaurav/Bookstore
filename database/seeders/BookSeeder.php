<?php

namespace Database\Seeders;

use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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

            foreach ($books['data'] as $book) {
                // Create a book table
                // Create a genre table
                // Create a author table
                // Create a publisers table

                // Book::create([]);

                

                $author = new Author()
                $author->save();

                $author->id;

                $book['author'];
                $book['publisher'];

                Log::debug($book);
            }
        }
    }
}
