<?php

namespace App\Http\Controllers;

use App\OriginWord;
use App\TranslatedWord;
use App\Configuration;
use App\CategoryItem;
use App\Category;
use App\User;
use App\Language;
use App\Speaking;
use App\CategorizedWord;
use App\Log;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class debugController extends Controller {
    public function debug(Request $request) {
        $result = array();
        $where = $request->input('language_id');
//        $data   = TranslatedWord::All();
        $data   = TranslatedWord::raw(function($collection) use($where) {
            return $collection->aggregate([
                ['$match' => [
                    'language_id' => $where,
                    'translated_to' => [
                        '$ne'   => ""
                    ]
                ]],
                ['$group'   => [
                    '_id'   => '$origin_word_id',
                    'count' => [
                        '$sum'  => 1
                    ]
                ]],
                ['$sort'    => [
                    'count' => 1
                ]],
                ['$limit'   => 100]
            ]);
        });
        //var_dump($data);
        $result['data'] = $data;//['result'][0];

        return  response()->json($result, 200)->header('access-control-allow-origin', '*');
    }

    public function uploadfile(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Coba upload file success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input  = $request->all();
        $file   = ($request->hasFile('file')) ? $request->file('file') : null;

        if(!isset($file)){
            $missingParams[] = "file";
        }

        if(isset($missingParams)){
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if(!$isError) {
            try {
                $destinationPath    = 'resources/temp';
                $extension          = $file->getClientOriginalExtension();
                $filename           = time()."_".str_random(12).".".$extension;

                $file->move($destinationPath."/"."{id category group}", $filename);
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

    public function tesmail($username){
        // \Mail::raw('Laravel with Mailgun is easy!', function($message)
        // {
        //     $message->to('fawwazmuhammad@gmail.com');
        // });
        $user = \App\User::where('username','=',$username)->first();
        \Mail::send('emails.test',['user'=>$user],function($m) use ($user){
            $m->from('Jong-Un@un.com','I am handsome');
            $m->to('13510104@std.stei.itb.ac.id', 'fawwaz')->subject('Halo om.. ini ngetes email masuk gak ? Kalau masuk coba message  di whatsapp!');
        });
    }

    public function statistic(){
        $data   = Log::raw(function($collection){
            return $collection->aggregate([
                // Harus dilimit hanya <gt dari tanggal tertentu relatif terhadap hari ini
                // ['$match' => [
                //     'language_id' => $selected_language_id,
                //     'translated_to' => [
                //         '$ne'   => ""
                //     ]
                // ]],
                [
                    '$group'    =>
                    [
                        '_id'   =>
                        [
                            'date' =>
                            [
                                '$dayOfMonth' => '$created_at'
                            ],
                            'month' =>
                            [
                                '$month' => '$created_at'
                            ],
                            'action_type'=> '$action_type'
                        ],
                        'count' =>
                        [
                            '$sum'  => 1
                        ]
                    ],

             ],
             [
                '$sort'=>
                [
                    '_id'=>1
                ]
             ]
            ]);
        });


        $linelabels          = array();
        $lineseries          = array();
        $counters            = array();
        $counter_categorize  = array();
        $counter_vote        = array();
        $counter_translate   = array();
        $counter_alternate   = array();

        $datas = $data['result'];
        foreach ($datas as $datum) {
            $counter     = $datum['count'];
            $action_type = $datum['_id']['action_type'];
            $date        = $datum['_id']['date'];
            $month       = $datum['_id']['month'];
            $date_string = $date."/".$month;

            // Tambahkan tanggal jika belum ada di dalam array line labels
            if(!in_array($date_string, $linelabels)){
                $linelabels[] = $date_string;
            }

            if(!in_array($action_type,$lineseries)){
                $lineseries[] = $action_type;
            }

            $counters[$action_type][] = $counter;

            // if($action_type=="translate"){
            //     $counter_translate[]  = $counter;
            // }else if($action_type=="categorize"){
            //     $counter_categorize[] = $counter;
            // }else if(($action_type=="vote_up") || ($action_type == "vote_down")){
            //     $counter_vote[]       = $counter;
            // }else if($action_type=="alternate"){
            //     $counter_alternate[]  = $counter;
            // }
        }

        $result['lineseries'] = $lineseries;
        $result['linelabels'] = $linelabels;
        $result['counters'] = $counters;

        return  response()->json($result, 200)->header('access-control-allow-origin', '*');
    }

    public function export(){
        // \Excel::create('PulseLab_Crowdsource_Raw_Data',function($excel){
        //     $excel->sheet('Raw_Data',function($sheet){
        //         $sheet->row(1,array("tes","tos"));
        //         $sheet->row(2,array("ttot","tid","crot"));
        //     });
        // })->download('csv');
        // Menulis file terlebih dahulu
        // $data   = TranslatedWord::raw(function($collection){
        //     return $collection->aggregate([
        //         // Harus dilimit hanya <gt dari tanggal tertentu relatif terhadap hari ini
        //         // ['$match' => [
        //         //     'language_id' => $selected_language_id,
        //         //     'translated_to' => [
        //         //         '$ne'   => ""
        //         //     ]
        //         // ]],
        //         ['$group'=>[
        //             '_id'   => [
        //                 'origin_id'=>'$origin_word_id',
        //                 'language_id'=>'$language_id'
        //             ],
        //             'count' => [
        //                 '$sum'  => 1
        //             ]
        //         ]],
        //         ['$sort'=>[
        //                 '_id'=>1
        //             ]
        //         ]]);
        // });

        /*
        MEtode map reduce buat coba coaba
        */

        $db = \DB::connection('mongodb');
        $mapTranslated = new \MongoCode("
            function(){
                var values ={
                    ids : this._id
                }
                var keys = {
                    origin_id : this.origin_word_id,
                    language_id : this.language_id,
                    translated_to : this.translated_to
                }
                emit(keys,values);
            }
        ");

        $mapCategory = new \MongoCode("
            function(){
                var values = {
                    categorized_to : this.categorized_to
                }
                emit(this.translated_word_id,values);
            }
        ");

        $reduce = new \MongoCode("
            function(key,values){
                // var result={
                //     ids:[],
                //     categorized_to:[]
                // };
                var result ={};
                // var categoryfields ={
                //     categorized_to : [],
                // };

                values.forEach(function(value){
                    var field;


                    for(field in value){
                        if(value.hasOwnProperty(field)){
                            printjson(value);
                            if(value[field] instanceof Array){
                                value[field].forEach(function(v){
                                    result[field].push(v);
                                });
                            }else{
                                result[field] = value[field];
                            }
                        }
                    }
                });

                return result;
            }
        ");

        $data = $db->command(
            [
                'mapreduce'=>'translated_words',
                'map'=>$mapTranslated,
                'reduce'=>$reduce,
                'out'=>[
                    'reduce'=>'translated_categorized'
                    // 'inline'=>1
                ]
            ]
        );

        /*
        $data = $db->command(
            [
                // 'mapreduce'=>'translated_words',
                // 'map'=>$mapTranslated,
                'mapreduce'=>'categorized_words',
                'map'=>$mapCategory,
                'reduce'=>$reduce,
                'out'=>[
                    'reduce'=>'translated_categorized'
                    // 'inline'=>1
                ]
            ]
        );
        /**/

        $result = $data;


        // $filepath = "/";
        // $namafile = "tesdownload.csv";
        // \File::put($namafile,$file_content);

        // // Me
        // $file = public_path().$filepath.$namafile;
        // $headers = array('Content-Type: text/csv');
        // return \Response::download($file,$namafile,$headers);

        //  buat debugging return biasa dulu aja :

        /*
        BALIK KE METODE GROUP BY TAPI MAKE TEMOPORARY TABLE
        */
        // $data   = TranslatedWord::raw(function($collection){
        //             return $collection->aggregate([
        //                 ['$group'=>[
        //                     '_id'   => [
        //                         'origin_word_id'=>'$origin_word_id',
        //                         'translated_to'=>'$translated_to',
        //                         'language_id'=>'$language_id'
        //                     ],
        //                     'ids' => [
        //                         '$push'  => '$_id'
        //                     ],
        //                     'origin_id'=>[
        //                         '$first'=>'$origin_word_id'
        //                     ]
        //                 ]],
        //                 ['$sort'=>[
        //                         '_id'=>1
        //                     ]
        //                 ]]);
        //         });
        // $categorized = CAtegorizedWord::raw(function($collection){
        //     return $collection->aggregate([
        //         ['$group'=>[
        //             '_id'=>[
        //                 ''
        //             ]
        //         ]]
        //     ]);
        // });

        // $result['translated_word'] = $data;
        // $result['categorized'] = $categorized;

        return  response()->json($result, 200)->header('access-control-allow-origin', '*');
    }

    public function downdown() {
        $result = array();

        $language_ids   = ["566cf51d842e9fc02600002a", "566cf51d842e9fc02600002c"];
        $category_ids = ["566cf51d842e9fc026000035","566cf51d842e9fc026000036"];

        $translated_words = TranslatedWord::whereIn('language_id', $language_ids)->with(['origin_word', 'language'])->get();

        $groupedTranslate   = $translated_words->groupBy('origin_word_id');


        $category_items = CategoryItem::all();
        //lakukan mapping dari id=> categoryitem
        foreach ($category_items as $catitems) {
            $hasil[$catitems->_id]=$catitems->category_name;
        }


        $file_content = "";
        TranslatedWord::whereIn('language_id', $language_ids)
            ->with(['origin_word', 'language'])
            ->orderBy('origin_word_id')
            ->orderBy('language_id')
            ->orderBy('translated_to')
            ->chunk(200,function($translatedwords) use (&$result,&$file_content,&$category_ids,&$hasil){
                foreach($translatedwords as $translatedword){

                    $categories       = CategorizedWord::where('translated_word_id','=',$translatedword->_id)->whereIn('categorized_to',$category_ids)->get();
                    $category_counter = array();
                    // Lakukan counting category
                    foreach ($categories as $cat_ids) {
                        foreach ($cat_ids->categorized_to as $catid) {
                            if(!array_key_exists($hasil[$catid], $category_counter)){
                                $category_counter[$hasil[$catid]] = 1;
                            }else{
                                $category_counter[$hasil[$catid]] = $category_counter[$hasil[$catid]]+1;
                            }
                        }
                    }

                    $origin_word      = $translatedword->origin_word->origin_word;
                    $translated_to    = $translatedword->translated_to;
                    $language         = $translatedword->language->language_name;
                    $counter_voteup   = $translatedword->counter_voteup;
                    $counter_votedown = $translatedword->counter_votedown;

                    $file_content = $file_content . $origin_word . "," . $language . "," . $translated_to . "," . $counter_voteup . "," . $counter_votedown . ",";
                    $file_content = $file_content . "[";
                    if(count($category_counter)>0){
                        foreach ($category_counter as $key => $value) {
                            $file_content = $file_content . "(";
                            $file_content = $file_content . $key . "-" . $value;
                            $file_content = $file_content . "),";
                        }
                        $file_content = substr($file_content, 0, -1);
                    }
                    // Cleanup koma paling akhir
                    $file_content = $file_content . "]";
                    $file_content = $file_content . "\n";
                    $result[] = $translatedword;
                }
            });

        echo $file_content;

        // DIDISABLE DULU SEMENTARA UNTUK BIKIN MODIFIKASI MENGGUNKANA CHUNK
        /*
        // Buffer sementara category items
        $category_items = CategoryItem::all();
        //lakukan mapping dari id=> categoryitem
        foreach ($category_items as $catitems) {
            $hasil[$catitems->_id]=$catitems->category_name;
        }

        foreach ($groupedTranslate as $key => $value) {
            $temp   = array(
                'origin_word'   => $value[0]->origin_word->origin_word,
                'data'          => array()
            );
            foreach ($value as $keykey => $valval) {
                $categories = CategorizedWord::where('translated_word_id','=',$valval->_id)->whereIn('categorized_to',$category_ids)->get();
                if(count($categories)>0){
                    // Lakukan counting disini
                    $category_counter = array();
                    foreach ($categories as $cat_ids) {
                        foreach ($cat_ids->categorized_to as $catid) {
                            if(!array_key_exists($hasil[$catid], $category_counter)){
                                $category_counter[$hasil[$catid]]=1;
                            }else{
                                $category_counter[$hasil[$catid]]=$category_counter[$hasil[$catid]]+1;
                            }

                        }
                    }


                    $data   = array(
                        'translated_to'    => $valval->translated_to,
                        'language'         => $valval->language->language_name,
                        'counter_voteup'   => $valval->counter_voteup,
                        'counter_votedown' => $valval->counter_votedown,
                        'categories'       => $category_counter,
                    );
                    $temp["data"][]     = $data;
                    $result[]           = $temp;
                }
            }
        }

        // $result = $groupedTranslate;

        // Parsing result


        $file_content ="";
        foreach ($result as $res) {
            foreach ($res['data'] as $datum) {
                $file_content = $file_content . $res['origin_word'];

                $file_content = $file_content . "," . $datum['language'] . "," . $datum['translated_to'] . "," . $datum['counter_voteup'] . "," . $datum['counter_votedown'] . ",";

                $file_content = $file_content. "[";
                foreach ($datum['categories'] as $key=>$value) {
                    $file_content = $file_content . "(";
                    $file_content = $file_content . $key . "-" . $value;
                    $file_content = $file_content . ")";
                    $file_content = $file_content . ",";
                }
                // Cleanup koma paling akhir
                $file_content = substr($file_content, 0, -1);
                $file_content = $file_content. "]";

                $file_content = $file_content . "\n";
            }
        }






        $filepath = "/";
        $namafile = "tesdownload.csv";
        \File::put($namafile,$file_content);

        // Me
        $file = public_path().$filepath.$namafile;
        $headers = array('Content-Type: text/csv');
        return \Response::download($file,$namafile,$headers);
        */

       return  response()->json($result, 200)->header('access-control-allow-origin', '*');
    }

}
