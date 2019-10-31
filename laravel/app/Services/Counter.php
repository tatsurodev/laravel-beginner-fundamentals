<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Counter
{
    private $timeout;

    public function __construct(int $timeout)
    {
        $this->timeout = $timeout;
    }

    public function increment(string $key, array $tags = null): int
    {
        // session idを取得
        $sessionId = session()->getId();
        // 閲覧中user数を保存するキー
        $counterKey = "{$key}-counter";
        // 閲覧中のusersを配列に保存するキー
        $usersKey = "{$key}-users";
        // usersをキャッシュから復元、キーがなければ初アクセスなので空配列セット
        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        // アクセスしてきたuserを保存する配列で、一定時間(この場合1分)を超えていたら削除する
        $usersUpdate = [];
        // counterの増減
        $diffrence = 0;
        // 現時刻
        $now = now();

        // usersを一定時間超えていたらdiffrenceを-1、そうでなければcacheに保存する用の変数にsession idと現時間格納
        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= $this->timeout) {
                $diffrence--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        // 初アクセス or アクセス済だが一定時間を超えていた場合、diffrenceを+1
        if (!array_key_exists($sessionId, $users) || $now->diffInMinutes($users[$sessionId]) >= $this->timeout) {
            $diffrence++;
        }

        // 現ユーザーのアクセス時間を更新
        $usersUpdate[$sessionId] = $now;

        // usersをcacheに保存
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);

        // counterをcacheに保存
        if (!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $diffrence);
        }

        // 現counterの値を取得
        $counter = Cache::tags(['blog-post'])->get($counterKey);

        return $counter;
    }
}
