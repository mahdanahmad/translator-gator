<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\User;
use App\Redeem;
use App\Configuration;

class RedeemController extends Controller {
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
        $message            = "Get all redeem data success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $limit              = (isset($input['limit']))     ? $input['limit']    : null;
        $offset             = (isset($input['offset']))    ? $input['offset']   : null;

        if (!$isError) {
            try {
                $result     = Redeem::with(array('user'))->orderBy('created_at', 'DESC')->take($limit)->skip($offset)->get();

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

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

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
        $message            = "Add new redeem data success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $mobile             = (isset($input['mobile']))     ? $input['mobile']  : null;
        $credit             = (isset($input['credit']))     ? $input['credit']  : null;
        $user_id            = (isset($input['user_id']))    ? $input['user_id'] : null;

        if (!isset($mobile) || $mobile == '') {
            $missingParams[] = "mobile";
        }
        if (!isset($credit) || $credit == '') {
            $missingParams[] = "credit";
        }
        if (!isset($user_id) || $user_id == '') {
            $missingParams[] = "user_id";
        }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if (!$isError) {
            try {
                $user   = User::find($user_id);
                $config = Configuration::first();
                if ($user) {
                    if ($user->point * $config->point_value > $credit) {
                        $consumedpoint  = ceil($credit / $config->point_value);

                        $redeem = Redeem::create(array(
                            '_id'       => Redeem::getNextSequence(),
                            'mobile'    => "".$mobile,
                            'credit'    => $credit + 0,
                            'status'    => 'On progress',
                            'points'    => $consumedpoint,
                            'user_id'   => $user_id,
                            'prev'      => $user->point,
                        ));

                        $user->point    = $user->point - $consumedpoint;
                        $user->save();

                        $result         = $user->point;
                    } else {
                        throw new \Exception("User with id $user_id didn't have enough points.");
                    }
                } else {
                    throw new \Exception("User with id $user_id not found.");
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

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Get all redeem data by user $id success.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $user = User::find($id);

                if ($user) {
                    $result = Redeem::where('user_id', $id)->orderBy('created_at', 'DESC')->get();
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

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
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

    public function bulkStatus(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Edit all status from csv file success.";
        $isError            = FALSE;
        $missingParams      = null;

        $data               = ($request->hasFile('data'))   ? $request->file('data')  : null;

        if (!isset($data)) {
            $missingParams[] = "data";
        }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if (!$isError) {
            try {
                $extension  = $data->getClientOriginalExtension();

                if ($extension == 'csv') {
                    $status = \Excel::load($data)->toArray();

                    foreach ($status as $key => $value) {
                        Redeem::where('_id', $value['id'])->update(array('status' => ucfirst(strtolower($value['status']))), array('upsert' => true));
                    }
                } else {
                    throw new \Exception("Input file must be csv.");

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

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }
}
