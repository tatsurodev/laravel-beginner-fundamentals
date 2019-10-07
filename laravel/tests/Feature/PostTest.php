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
}
