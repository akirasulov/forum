<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

it('requires authentication', function () {
    put(route('comments.update', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can update comment', function () {
    $comment = Comment::factory()->create(['body' => 'Old body']);
    $newBody = 'New body';

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => $newBody]);

    $this->assertDatabaseHas(Comment::class, [
        'id' => $comment->id,
        'body' => $newBody,
    ]);
});

it('redirects to the post show page', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => 'New body'])
        ->assertRedirect($comment->post->showRoute());
});

it('redirects to the current page of comments', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page' => 2]), ['body' => 'New body'])
        ->assertRedirect($comment->post->showRoute(['page' => 2]));
});

it('cannot update a comment from anothe user', function () {
    $comment = Comment::factory()->create();

    actingAs(User::factory()->create())
        ->put(route('comments.update', $comment), ['body' => 'New body'])
        ->assertForbidden();
});

it('requires a valid body', function ($body) {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => $body])
        ->assertInvalid('body');
})->with([
    null,
    true,
    1,
    1.5,
    fake()->paragraph(2501),
]);
