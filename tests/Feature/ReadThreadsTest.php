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
        $this->get($this->thread->path())->assertSee($this->thread->title);
    }

    public function testAUserCanReadAppliesAssociatedWithAThread()
    {
        $reply = factory('App\Reply')->create([
           'thread_id' => $this->thread->id,
        ]);
        $this->get($this->thread->path())->assertSee($reply->body);
    }

    public function testAUserCanFilterThreadsAccordingToChannel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);

    }

    public function testAUserCanFilterThreadsByAnyUsername()
    {
        $this->signIn(create('App\User', ['name' => 'Foo']));
        $threadByFoo = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByFoo = create('App\Thread');

        $this->get('/threads?by=Foo')
            ->assertSee($threadByFoo->title)
            ->assertDontSee($threadNotByFoo->title);
    }

    public function testAUserCanFilterThreadsByPopularity()
    {
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply',['thread_id'=>$threadWithTwoReplies->id],2);

        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply',['thread_id'=>$threadWithThreeReplies->id],3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popularity=1')->json();

        $this->assertEquals([3,2,0],array_column($response,'replies_count'));
    }
}
