<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing, deleteJson, getJson, postJson, putJson};

test('access list of category', function() {
    $categories = Category::all();

    $response = getJson('/api/categories');

    $response->assertStatus(200);
});

test('show one category', function() {
    $categories = Category::all();

    $response = getJson('/api/categories/1');

    $response->assertStatus(200);
});

test('only auth user can create category', function () {

    $response = postJson('/api/categories', [
        'title' => 'product 1',
        'image' => UploadedFile::fake()->image('category.jpg'),
    ]);

    $response->assertStatus(401);
});

test('only auth user can delete category', function () {
    
    $response = deleteJson('/api/categories/1', []);

    $response->assertStatus(401);
});

test('Create category', function() {
    $user = User::find(1);

    actingAs($user);

    $response = postJson('/api/categories', [
        'title' => fake()->title,
        'image' => UploadedFile::fake()->image('product.jpg'),
    ]);

    $response->assertStatus(200);
});

test('forgot required input', function() {
    $user = User::find(1);

    actingAs($user);

    $response = postJson('/api/categories', [
        'title' => fake()->title,
    ]);

    $response->assertStatus(422);
});