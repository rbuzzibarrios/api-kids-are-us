<?php

namespace Tests\Feature\Product;

use Tests\TestCase;

class SearchProductTest extends TestCase
{
    public function testBasic()
    {
        $response = $this->get(route('search.product'), ['name' => 'hic']);

        $response->dump();

        $response->assertStatus(200);
    }
}