<?php

namespace Tests\Feature\News;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;

use Tests\TestCase;

class getListTest extends TestCase
{

    /** @test **/
    public function get_list()
    {
        $Response = $this->getJson(route('news.index'));
        $Response->assertStatus(Response::HTTP_OK);
    }
}
