<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use App\Activity;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }


    public function saveLog($user_id,$ip_address,$notes){
        $user = User::find($user_id);
        $user_name = isset($user)? ' '.$user->name.'('.$user->id.')':'';

        $activity = new Activity();
        $activity->user_id= $user_id;
        $activity->ip_address= $ip_address;
        $activity->notes= $notes.$user_name;
        $activity->save();
    }
}