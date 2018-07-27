<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    public function testAThreadHasReplies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function testAThreadHasACreator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    public function testAThreadCanAddAReply()
    {
        $this->thread->addReply([
            'body' => 'FooBar',
            'user_id' => 1,
        ]);
        $this->assertCount(1, $this->thread->replies);
    }
}
