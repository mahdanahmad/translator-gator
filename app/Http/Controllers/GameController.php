<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Log;
use App\Models\User;
use App\Models\Category;
use App\Models\Language;
use App\Models\Speaking;
use App\Models\OriginWord;
use App\Models\Configuration;
use App\Models\TranslatedWord;
use App\Models\CategorizedWord;

class GameController extends Controller {
    /**
     * Retrieve words that haven't translated yet.
     *
     * @return Response
     */
    public function getUntranslated() {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrive untranslated word success.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $config             = Configuration::first();
                $translatedwords    = OriginWord::whereNotNull('translated_counter')->where('is_deleted', '!=', 1)->get();
                $untranslatedwords  = OriginWord::whereNull('translated_counter')->where('is_deleted', '!=', 1)->get();

                if ($config->display_items_per_page > $untranslatedwords->count()) {
                    $result     = array_merge($untranslatedwords->shuffle()->toArray(), $translatedwords->random($config->display_items_per_page - $untranslatedwords->count())->shuffle()->toArray());
                } else {
                    $result     = $untranslatedwords->random($config->display_items_per_page)->shuffle()->toArray();
                }

                if ($config->display_items_per_page == 1) { $result = array($result); }
                $result     = array_values($result);
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
     * Retrieving available translated words that can be voted based on user languages.
     *
     * @param  string  $user_id
     * @return Response
     */
    public function getNextVote($user_id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrive vote for user $user_id success.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $user       = User::find($user_id);
                if ($user) {
                    $config = Configuration::first();

                    $language_available = TranslatedWord::whereIn('language_id', $user->languages)->get()->unique('language_id')->pluck('language_id')->toArray();
                    if (!empty($language_available)) {
                        $selected_language  = $language_available[array_rand($language_available)];
                        $data               = TranslatedWord::raw(function($collection) use($selected_language, $config) {
                            return $collection->aggregate(array(
                                array('$match'      => array(
                                    'language_id'       => $selected_language,
                                    'translated_to'     => array('$ne' => ""),
                                    'is_deleted'        => array('$ne' => 1))),
                                array('$project'    => array(
                                    'total'             => array('$add' => array('$counter_voteup', '$counter_votedown')),
                                    'translated_to'     => '$translated_to',
                                    'language_id'       => '$language_id',
                                    'origin_word_id'    => '$origin_word_id')),
                                array('$sort'       => array('total' => 1)),
                                array('$limit'      => $config->display_items_per_page * 4)
                            ));
                        });

                        if ($data->count() > $config->display_items_per_page) { $data = $data->random($config->display_items_per_page)->toArray(); } else { $data = $data->toArray(); }
                        if ($config->display_items_per_page == 1) { $data = array($data); }

                        $data           = array_values($data);
                        $origin_words   = OriginWord::whereIn('_id', array_map(function($o) { return $o['origin_word_id']; }, $data))->get(array('origin_word'))->pluck('origin_word', '_id')->toArray();
                        $result         = array(
                            'language_id'   => $selected_language,
                            'language_name' => Language::find($selected_language)->language_name,
                            'data'          => array_map(function($o) use ($origin_words) { return array('translated_id' => $o['_id'], 'origin_word' => $origin_words[$o['origin_word_id']], 'translated_to' => $o['translated_to']); }, $data),
                        );
                    } else { throw new \Exception("Currently no language available for you. :("); }
                } else { throw new \Exception("User with id $user_id not found"); }
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
     * Retrieving translated word that can be given another translation based on user languages.
     *
     * @param  string  $user_id
     * @return Response
     */
    public function getNextAlternate($user_id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrive translate for user with id $user_id success.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $user       = User::find($user_id);
                if ($user) {
                    $config = Configuration::first();

                    $language_available = TranslatedWord::whereIn('language_id', $user->languages)->get()->unique('language_id')->pluck('language_id')->toArray();
                    if (!empty($language_available)) {
                        $selected_language  = $language_available[array_rand($language_available)];
                        $data               = TranslatedWord::raw(function($collection) use($selected_language, $config) {
                            return $collection->aggregate(array(
                                array('$match'      => array(
                                    'language_id'       => $selected_language,
                                    'translated_to'     => array('$ne' => ""),
                                    'is_deleted'        => array('$ne' => 1))),
                                array('$group'      => array(
                                    '_id'               => '$origin_word_id',
                                    'count'             => array('$sum' => 1),
                                    'translated_id'     => array('$last' => '$_id'),
                                    'translated_to'     => array('$last' => '$translated_to'))),
                                array('$sort'       => array('count' => 1)),
                                array('$limit'      => $config->display_items_per_page * 4)
                            ));
                        });

                        if ($data->count() > $config->display_items_per_page) { $data = $data->random($config->display_items_per_page)->toArray(); } else { $data = $data->toArray(); }
                        if ($config->display_items_per_page == 1) { $data = array($data); }

                        $data           = array_values($data);
                        $origin_words   = OriginWord::whereIn('_id', array_map(function($o) { return $o['_id']; }, $data))->get(array('origin_word'))->pluck('origin_word', '_id')->toArray();
                        $result         = array(
                            'language_id'   => $selected_language,
                            'language_name' => Language::find($selected_language)->language_name,
                            'data'          => array_map(function($o) use ($origin_words) { return array('origin_id' => $o['_id'], 'origin_word' => $origin_words[$o['_id']], 'translated_id' => $o['translated_id'], 'translated_to' => $o['translated_to']); }, $data),
                        );
                    } else { throw new \Exception("Currently no language available for you. :("); }
                } else { throw new \Exception("User with id $user_id not found"); }
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
     * Retrieve translated words that can be categorized based on user language.
     *
     * @param  string  $user_id
     * @return Response
     */
    public function getNextCategorize($user_id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrive categorize for user $user_id success.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $user       = User::find($user_id);
                if ($user) {
                    $config = Configuration::first();

                    $language_available = TranslatedWord::whereIn('language_id', $user->languages)->get()->unique('language_id')->pluck('language_id')->toArray();
                    if (!empty($language_available)) {
                        $data               = TranslatedWord::raw(function($collection) use($language_available, $config) {
                            return $collection->aggregate(array(
                                array('$match'      => array(
                                    'language_id'       => array('$in' => $language_available),
                                    'translated_to'     => array('$ne' => ""),
                                    'is_deleted'        => array('$ne' => 1))),
                                array('$sort'       => array('categorized_counter' => 1)),
                                array('$limit'      => 10)
                            ));
                        })->random(1)->toArray();

                        $category       = Category::with('category_items')->get()->random(1);
                        $category_items = $category->category_items->reject(function($o) { return $o->category_name == 'other'; });

                        if ($category_items->count() > 3) { $category_items = $category_items->random(3); }
                        $category_items = array_values($category_items->toArray());

                        $result         = array(
                            'category'      => array(
                                '_id'               => $category->_id,
                                'category_group'    => $category->category_group,
                                'category_items'    => array_merge($category_items, $category->category_items->where('category_name', 'other')->toArray()),
                            ),
                            'uncategorized' => array(
                                'translated_id'     => $data['_id'],
                                'translated_to'     => $data['translated_to'],
                                'language_id'       => $data['language_id'],
                                'language_name'     => Language::find($data['language_id'])->language_name,
                            ),
                        );
                    } else { throw new \Exception("Currently no language available for you. :("); }
                } else { throw new \Exception("User with id $user_id not found"); }
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
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return Response
     */
    public function storeVote(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrive language with id $id success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $language_name      = (isset($input['language_name'])) ? $input['language_name'] : null;

        if (!isset($language_name) || $language_name == '') { $missingParams[] = "language_name"; }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if(!$isError) {
            try {

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
     * Store translated word for alternate and translate game.
     *
     * @param  Request  $request
     * @return Response
     */
    public function storeTranslated(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Store new translated word success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $user_id            = (isset($input['user_id']))        ? $input['user_id']         : null;
        $action_type        = (isset($input['action_type']))    ? $input['action_type']     : null;
        $language_id        = (isset($input['language_id']))    ? $input['language_id']     : null;
        $raw_translations   = (isset($input['translations']))   ? $input['translations']    : null;

        if (!isset($user_id) || $user_id == '') { $missingParams[] = "user_id"; }
        if (!isset($action_type) || $action_type == '') { $missingParams[] = "action_type"; }
        if (!isset($language_id) || $language_id == '') { $missingParams[] = "language_id"; }
        if (!isset($raw_translations) || $raw_translations == '') { $missingParams[] = "translations"; }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if(!$isError) {
            try {
                $user   = User::find($user_id);
                if ($user) {
                    $translations   = json_decode($raw_translations);
                    if (is_array($translations)) {
                        $config         = Configuration::first();

                        $counter_point  = 0;
                        foreach ($translations as $translation) {
                            if (OriginWord::find($translation->origin_id)) {
                                $translatedword = TranslatedWord::create(array(
                                    'user_id'               => $user_id,
                                    'language_id'           => $language_id,
                                    'translated_to'         => $translation->translated_to,
                                    'origin_word_id'        => $translation->origin_id,
                                    'counter_voteup'        => 0,
                                    'counter_votedown'      => 0,
                                    'alternate_source'      => isset($translation->translated_id) ? $translation->translated_id : '',
                                    'categorized_counter'   => 0,
                                ));

                                if ($translation->translated_to !== "") {
                                    if ($action_type == 'translate') {
                                        $user->increment('point', $config->translate_point);
                                        $counter_point  += $config->translate_point;
                                    } else if ($action_type == 'alternate') {
                                        $user->increment('point', $config->alternate_point);
                                        $counter_point  += $config->alternate_point;
                                    }

                                    OriginWord::where('_id', $translation->origin_id)->increment('translated_counter');
                                    Speaking::where('user_id', $user_id)->where('language_id', $language_id)->increment('translated_counter');
                                }

                                Log::create(array(
                                    'result'        => $user->point,
                                    'user_id'       => $user_id,
                                    'action_type'   => $action_type,
                                    'translated_id' => $translatedword->_id,
                                ));
                            }
                        }

                        $result = $counter_point;
                    } else { throw new \Exception("Error parsing translation."); }
                } else { throw new \Exception("User with id $user_id not found."); }
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
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return Response
     */
    public function storeCategorize(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrive language with id $id success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $language_name      = (isset($input['language_name'])) ? $input['language_name'] : null;

        if (!isset($language_name) || $language_name == '') { $missingParams[] = "language_name"; }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if(!$isError) {
            try {

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
}
