<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeDeleteToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // cascadeを外部キーにつける為に一度、外部キー設定を外してから再度cascadeをつけて設定する
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['blog_post_id']);
            // 外部キーの参照先である親tableのrecordが削除されたら、この外部キーを持つrecordも削除する。
            // blog_posts.id=1が削除されたらcomments.blog_post_id=1のrecordも削除される
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['blog_post_id']);
            $table->foreign('blog_post_id')->references('id')->on('blog_posts');
        });
    }
}
