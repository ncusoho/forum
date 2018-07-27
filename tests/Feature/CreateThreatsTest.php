<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreatsTest extends TestCase
{
    use DatabaseMigrations;

    public function testAnAuthenticatedUserCanCreateNewForumThreads()
    {
        $user = factory('App\User')->create();
        $this->actingAs($user);
        $thread = factory('App\Thread')->create(['user_id' => $user->id]);
        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    public function testGuestsMayNotCreateThreads()
    {
        $this->withExceptionHandling();

        $this->post('/threads')
            ->assertRedirect('/login');


        $this->get('/threads/create')
            ->assertRedirect('/login');
    }
}
