<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use AuthorizesRequests;

    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        // Only apply auth:sanctum to protected routes, not public ones
       // $this->middleware('auth:sanctum')->except(['publicIndex', 'publicShow']);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $posts = $this->postService->getUserPosts($user, $request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->postService->getValidationRules('store'));
        $post = $this->postService->createPost($request->user(), $validated);

        return response()->json([
            'success' => true,
            'data' => $post,
        ], 201);
    }

    public function show(Post $post): JsonResponse
    {
        $this->authorize('view', $post); // Ensure user can view post

        return response()->json([
            'success' => true,
            'data' => $post,
        ]);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        $this->authorize('update', $post);

        $validated = $request->validate($this->postService->getValidationRules('update'));
        $updatedPost = $this->postService->updatePost($post, $validated);

        return response()->json([
            'success' => true,
            'data' => $updatedPost,
        ]);
    }

    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);
        $this->postService->deletePost($post);

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
        ]);
    }

    /**
     * Get all posts (public - no authentication required).
     */
    /**
 * Get all posts (public - no authentication required).
 */
public function publicIndex(Request $request): JsonResponse
{
    $posts = Post::with('user')
        ->latest()
        ->paginate($request->input('per_page', 15));

    return response()->json([
        'success' => true,
        'data' => $posts,
    ]);
}


    /**
     * Get a single post details (public - no authentication required).
     */
    public function publicShow(Post $post): JsonResponse
{
    $post->load('user');

    return response()->json([
        'success' => true,
        'data' => $post,
    ]);
}

}
