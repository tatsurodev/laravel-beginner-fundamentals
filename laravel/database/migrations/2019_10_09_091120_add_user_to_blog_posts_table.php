<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            // test環境のsqlite(sqlite_testing)でカラムを追加する時、nullableかdefaultが必要
            if (env('DB_CONNECTION') === 'sqlite_testing') {
                $table->unsignedBigInteger('user_id')->default(0);
            } else {
                // 既存のblog_posts tableにuser_id fieldを追加するとデフォルト値がないのでエラーとなるのでnullableを指定する、art migrate:refreshの場合はdataをゼロから作るので不要
                // $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('user_id');
            }
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            // dropForeign(tablename_filedname_foreign), dropForeign([fieldname]): 引数を配列出ない方法で指定するとテーブル名とカラム名をつなげ"_foreign"を最後につけた引数を指定する必要があるので配列で指定する方が楽
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
