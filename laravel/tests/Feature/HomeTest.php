<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    // test名自体はphpunitで自動的に呼ばれるためできるだけdescriptiveに
    public function testHomePageIsWorkingCorrectly()
    {
        $response = $this->get('/');

        // textがあるかどうか確認
        $response->assertSeeText('Welcome to Laravel!');
        $response->assertSeeText('This is the ontent of the main page!');
    }

    public function testContactPageIsWorkingCorrectly()
    {
        $response = $this->get('/contact');

        $response->assertSeeText('Contact');
        $response->assertSeeText('Hello this is contact!');
    }
}
