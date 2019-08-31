<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    function test_guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    function test_an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        // /replies/id/favorites
        $reply = create('App\Reply');

        // If I post to a "favorite" endpoint
        $this->post('replies/' . $reply->id . '/favorites');

        // dd(\App\Favorite::all());

        // It should be recorded in the database.
        $this->assertCount(1, $reply->favorites);
    }

    function test_an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }
        // dd(\App\Favorite::all()->toArray());

        $this->assertCount(1, $reply->favorites);
    }
}
