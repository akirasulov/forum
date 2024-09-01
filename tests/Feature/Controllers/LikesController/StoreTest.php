<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

it('requires authentication', function () {
    $this->post(route('likes.store', ['comment', 1]))->assertRedirect(route('login'));
});

it('allows liking a likeabke', function (Model $likeable) {

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

    expect($likeable->fresh()->likes_count)->toBe(1);

})->with([
    fn() => Post::factory()->create(),
    fn() => Comment::factory()->create(),
]);

it('prevents liking the same likeable twice', function () {

    $like = Like::factory()->create();
    $likeable = $like->likeable;

    $this->actingAs($like->user)
        ->post(route('likes.store', [$likeable->getMorphClass(), $likeable->id]))
        ->assertForbidden();
});

it('only allows like supported models', function () {
    $user = User::factory()->create();
    $this->actingAs($user)
        ->post(route('likes.store', [$user->getMorphClass(), $user->id]))
        ->assertForbidden();
});

it('throws a 404 if the type is not supported', function () {

    $this->actingAs(User::factory()->create())
        ->post(route('likes.store', ['foo', 1]))
        ->assertNotFound();
});
