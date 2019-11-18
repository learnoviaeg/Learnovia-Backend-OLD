<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Contacts;
use Validator;
use App\Http\Resources\Contactresource;
use Auth;

class ContactController extends Controller

{
    /**
     *
     * @Description : add Contact
     * @param : Request to Access his id ("Person_id") and ("Friend_id")
     * @return : if addition succeeded ->  return MSG : 'Contact insertion sucess'
     *           if not -> return MSG: 'Contact insertion Fail'
     *
    ``
     */
    public function addContact(Request $req)
    {
        $session_id = Auth::User()->id;

        $valid = Validator::make($req->all(), [
            'Friend_id' => 'required | exists:users,id',
        ]);
        if ($valid->fails()) {
            return response()->json(['msg' => $valid->errors()], 404);
        }
        if ($req->Friend_id != $req->Person_id) {
            $contacts = Contacts::firstOrCreate([
                'Friend_id' => $req->Friend_id,
                'Person_id' => $session_id,
            ]);
            if ($contacts) {
              //  return response()->json(['msg' => 'Contact insertion sucess'], 200);
                return HelperController::api_response_format(200, null, 'Contact insertion sucess');

            }
        }

        return HelperController::api_response_format(404, null, 'Contact insertion Fail');

//        return response()->json(['msg' => 'Contact insertion Fail'], 404);

    }

    /*
     * @Description: Get All MY Friends
     * @param: no take parameter
     * @return: List of my friends
     *
     * */
    public function ViewMyContact()
    {
        $session_id = Auth::User()->id;
        $user=User::find($session_id);
        $contacts = $user->contacts;
        return HelperController::api_response_format(200, Contactresource::Collection($contacts), 'My Contact ');
    }
}
