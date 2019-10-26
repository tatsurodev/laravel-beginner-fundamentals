<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBlogPostTagTableToTaggables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // table名を変更して外部キーを削除する時、最初に外部キーを削除してからtable名の変更をする。最初にtable名を変更してしまうと、dropForeign methodで変更前のtable名を使ってfullの外部キー名をlaravelが作ってしまうため
        Schema::table('blog_post_tag', function (Blueprint $table) {
            $table->dropForeign(['blog_post_id']);
            $table->dropColumn('blog_post_id');
        });
        // table名をtaggablesにするのは、polymorphicにするTag modelで使用するmorphedByMany methodがdefaultで指定するprefix名を指定すると自動的にそれを複数形にしたtable名とリンクしてくれるため
        Schema::rename('blog_post_tag', 'taggables');
        Schema::table('taggables', function (Blueprint $table) {
            $table->morphs('taggable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taggables', function (Blueprint $table) {
            $table->dropMorphs('taggable');
        });
        Schema::rename('taggables', 'blog_post_tag');
        Schema::disableForeignKeyConstraints();
        Schema::table('blog_post_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('blog_post_id')->index();
            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }
}
