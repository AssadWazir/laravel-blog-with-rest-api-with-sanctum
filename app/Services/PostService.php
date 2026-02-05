<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;

use Illuminate\Pagination\LengthAwarePaginator;


class PostService
{
    /**
     * Get all posts for a user with pagination.
     */
    public function getUserPosts(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $user->posts()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get a single post by ID.
     */
    public function getPost(int $postId): ?Post
    {
        return Post::find($postId);
    }

    /**
     * Create a new post for a user.
     */
    public function createPost(User $user, array $data): Post
    {
        return $user->posts()->create([
            'title' => $data['title'],
            'body' => $data['body'],
        ]);
    }

    /**
     * Update an existing post.
     */
    public function updatePost(Post $post, array $data): Post
    {
        $post->update([
            'title' => $data['title'] ?? $post->title,
            'body' => $data['body'] ?? $post->body,
        ]);

        return $post->refresh();
    }

    /**
     * Delete a post.
     */
    public function deletePost(Post $post): bool
    {
        return $post->delete();
    }

    /**
     * Check if a user owns the post.
     */
    public function isOwner(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Get validation rules for post creation/update.
     */
    public function getValidationRules(string $action = 'store'): array
    {
        $baseRules = [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ];

        return $baseRules;
    }
}
