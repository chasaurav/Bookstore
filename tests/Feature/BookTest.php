<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use Tests\WithStubUser;
use Faker\Factory as Faker;
use Illuminate\Testing\Fluent\AssertableJson;

class BookTest extends TestCase
{
    use WithStubUser;

    public function test_index_authentication()
    {
        $this->assertAuthenticationRequired('/books');
        $this->assertAuthenticationRequired('/books/datatable');
        $this->assertAuthenticationRequired('/books/{book}/edit');
    }

    public function test_index_view()
    {
        $user = $this->createStubUser();
        $response = $this->actingAs($user)->get('/books');

        $response->assertStatus(200);
        $response->assertSee('container');
        $this->deleteStubUser();
    }

    public function test_authenticated_user_can_create_new_book()
    {
        $user = User::first();
        $this->actingAs($user)->get('/books/create')
            ->assertStatus(200)
            ->assertViewIs('create')
            ->assertViewHas('authors')
            ->assertViewHas('genres')
            ->assertViewHas('publishers');

        $this->post('/books', [
            'title' => 'Test book',
            'author' => 2,
            'genre' => 3,
            'description' => $this->faker()->paragraph(),
            'isbn' => '123-4-56-123893-2',
            'published' => now()->format('d-m-Y'),
            'publisher' => 1,
        ])->assertJson(fn (AssertableJson $json) => $json->where('isbn', '1234561238932')->where('title', 'Test book')->etc());
    }

    public function test_it_checks_for_invalid_post_data()
    {
        $user = User::first();
        $this->actingAs($user);

        $this->postJson('/books', [
            'title' => 'Test book',
            'published' => now()->format('m-d-Y'),
            'publisher' => 1,
        ])->assertJsonStructure(['message', 'errors' => ['isbn', 'author', 'genre', 'description']]);
    }

    public function test_authenticated_user_can_edit_an_existing_book()
    {
        $book = $this->createBook();

        $this->get("/books/{$book->id}/edit")
            ->assertStatus(200)
            ->assertViewIs('edit')
            ->assertViewHas('book');

        $this->post(
            "/books/{$book->id}/update",
            array_merge(
                $book->toArray(),
                [
                    'title' => 'Updated test book title',
                    'isbn' => '123-4-56-123893-2',
                    'author' => $book->author_id,
                    'genre' => $book->genre_id,
                    'publisher' => $book->publisher_id
                ]
            )
        )
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->where('title', 'Updated test book title')->etc());
    }

    public function test_authenticated_user_can_delete_existing_book() {
        $book = $this->createBook();

        $this->delete("/books/{$book->id}/delete")
            ->assertStatus(200);
    }

    private function faker()
    {
        return Faker::create();
    }

    private function createBook($authenticated = true)
    {
        $book = Book::factory()->create();

        if ($authenticated) {
            $this->actingAs(User::first());
        }

        return $book;
    }
}