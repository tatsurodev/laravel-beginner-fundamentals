<?php

use App\Tag;
use Illuminate\Database\Seeder;
use App\BlogPost;

class BlogPostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // tag数
        $tagCount = Tag::all()->count();
        // tag数ゼロならinfo
        if (0 === $tagCount) {
            $this->command->info('No tags found, skipping assigning tags to blog posts');
        }
        // 各postにつけるtag数の最小値
        $howManyMin = (int) $this->command->ask('Minimun tags no blog post?', 0);
        // 各postにつけるtag数の最大値
        $howManyMax = min((int) $this->command->ask('Maximum tags on blog post?', $tagCount), $tagCount);

        // postsのそれぞれに対してrelationでtagをsync
        BlogPost::all()->each(function (BlogPost $post) use ($howManyMin, $howManyMax) {
            // このpostで使用するtag数
            $take = random_int($howManyMin, $howManyMax);
            // tagsをランダムに並べなおしてtag数分のcollectionを取得し、idのみ抽出
            $tags = Tag::inRandomOrder()->take($take)->get()->pluck('id');
            // relationでpostにtagをsync
            $post->tags()->sync($tags);
        });
    }
}
