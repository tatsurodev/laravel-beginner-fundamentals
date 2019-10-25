<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPolymorphToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['blog_post_id']);
            $table->dropColumn('blog_post_id');

            $table->morphs('commentable');
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
            $table->dropMorphs('commentable');

            // comments tableにrecordは既にあるので外部キー設定時になにかしら値を設定するか、null okにするしかない。nullable()を設定しないと外部キーの制約エラーが出るので注意
            $table->unsignedBigInteger('blog_post_id')->index()->nullable();
            $table->foreign('blog_post_id')->references('id')->on('blog_posts');
        });
    }
}
