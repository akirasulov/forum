<?php

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use function Pest\Laravel\get;

it('can show a post', function () {
    $post = Post::factory()->create();

    get($post->showRoute())
        ->assertComponent('Posts/Show');
});

it('passes a post to the view', function () {
    $post = Post::factory()->create();

    $post->load(['user', 'topic', 'likes']);

    get($post->showRoute())
        ->assertHasResource('post', PostResource::make($post->load('user'))->withLikePermission());
});

it('passes comments to the view', function () {
    $post = Post::factory()->create();
    $comments = Comment::factory(2)->for($post)->create();

    $comments->load('user');

    $expectedResource = CommentResource::collection($comments->reverse());

    $expectedResource->collection->transform(fn($resource) => $resource->withLikePermission());

    get($post->showRoute())
        ->assertHasPaginatedResource('comments', $expectedResource);
});

it('will redirect if the slug is invalid', function (string $invalidSlug) {
    $post = Post::factory()->create(['title' => 'Hello world']);
    get(route('posts.show', [$post, $invalidSlug, 'page' => 2]))
        ->assertRedirect($post->showRoute(['page' => 2]));
})
    ->with([
        'foo-bar',
        'hello',
    ]);
