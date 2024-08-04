<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public static function createUser(array $data)
    {
        // Hash the password
        $data['password'] = Hash::make($data['password']);

        // Insert the user into the database and get the inserted ID
        $id = DB::table('users')->insertGetId([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Fetch the newly created user (excluding password)
        return DB::table('users')->select('id', 'name', 'email', 'created_at', 'updated_at')->where('id', $id)->first();
    }

    /**
     * Delete the user and their related posts.
     *
     * @return bool
     */
    public function deleteWithPosts()
    {
        DB::beginTransaction();

        try {
            // Delete all posts related to the user
            DB::table('posts')->where('user_id', $this->id)->delete();

            // Delete the user
            $this->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error message for debugging
            Log::error('Error deleting user and related posts: ' . $e->getMessage());

            return false;
        }
    }
}
