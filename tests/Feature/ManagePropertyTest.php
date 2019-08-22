<?php

namespace Tests\Feature;

use App\Property;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagePropertyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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

    public function test_it_can_view_specific_property_resource()
    {
        $this->withoutExceptionHandling();

        $property = factory(Property::class)->create();

        $this->get("/properties/{$property->id}")
             ->assertStatus(200)
             ->assertViewHas('property');
    }

    public function test_guest_cannot_create_properties()
    {
        $this->get("/properties/create")->assertRedirect('login');
        $this->post('/properties', [])->assertRedirect('login');
    }

    public function test_authorized_user_can_access_create_a_property_form()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->get("/properties/create")->assertStatus(200);
    }

    public function test_authorized_user_can_create_a_property()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $attributes = factory(Property::class)->raw();

        $this->actingAs($user)
             ->post("/properties", $attributes)
             ->assertRedirect('/properties');

        $this->assertDatabaseHas('properties', $attributes);
    }
}
