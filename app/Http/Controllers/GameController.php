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
use App\Models\CategoryItem;
use App\Models\Configuration;
use App\Models\TranslatedWord;
use App\Models\CategorizedWord;

class GameController extends Controller {
    /**
     * Retrieve words that haven't translated yet.
     *
     * @param  string  $user_id
     * @return Response
     */
    public function getUntranslated($user_id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrive untranslated word success.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $user   = User::find($user_id);
                if ($user) {
                    $config             = Configuration::first();

                    $untranslatedwords  = OriginWord::where('translated_counter', 0)->where('is_deleted', '!=', 1)->take($config->display_items_per_page * 10)->get();
                    if ($untranslatedwords->count() && $untranslatedwords->count() > $config->display_items_per_page) {
                        $data   = $untranslatedwords->random($config->display_items_per_page)->toArray();
                    } else {
                        $translatedwords    = OriginWord::raw(function($collection) use ($config) {
                            return $collection->aggregate(array(
                                array('$match'      => array(
                                    'is_deleted'        => array('$ne' => 1))),
                                    array('$sort'       => array('translated_counter' => 1)),
                                    array('$limit'      => $config->display_items_per_page * 10)
                                ));
                            });
                            if ($translatedwords->count() > $config->display_items_per_page) { $translatedwords = $translatedwords->random($config->display_items_per_page); } else { $translatedwords = $translatedwords; }

                            $data   = array_merge($untranslatedwords->toArray(), $translatedwords->toArray());
                        }
                        if ($config->display_items_per_page == 1) { $data = array($data); }

                        Log::create(array(
                            'user_id'       => $user_id,
                            'origin_id'     => array_map(function($o) { return $o['_id']; }, $data),
                            'action_type'   => 'retrieve',
                            'raw_result'    => 'retrieve for translate',
                        ));

                        $selected_language  = $user->languages[array_rand($user->languages)];
                        $result     = array(
                            'language_id'   => $selected_language,
                            'language_name' => Language::find($selected_language)->language_name,
                            'data'          => array_values($data)
                        );
                } else { throw new \Exception("User with id $user_id not found.");
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

                    $language_available = TranslatedWord::whereIn('language_id', $user->languages)->groupBy('language_id')->get()->pluck('language_id')->toArray();
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
                                array('$limit'      => $config->display_items_per_page * 10)
                            ));
                        });

                        if ($data->count() > $config->display_items_per_page) { $data = $data->random($config->display_items_per_page)->toArray(); } else { $data = $data->toArray(); }
                        if ($config->display_items_per_page == 1) { $data = array($data); }

                        $data           = array_values($data);
                        Log::create(array(
                            'user_id'       => $user_id,
                            'translated_id' => array_map(function($o) { return $o['_id']; }, $data),
                            'action_type'   => 'retrieve',
                            'raw_result'    => 'retrieve for vote',
                        ));

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

                    $language_available = TranslatedWord::whereIn('language_id', $user->languages)->groupBy('language_id')->get()->pluck('language_id')->toArray();
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
                                array('$limit'      => $config->display_items_per_page * 10)
                            ));
                        });

                        if ($data->count() > $config->display_items_per_page) { $data = $data->random($config->display_items_per_page)->toArray(); } else { $data = $data->toArray(); }
                        if ($config->display_items_per_page == 1) { $data = array($data); }

                        $data           = array_values($data);
                        Log::create(array(
                            'user_id'       => $user_id,
                            'translated_id' => array_map(function($o) { return $o['_id']; }, $data),
                            'action_type'   => 'retrieve',
                            'raw_result'    => 'retrieve for alternate',
                        ));

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

                    $language_available = TranslatedWord::whereIn('language_id', $user->languages)->groupBy('language_id')->get()->pluck('language_id')->toArray();
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
                        $category_items = array_merge($category_items, $category->category_items->where('category_name', 'other')->toArray());

                        Log::create(array(
                            'user_id'           => $user_id,
                            'translated_id'     => $data['_id'],
                            'action_type'       => 'retrieve',
                            'raw_result'        => 'retrieve for categorize',
                            'category_items'    => array_map(function($o) { return $o['_id']; }, $category_items),
                        ));

                        $result         = array(
                            'category'      => array(
                                '_id'               => $category->_id,
                                'category_group'    => $category->category_group,
                                'category_items'    => $category_items,
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
     * Store vote result from user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function storeVote(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Store new vote word success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $user_id            = (isset($input['user_id']))    ? $input['user_id'] : null;
        $raw_votes          = (isset($input['votes']))      ? $input['votes']   : null;

        if (!isset($user_id) || $user_id == '') { $missingParams[] = "user_id"; }
        if (!isset($raw_votes) || $raw_votes == '') { $missingParams[] = "votes"; }

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
                    $votes  = json_decode($raw_votes);
                    if (is_array($votes)) {
                        $config = Configuration::first();

                        $counter_point  = 0;
                        foreach ($votes as $vote) {
                            $translated_word    = TranslatedWord::find($vote->translated_id);
                            if ($translated_word) {
                                if ($vote->action == 'voteup') {
                                    $translated_word->increment('counter_voteup');
                                    $user->increment('point', $config->voter_point);

                                    $translator = User::find($translated_word->user_id);
                                    $translator->increment('point', $config->vote_up_point);

                                    Log::create(array(
                                        'result'        => $user->point,
                                        'user_id'       => $user_id,
                                        'action_type'   => 'vote_up',
                                        'translated_id' => $vote->translated_id,
                                        'affected_user' => $translated_word->user_id,
                                    ));

                                    Log::create(array(
                                        'result'        => $translator->point,
                                        'user_id'       => $translator->_id,
                                        'action_type'   => 'voted by another user',
                                        'translated_id' => $vote->translated_id,
                                        'affected_user' => $user_id,
                                    ));

                                    $counter_point += $config->voter_point;
                                    if ($translated_word->user_id == $user_id) { $counter_point += $config->vote_up_point; }
                                } else if ($vote->action == 'votedown') {
                                    $translated_word->increment('counter_votedown');
                                    $user->increment('point', $config->voter_point);

                                    $translator = User::find($translated_word->user_id);
                                    $translator->decrement('point', $config->vote_up_point);

                                    if (\time() < \strtotime($translator->last_kicked)) {
                                        // do nothing while the translator being kicked out.
                                    } else if ($translator->health > 1) {
                                        $translator->decrement('health');
                                    } else if ($translator->health == 1) {
                                        $translator->health       = $config->max_health;
                                        $translator->last_kicked  = \date(DATE_RFC2822, \strtotime('+'.$config->kick_time.' minute'));
                                    }

                                    $translator->save();

                                    Log::create(array(
                                        'result'        => $user->point,
                                        'user_id'       => $user_id,
                                        'action_type'   => 'vote_down',
                                        'translated_id' => $vote->translated_id,
                                        'affected_user' => $translator->_id,
                                    ));

                                    Log::create(array(
                                        'result'        => $translator->point,
                                        'user_id'       => $translator->_id,
                                        'action_type'   => 'voted by another user',
                                        'translated_id' => $vote->translated_id,
                                        'affected_user' => $user_id,
                                    ));

                                    $counter_point += $config->voter_point;
                                    if ($translated_word->user_id == $user_id) { $counter_point -= $config->vote_up_point; }
                                }
                            }
                        }

                        $result = $counter_point;
                    } else { throw new \Exception("Error parsing votes."); }
                } else { throw new \Exception("User with id $user_id not found."); }
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
     * Store categorized word from user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function storeCategorize(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Store new catagorized word success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $user_id            = (isset($input['user_id']))        ? $input['user_id']         : null;
        $translated_id      = (isset($input['translated_id']))  ? $input['translated_id']   : null;
        $raw_categories     = (isset($input['categories']))     ? $input['categories']      : null;

        if (!isset($user_id) || $user_id == '') { $missingParams[] = "user_id"; }
        if (!isset($translated_id) || $translated_id == '') { $missingParams[] = "translated_id"; }
        if (!isset($raw_categories) || $raw_categories == '') { $missingParams[] = "categories"; }

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
                    $categories = json_decode($raw_categories);
                    if (is_array($categories)) {
                        $translatedwords    = TranslatedWord::find($translated_id);
                        if ($translatedwords) {
                            $submitted_categories   = CategoryItem::whereIn('_id', $categories)->get()->pluck('_id')->toArray();
                            if (!empty($submitted_categories)) {
                                $config             = Configuration::first();

                                CategorizedWord::create(array(
                                    'user_id'               => $user_id,
                                    'categorized_to'        => $submitted_categories,
                                    'translated_word_id'    => $translated_id,
                                ));

                                $translatedwords->increment('categorized_counter');
                                $user->increment('point', $config->categorize_point);

                                Log::create(array(
                                    'result'        => $user->point,
                                    'user_id'       => $user->_id,
                                    'action_type'   => 'categorize',
                                    'translated_id' => $translated_id,
                                ));

                                $result = $config->categorize_point;
                            } else { $result = 0; }
                        } else { throw new \Exception("Translated word with id $translated_id not found."); }
                    } else { throw new \Exception("Error parsing translation."); }
                } else { throw new \Exception("User with id $user_id not found."); }
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
