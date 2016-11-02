<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OriginWord;
use App\Configuration;

class OriginWordController extends Controller
{
    public function getAll(){
    	$returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success adding words to database.";
        $isError            = FALSE;
        $missingParams      = null;
        
        
        
        if(!$isError) {
        	try {
        		$result = OriginWord::all();
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

    public function getId($id){

    }



    public function create(Request $request){
        $duplicates         = array();
    	$returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success adding words to database.";
        $isError            = FALSE;
        $missingParams      = null;
        

        $validator          = \Validator::make($request->all(),[
           'origin_files' => 'required'
        ]);


        /*
        * FORMAT VALID YANG DITERIMA (isi file csv seperti ini : ): 
            "eat",
            "sleep",
            "water",
            "who is inside ?",
            "where do you come from ?",
            "when we will get married ?",
            "why you are so serious ?",
            "how do you think about jokowi's policy ?",
            "I am student at Institut Teknologi Bandung.",
            "kmpret",
        *
        *   Kalau gak jalan mungkin karena line_endings di linux tanpa carriege return (\n saja bukan \r\n)
        *   Silahkan ganti konfigurasi file di config/excel.php (bagian line_endings)
        *   aing sudah sertakan juga contoh file csv di /public directory sebagai bahan tes
        *   Kalau gak nambah kemungkinan besar karena duplikasi origin word
        */

        if(!$validator->fails()) {
            $input          = $request->all();
            if($request->file('origin_files')->isValid()){

                // Lakukan import menggunakan php excel
				$file			= $request->file('origin_files');
                $origin_words = \Excel::load($file,function($reader){
					$reader->noHeading();
				})->toArray();

                
                // Melakukan parsing dari file dan meng-insert ke database
				foreach ($origin_words as $sheet) {
					foreach($sheet as $key=>$value){
						try {
                            $response   = "OK";
                            $statusCode = 200;
							$result = OriginWord::create(array(
								'origin_word'=>$value,
							));
						} catch (\Exception $e) {
							$response   = "FAILED";
							$statusCode = 400;
							$message    = $e->getMessage();
                            if(strpos($message,'duplicate')!==false){
                                $duplicates[] = $value;
                            }
						}
					}
				}

                // Jika terdapat error untuk sebagian kata, tampilkan dalam message (biasanya karena duplikasi)
                if(!empty($duplicates)){
                    $message = "Failed to insert (".implode(',', $duplicates).") because duplication";
                }
            }else{
            	$response   = "FAILED";
                $statusCode = 400;
                $message    = "Failed to upload files";
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

    public function updateId($id){

    }


    public function getRandom(){
    	$returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success adding words to database.";
        $isError            = FALSE;
        $missingParams      = null;
        

        if(!$isError) {
        	try {
        		$config = Configuration::first();
        		$items  = $config->display_items_per_page;
        		$result = OriginWord::all()->random($items);
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
