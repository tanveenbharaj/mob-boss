<?php

namespace Tests\Feature;

use App\Models\Mob;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class MobDataTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function create_mob_datastore_when_passing_valid_data()
    {
        $this->disableProtectionHandling();
        //Arrange
        $mob_data = factory(\App\Models\Mob::class)->make();

        //Act
        $response = $this->json('post', 'api/mob', [
            'name' => $mob_data->name,
            'storage' => $mob_data->storage,
        ]);

        //Assert
        $response->assertStatus(201);

        $store_data = Mob::where('name', $mob_data->name)->first();
        $this->assertJsonStringEqualsJsonString($mob_data->storage, $store_data->storage);

    }

    /** @test */
    public function error_creating_mob_datastore_if_name_not_unique()
    {
        //Arrange
        $made_mob = factory(\App\Models\Mob::class)->create();
        $mob_data = factory(\App\Models\Mob::class)->make([
            "name" => $made_mob->name
        ]);

        //Act
        $response = $this->json('post', 'api/mob', [
            'name' => $mob_data->name,
            'storage' => $mob_data->storage,
        ]);

        //Assert
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "name"
            ]);

        $store_data = Mob::where('name', $mob_data->name)->first();
        $this->assertNotEquals($mob_data->storage, $store_data->storage);

    }

    /** @test */
    public function error_creating_mob_datastore_if_data_not_json()
    {

        // $this->disableProtectionHandling();

        //Arrange
        $mob_data = factory(\App\Models\Mob::class)->make();

        //Act
        $response = $this->json('post', 'api/mob', [
            'name' => $mob_data->name,
            'storage' => "this is a string",
        ]);

        //Assert
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "storage"
            ]);
        $store_data = Mob::where('name', $mob_data->name)->first();
        $this->assertEmpty($store_data);

    }

    /** @test */
    public function pull_mob_by_slug()
    {
        // $this->disableProtectionHandling();
         //Arrange
        $mob_data = factory(\App\Models\Mob::class)->create();

        //Act
        $response = $this->json('get', 'api/mob/' . $mob_data->slug);

        //Assert
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "name",
                "slug",
                "storage"
            ]);

        $decodeResponse = $response->decodeResponseJson();
        $this->assertJsonStringEqualsJsonString($mob_data->storage, $decodeResponse['storage']);
    }

    /** @test */
    public function if_slug_not_found_404()
    {
        //Arrange

        //Act
        $response = $this->json('get', 'api/mob/' . "UNKNOWNSLUG");

        //Assert
        $response
            ->assertStatus(404);
    }

    /** @test */
    public function update_storage_with_vaild_slug()
    {
        $this->disableProtectionHandling();
        $mob_data = factory(\App\Models\Mob::class)->create();
        $new_mob_data = factory(\App\Models\Mob::class)->make();

        $response = $this->json('put', 'api/mob/' . $mob_data->slug, [
            "storage" => $new_mob_data->storage
        ]);

        $response->assertStatus(200);

        $store_data = Mob::where('name', $mob_data->name)->first();
        $this->assertJsonStringEqualsJsonString($new_mob_data->storage, $store_data->storage);

    }

    /** @test */
    public function error_updating_without_valid_slug()
    {
        $mob_data = factory(\App\Models\Mob::class)->create();
        $new_mob_data = factory(\App\Models\Mob::class)->make();

        $response = $this->json('put', 'api/mob/' . "BADSLUG", [
            "storage" => $new_mob_data->storage
        ]);

        $response->assertStatus(404);

        $store_data = Mob::where('name', $mob_data->name)->first();
        $this->assertJsonStringNotEqualsJsonString($new_mob_data->storage, $store_data->storage);
    }

    /** @test */
    public function error_updating_with_invalid_data()
    {
        $mob_data = factory(\App\Models\Mob::class)->create();
        $new_mob_data = factory(\App\Models\Mob::class)->make();

        $response = $this->json('put', 'api/mob/' . $mob_data->slug, [
            "storage" => "Bad Data"
        ]);

        $response->assertStatus(422);

        $store_data = Mob::where('name', $mob_data->name)->first();
        $this->assertJsonStringNotEqualsJsonString($new_mob_data->storage, $store_data->storage);
    }

    /** @test */
    public function change_name_and_slug()
    {
        $this->disableProtectionHandling();
        $mob_data = factory(\App\Models\Mob::class)->create();
        $new_mob_name = "Moe's Mob"; //Try a name with special char

        $response = $this->json('put', 'api/changemobname', [
            "current" => $mob_data->slug,
            "name" => $new_mob_name
        ]);

        $response->assertStatus(200);

        $store_data = Mob::where('name', $new_mob_name)->first();
        $this->assertJsonStringEqualsJsonString($mob_data->storage, $store_data->storage);

    }

    /** @test */
    public function error_changing_name_without_current_slug()
    {
        $mob_data = factory(\App\Models\Mob::class)->create();
        $new_mob_data = factory(\App\Models\Mob::class)->make();

        $response = $this->json('put', 'api/changemobname', [
            "name" => $new_mob_data->name
        ]);

        $response->assertStatus(422);

        $store_data = Mob::where('name', $new_mob_data->name)->first();

        $this->assertEmpty($store_data);

    }

    /** @test */
    public function error_changing_name_without_a_name()
    {
        $mob_data = factory(\App\Models\Mob::class)->create();
        $new_mob_data = factory(\App\Models\Mob::class)->make();

        $response = $this->json('put', 'api/changemobname', [
            "current" => $mob_data->slug,
        ]);

        $response->assertStatus(422);

        $store_data = Mob::where('name', $new_mob_data->name)->first();

        $this->assertEmpty($store_data);

    }

    /** @test */
    public function mob_can_be_deleted()
    {
        $mob_data = factory(\App\Models\Mob::class)->create();

        $response = $this->json('DELETE', 'api/mob/' . $mob_data->slug);

        $response->assertStatus(200);
        $store_data = Mob::where('name', $mob_data->name)->first();

        $this->assertEmpty($store_data);
    }

}
