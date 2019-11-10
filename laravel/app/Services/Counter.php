<?php

namespace App\Services;

// use Illuminate\Support\Facades\Cache;
use App\Contracts\CounterContract;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Cache\Factory as Cache;

class Counter implements CounterContract
{
    private $timeout;
    private $cache;
    private $session;
    private $supportsTags;

    public function __construct(Cache $cache, Session $session, int $timeout)
    {
        $this->timeout = $timeout;
        $this->cache = $cache;
        $this->session = $session;
        $this->supportsTags = method_exists($cache, 'tags');
    }

    public function increment(string $key, array $tags = null): int
    {
        dump($this->session);
        dd($this->cache);
        // session idを取得
        $sessionId = $this->sesison->getId();
        // 閲覧中user数を保存するキー
        $counterKey = "{$key}-counter";
        // 閲覧中のusersを配列に保存するキー
        $usersKey = "{$key}-users";

        $cache = $this->supportsTags && null !== $tags ? $this->cache->tags($tags) : $this->cache;

        // usersをキャッシュから復元、キーがなければ初アクセスなので空配列セット
        $users = $cache->get($usersKey, []);
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
        $cache->forever($usersKey, $usersUpdate);

        // counterをcacheに保存
        if (!$cache->has($counterKey)) {
            $cache->forever($counterKey, 1);
        } else {
            $cache->increment($counterKey, $diffrence);
        }

        // 現counterの値を取得
        $counter = $cache->get($counterKey);

        return $counter;
    }
}
