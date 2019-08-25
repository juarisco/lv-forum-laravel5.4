<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    function test_a_user_can_view_all_threads()
    {
        $thread = factory(\App\Thread::class)->create();

        $response = $this->get('threads');
        $response->assertSee($thread->title);
    }

    function test_a_user_can_view_a_single_thread()
    {
        $thread = factory(\App\Thread::class)->create();

        $response = $this->get('threads/' . $thread->id);
        $response->assertSee($thread->title);
    }
}
