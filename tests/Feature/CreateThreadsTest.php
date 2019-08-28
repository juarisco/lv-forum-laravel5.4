<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    function test_guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads', [])
            ->assertRedirect('/login');
    }

    function test_an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have a signed in user
        $this->signIn();

        // When we hit the endpoint to create a new thread
        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        // dd($thread->path());
        // dd($response->headers->get('Location'));

        // Then, when we visit the thread page.
        $this->get($response->headers->get('Location'))
            // We should see the new thread
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    function test_a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    function test_a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    function test_a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 3])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        // dd($thread);

        return $this->post('/threads', $thread->toArray());
    }
}
