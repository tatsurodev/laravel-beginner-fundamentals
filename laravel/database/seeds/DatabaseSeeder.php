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
        // art db:seedを実行する際、dataを初期化するので再確認の処理を追加
        // yes or noをconfirm、yesならart migrate:refresh。defaultはno
        if ($this->command->confirm('Do you want to refresh the database?')) {
            // php artisan以降のcommandをcall
            $this->command->call('migrate:refresh');
            // 終了後に表示するメッセージ
            $this->command->info('Database was refreshed');
        }

        // blog関連のcacheを削除
        Cache::tags(['blog-post'])->flush();

        // 各テーブルごとのseederを左から右へ順番にcall。userを作成する前にblogpostを作成してもblog_posts tableのuser_idに割り当てるuserが未だ作成されていないのでエラーとなるのでcallする順番が重要
        // DatabaseSeederにSeederを変更する度、composer dump-autoloadが必要
        $this->call([UsersTableSeeder::class, BlogPostsTableSeeder::class, CommentsTableSeeder::class, TagsTableSeeder::class, BlogPostTagTableSeeder::class]);
    }
}
