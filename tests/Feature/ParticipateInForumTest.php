<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    public function testAnAuthenticatedUserMayParticipateInForumThreads()
    {
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    public function testUnauthenticatedUserMayNoAddReplies()
    {
        $this->withExceptionHandling()
            ->post('threads/some-channel/1/replies',[])
            ->assertRedirect('/login');

    }

}
