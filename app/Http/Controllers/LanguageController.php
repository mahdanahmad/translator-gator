<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public function index(){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success reading Language.";
        $isError            = FALSE;
        $missingParams      = null;
        
        

        if(!$isError) {
            try {                
                //$result = Users::take($limit)->skip($offset)->get();
                $result     = \App\Language::all();

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

    public function get($id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success reading Language.";
        $isError            = FALSE;
        $missingParams      = null;
        
        

        if(!$isError) {
            try {                
                $result     = \App\Language::find($id);
                if(!$result){
                    throw new Exception("Language with id =".$id." Not Found", 1);
                }
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success adding new Language.";
        $isError            = FALSE;
        $missingParams      = null;
        
        
        $validator          = \Validator::make($request->all(),[
            'language_name' => 'required|unique:languages,language_name'
        ]);

        if(!$validator->fails()) {
            
            $input              = $request->all();
            $language_name      = (isset($input['language_name'])) ? $input['language_name'] : null;

            try {                
                //$result = Users::take($limit)->skip($offset)->get();
                $result     = \App\Language::create(array(
                    'language_name' => $language_name
                ));

            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }else{
            // invalid input 
            $response   = "FAILED";
            $statusCode = 400;
            $message    = $validator->errors()->all();
        }
        
        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );
        
        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }


    public function edit(Request $request, $id)
    {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success editing new Language.";
        $isError            = FALSE;
        $missingParams      = null;
        
        
        $validator          = \Validator::make($request->all(),[
            'language_name' => 'required|unique:languages,language_name'
        ]);

        if(!$validator->fails()) {
            
            $input         = $request->all();
            $language_name = (isset($input['language_name'])) ? $input['language_name'] : null;

            try {                
                //$result = Users::take($limit)->skip($offset)->get();
                $lang       = \App\Language::find($id);
                
                if ($lang && isset($language_name)) {
                    $lang->language_name = $language_name;

                    $lang->save();
                } else {
                    throw new \Exception("language not found.");
                }                
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }else{
            // invalid input 
            $response   = "FAILED";
            $statusCode = 400;
            $message    = $validator->errors()->all();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success deleting new Language.";
        $isError            = FALSE;
        $missingParams      = null;
        
        
        $validator          = \Validator::make($request->all(),[

        ]);

        if(!$validator->fails()) {
            
            $input         = $request->all();
            
            try {                
                //$result = Users::take($limit)->skip($offset)->get();
                $lang                = \App\Language::destroy($id);
                
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }else{
            // invalid input 
            $response   = "FAILED";
            $statusCode = 400;
            $message    = $validator->errors()->all();
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
