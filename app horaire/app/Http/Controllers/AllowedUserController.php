<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AllowedUser;
class AllowedUserController extends Controller
{
    public function insertAllowedUser(Request $request) {
        try {
            $user = AllowedUser::insertAllowedUser($request->post("email"));
        } catch (\Exception $ex) {
            return response()->json(false, 500);
        }
        return response()->json(["email"=>$user], 201); 
    }
}
