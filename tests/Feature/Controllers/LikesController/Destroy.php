<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

it('requires authentication', function () {
    $this->delete(route('likes.destroy', ['comment', 1]))->assertRedirect(route('login'));
});

it('allows liking a likeable', function (Model $likeable) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->fromRoute('dashboard')
        ->post(route('likes.store', [$likeable->getMorphClass(), $likeable->id]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas(Like::class, [
        'user_id' => $user->id,
        'likeable_id' => $likeable->id,
        'likeable_type' => $likeable->getMorphClass(),
    ]);
    expect($likeable->refresh()->likes_count)->toBe(1);
})->with([
    fn () => Post::factory()->create(),
    fn () => Comment::factory()->create(),
]);

it('prevents unliking you have not like', function () {
    $likeable = Post::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('likes.destroy', [$likeable->getMorphClass(), $likeable->id]))
        ->assertForbidden();
});

it('only allows unliking supported models', function () {
    $user = User::factory()->create();
    $this->actingAs($user)
        ->delete(route('likes.destroy', [$user->getMorphClass(), $user->id]))
        ->assertForbidden();
});

it('throws a 404 if the type is not supported', function () {

    $this->actingAs(User::factory()->create())
        ->delete(route('likes.destroy', ['foo', 1]))
        ->assertNotFound();
});
