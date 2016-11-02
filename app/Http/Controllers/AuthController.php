<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

use App\Models\Log;
use App\Models\User;
use App\Models\Configuration;

class AuthController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Login Success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $username           = (isset($input['username']))           ? $input['username']        : null;
        $password           = (isset($input['password']))           ? $input['password']        : null;

        if(!isset($username)) { $missingParams[] = "username"; }
        if(!isset($password)) { $missingParams[] = "password"; }

        if(isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if(!$isError) {
            try {
                $user   = User::where('username', $username)->first();
                if ($user) {
                    if (app('hash')->check($password, $user->password)) {
                        if ($user->isconfirmed) {
                            $result         =  array(
                                '_id'       => $user->_id,
                                'role'      => $user->role,
                                'isVirgin'  => $user->isVirgin,
                                'isKicked'  => (\time() < \strtotime($user->last_kicked))
                            );
                            if ($user->isVirgin) { $user->isVirgin = 0; $user->save(); }
                        } else { throw new \Exception("User unconfirmed, do you want to resent confirmation ?", 1); }
                    } else { throw new \Exception("Invalid username and password combination"); }
                } else { throw new \Exception("Username doesn't exist"); }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage().". on line: " . $e->getLine();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    /**
     * Change confirmation code and resend email confirmation to given email.
     *
     * @param  Request  $request
     * @return Response
     */
    public function resetconfirmation(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Confirmation resent.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $email              = (isset($input['email'])) ? $input['email'] : null;

        if(!isset($email)) { $missingParams[] = "email"; }

        if(isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if(!$isError) {
            try {
                $user                   = User::where('email', $email)->first();
                if ($user->confirmationcode) {
                    $confirmationcode       = str_random(30);
                    $user->confirmationcode = $confirmationcode;
                    $user->save();

                    Mail::send('emails.confirmation', ['confirmationcode'=>$confirmationcode], function($m) use ($user) {
                        $m->from(env('MAIL_ADDRESS','translator-gator@pulselab.com'), env('MAIL_NAME','Translator-gator'));
                        $m->to($user->email, $user->username)->subject(env('MAIL_SUBJECT','Translator-gator Confirmation'));
                    });
                } else { throw new \Exception("User with email $email already activated."); }

            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Confirm user by code.
     *
     * @param  string $confirmationcode
     * @return Response
     */
    public function confirm($confirmationcode) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "User with confirmation code $confirmationcode activated.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $user = User::where('confirmationcode', $confirmationcode)->first();
                if(!is_null($user)){
                    $config     = Configuration::first();

                    if (isset($config->referral_point) && isset($user->referral)) {
                        $user->increment('point', $config->referral_point);

                        $referrer   = User::where('username', $user->referral)->first();
                        $referrer->increment('point', $config->referral_point);

                        Log::create(array(
                            'result'        =>$referrer->point,
                            'user_id'       =>$referrer->_id,
                            'action_type'   =>'Get referral point',
                            'affected_user' => $user->_id,
                        ));
                    }

                    $user->confirmationcode = null;
                    $user->isconfirmed      = true;
                    $user->save();
                } else { throw new \Exception("User with confirmation code $confirmationcode not found."); }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Create an reset code for user by given email. Then sent it to user, waiting for confirmation.
     *
     * @param  Request  $request
     * @return Response
     */
    public function resetpassword(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Reset password code sent.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $email              = (isset($input['email'])) ? $input['email'] : null;

        if(!isset($email)) { $missingParams[] = "email"; }

        if(isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if(!$isError) {
            try {
                $user            = User::where('email', $email)->first();
                $resetCode       = str_random(30);
                $user->resetcode = $resetCode;
                $user->save();

                Mail::send('emails.resetpassword', ['resetCode'=>$resetCode], function($m) use ($user) {
                    $m->from(env('MAIL_ADDRESS','translator-gator@pulselab.com'), env('MAIL_NAME','Translator-gator'));
                    $m->to($user->email, $user->username)->subject(env('MAIL_SUBJECT','Translator-gator Confirmation'));
                });
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Check the validity of given reset code
     *
     * @param  string  $resetcode
     * @return Response
     */
    public function checkresetcode($resetcode) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Checking user by reset code $resetcode success.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $user       = User::where('resetcode', $resetcode)->first();
                if ($user) {
                    $result     = array("resetcode" => $resetcode);
                } else { throw new \Exception("User with reset code $resetcode not found."); }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Change user password by given reset code.
     *
     * @param  Request  $request
     * @return Response
     */
    public function newpassword(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Change user's password success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $password           = (isset($input['password']))   ? $input['password']    : null;
        $resetcode          = (isset($input['resetcode']))  ? $input['resetcode']   : null;

        if(!isset($password)) { $missingParams[] = "password"; }
        if(!isset($resetcode)) { $missingParams[] = "resetcode"; }

        if(isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if(!$isError) {
            try {
                $user       = User::where('resetcode', $resetcode)->first();
                if ($user) {
                    $user->password  = app('hash')->make($password);
                    $user->resetcode = null;
                    $user->save();
                } else { throw new \Exception("User with reset code $resetcode not found."); }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }
}
