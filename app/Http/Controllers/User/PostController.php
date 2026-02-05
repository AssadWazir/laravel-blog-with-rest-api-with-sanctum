<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    use AuthorizesRequests;

    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display all posts by the logged-in user.
     */
    public function index(): View
    {
        $user = auth()->user();
        $posts = $this->postService->getUserPosts($user, 100);

        return view('user.posts.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        return view('user.posts.create');
    }

    /**
     * Store a newly created post in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        $validated = $request->validate(
            $this->postService->getValidationRules('store')
        );

        $this->postService->createPost($user, $validated);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post): View
    {

        return view('user.posts.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        return view('user.posts.edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified post in the database.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $validated = $request->validate(
            $this->postService->getValidationRules('update')
        );

        $this->postService->updatePost($post, $validated);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified post from the database.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $this->postService->deletePost($post);

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}
