<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

use App\Models\Log;
use App\Models\User;

class LogController extends Controller {
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
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Store new logs success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $result             = (isset($input['result']))         ? $input['result']          : null;
        $user_id            = (isset($input['user_id']))        ? $input['user_id']         : null;
        $origin_id          = (isset($input['origin_id']))      ? $input['origin_id']       : null;
        $raw_result         = (isset($input['raw_result']))     ? $input['raw_result']      : null;
        $action_type        = (isset($input['action_type']))    ? $input['action_type']     : null;
        $affected_user      = (isset($input['affected_user']))  ? $input['affected_user']   : null;
        $translated_id      = (isset($input['translated_id']))  ? $input['translated_id']   : null;
        $category_items     = (isset($input['category_items'])) ? $input['category_items']  : null;

        if (!isset($user_id) || $user_id == '') { $missingParams[] = "user_id"; }
        if (!isset($action_type) || $action_type == '') { $missingParams[] = "action_type"; }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if (!$isError) {
            try {
                if (User::find($user_id)) {
                    $log    = Log::create(array(
                        'result'            => $result,
                        'user_id'           => $user_id,
                        'origin_id'         => $origin_id,
                        'raw_result'        => $raw_result,
                        'action_type'       => $action_type,
                        'affected_user'     => $affected_user,
                        'translated_id'     => $translated_id,
                        'category_items'    => $category_items,
                    ));
                } else { throw new \Exception("User with id $user_id not found."); }

                $result     = array('_id' => $log->_id);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request) {
        //
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
}
