<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function get_mod_data_with_valid_slug()
    {
        $this->disableProtectionHandling();
        //Arrange
        $mob_data = factory(\App\Models\Mob::class)->create();

        //Act
        $response = $this->json('get', 'report/' . $mob_data->slug);

        //Assert
        $response->assertStatus(200);
        $response->assertViewHas("mob", function ($viewMob) use ($mob_data) {
            return $mob_data->name === $viewMob->name;
        });
    }
}
