<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    public function testAUserCanViewAllThreads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    public function testAUserCanReadASingleThread()
    {
        $this->get('/threads/' . $this->thread->id)->assertSee($this->thread->title);
    }

    public function testAUserCanReadAppliesAssociatedWithAThread()
    {
        $reply = factory('App\Reply')->create([
           'thread_id' => $this->thread->id,
        ]);
        $this->get('/threads/' . $this->thread->id)->assertSee($reply->body);
    }
}
