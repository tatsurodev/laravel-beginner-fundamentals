<?php

namespace Tests\Feature;

use App\Comment;
use App\BlogPost;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiPostCommentsTest extends TestCase
{
    use RefreshDatabase;

    // new postはno commentかassert
    public function testNewBlogPostDoesNotHaveComments()
    {
        factory(BlogPost::class)->create([
            'user_id' => $this->user()->id,
        ]);

        $response = $this->json('GET', 'api/v1/posts/1/comments');
        $response->assertStatus(200)
            // 指定したJSONの構造を持っているか
            ->assertJsonStructure(['data', 'links', 'meta'])
            // レスポンスJSONが、指定したキーのアイテムを指定した分持っているか
            ->assertJsonCount(0, 'data');
    }

    // 作ったpostに10のcommentをつけてassert
    public function testBlogPostHas10Comments() {
        factory(BlogPost::class)->create([
            'user_id' => $this->user()->id,
        ])->each(function(BlogPost $post) {
            $post->comments()->saveMany(
                factory(Comment::class, 10)->make([
                    'user_id' => $this->user()->id,
                ])
            );
        });

        // RefreshDatabase traitを使用してもauto_incrementの値は初期化されないので、上のtestNewBlogPostDoesNotHaveCommentsで作ったpost id 1の次の2がこのpostのidとなる
        $response = $this->json('GET', 'api/v1/posts/2/comments');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'content',
                        'created_at',
                        'updated_at',
                        'user' => [
                            'id',
                            'name'
                        ]
                    ]
                ],
                'links',
                'meta'
            ])
            ->assertJsonCount(10, 'data');

    }
}
