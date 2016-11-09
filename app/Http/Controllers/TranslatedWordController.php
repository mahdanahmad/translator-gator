<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Carbon\Carbon;

use App\Models\Log;
use App\Models\Language;
use App\Models\Speaking;
use App\Models\OriginWord;
use App\Models\CategoryItem;
use App\Models\TranslatedWord;
use App\Models\CategorizedWord;

class TranslatedWordController extends Controller {
    /**
     * Display a statistic from translated_word collection.
     *
     * @return Response
     */
    public function statistic() {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Create statistic from translated words success.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $line   = array(
                    'lineseries'    => array('translate','alternate','vote','categorize'),
                    'linelabels'    => null,
                    'linedata'      => array(null, null, null, null),
                );

                $action_types   = array('translate', 'alternate', 'vote_up', 'vote_down', 'categorize');
                for ($i = 6; $i >= 0; $i--) {
                    $start  = Carbon::now()->subDays($i)->startOfDay();
                    $end    = Carbon::now()->subDays($i)->endOfDay();

                    $logs   = Log::whereIn('action_type', $action_types)->where('created_at', '>' , $start)->where('created_at', '<=' , $end)->get();

                    $line['linelabels'][]   = $start->format('d / m');
                    $line['linedata'][0][]  = $logs->where('action_type', 'translate')->count();
                    $line['linedata'][1][]  = $logs->where('action_type', 'alternate')->count();
                    $line['linedata'][2][]  = $logs->filter(function($o) { return $o->action_type == 'vote_up' || $o->action_type == 'vote_down'; })->count();
                    $line['linedata'][3][]  = $logs->where('action_type', 'categorize')->count();
                }

                $radar  = array(
                    'radarlabels'   => null,
                    'radarseries'   => array("User","Translated Word"),
                    'radardata'     => array(null, null),
                );

                foreach (Language::All() as $language) {
                    $radar['radarlabels'][]     = $language->language_name;
                    $radar['radardata'][0][]    = Speaking::where('language_id', $language->_id)->count();
                    $radar['radardata'][1][]    = TranslatedWord::where('language_id', $language->_id)->where('alternate_source', '')->get()->groupBy('origin_word_id')->count();
                };

                $logs   = Log::raw(function($collection) use ($action_types) {
                    return $collection->aggregate(array(
                        array('$match'  => array('action_type' => array('$in' => $action_types))),
                        array('$group'  => array(
                            '_id'           => '$action_type',
                            'total'         => array('$sum' => 1)))
                    ));
                })->pluck('total', '_id');

                $stats  = array(
                    'translated'    => (isset($logs['translate']) ? $logs['translate'] : 0),
                    'alternated'    => (isset($logs['alternate']) ? $logs['alternate'] : 0),
                    'voted'         => (isset($logs['vote_up']) ? $logs['vote_up'] : 0) + (isset($logs['vote_down']) ? $logs['vote_down'] : 0),
                    'categorized'   => (isset($logs['categorize']) ? $logs['categorize'] : 0),
                );

                $wordcount          = OriginWord::count();
                $words  = array(
                    'translated'    => TranslatedWord::where('alternate_source', '')->groupBy('origin_word_id')->get()->count().'/'.$wordcount,
                    'alternated'    => TranslatedWord::where('alternate_source', '!=', '')->groupBy('origin_word_id')->get()->count().'/'.$wordcount,
                    'voted'         => TranslatedWord::where('counter_voteup', '>', 0)->orWhere('counter_votedown', '>', 0)->groupBy('origin_word_id')->get()->count().'/'.$wordcount,
                    'catagorized'   => TranslatedWord::whereIn('_id', CategorizedWord::groupBy('translated_word_id')->get()->pluck('translated_word_id'))->groupBy('origin_word_id')->get()->count().'/'.$wordcount
                );

                $result = array(
                    'line_chart'    => $line,
                    'radar_chart'   => $radar,
                    'stats'         => $stats,
                    'words'         => $words,
                );
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
     * Export translated_words collection.
     *
     * @param  Request  $request
     * @return Response
     */
    public function export(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Exporting translated words success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $raw_languages      = (isset($input['languages']))  ? $input['languages']   : null;
        $raw_categories     = (isset($input['categories'])) ? $input['categories']  : null;

        if (!$isError) {
            try {
                $filename   = "crowdsource_dump_".Carbon::now().".csv";
                $languages  = $raw_languages ? json_decode($raw_languages) : null;
                $categories = $raw_categories ? json_decode($raw_categories) : null;

                if ($languages) { if (!is_array($languages)) { throw new \Exception("Error parsing languages."); }}
                if ($categories) { if (!is_array($categories)) { throw new \Exception("Error parsing categories."); }}

                if (!empty($categories)) {
                    $all_categories = CategoryItem::whereIn('_id', $categories)->pluck('category_name', '_id');
                } else {
                    $all_categories = CategoryItem::All()->pluck('category_name', '_id');
                }

                $writer     = fopen($filename, "w");
                $query      = TranslatedWord::with(array('language', 'origin_word', 'categorized_word'))->orderBy('origin_word_id')->orderBy('language_id')->orderBy('translated_to');
                if (!empty($languages)) { $query = $query->whereIn('language_id', $languages); }
                $query->chunk(200, function($translatedwords) use (&$writer, $all_categories) {
                    foreach ($translatedwords as $o) {
                        $language           = $o->language->language_name;
                        $origin_word        = $o->origin_word->origin_word;
                        $translated_to      = $o->translated_to;
                        $counter_voteup     = $o->counter_voteup;
                        $counter_votedown   = $o->counter_votedown;

                        $category_counter   = array_count_values($o->categorized_word->pluck('categorized_to')->flatten()
                                                                ->filter(function($item) use ($all_categories) { return isset($all_categories[$item]); })
                                                                ->map(function($item) use ($all_categories) { return $all_categories[$item]; })
                                                                ->toArray());
                        array_walk($category_counter, function(&$val, $key) { $val = "(\"" . $key . "\"-" . $val . ")"; });
                        fputs($writer, "\"$origin_word\", \"$language\", \"$translated_to\", $counter_voteup, $counter_votedown, [".implode(',', $category_counter)."]\n");
                    }
                });
                fclose($writer);

                return response()->download($filename, $filename, array('Content-Type: text/csv'))->deleteFileAfterSend(true);
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
