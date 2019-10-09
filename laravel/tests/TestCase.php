<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // RefreshDatabase traitで毎testごとにdbが真っさらになるので、全TestのベースとなるTestCaseにuser作成用のmethod作成していると便利
    protected function user()
    {
        // UserFactoryはdefaultで用意されている
        return factory(User::class)->create();
    }
}
