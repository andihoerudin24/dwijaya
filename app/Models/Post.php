<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Pagination\LengthAwarePaginator;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id'];

    public static function createPost(array $data)
    {
        // Insert the post into the database and get the inserted ID
        $id = DB::table('posts')->insertGetId([
            'title' => $data['title'],
            'body' => $data['body'],
            'user_id' => $data['user_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Fetch the newly created post
        return DB::table('posts')->where('id', $id)->first();
    }

    public static function getPostsByUserId($userId)
    {
        // Check if user exists
        $userExists = DB::table('users')->where('id', $userId)->exists();

        if (!$userExists) {
            return null; // User not found
        }

        // Get all posts for the user
        return DB::table('posts')->where('user_id', $userId)->get();
    }

    public static function searchPosts($search = null, $perPage = 15) : LengthAwarePaginator
    {
        $query = DB::table('posts');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        return $query->paginate($perPage);
    }
}
