<?php

namespace Tests\Feature;

use App\BlogPost;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    // 各テスト(メソッド)の後にDBをリセットしてくれるトレイト。毎回テストした後、DBをまっさらにしてくれるので他のテストの影響を受けずにテストを行うことができる
    use RefreshDatabase;

    // postゼロの時のテスト
    public function testNoBlogPostsWhenNothingInDatabase()
    {
        // actoin
        $response = $this->get('/posts');
        // assert
        $response->assertSeeText('No blog posts yet!');
    }

    // postが1つのみのテスト
    public function testSee1BlogPostWhenThereIs1()
    {
        // arrange
        $post = new BlogPost();
        $post->title = 'New title';
        $post->content = 'Content of the blog post';
        $post->save();

        // act
        $response = $this->get('/posts');

        // assert
        $response->assertSeeText('New title');

        // table内にデータがあるかどうかチェック
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New title'
        ]);
    }

    public function testStoreValid()
    {
        // arrange
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters',
        ];
        // action, assert
        // http verb: post(endpoint, data)
        // post成功時、redirect(302)され、sessionのキーstatusに成功時のメッセージが格納される
        $this->post('/posts', $params)->assertStatus(302)->assertSessionHas('status');
        // sessionのstatusキーの値をテスト
        $this->assertEquals(session('status'), 'Blog post was created!');
    }

    public function testStoreFail()
    {
        $params = [
            'title' => 'x',
            'content' => 'x',
        ];
        $this->post('/posts', $params)->assertStatus(302)->assertSessionHas('errors');
        // dd(session('errors'));
        $messages = session('errors')->getMessages();
        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }
}
