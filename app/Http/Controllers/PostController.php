<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostListRequest;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Services\ResponseService;

class PostController extends Controller
{

    public function index(PostListRequest $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 15);

        // Retrieve posts using model's method with search and pagination
        $posts = Post::searchPosts($search, $limit);
        return ResponseService::success($posts->toArray(), 200);
    }



    public function store(StorePostRequest $request)
    {
        // Insert post into database
        $post = Post::createPost($request->only('title', 'body', 'user_id'));

        // Return response with status 201 and post data
        return ResponseService::success([
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'user_id' => $post->user_id,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ], 201);
    }

    public function getUserPosts($id)
    {
        $posts = Post::getPostsByUserId($id);

        if (is_null($posts)) {
            // Return response if user not found
            return ResponseService::error(['message' => 'User not found'], 404);
        }

        if ($posts->isEmpty()) {
            // Return response if no posts found
            return ResponseService::error(['message' => 'No posts found for this user'], 404);
        }

        // Return response with posts data
        return ResponseService::success($posts->toArray(), 200);
    }
}
