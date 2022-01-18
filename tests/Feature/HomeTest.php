<?php

namespace Tests\Feature;


use Tests\TestCase;

class HomeTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomePage()
    {

        $response=$this->get('/');
        $response->assertSeeText('hello world');
    }

}
