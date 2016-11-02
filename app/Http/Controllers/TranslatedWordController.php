<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
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
use DB;

class TranslatedWordController extends Controller
{


    public function getAll(){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success getting all translated words.";
        $isError            = FALSE;
        $missingParams      = null;


        if(!$isError) {
            try {
                $result     = TranslatedWord::all();
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

    public function getId(){

    }

    public function create(Request $request){
        $valid_origins      = array();
        $invalid_origins    = array();
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success adding new Translation / Alternate.";
        $isError            = FALSE;
        $missingParams      = null;


        $validator        = \Validator::make($request->all(),[
            'action_type' => 'required|in:translate,alternate',
            'translations'=> 'required', // hasil JSON stringify format yang valid diterima : [{origin_word_id:xyz,translated_to:makan},{origin_word_id:xyz,translated_to:dahar}]
            'user_id'     => 'required|exists:users,_id',
            'language_id' => 'required|exists:languages,_id',
        ]);



        if(!$validator->fails()) {
            try{
                $user_id             = $request->input('user_id');
                $language_id         = $request->input('language_id');
                $translations        = json_decode($request->input('translations'));

                // memastikan origin_id terdapat dalam collection origin_word dan tidak ada yang salah ketik
                $counter_point  = 0;
                foreach ($translations as $translation) {

                    $origin_word = OriginWord::where('_id','=',$translation->origin_id)->first();

                    if(!is_null($origin_word)){
                        // Berarti tidak salah ketik

                        // contoh kasus :
                        // User 1 menerjemahkan EAT ke bahasa SUNDA dengan kata DAHAR
                        // Karena gak kreatif, User 2 juga menerjemahkan EAT ke bahasa SUNDA dengan kata DAHAR juga
                        // Kata dahar milik User 2 tidak perlu ditambahkan ke collection Translated Word
                        // Tapi Tetap tercatat dalam Log bahwa user 2 pernah menerjemahkan Word tersebut.

                        // cek lagi, apakah sudah ada yang pernah membuat? Jika sudah pernah ada yang membuat, tidak perlu dicreate
                        // UPDATE: Sementara ini didisable dulu sehingga seluruh data disimpan tanpa diamin keunique-anya
                        $already_translated = null;
                        // $already_translated = TranslatedWord::where('translated_to','=',$translation->translated_to)
                        //                         ->where('origin_word_id','=',$translation->origin_id)
                        //                         ->where('language_id','=',$language_id)
                        //                         ->first();

                        if(is_null($already_translated)){
                            $alternate_source   = '';
                            if (isset($translation->translated_id)) {
                                $alternate_source   = $translation->translated_id;
                            }

                            $temp     = TranslatedWord::create(array(
                                'translated_to'    => $translation->translated_to,
                                'categorized_counter' =>0,
                                'counter_voteup'   => 0,
                                'counter_votedown' => 0,
                                'user_id'          => $user_id,
                                'origin_word_id'   => $translation->origin_id,
                                'language_id'      => $language_id,
                                'alternate_source' => $alternate_source,
                            ));

                            // Jangan lupa increment counter jumlah yang sudah pernah di translasi dalam collection origin_word
                            OriginWord::where('_id','=',$translation->origin_id)->increment('translated_counter');


                        }else{
                            $message = "Already Translated with that word but your activity is logged and you still get reward point";
                            // Jika kita ingin memberikan reward point berbeda antara orang yang pertama kali
                            // menerjemahkan kata tersebut dengan orang yang gak kreatif
                            // Misal, orang yang kreatif menerjemahkan kata tersebut dan diberik skor 500
                            // Mial orang yang gak kreatif diberi skor 250 saja..
                            // silahkan lakukan itu semua  disini.. sementara ini gak dihandle dulu..

                        }

                        // TODO yang belum dilakukan : Menambahkan point milik user
                        // 1. Cari user dengan id = $id_user
                        // 2. Ambil translated point dari Configuration
                        // 3. Increment point milik user
                        // 4. Save.
                        // Belum di test
                        if($translation->translated_to!=""){
                            $config = Configuration::first();
                            $user = User::where('_id','=',$user_id)->first();
                            $user->word_translated = $user->word_translated + 1;

                            if ($request->input('action_type') == 'translate') {
                                $user->point    = $user->point + $config->translate_point;
                                $counter_point += $config->translate_point;
                            } else if ($request->input('action_type') == 'alternate') {
                                $user->point    = $user->point + $config->alternate_point;
                                $counter_point += $config->alternate_point;
                            }

                            $user->save();

                            //Tambahin buat counter jumlah kata yang pernah ditranslate
                            Speaking::where('user_id','=',$user_id)
                                    ->where('language_id','=',$language_id)
                                    ->increment('translated_counter');

                            // Tulis log untuk penambahan point dari objek
                            Log::create(array(
                                'action_type'       => $request->input('action_type'),
                                'result'            => $user->point,
                                'user_id'           => $user_id,
                                'translated_id'     => $temp->_id,
                                ));
                        }else{
                            // kalau translated_to bernilai empty string, gak perlu ditambahin point
                            Log::create(array(
                                'action_type'       =>$request->input('action_type'),
                                'result'            =>0,
                                'user_id'           =>$user_id,
                                'translated_id'     => $temp->_id,
                            ));
                        }

                        // TODO yang belum dilakukan : Mencatat ke log
                        // 1. Cari user dengan id = $id_user
                        // 2. Catat Origin Word_id, Translated_to dan action = translate ke dalam log

                    }else{
                        $invalid_origins[] = $translation->origin_id;
                    }
                }

                $result = $counter_point;
                // Jika terdapat invalid origins, tampilkan yang invalid dalam message

                if(!empty($invalid_origins)){
                    $invalids = "";
                    foreach ($invalid_origins as $invalid_origin) {
                        $invalids = $invalids . $invalid_origin.", ";
                    }
                    $message = "Partially success, failed inserting translation becaouse origin_id : (".$invalids.") is not found";
                }

            }catch(\Exception $e){
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


    public function getNextUntranslatedWord(){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success getting untranslated words.";
        $isError            = FALSE;
        $missingParams      = null;


        if(!$isError) {
            try {
                $config                     = Configuration::first();
                $pulselab_id                = User::where('username', 'pulselab')->first()->_id;
                $words_have_translation     = TranslatedWord::where('alternate_source', '')->where('user_id', '!=', $pulselab_id)->get()->groupBy('origin_word_id')->keys()->toArray();
                $words_not_have_translation = OriginWord::whereNotIn('_id',$words_have_translation)->where('is_deleted', '!=', 1)->get();

                // var_dump($words_have_translation);

                if(count($words_not_have_translation)<=0){
                    $message                = "Success getting untranslated words. But All Word Already Translated";
                    // pastikan bahwa masih ada kata yang belum ditranslate, jika sudah habis,
                    $temp_output                 = OriginWord::where('is_deleted', '!=', 1)->get()->random($config->display_items_per_page);
                }else{
                    if ($config->display_items_per_page < count($words_not_have_translation)) {
                        $temp_output                 = $words_not_have_translation->random($config->display_items_per_page);
                    } else {
                        // pindahin sejumlah kata lama
                        foreach ($words_not_have_translation as $word) {
                            $expected_output[] = $word;
                        }

                        // hitung selisih jumlah yang kurang agar sesuai dengan admin
                        $delta = $config->display_items_per_page - count($words_not_have_translation);

                        // tambahkan kekurangan output dengan random agar memenuhi sesuai config
                        for ($i=0; $i <$delta; $i++) {
                            $expected_output[] = OriginWord::where('is_deleted', '!=', 1)->get()->random(1);
                        }

                        $temp_output = $expected_output;
                    }
                }

                // reformat output agar sesuai dengan yang diminta
                $expected_output = array();
                if ($config->display_items_per_page > 1) {
                    foreach ($temp_output as $key => $value) {
                        $expected_output[]  = $value;
                    }
                } else {
                    if (count($temp_output[0]) == 1) {
                        $expected_output    = $temp_output;
                    } else {
                        $expected_output[]  = $temp_output;
                    }
                }

                $result = $expected_output;

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


    public function getNextAlternateWord(Request $request,$user_id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success getting words to be alternated.";
        $isError            = FALSE;
        $missingParams      = null;

        $validator = \Validator::make(
            ['user_id'=>$user_id],
            ['user_id'=>'required|exists:users,_id',]
        );

        if(!$validator->fails()) {
            try {
                $user           = User::where('_id','=',$user_id)->first();
                $user_languages = $user->languages;

                foreach ($user_languages as $key => $value) {
                    $lang   = Language::find($value);

                    if (!$lang->is_deleted) {
                        $valid_languages[] = $value;
                    }
                }

                if(!empty($valid_languages)){
                    // Hitung jumlah origin_id paling minimum.
                    $origin_words_to_be_alternated       = array();
                    $config                              = Configuration::first();

                    // kocok language
                    shuffle($valid_languages);
                    $data   = TranslatedWord::raw(function($collection) use ($valid_languages){
                        return $collection->aggregate([
                            ['$match'   => [
                                'language_id'   => ['$in'   => $valid_languages],
                                'translated_to' => ['$ne'   => ''],
                                'is_deleted'    => ['$ne'   => 1],
                            ]],
                            ['$group'   => [
                                '_id'   => '$origin_word_id',
                               'count'  => [
                                   '$sum'  => 1
                               ]
                            ]],
                            ['$sort'    => [
                                'count' => 1
                            ]],
                            ['$limit'   => 1]
                        ]);
                    });

                    // var_dump($data);
                    if (isset($data['result'][0])) {
                        // dapatkan nilai paling minimumnya berapa
                        $matching_count = $data['result'][0]['count'];

                        // Select seluruh kata dengan jumlah yang minimum
                        $next   = TranslatedWord::raw(function($collection) use($valid_languages,$config,$matching_count) {
                            return $collection->aggregate([
                                ['$match' => [
                                    'language_id'   => ['$in'   => $valid_languages],
                                    'translated_to' => ['$ne'   => ''],
                                    'is_deleted'    => ['$ne'   => 1],
                                ]],
                                ['$group'    => [
                                    '_id'   => '$origin_word_id',
                                    'count' => [
                                        '$sum'  => 1
                                    ]
                                ]],
                                ['$match'     => [
                                    'count' => $matching_count
                                ]],
                            ]);
                        });
                    }
                    // var_dump($next['result']);

                    // Cek lagi apakah keluar dari loop karena break, atau justru karena seluruh bahasa tidak ada artinya..
                    if(count($next['result'])>0){
                        $idx_randomed   = array_rand($next['result'], $config->display_items_per_page);
                        $selected_data  = array();

                        if (count($idx_randomed) == 1) {
                            $selected_data[]    = $next['result'][$idx_randomed];
                        } else {
                            foreach ($idx_randomed as $key => $value) {
                                $selected_data[]    = $next['result'][$value];
                            }
                        }

                        // ukuran dari $data selalu dalam batas $config->display_items_per_page karena di query sebelumnya sudah dlimit
                        $expected_output_data =array();
                        foreach ($selected_data as $d) {
                            $data_item = TranslatedWord::where('origin_word_id',$d['_id'])
                                            ->where('translated_to','<>','')
                                            ->whereIn('language_id', $valid_languages)
                                            ->with('origin_word')
                                            ->get();

                            // pastikan hanya pilih satu terjemahan per origin word secara random
                            $selected_data_item                         = $data_item->random();

                            $expected_output_data_item                  = array(); // dicreate ulang agar nge-"flush" data lama
                            $expected_output_data_item['origin_id']     = $selected_data_item->origin_word_id;
                            $expected_output_data_item['origin_word']   = $selected_data_item->origin_word->origin_word;
                            $expected_output_data_item['translated_id'] = $selected_data_item->_id;
                            $expected_output_data_item['translated_to'] = $selected_data_item->translated_to;

                            $expected_output_data[]                     = $expected_output_data_item; // dikurangi 1 karena start index array dari 0

                            $selected_language_id                       = $selected_data_item->language_id;
                        }

                        $expected_output['language_name'] = Language::where('_id','=',$selected_language_id)->first()->language_name;
                        $expected_output['language_id']   = $selected_language_id;
                        $expected_output['data']          = $expected_output_data;
                        $result                           = $expected_output;

                    }else{
                        // Ini berarti karena loop dalam for diatas dan masih belum menemukan ada isinya atau belum ada bahasa yang punya translate
                        // oleh karena itu harus di redirect agar melakukan translte sebelum melakukan alternate
                        $result = null;
                    }
                }else{
                    throw new \Exception("You must pick at least one valid languages", 1);
                }
                /**/
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



    /*
    * WARNING
    * Fungsi ini juga masih eksperimental.. belum stabil, gak perlu dibaca
    * dulu kecuali punya ide untuk "ngebenerin"
    */
    public function getNextVoteWord(Request $request,$user_id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success getting words to be alternated.";
        $isError            = FALSE;
        $missingParams      = null;

        $validator = \Validator::make(
            ['user_id'=>$user_id],
            ['user_id'=>'required|exists:users,_id']
        );

        if(!$validator->fails()) {
            try {
                $config         = Configuration::first();
                $user           = User::where('_id','=',$user_id)->first();
                $user_languages = $user->languages;

                foreach ($user_languages as $key => $value) {
                    $lang   = Language::find($value);
                    if (!$lang->is_deleted) {
                        $valid_languages[] = $value;
                    }
                }

                if(!empty($valid_languages)){

                    // kocok bahasa dulu...
                    shuffle($valid_languages);
                    foreach ($valid_languages as $valid_language) {
                        $selected_language_id = $valid_language;

                        $data = TranslatedWord::orderBy('counter_voteup','asc')
                                    ->orderBy('counter_votedown','asc')
                                    ->where('translated_to','<>','')
                                    ->where('language_id',$selected_language_id)
                                    ->take($config->display_items_per_page*4) // dikali 4 biar ada 0.25 kemungkinan random
                                    ->get(array('origin_word_id'));
                                    // ->get(array('origin_word_id'));
                        $data   = TranslatedWord::raw(function($collection) use($selected_language_id){
                            return $collection->aggregate([
                                ['$match'=>[
                                   'language_id' => $selected_language_id,
                                    'translated_to' => [
                                        '$ne'   => ""
                                    ]
                                ]],
                                ['$project'=>[
                                    // 'id'=>'$_id',
                                    'total'=>[
                                        '$add'=>[
                                            '$counter_voteup',
                                            '$counter_votedown',
                                        ]
                                    ],
                                    'translated_to' =>'$translated_to',
                                    'language_id'   => '$language_id'
                                ]],
                                ['$sort'=> [
                                    'total'=>1
                                ]],
                                ['$limit' => 1]
                            ]);
                        });

                        if (isset($data['result'][0])) {
                            // dapatkan jumlah count / total yang paling minimum
                            $matching_counter = $data['result'][0]['total'];

                            // Lakukan seleksi berdasarkan counter yang sesuai (paling minimum)
                            $data   = TranslatedWord::raw(function($collection) use($selected_language_id,$matching_counter){
                                return $collection->aggregate([
                                    ['$match'=>[
                                        'language_id' => $selected_language_id,
                                        'translated_to' => [
                                            '$ne'   => ""
                                        ],
                                        'is_deleted' => ['$ne' => 1],
                                    ]],
                                    ['$project'=>[
                                        // 'id'=>'$_id',
                                        'total'=>[
                                            '$add'=>[
                                                '$counter_voteup',
                                                '$counter_votedown',
                                        ]
                                        ],
                                        'translated_to'  => '$translated_to',
                                        'language_id'    => '$language_id',
                                        'origin_word_id' => '$origin_word_id',
                                    ]],
                                    ['$match'=>[
                                        'total'=> $matching_counter
                                    ]],
                                    ['$sort'=> [
                                        'total'=>1
                                    ]],
                                ]);
                            });

                            if(count($data)>0){
                                break;
                            }else{
                                continue;
                            }
                        }
                    }

                    //cek lagi, apakah keluar dari loop foreach karena memang jumlah datanya >0 atau justru karena for loopnya habis.
                    if(count($data['result'])>0){
                        // untuk mengocok menggunakan fungsi native untuk mengocok array dalam php (shuffle), harus diubah dari format object eloquent laravel menjadi array
                        for ($i=0; $i < count($data['result']); $i++) {
                            $unrandomized_data[] = $data['result'][$i];
                        }

                        //dikocok biar random
                        shuffle($unrandomized_data);

                        // Pilih sesuai config
                        for ($i=0; $i < $config->display_items_per_page; $i++) {
                            $selected_data[] = $unrandomized_data[$i];
                        }
                        //$selected_data[] = $data[rand(0,count($data)-1)];

                        $expected_output_data =array();
                        foreach ($selected_data as $d) {
                            $data_item = TranslatedWord::where('origin_word_id',$d['origin_word_id'])
                                            ->where('translated_to','<>','')
                                            ->where('language_id','=',$selected_language_id)
                                            ->with('origin_word')
                                            ->take($config->display_items_per_page*4) // biar ada faktor random sedikit (lihat bagian array_rand)
                                            ->get();

                            // pastikan hanya pilih satu translasi per origin word
                            $selected_data_item                         = $data_item[rand(0,count($data_item)-1)];

                            $expected_output_data_item                  = array(); // dicreate ulang agar nge-"flush" data lama
                            $expected_output_data_item['translated_id'] = $selected_data_item->_id;
                            $expected_output_data_item['origin_word']   = $selected_data_item->origin_word->origin_word;
                            $expected_output_data_item['translated_to'] = $selected_data_item->translated_to;

                            $expected_output_data[]                     = $expected_output_data_item; // dikurangi 1 karena start index array dari 0
                        }

                        $expected_output['language_name'] = Language::where('_id','=',$selected_language_id)->first()->language_name;
                        $expected_output['language_id']   = $selected_language_id;
                        $expected_output['data']          = $expected_output_data;
                        $result                           = $expected_output;
                        /**/
                    }else{
                        $result = null;
                    }
                }else{
                    throw new \Exception("You must pick at least one valid languages", 1);
                }
                /**/
            } catch (Exception $e) {
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

    public function create_vote_word(Request $request){
        $valid_origins      = array();
        $invalid_origins    = array();
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success adding new Language.";
        $isError            = FALSE;
        $missingParams      = null;


        $validator        = \Validator::make($request->all(),[
            'votes'       => 'required', // hasil JSON stringify format yang valid diterima : [{origin_word_id:xyz,translated_to:makan},{origin_word_id:xyz,translated_to:dahar}]
            'user_id'     => 'required|exists:users,_id',
        ]);

        // Format votes yang diterima :
        // [
        //    {
        //           translated_id : <translated_id>,
        //           action : voteup/votedown
        //    },{
        //           translated_id>: <translated_id>:
        //           action : <voteup/votedown>
        //    },
        //  dst..]

        // contoh input yang udha di test
        // [{"translated_id":"564451bc842e9f200500006b","action":"voteup"},{"translated_id":"564451bc842e9f200500006a","action":"votedown"},{"translated_id":"564451bc842e9f2005000069","action":"voteup"}]



        if(!$validator->fails()) {
            try{
                $config          = Configuration::first();
                $user_id         = $request->input('user_id');
                $votes           = json_decode($request->input('votes'));

                $counter        = 0;

                // Memastikan bahwa setiap translated id terdapat dalam TranslatedWord
                foreach ($votes as $vote) {
                    $translated_word = TranslatedWord::where('_id','=',$vote->translated_id)->first();

                    if(!is_null($translated_word)){
                        // Berarti tidak salah ketik
                        $votevalue = $vote->action;
//                        $result[] = $translated_word;

                        if(!is_null($votevalue)){
                            // Increment counter sesuai dengan jenis vote
                            if($votevalue=='voteup'){
                                $translated_word->increment('counter_voteup');
                                $translated_word->save();

                                // Tambahkan point untuk subjek yang melakukan voting
                                $user = User::where('_id','=',$user_id)->first();
                                $user->point = $user->point + $config->voter_point;
                                $user->save();

                                $counter += $config->voter_point;

                                // Tambahkan point untuk objek yang menerjemahkan
                                $translator = User::where('_id','=',$translated_word->user_id)->first();
                                $translator->point = $translator->point + $config->vote_up_point;
                                $translator->save();

                                if ($user_id == $translated_word->user_id) {
                                    $counter += $config->vote_up_point;
                                }

                                // Tulis log untuk penambahan point dari subjek
                                Log::create(array(
                                    'action_type'=>'vote_up',
                                    'result'=>$user->point,
                                    'user_id'=>$user_id,
                                    'translated_id' => $vote->translated_id,
                                    'affected_user' => $translator->_id,
                                ));

                                // Tulis log untuk penambahan point dari objek
                                Log::create(array(
                                    'action_type'=>'voted by another user',
                                    'result'=>$translator->point,
                                    'user_id'=>$translator->_id,
                                    'translated_id' => $vote->translated_id,
                                    'affected_user' => $user->_id,
                                ));

                            }else if($votevalue=='votedown'){
                                $translated_word->increment('counter_votedown');
                                $translated_word->save();

                                // Tambahkan point untuk subjek yang melakukan voting
                                $user = User::where('_id','=',$user_id)->first();
                                $user->point = $user->point + $config->voter_point;
                                $user->save();

                                $counter += $config->voter_point;

                                // kurangi point untuk objek yang menerjemahkan
                                $translator = User::where('_id','=',$translated_word->user_id)->first();
                                $translator->point = $translator->point - $config->vote_down_point;

                                if (\time() < \strtotime($translator->last_kicked)) {

                                } else if ($translator->health > 1) {
                                    $translator->decrement('health');
                                } else if ($translator->health == 1) {
                                    $translator->health       = $config->max_health;
                                    $translator->last_kicked  = \date(DATE_RFC2822, \strtotime('+'.$config->kick_time.' minute'));
                                }

                                $translator->save();

                                if ($user_id == $translated_word->user_id) {
                                    $counter -= $config->vote_down_point;
                                }

                                // Tulis log untuk penambahan point dari subjek
                                Log::create(array(
                                    'action_type'=>'vote_down',
                                    'result'=>$user->point,
                                    'user_id'=>$user_id,
                                    'translated_id' => $vote->translated_id,
                                    'affected_user' => $translator->_id,
                                ));

                                // Tulis log untuk penambahan point dari objek
                                Log::create(array(
                                    'action_type'=>'voted by another user',
                                    'result'=>$translator->point,
                                    'user_id'=>$translator->_id,
                                    'translated_id' => $vote->translated_id,
                                    'affected_user' => $user->_id,
                                ));
                            }else{
                                // Berarti invalid bukan vote up/ vote down abaikan
                                continue;
                            }
                        }else{
                            throw new \Exception("You should put action values for every translated id", 1);
                        }

                        // increment counter vote
                        OriginWord::where('_id','=',$translated_word->origin_id)->increment('voted_counter');

                        // TODO yang belum dilakukan : Menambahkan point milik user
                        // 1. Cari user dengan id = $id_user
                        // 2. Ambil translated point dari Configuration
                        // 3. Increment point milik user
                        // 4. Save.

                        // TODO yang belum dilakukan : Mencatat ke log
                        // 1. Cari user dengan id = $id_user
                        // 2. Catat Origin Word_id, Translated_to dan action = translate ke dalam log

                    }else{
                        $invalid_voted_word[] = $vote->translated_id;
                    }
                }

                $result = $counter;

                // Jika terdapat invalid origins, tampilkan yang invalid dalam message
                if(!empty($invalid_voted_word)){
                    $message    = $message."Your voting is recorded on log but Theese translated_ids are not valid : {".implode(', ', $invalid_voted_word)."}";
                }

            }catch(Exception $e){
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

    public function getNextUncategorized(Request $request,$user_id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success getting words to be alternated.";
        $isError            = FALSE;
        $missingParams      = null;

        $validator = \Validator::make(
            ['user_id'=>$user_id],
            ['user_id'=>'required|exists:users,_id',]
        );

        if(!$validator->fails()) {
            try {
                $config         = Configuration::first();
                $user           = User::where('_id','=',$user_id)->first();
                $user_languages = $user->languages;

                foreach ($user_languages as $key => $value) {
                    $lang   = Language::find($value);
                    if (!$lang->is_deleted) {
                        $valid_languages[] = $value;
                    }
                }

                // Algoritma baru sesuai spek baru
                // 1. Pilih bahasa yang dikuasai user
                // 2. Cek apakah bahasa tersebut sudah pernah diterjemahkan dalam Translated word
                // 3. Jika sudah pernah, pilih kata yang memiliki count categorized words paling sedikit lakukan groupby dengan id

                /*
                db.translated_words.aggregate(
                    [
                        {
                            $group:
                                {
                                    $_id:"$translated_to",
                                    ids:{
                                        $push:"$_id"
                                    }
                                }

                        }
                    ]
                )
                */

                if(!empty($valid_languages)){
                    // kocok bahasa pilih salah satu bahasa
                    // shuffle($valid_languages);
                    // for ($i=0; $i <count($valid_languages); $i++) {
                        // $selected_language_id = $valid_languages[$i];
                        // var_dump($valid_languages);
                        // Ambil origin word yang belum pernah dikategorikan
                        $data   = TranslatedWord::raw(function($collection) use($config,$valid_languages) {
                                                    return $collection->aggregate([
                                                        ['$match' => [
                                                            'language_id' => [
                                                                '$in'=>$valid_languages
                                                            ],
                                                            'translated_to' => [
                                                                '$ne'   => ""
                                                            ]
                                                        ]],
                                                        ['$group'    => [
                                                            '_id'   => [
                                                                'translated_to' =>'$translated_to',
                                                                'language_id'=>'$language_id'
                                                            ],
                                                            'ids' => [
                                                                '$push'  => '$_id'
                                                            ],
                                                            'language_id' => [
                                                                '$first' => '$language_id'
                                                            ],
                                                            'count'=>[
                                                                '$sum' => '$categorized_counter'
                                                            ]
                                                        ]],
                                                        ['$sort'     => [
                                                            'count' => 1
                                                        ]],
                                                        ['$limit'   => 1] // hanya 1 buah yang ditampilkan ke layar
                                                    ]);
                                                });

                        // Dapatkan jumlah countnya yang sama dengan yang dipilih pertamakali
                        $matching_counter = $data['result'][0]['count'];

                        // Pilih N buah yang jumlahnya sama dengan jumlah minimum
                        $data   = TranslatedWord::raw(function($collection) use($config,$valid_languages,$matching_counter) {
                                                    return $collection->aggregate([
                                                        ['$match' => [
                                                            'language_id' => [
                                                                '$in'=>$valid_languages
                                                            ],
                                                            'translated_to' => [
                                                                '$ne'   => ""
                                                            ],
                                                            'is_deleted' => ['$ne' => 1],
                                                        ]],
                                                        ['$group'    => [
                                                            '_id'   => [
                                                                'translated_to' =>'$translated_to',
                                                                'language_id'=>'$language_id'
                                                            ],
                                                            'ids' => [
                                                                '$push'  => '$_id'
                                                            ],
                                                            'language_id' => [
                                                                '$first' => '$language_id'
                                                            ],
                                                            'count'=>[
                                                                '$sum' => '$categorized_counter'
                                                            ]
                                                        ]],
                                                        ['$match'     => [
                                                            'count' => $matching_counter,
                                                        ]],
                                                    ]);
                                                });


                    // format ulang agar sesuai dengan output
                    if (!empty($data['result'])) {
                        // karena hanya dipilih 1 tidak perlu menggunakan shuffle dan cukup dengan random
                        $selected_data          = $data['result'][rand(0,count($data['result'])-1)];

                        $ids                    = $selected_data['ids']; // dipilih index 0 karena ukuran array hasil query hanya 1 buah
                        $selected_id            = $ids[rand(0,count($ids)-1)]->{'$id'}; // random salah satu saja
                        $selected_language_id   = $selected_data['language_id'];
                        $selected_language      = Language::find($selected_language_id)->first()->language_name;
                        $selected_translated_to = $selected_data['_id']['translated_to'];

                        $expected_output_uncategorized['translated_id'] = $selected_id;
                        $expected_output_uncategorized['translated_to'] = $selected_translated_to;
                        $expected_output_uncategorized['language_id']   = $selected_language_id;
                        $expected_output_uncategorized['language_name'] = $selected_language;

                        $selected_category          = Category::with('category_items')->get()->random(1);
                        $selected_category_items = $selected_category->category_items;

                        $unrandomized_category_item = array();

                        foreach ($selected_category_items as $selected_category_item) {
                            $unrandomized_category_item[] = $selected_category_item->toArray();
                        }

                        if(count($unrandomized_category_item)>0){
                            // Random dan pilih 3 saja

                            $other_key      = array_search('other', array_column($unrandomized_category_item, 'category_name'));
                            $other_value    = $unrandomized_category_item[$other_key];

                            unset($unrandomized_category_item[$other_key]);

                            $unrandomized_category_item = array_values($unrandomized_category_item);

                            if (count($unrandomized_category_item) > 3) {
                                $randomed_key  = array_rand($unrandomized_category_item, 3);

                                foreach($randomed_key as $value) {
                                    $randomized_category_items[]    = $unrandomized_category_item[$value];
                                }

                            } else {
                                $randomized_category_items  = $unrandomized_category_item;
                            }
                            // Last piece tambahkan category other yang memiliki id parent category = id  selectedcategory
                            $randomized_category_items[] = $other_value;

    //                        $randomized_category_items = null;
                            $expected_output_category['_id']   = $selected_category->_id;
                            $expected_output_category['category_group'] = $selected_category->category_group;
                            $expected_output_category['category_items'] = $randomized_category_items;

                            $result['uncategorized'] = $expected_output_uncategorized;
                            $result['category'] = $expected_output_category;
                        }else{
//                            throw new \Exception("Your Category Group doesn't have any category item yet or your category item is less than 3 category", 1);

                        }

                        // var_dump($unrandomized_category_item);

                    } else {

                    }

                }else{
                    throw new \Exception("You Should pick at least one language", 1);
                }
            } catch (Exception $e) {
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

    public function create_categorized_word(Request $request){
        $valid_origins      = array();
        $invalid_origins    = array();
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success updating category items for origin word.";
        $isError            = FALSE;
        $missingParams      = null;


        $validator        = \Validator::make($request->all(),[
            'categories'    => 'required', // hasil JSON stringify format yang valid diterima baca dibawah
            'translated_id' => 'required|exists:translated_words,_id',
            'user_id'       => 'required|exists:users,_id',
        ]);

        // Format votes yang diterima :
        // [
        //      <category_item_id>,
        //      <category_item_id>,
        //      <category_item_id>,
        //         ... dst ...
        //      <category_item_id>,
        // ]


        if(!$validator->fails()) {
            try{
                $config        = Configuration::first();
                $user_id       = $request->input('user_id');
                $translated_id = $request->input('translated_id');
                $categories    = json_decode($request->input('categories'));
                $counter_point = 0; // menghitung berapa jumlah point yang diberikan kepada user

                // Memastikan bahwa setiap category id terdapat dalam category items
                $valid_categories = array();
                foreach ($categories as $category_items_id) {
                    $category = CategoryItem::where('_id','=',$category_items_id)->first();

                    if(!is_null($category)){
                        $valid_categories[] = $category->_id;
                    }else{
                        $invalid_voted_word[] = $category_items_id;
                    }
                }


                if(!empty($valid_categories)){
                    CategorizedWord::create(array(
                        'translated_word_id' => $translated_id,
                        'categorized_to' => $valid_categories,
                        'user_id' => $user_id,
                    ));

                    TranslatedWord::where('_id','=',$translated_id)->first()->increment('categorized_counter');

                    $user          = User::where('_id','=',$user_id)->first();
                    $user->point   = $user->point + $config->categorize_point;
                    $user->save();
                    $counter_point = $counter_point + $config->categorize_point;

                    // Tulis log
                    // Tulis log untuk penambahan point dari objek
                    Log::create(array(
                        'action_type'=>'categorize',
                        'result'=>$user->point,
                        'user_id'=>$user->_id,
                        'translated_id'=> $translated_id,
                    ));
                }

                // tambahan jika diperlukan tampilkan juga kategori yang invalid
                if(!empty($invalid_categories)){
                    $message    = $message."Your voting is counted but Theese translated_ids are not valid : {".implode(', ', $invalid_categories)."}";
                }
                $result = $counter_point;
            }catch(\Exception $e){
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

    public function statistic(){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success getting words to be alternated.";
        $isError            = FALSE;
        $missingParams      = null;

        try {
            $result['line_chart']   = array(
                'lineseries'    => array('translate','alternate','vote','categorize'),
                'linelabels'    => null,
                'linedata'      => array(null, null, null, null),
            );

            for ($i = 6; $i >= 0; $i--) {
                $now        = new \Datetime( (new \Datetime( "-$i days" ))->format('Y-m-d') );
                $next       = new \Datetime( (new \Datetime( "-".($i - 1)." days" ))->format('Y-m-d') );
                $label      = $now->format('d / m');

                $result['line_chart']['linelabels'][]   = $label;
                $result['line_chart']['linedata'][0][]  = Log::where('action_type', 'translate')->where('created_at', '>=', $now)->where('created_at', '<', $next)->count();
                $result['line_chart']['linedata'][1][]  = Log::where('action_type', 'alternate')->where('created_at', '>=', $now)->where('created_at', '<', $next)->count();
                $result['line_chart']['linedata'][2][]  = Log::where('action_type', 'vote_up')->orWhere('action_type', 'vote_down')->where('created_at', '>=', $now)->where('created_at', '<', $next)->count();
                $result['line_chart']['linedata'][3][]  = Log::where('action_type', 'categorize')->where('created_at', '>', $now)->where('created_at', '<', $next)->count();
            }

            $pulselab_id        = User::where('username', 'pulselab')->first()->_id;

            // Format ulang agar sesuai dengan hasil yang diharapkan.
            $languages           = Language::all();

            $radar  = array(
                'radarlabels'   => null,
                'radarseries'   => array("User","Translated Word"),
                'radardata'     => array(null, null),
            );

            foreach ($languages as $language) {
                $radar['radarlabels'][] = $language->language_name;
                $radar['radardata'][0][]    = Speaking::where('language_id', $language->_id)->count();
                $radar['radardata'][1][]    = TranslatedWord::where('language_id', $language->_id)->where('alternate_source', '')->where('user_id', '!=', $pulselab_id)->get()->groupBy('origin_word_id')->count();
            }
            $result['radar_chart']    = $radar;

            // Langkah 3 : Cari statistic untuk ringkasan seluruhnya
            $data_overall1   = TranslatedWord::raw(function($collection){
                return $collection->aggregate([
                    ['$group'    => [
                        '_id'   => [],
                        'total_counter_voteup' => [
                            '$sum'=>'$counter_voteup'
                        ],
                        'total_counter_votedown' => [
                            '$sum'=>'$counter_votedown'
                        ],
                        'total_counter_categorized' => [
                            '$sum'=>'$counter_categorized'
                        ]
                    ]],
                ]);
            });
            $result_stats['translated']  = Log::where('action_type','=','translate')->count();
            $result_stats['alternated']  = Log::where('action_type','=','alternate')->count();
            $result_stats['voted']       = Log::where('action_type','=','vote_up')->count()+Log::where('action_type','=','vote_down')->count();//$data_overall1['result'][0]['total_counter_votedown'] + $data_overall1['result'][0]['total_counter_voteup'];
            $result_stats['categorized'] = Log::where('action_type','=','categorize')->count();//$data_overall1['result'][0]['total_counter_categorized'];
            $result['stats'] = $result_stats;

            $translatedWords    = TranslatedWord::where('alternate_source', '')->where('user_id', '!=', $pulselab_id)->get()->groupBy('origin_word_id')->count();
            $alternatedWords    = TranslatedWord::where('alternate_source', '!=', '')->where('user_id', '!=', $pulselab_id)->get()->groupBy('origin_word_id')->count();
            $votedWords         = TranslatedWord::where('counter_voteup', '>', 0)->orWhere('counter_votedown', '>', 0)->get()->groupBy('origin_word_id')->count();
            $categorizedWords   = CategorizedWord::with(array('translated_word'))->get();
            $words              = OriginWord::count();

            $categorizedCounter = array();
            foreach ($categorizedWords as $key => $value) {
                $categorizedCounter[$value->translated_word['origin_word_id']]  = 1;
            }

            $result['words']    = array(
                'translated'    => $translatedWords.'/'.$words,
                'alternated'    => $alternatedWords.'/'.$words,
                'voted'         => $votedWords.'/'.$words,
                'categorized'   => sizeof($categorizedCounter).'/'.$words,
            );

        } catch (\Exception $e) {
            $response   = "FAILED";
            $statusCode = 400;
            $message    = $e->getMessage();
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }


    /*
    *  Catatan / DISCLAIMER
    *  Implementasi fungsi export disini masih belum efisien, dan masih boros query. Implementasi disini belum dibandingkan
    *  dengan implementasi menggunakan map reduce
    */
    public function export(Request $request){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success exporting translated word.";
        $isError            = FALSE;
        $missingParams      = null;
        $jumlah_chunk_data  = 200;


        // FORMAT Input yang valid diterima :
        // languages : [Array of id_language]
        // categories : [Array of id_category]
        // Validasi input, pastikan setiap languages / categories ada di dalam database
        // Buffer sementara category items

        $category_items = CategoryItem::all();
        //lakukan mapping dari id=> categoryitem
        foreach ($category_items as $catitems) {
            $hasil[$catitems->_id]=$catitems->category_name;
        }

        try {
            if(($request->input('languages')!=null) || ($request->input('categories')!=null)){
                $valid_languages  = array();
                $valid_categories = array();
                $input_languages  = json_decode($request->input('languages'));
                $input_categories = json_decode($request->input('categories'));

                $namafile = "Crowdsource_dump.csv";

                if(!is_null($input_languages)){
                    $valid_languages = array();
                    foreach ($input_languages as $input_language) {
                        // Periksa apakah ada language dengan id = $input_language
                        $lang = Language::find($input_language);//->first();

                        if(!is_null($lang)){
                            $valid_languages[] = $lang->_id;
                        }
                    }

                    if(count($valid_languages)>0){
                        if(!is_null($input_categories)){
                            foreach($input_categories as $input_category){
                                $cat = CategoryItem::find($input_category);

                                if(!is_null($cat)){
                                    $valid_categories[] = $cat->_id;
                                }
                            }

                            if(count($valid_categories) > 0){
                                // Seleksi berdasarkan category dan bahasa
                                $temp_result = array();

                                // Langkah 1 : pilih berdasarkan bahasa dulu
                                $file_content = "";
                                $fp = fopen($namafile, "w");
                                TranslatedWord::whereIn('language_id', $valid_languages)
                                    ->with(['origin_word', 'language'])
                                    ->orderBy('origin_word_id')
                                    ->orderBy('language_id')
                                    ->orderBy('translated_to')
                                    ->chunk($jumlah_chunk_data,function($translatedwords) use (&$result,&$file_content,&$category_ids,$hasil,$valid_categories,$fp){
                                        foreach($translatedwords as $translatedword){

                                            $categories       = CategorizedWord::where('translated_word_id','=',$translatedword->_id)->whereIn('categorized_to',$valid_categories)->get();
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
                                            // tambahan
                                            fputs($fp,$file_content);
                                            $file_content = null;
                                            $result[] = $translatedword;
                                        }
                                    });
                                    fclose($fp);


                                // Langkah 4. Buat file dan lakukan dowload
                                $filepath = "/";
                                // \File::put($namafile,$file_content);

                                // Me
                                $file = public_path().$filepath.$namafile;
                                $headers = array('Content-Type: text/csv');
                                return \Response::download($file,$namafile,$headers);
                            }else{
                                // seleksi hanya berdasarkan bahasa
                                $temp_result = array();

                                // Langkah 1 : pilih berdasarkan bahasa, Pilih seluruh kategori
                                $file_content = "";
                                $fp = fopen($namafile, "w");
                                TranslatedWord::whereIn('language_id', $valid_languages)
                                    ->with(['origin_word', 'language'])
                                    ->orderBy('origin_word_id')
                                    ->orderBy('language_id')
                                    ->orderBy('translated_to')
                                    ->chunk($jumlah_chunk_data,function($translatedwords) use (&$result,&$file_content,&$category_ids,$hasil,$valid_categories,$fp){
                                        foreach($translatedwords as $translatedword){

                                            $categories       = CategorizedWord::where('translated_word_id','=',$translatedword->_id)->get();
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
                                            // tambahan
                                            fputs($fp,$file_content);
                                            $file_content = null;
                                            $result[] = $translatedword;
                                        }
                                    });
                                fclose($fp);


                                // Langkah 4. Buat file dan lakukan dowload
                                $filepath = "/";
                                // \File::put($namafile,$file_content);

                                // Me
                                $file = public_path().$filepath.$namafile;
                                $headers = array('Content-Type: text/csv');
                                return \Response::download($file,$namafile,$headers);
                            }
                        }else{
                            // seleksi hanya berdasarkan bahasa saja
                            $temp_result = array();

                            // Langkah 1 : pilih hanya berdasarkan bahasa karena input categories = null
                            $file_content = "";
                            $fp = fopen($namafile, "w");
                            TranslatedWord::whereIn('language_id', $valid_languages)
                                ->with(['origin_word', 'language'])
                                ->orderBy('origin_word_id')
                                ->orderBy('language_id')
                                ->orderBy('translated_to')
                                ->chunk($jumlah_chunk_data,function($translatedwords) use (&$result,&$file_content,&$category_ids,$hasil,$valid_categories,$fp){
                                    foreach($translatedwords as $translatedword){

                                        $categories       = CategorizedWord::where('translated_word_id','=',$translatedword->_id)->get();
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
                                        //tambahan
                                        fputs($fp,$file_content);
                                        $file_content = null;
                                        $result[] = $translatedword;
                                    }
                                });
                            fclose($fp);


                            // Langkah 4. Buat file dan lakukan dowload
                            $filepath = "/";
                            // \File::put($namafile,$file_content);

                            // Me
                            $file = public_path().$filepath.$namafile;
                            $headers = array('Content-Type: text/csv');
                            return \Response::download($file,$namafile,$headers);
                        }
                    }else{
                        throw new \Exception("Must Select at least one valid languages", 1);
                    }
                }else{
                    if(!is_null($input_categories)){
                        foreach($input_categories as $input_category){
                            $cat = CategoryItem::find($input_category);

                            if(!is_null($cat)){
                                $valid_categories[] = $cat->_id;
                            }
                        }

                        if(count($valid_categories) > 0){
                            // Download hanya berdasarkan kategori
                            $temp_result = array();

                            // Langkah 1 : pilih SELURUH BAHASA
                            $file_content = "";
                            $fp = fopen($namafile, "w");
                            TranslatedWord::with(['origin_word', 'language'])
                                ->orderBy('origin_word_id')
                                ->orderBy('language_id')
                                ->orderBy('translated_to')
                                ->chunk($jumlah_chunk_data,function($translatedwords) use (&$result,&$file_content,&$category_ids,$hasil,$valid_categories,$fp){
                                    foreach($translatedwords as $translatedword){

                                        $categories       = CategorizedWord::where('translated_word_id','=',$translatedword->_id)->whereIn('categorized_to',$valid_categories)->get();
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
                                        //tambahan
                                        fputs($fp,$file_content);
                                        $file_content = null;
                                        $result[] = $translatedword;
                                    }
                                });
                            fclose($fp);


                            //Langkah 4. Buat file dan lakukan dowload

                            $filepath = "/";
                            $namafile = "tesdownload.csv";
                            // \File::put($namafile,$file_content);


                            // Me
                            $file = public_path().$filepath.$namafile;
                            $headers = array('Content-Type: text/csv');
                            return \Response::download($file,$namafile,$headers);
                        }else{
                            throw new \Exception("Must Select at least one valid categories", 1);
                        }
                    }else{
                        throw new \Exception("Pick either categories or languages should be suplied", 1);
                    }
                }
            }else{
                throw new \Exception("Either Categories or languages should be supplied", 1);
            }
        } catch (\Exception $e) {
            $response   = "FAILED";
            $statusCode = 400;
            $message    = $e->getMessage();
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
