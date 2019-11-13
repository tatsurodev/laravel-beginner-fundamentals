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
        $this->blogPost();

        $response = $this->json('GET', 'api/v1/posts/1/comments');
        // 200 OK リクエスト成功
        $response->assertStatus(200)
            // 指定したJSONの構造を持っているか
            ->assertJsonStructure(['data', 'links', 'meta'])
            // レスポンスJSONが、指定したキーのアイテムを指定した分持っているか
            ->assertJsonCount(0, 'data');
    }

    // 作ったpostに10のcommentをつけてassert
    public function testBlogPostHas10Comments() {
        $this->blogPost()->each(function(BlogPost $post) {
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

    // non-authorized userのcomment投稿はunauthrizedかassert
    public function testAddingCommentsWhenNoAuthenticated(){
        $this->blogPost();
        $response = $this->json('POST', 'api/v1/posts/3/comments', [
            'content' => 'Hello',
        ]);
        // 401 Unauthorized
        // $response->assertStatus(401);
        // 上下は同義
        $response->assertUnauthorized();
    }

    // authorized userのcomment投稿はauthrizedかassert
    public function testAddingCommentsWhenAuthenticated()
    {
        $this->blogPost();
        // api
        $response = $this->actingAs($this->user(), 'api')->json('POST', 'api/v1/posts/4/comments', [
            'content' => 'Hello',
        ]);
        // 201 created、リソース作成のリクエスト完了
        $response->assertStatus(201);
    }

    // 空のcomment投稿で422のvalidationerrorかassert
    public function testAddingCommentWithInvalidData(){
        $this->blogPost();
        $response = $this->actingAs($this->user(), 'api')->json('POST', 'api/v1/posts/5/comments', []);
        // 422 処理できないエンティティ、validation error
        $response->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'content' => [
                    'The content field is required.',
                ],
            ],
        ]);
    }
}
