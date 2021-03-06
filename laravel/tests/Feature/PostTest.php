<?php

namespace Tests\Feature;

use App\Comment;
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
    public function testSee1BlogPostWhenThereIs1WithoNoComments()
    {
        // arrange
        $post = $this->createDummyBlogPost();

        // act
        $response = $this->get('/posts');

        // assert
        $response->assertSeeText('New title');
        // 作りたてのpostなのでno commentのassertion
        $response->assertSeeText('No comments yet');

        // table内にデータがあるかどうかチェック
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New title'
        ]);
    }

    // blogとcommentsを作成してassert
    public function testSee1BlogPostWithComments()
    {
        // arrange
        // commentするuser
        $user = $this->user();
        $post = $this->createDummyBlogPost();
        // factory(クラス, 作成数)->make() or create()、作成数は省略すると1
        // create methodに配列を渡すことでモデルの属性をoverride
        factory(Comment::class, 4)->create([
            'commentable_id' => $post->id,
            'commentable_type' => 'App\BlogPost',
            'user_id' => $user->id,
        ]);
        // act
        $response = $this->get('/posts');
        $response->assertSeeText('4 comments');
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
        // PostController@storeはauth middlewareが設定されているので、actingAsでlogin userをセットする必要あり。また$thisを返すので他のmethodをそのままチェーンできる
        $this->actingAs($this->user())->post('/posts', $params)->assertStatus(302)->assertSessionHas('status');
        // sessionのstatusキーの値をテスト
        $this->assertEquals(session('status'), 'Blog post was created!');
    }

    public function testStoreFail()
    {
        $params = [
            'title' => 'x',
            'content' => 'x',
        ];
        $this->actingAs($this->user())->post('/posts', $params)->assertStatus(302)->assertSessionHas('errors');
        // dd(session('errors'));
        $messages = session('errors')->getMessages();
        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function testUpdateValid()
    {
        // authorize用user作成、格納
        $user = $this->user();
        // user.idを指定して、store用data用意
        $post = $this->createDummyBlogPost($user->id);

        // assertDatabaseHas(table, array): tableに$post model instanceの配列版があるかassert
        $this->assertDatabaseHas('blog_posts', $post->toArray());

        // update用data用意
        $params = [
            'title' => 'A new named title',
            'content' => 'Content was changed'
        ];
        // putでupdateして、session key, statusがあるかassert
        $this->actingAs($user)->put("/posts/{$post->id}", $params)->assertStatus(302)->assertSessionHas('status');
        // sessionのstatusキーの値をテスト
        $this->assertEquals(session('status'), 'Blog post was updated!');
        // update前の$postが消えていることをassert
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
        // update後のdataがあることをassert
        $this->assertDatabaseHas('blog_posts', $params);
    }

    public function testDelete()
    {
        // authorize用user作成、格納
        $user = $this->user();
        // user.idを指定して、検証用post作成
        $post = $this->createDummyBlogPost($user->id);
        // 検証用postがあるかassert
        $this->assertDatabaseHas('blog_posts', $post->toArray());
        // deleteして、session key, statusがあるかassert
        $this->actingAs($user)->delete("/posts/{$post->id}")->assertStatus(302)->assertSessionHas('status');
        // deleteの第一引数は名前付きrouteを使ってもおｋ
        // $this->delete(route('posts.destroy', $post->id))->assertStatus(302)->assertSessionHas('status);
        $this->assertEquals(session('status'), 'Blog post was deleted!');
        // $this->assertDatabaseMissing('blog_posts', $post->toArray());
        // softdeletesに対応したassert
        $this->assertSoftDeleted('blog_posts', $post->toArray());
    }

    // 最初の検証用post instanceを作成する関数、返り値はBlogPost
    // PostController@update, deleteはauthorizeとしてpostを作成したuserが必要。よって引数にpost instanceを作成するuserを指定、なければその場で作ってuser.idをセット
    private function createDummyBlogPost($userId = null): BlogPost
    {
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'Content of the blog post';
        // $post->save();
        // return $post;

        // factory stateを使用
        return factory(BlogPost::class)->states('new-title')->create([
            'user_id' => $userId ?? $this->user()->id,
        ]);
    }
}
