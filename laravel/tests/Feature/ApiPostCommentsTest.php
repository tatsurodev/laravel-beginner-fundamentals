<?php

namespace Tests\Feature;

use App\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiPostCommentsTest extends TestCase
{
    use RefreshDatabase;

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
}
