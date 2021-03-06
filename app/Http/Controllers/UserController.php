<?php

namespace App\Http\Controllers;

use App\Models\AllLocation;
use App\Models\Excludedarea;
use App\Models\SafeLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    //

    private $radius = 5;

    public function register_user (Request $req) {

        $validator = Validator::make($req->all(), [

            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required_with:password|same:password',
            'avatar' => 'sometimes|image:jpeg,png,jpg|max:4000',
            'device_token' => 'required',

        ]);
        if ($validator->fails()) {

            return response()->json([
                'status' => "error",
                'error' => $validator->errors()
            ], 400);

        } else  {

            if ( User::where('email', $req->email)->exists() ) {

                return response()->json([
                    'status' => "error",
                    'message' => "User Already Registered",
                ], 401);

            } else {

                $new_user = User::create([

                    'username' => $req->username,
                    'email' => $req->email,
                    'password' => bcrypt($req->password),
                    'confirm_password' => $req->confirm_password,
                    'device_token' => $req->device_token,
                    'type' => "1",
                ]);

                if($req->avatar){

                    $image = $req->file('avatar');
                    $imageName = time() . mt_rand(1000, 999999) . '_'  . $image->getClientOriginalName();
                    $image->move(public_path('images'), $imageName);

                    User::where('id', $new_user->id)
                        ->update(['avatar' => $imageName]);
                
                }

                $user = User::where('id', $new_user->id)->first();
                Auth::login($user);
                $user = Auth::user();
                $token = $user->createToken('PingMe')->accessToken;

                User::where('id', $new_user->id)
                    ->update(['api_token' => $token]);

                $driver = User::where('id', $new_user->id)->first();

                return response()->json([
                    'status' => "success",
                    // 'token' => $token,
                    'user' => $driver
                ], 200);
            }
        }
    }

    public function login_user (Request $req) {

        $validator = Validator::make($req->all(), [

            'email' => 'required',
            'password' => 'required',
            'device_token' => 'required',

        ]);

        if ( $validator->fails() ) {

            return response()->json([
                'status' => "error",
                'message' => "Validator Error",
                'error' => $validator->errors(),
            ], 400);

        } else {

            if ( User::where('email', $req->email)->exists() ) {

                $user = User::where('email', $req->email)->first();

                if (Hash::check($req->password, $user->password)) {

                    // Auth::login($user);
                    $user = Auth::user();
                    $token = $user->createToken('PingMe')->accessToken;

                    User::where('email', $req->email)
                        ->update([

                            'api_token' => $token,
                            'device_token' => $req->device_token,

                        ]);

                    $login_user = User::where('email', $req->email)->first();    

                    return response()->json([
                        'status' => "success",
                        'message' => "Password Matched Successfully",
                        'user' => $login_user,
                    ], 200);


                } else {

                    return response()->json([
                        'status' => "error",
                        'message' => "Password not Matched",
                    ], 401);
                }

            } else  {

                return response()->json([
                    'status' => "error",
                    'message' => "User Does not Exists",
                ], 404);
            }
        }
    }

    public function logout_user (Request $req)
    {
        $req->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);        
    }

    public function nearby_locations (Request $req) {

        $validator = Validator::make($req->all(), [

            'longitude' => 'required',
            'latitude' => 'required',
            'zip_code' => 'required',

        ]);

        if ( $validator->fails() ) {

            return response()->json([
                'status' => "error",
                'message' => "Validator Error",
                'error' => $validator->errors(),
            ], 400);

        } else {

            if (Excludedarea::where('zip_code', $req->zip_code)->exists()) {

                return response()->json([
                    'status' => "error",
                    'message' => "This are is prohibited due to zip code",
                ], 404);                

            } else {

                $all_locations = AllLocation::where('zip_code', '<>', $req->zip_code)->get();

                $nearby_locations = [];

                foreach ( $all_locations as $nearby ) {

                    $distance = $this->circle_distance($nearby->latitude, $nearby->longitude, $req->latitude, $req->longitude);

                    // die($distance);

                    if ( $distance <= $this->radius ) {

                        array_push($nearby_locations, $nearby);
                        
                    }
                }

                return response()->json([
                    'status' => "success",
                    'message' => "Nearby Locations Fetched Successfully",
                    'nearby_locations' => $nearby_locations,

                ], 200);

            }

        }

    }

    public function add_safe_location (Request $req) {

        $validator = Validator::make($req->all(), [

            'longitude' => 'required',
            'latitude' => 'required',
            'zip_code' => 'required',
            'address' => 'required',
            'user_id' => 'required',

        ]);

        if ( $validator->fails() ) {

            return response()->json([
                'status' => "error",
                'message' => "Validator Error",
                'error' => $validator->errors(),
            ], 400);

        } else {

            if (User::where('id', $req->user_id)->exists()) {

                SafeLocation::insert([

                    'longitude' => $req->longitude,
                    'latitude' => $req->latitude,
                    'address' => $req->address,
                    'user_id' => $req->user_id,
                ]);

                return response()->json([
                    'status' => "success",
                    'message' => "Safe Location Added Successfully",
    
                ], 200);

            } else {

                return response()->json([
                    'status' => "error",
                    'message' => "User does not Exists",
    
                ], 404);

            }

        }

    }

    public function fetch_save_location (Request $req) {

        $validator = Validator::make($req->all(), [

            'user_id' => 'required',

        ]);

        if ( $validator->fails() ) {

            return response()->json([
                'status' => "error",
                'message' => "Validator Error",
                'error' => $validator->errors(),
            ], 400);

        } else {

            if (SafeLocation::where('user_id', $req->user_id)->exists()) {

                $safe_locations = SafeLocation::where('user_id', $req->user_id)->get();

                return response()->json([
                    'status' => "success",
                    'message' => "Your safe locations fetched successfully",
                    'safe_locations' => $safe_locations,
    
                ], 200);


            } else {

                return response()->json([
                    'status' => "error",
                    'message' => "You have no safe locations",
    
                ], 200);

            }
        }
    }


    public function check_excluded_area (Request $req) {

        $validator = Validator::make($req->all(), [

            'zip_code' => 'required',

        ]);

        if ( $validator->fails() ) {

            return response()->json([
                'status' => "error",
                'message' => "Validator Error",
                'error' => $validator->errors(),
            ], 400);

        } else {

            if (Excludedarea::where('zip_code', $req->zip_code)->exists()) {

                return response()->json([
                    'status' => "error",
                    'message' => "Our service is not available for this area at this moment",
    
                ], 200);

            } else {

                return response()->json([
                    'status' => "success",
                    'message' => "Congrats! Our service is available for this area",
    
                ], 200);

            }
        }

    }

    function circle_distance($lat1, $lon1, $lat2, $lon2)
    {
        $rad = M_PI / 180;
        return acos(sin($lat2 * $rad) * sin($lat1 * $rad) + cos($lat2 * $rad) * cos($lat1 * $rad) * cos($lon2 * $rad - $lon1 * $rad)) * 6371; // Kilometers
    }
}
