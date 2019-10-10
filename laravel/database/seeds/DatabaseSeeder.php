<?php

use Illuminate\Database\Seeder;
// use  Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 各テーブルごとのseederを左から右へ順番にcall。userを作成する前にblogpostを作成してもblog_posts tableのuser_idに割り当てるuserが未だ作成されていないのでエラーとなるのでcallする順番が重要
        // DatabaseSeederにSeederを変更する度、composer dump-autoloadが必要
        $this->call([UsersTableSeeder::class, BlogPostsTableSeeder::class, CommentsTableSeeder::class]);
    }
}
