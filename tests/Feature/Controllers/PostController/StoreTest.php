<?php

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->validData = fn () => [
        'title' => fake()->sentence(6),
        'topic_id' => Topic::factory()->create()->getKey(),
        'body' => fake()->paragraph(100),
    ];
});

it('requires authentication', function () {
    post(route('posts.store'))->assertRedirect(route('login'));
});

it('stores a post', function () {
    $user = User::factory()->create();
    $data = value($this->validData);

    actingAs($user)->post(route('posts.store'), $data);

    $this->assertDatabaseHas(Post::class, [
        ...$data,
        'user_id' => $user->id,
    ]);
});

it('redirects to the post show page', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), value($this->validData))
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires valid data', function (array $badData, array|string $errors) {
    actingAs(User::factory()->create())
        ->post(route('posts.store'), [...value($this->validData), ...$badData])
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => true], 'title'],
    [['title' => 1], 'title'],
    [['title' => 1.5], 'title'],
    [['title' => fake()->paragraph(121)], 'title'],
    [['title' => fake()->text(9)], 'title'],
    [['topic_id' => null], 'topic_id'],
    [['body' => null], 'body'],
    [['body' => true], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => fake()->paragraph(10_001)], 'body'],
    [['body' => fake()->text(99)], 'body'],
]);
