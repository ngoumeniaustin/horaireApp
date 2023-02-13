<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AllowedUser extends Model
{
    use HasFactory;

    public static function getAllAllowedUser()
    {
        return DB::table('AllowedUser')->get();;
    }

    public static function insertAllowedUser($email)
    {
        return DB::table('AllowedUser')->insert([
            'email' => $email
        ]);
    }
    public static function isAllowed($email)
    {
        $user = DB::table('AllowedUser')->where('email', $email)->get();
        return !$user->isEmpty();
    }
}
