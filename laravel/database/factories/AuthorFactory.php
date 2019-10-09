<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Author;
use Faker\Generator as Faker;

$factory->define(Author::class, function (Faker $faker) {
    return [
        //
    ];
});

// afterCreating(model, callback)
// afterCreatingであるmodel instanceがdbに保存された際にcallbackとして処理を行うことができる
$factory->afterCreating(App\Author::class, function ($author, $faker) {
    // authorが作成された後、profileをrelationを指定して保存する
    $author->profile()->save(factory(App\Profile::class)->make());
});
