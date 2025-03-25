<?php
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class ProductTest extends TestCase
{
    public function test_access_list_of_products()
    {
        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
    }

    public function test_only_auth_can_create()
    {
        $response = $this->postJson('/api/products', [
            'category_id' => 1, 
            'title' => fake()->title,
            'description' => fake()->text,
            'price' => rand(100, 1000),
            'image' => UploadedFile::fake()->image('category.jpg'),
        ]);
        $response->assertStatus(401);
    }

    public function test_create_product()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->postJson('/api/products', [
            'category_id' => 1, 
            'title' => fake()->title,
            'description' => fake()->text,
            'price' => rand(100, 1000),
            'images' => [UploadedFile::fake()->image('product.jpg'),UploadedFile::fake()->image('product1.jpg')],
        ]);
        $response->assertStatus(200);
    }

    public function test_update_product()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->postJson('/api/products/1', [
            'category_id' => 1, 
            'title' => 'lenovo',
            'description' => '16gb ram',
            'price' => rand(100, 1000),
            'images' => [UploadedFile::fake()->image('product.jpg'),UploadedFile::fake()->image('product1.jpg')],
        ]);
        $response->assertStatus(200);
    }
}