<?php

namespace Tests\Feature;

use App\Property;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagePropertyTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_view_has_property_resources()
    {
        $this->withoutExceptionHandling();

        factory(Property::class, 20)->create();

        $this->get('/properties')
             ->assertStatus(200)
             ->assertViewHasAll(['properties' => Property::all()]);
    }

    public function test_that_welcomepage_redirects_to_properties()
    {
        $this->get('/')->assertRedirect('/properties');
    }
}
