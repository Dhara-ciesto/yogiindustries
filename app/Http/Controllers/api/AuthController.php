<?php

namespace App\Http\Controllers\api;

use App\Models\OTP;
use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    //this method adds new users

    public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|unique:users,username|max:255',
            'whatsapp_no' => 'required|unique:users,whatsapp_no|digits:10'
        ],[
            'business_name.required' => 'The Business name is required',
            'business_name.max' => 'The Business name can not more than25 character',
            'business_name.unique' => 'The Business name is already been taken'
        ]);
        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            // return response()->json(['status' => '0', 'message' => $validator->messages()->first()]);
            return response()->json([
                'message' => $validator->messages()->first()
            ], 200);
        }
        // Check if validation pass then create user and auth token. Return the auth token
        if ($validator->passes()) {
            $user = User::create([
                'name' => $request->full_name,
                'username' => $request->business_name,
                'email' => $request->whatsapp_no . '@mailinator.com',
                'whatsapp_no' => $request->whatsapp_no,
                'password' => Hash::make('123456789')
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
    }



    public function sendotp(Request $request)
    {

        $request->validate(['whatsapp_no' => 'required']);

        $otp = OTP::create([
            'otp' => rand(111111, 999999),
            'whatsapp_no' => $request->whatsapp_no,
        ]);

        return response()->json([
            'otp' => $otp->otp,
            'message' => 'OTP Sent'
        ]);
        // $message = "Hii";
        // $recipients = '+9173158283';
        // $account_sid = getenv("MAIL_MAILER");
        // $auth_token = getenv("TWILIO_AUTH_TOKEN");
        // $twilio_number = getenv("TWILIO_NUMBER");
        // $client = new Client('AC0cf6b0a40260fea5cff0e51ab5c360f9', '79820c4f180276c8ffb4d7f55bd35c70');
        // $message = $client->messages
        // ->create("whatsapp:9173158283", // to
        //          [
        //              "from" => "whatsapp:+14155238886",
        //              "body" => "Hello, there!"
        //          ]
        // );

        // print($message->sid);


        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://api.interakt.ai/v1/public/message/',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => '{
        //         "countryCode": "+91",
        //         "phoneNumber": "9999999999",
        //         "callbackData": "some text here",
        //         "type": "Template",
        //         "template": {
        //             "name": "template_name_here",
        //             "languageCode": "en",
        //             "bodyValues": [
        //                 "body_variable_value_1",
        //                 "body_variable_value_n"
        //             ]
        //         }
        //     }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Authorization: Basic e50be13bf68adac9ed30e72b29206669c3c88b89',
        //         'Content-Type: application/json'
        //     ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // echo $response;
    }

    public function otpverify(Request $request)
    {

        $request->validate(['whatsapp_no' => 'required', 'otp' => 'required']);

        $otp = OTP::where('whatsapp_no', $request->whatsapp_no)->where('otp', $request->otp)->where('status', 'Active')->first();
        if ($otp) {

            $user = User::where('whatsapp_no', $request->whatsapp_no)->get()->first();
            if (!Auth::loginUsingId($user->id)) {
                return response()->json([
                    'message' => 'Invalid login details'
                ], 401);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            $otp->status = 'Deactive';
            $otp->save();
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'OTP Invalid'
            ]);
        }
    }
    //use this method to signin users
    // public function loginUser(Request $request)
    // {
    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response()->json([
    //             'message' => 'Invalid login details'
    //         ], 401);
    //     }
    //     $user = User::where('email', $request['email'])->firstOrFail();
    //     $token = $user->createToken('auth_token')->plainTextToken;
    //     // return auth()->user();
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //     ]);
    // }


    public function updateProfile(Request $request, $id)
    {

        $request->validate([
            'full_name' => 'required|string|max:255',
            'business_name' => 'required|max:255|unique:users,username,' . $id,
            'whatsapp_no' => 'required|digits:10|unique:users,whatsapp_no,' . $id,
        ], [
            'business_name.required' => 'The Business name is required',
            'business_name.max' => 'The Business name can not more than25 character',
            'business_name.unique' => 'The Business name is already been taken'
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->full_name;
        $user->username = $request->business_name;
        if ($request->whatsapp_no) {
            $user->email = $request->whatsapp_no . '@mailinator.com';
        }
        $user->whatsapp_no = $request->whatsapp_no;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile Updated Successfully'
        ]);
    }


    // this method signs out users by removing tokens
    public function signout()
    {
        auth()->user()->tokens()->delete();

        return [
            'success' => true,
            'message' => 'Tokens Revoked'
        ];
    }
}
