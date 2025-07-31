<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class cobacobalogin extends TestCase
{
    /**
     * A basic feature test example.
     */
    /** @test */
    public function cobalogin_returnstatus(){
        $response = $this->get('/login');
        $response -> assertStatus(200);
    }
}
