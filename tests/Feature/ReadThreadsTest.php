<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    function test_a_user_can_view_all_threads()
    {
        $this->get('threads')
            ->assertSee($this->thread->title);
    }

    function test_a_user_can_view_a_single_thread()
    {
        // $this->get('threads/' . $this->thread->id)
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    function test_a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // And that thread includes replies
        $reply = factory(\App\Reply::class)
            ->create(['thread_id' => $this->thread->id]);

        // When we visit a thread page
        $this->get($this->thread->path())
            // Then we should see the replies.
            ->assertSee($reply->body);
    }
}
