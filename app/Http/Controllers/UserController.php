<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Language;
use App\Models\Speaking;
use App\Models\Configuration;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrieve all user success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $limit              = (isset($input['limit']))     ? $input['limit']    : null;
        $offset             = (isset($input['offset']))    ? $input['offset']   : null;

        if (!$isError) {
            try {
                $result     = User::where('role', 'user')->take($limit)->skip($offset)->get();

            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
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
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Store new user success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $email              = (isset($input['email']))              ? $input['email']               : null;
        $gender             = (isset($input['gender']))             ? $input['gender']              : null;
        $referral           = (isset($input['referral']))           ? $input['referral']            : null;
        $username           = (isset($input['username']))           ? $input['username']            : null;
        $password           = (isset($input['password']))           ? $input['password']            : null;
        $age_range          = (isset($input['age_range']))          ? $input['age_range']           : 0;
        $twitter_id         = (isset($input['twitter_id']))         ? $input['twitter_id']          : null;
        $facebook_id        = (isset($input['facebook_id']))        ? $input['facebook_id']         : null;
        $raw_languages      = (isset($input['spoken_language']))    ? $input['spoken_language']     : null;

        if (!isset($email) || $email == '') { $missingParams[] = "email"; }
        if (!isset($username) || $username == '') { $missingParams[] = "username"; }
        if (!isset($password) || $password == '') { $missingParams[] = "password"; }
        if (!isset($raw_languages) || $raw_languages == '') { $missingParams[] = "spoken_language"; }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if (!$isError) {
            try {
                if (!User::where('email', $email)->first()) {
                    if (!User::where('username', $username)->first()) {
                        $spoken_language    = json_decode($raw_languages);
                        if (is_array($spoken_language)) {
                             $languages     = Language::whereIn('_id', $spoken_language)->get()->pluck('_id')->toArray();
                        } else { $languages = []; }

                        if (!empty($languages)) {
                            $config             = Configuration::first();
                            $confirmationcode   = str_random(30);
                            if (!User::where('username', $referral)->first()) { $referral = null; }

                            $user = User::create(array(
                                'role'              => 'user',
                                'point'             => 0,
                                'email'             => $email,
                                'gender'            => $gender,
                                'health'            => $config->max_health,
                                'referral'          => $referral,
                                'isVirgin'          => 1,
                                'username'          => $username,
                                'password'          => app('hash')->make($password),
                                'age_range'         => $age_range,
                                'languages'         => $languages,
                                'twitter_id'        => $twitter_id,
                                'facebook_id'       => $facebook_id,
                                'last_kicked'       => null,
                                'isconfirmed'       => false,
                                'confirmationcode'  => $confirmationcode,
                            ));

                            foreach ($languages as $language) {
                                Speaking::create(array(
                                    'user_id'               => $user->_id,
                                    'language_id'           => $language,
                                    'voted_counter'         => 0,
                                    'translated_counter'    => 0,
                                    'categorized_counter'   => 0,
                                ));
                            }

                            $result = array('_id' => $user->_id);
                            Mail::send('emails.confirmation', ['confirmationcode'=>$confirmationcode], function($m) use ($email, $username) {
                                $m->from(env('MAIL_ADDRESS','translator-gator@pulselab.com'), env('MAIL_NAME','Translator-gator'));
                                $m->to($email, $username)->subject(env('MAIL_SUBJECT','Translator-gator Confirmation'));
                            });
                        } else { throw new \Exception("You must be pick at least one language."); }
                    } else { throw new \Exception("Username ($username) already taken. Please choose another name."); }
                } else { throw new \Exception("Your email ($email) already registered to our site."); }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
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
     * Display the specified resource.
     *
     * @param  string  $id
     * @return Response
     */
    public function show($id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrieve user with id $id success.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $user       = User::find($id);
                $config     = Configuration::first();

                if (!$user) {
                    $user   = User::where('username', $id)->first();
                    if (!$user) { throw new \Exception("User with id $id not found."); }
                }

                if (\time() < \strtotime($user->last_kicked)) {
                    $result = array(
                        "countdown" => \strtotime($user->last_kicked) - \time(),
                        "time"      => $config->kick_time,
                        "points"    => $user->point,
                    );

                    throw new \Exception("User kicked out", 1);
                }

                $languages_spoke    = Speaking::where('user_id', $id)->with('language')->get();
                $result             = array(
                    'user'                      => $user,
                    'max_health'                => $config->max_health,
                    'redeem_time'               => $config->redeem_time,
                    'translated_word_counter'   => array_map(function($o) { return array('language' => $o['language']['language_name'], 'count' => $o['translated_counter']); }, $languages_spoke->toArray())
                );
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
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
     * @param  string  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Update user with id $id success.";
        $isError            = FALSE;
        $editedParams       = null;

        $input              = $request->all();
        $email              = (isset($input['email']))          ? $input['email']           : null;
        $raw_languages      = (isset($input['languages']))      ? $input['languages']       : null;
        $new_password       = (isset($input['new_password']))   ? $input['new_password']    : null;
        $old_password       = (isset($input['old_password']))   ? $input['old_password']    : null;

        if (!$isError) {
            try {
                $user      = User::find($id);
                if ($user) {
                    if (isset($email) && $email !== '') { $editedParams[] = "email"; $user->email = $email; }
                    if (isset($new_password) && $new_password !== '' && app('hash')->check($old_password, $user->password)) { $editedParams[] = 'password'; $user->password = app('hash')->make($new_password); }

                    if (isset($raw_languages) && $raw_languages !== '') {
                        $spoken_language    = json_decode($raw_languages);
                        if (is_array($spoken_language)) {
                             $languages     = Language::whereIn('_id', $spoken_language)->get()->pluck('_id')->toArray();
                        } else { $languages = []; }

                        if (!empty($languages)) {
                            $editedParams[] = "languages";
                            foreach (array_diff($languages, $user->languages) as $language) {
                                if (!Speaking::where('user_id', $id)->where('language_id', $language)->first()) {
                                    Speaking::create(array(
                                        'user_id'               => $id,
                                        'language_id'           => $language,
                                        'voted_counter'         => 0,
                                        'translated_counter'    => 0,
                                        'categorized_counter'   => 0,
                                    ));
                                }
                            }
                            $user->languages    = $languages;
                        }
                    }

                    if (isset($editedParams)) {
                        $user->save();

                        $message    = $message." Changed data : {".implode(', ', $editedParams)."}";
                    } else {
                        $message    = $message." No data changed.";
                    }
                } else {
                    throw new \Exception("User with id $id not found.");
                }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
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
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return Response
     */
    public function destroy($id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Delete user with id $id success.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $user    = User::find($id);
                if ($user) {
                    $user->delete();
                } else {
                    throw new \Exception("User with id $id not found.");
                }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
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
    * Display all languages by user id.
    *
    * @param  string  $id
    * @return Response
    */
    public function getLanguage($id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success getting user language info.";
        $isError            = FALSE;
        $missingParams      = null;


        if(!$isError) {
            try {
                $user  = User::find($id);
                if ($user) {
                    $result = Language::whereIn('_id', $user->languages)->where('is_deleted', '!=', 1)->get();
                } else { throw new \Exception("User doesn't exist", 1); }

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

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Display listing of top 10 user by point.
     *
     * @return Response
     */
    public function leaderboard(){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success getting leaderboard info.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $result = User::where('point','>=',0)->where('role', 'user')->orderBy('point','desc')->take(10)->get(array('username', 'point'));
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

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }
}
