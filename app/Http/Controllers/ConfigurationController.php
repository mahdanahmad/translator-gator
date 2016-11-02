<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Configuration;

class ConfigurationController extends Controller{


    public function getConfig(){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success updating configurations.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $result = \App\Configuration::first();
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
    public function update(Request $request){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Success updating configurations.";
        $isError            = FALSE;
        $missingParams      = null;

        $input                      = $request->all();
        $display_items_per_page     = (isset($input['display_items_per_page'])      ? $input['display_items_per_page']      : null);
        $display_options_per_page   = (isset($input['display_options_per_page'])    ? $input['display_options_per_page']    : null);
        $alternate_point            = (isset($input['alternate_point'])             ? $input['alternate_point']             : null);
        $translate_point            = (isset($input['translate_point'])             ? $input['translate_point']             : null);
        $categorize_point           = (isset($input['categorize_point'])            ? $input['categorize_point']            : null);
        $vote_down_point            = (isset($input['vote_down_point'])             ? $input['vote_down_point']             : null);
        $vote_up_point              = (isset($input['vote_up_point'])               ? $input['vote_up_point']               : null);
        $voter_point                = (isset($input['voter_point'])                 ? $input['voter_point']                 : null);
        $referral_point             = (isset($input['referral_point'])              ? $input['referral_point']              : null);
        $kick_time                  = (isset($input['kick_time'])                   ? $input['kick_time']                   : null);
        $max_health                 = (isset($input['max_health'])                  ? $input['max_health']                  : null);
        $is_on_translate            = (isset($input['is_on_translate'])             ? $input['is_on_translate']             : null);
        $is_on_alternative          = (isset($input['is_on_alternative'])           ? $input['is_on_alternative']           : null);
        $is_on_vote                 = (isset($input['is_on_vote'])                  ? $input['is_on_vote']                  : null);
        $is_on_categorize           = (isset($input['is_on_categorize'])            ? $input['is_on_categorize']            : null);
        $point_value                = (isset($input['point_value'])                 ? $input['point_value']                 : null);
        $redeem_time                = (isset($input['redeem_time'])                 ? $input['redeem_time']                 : null);

        if(!$isError) {
            try {
                $configuration = \App\Configuration::first();
                $editedParams  = null;

                if(isset($display_items_per_page)){
                    $editedParams[] = "display_items_per_page";
                    $configuration->display_items_per_page      = (int) $display_items_per_page + 0;
                }
                if(isset($display_options_per_page)){
                    $editedParams[] = "display_options_per_page";
                    $configuration->display_options_per_page    = (int) $display_options_per_page + 0;
                }
                if(isset($alternate_point)){
                    $editedParams[] = "alternate_point";
                    $configuration->alternate_point             = (int) $alternate_point + 0;
                }
                if(isset($translate_point)){
                    $editedParams[] = "translate_point";
                    $configuration->translate_point             = (int) $translate_point + 0;
                }
                if(isset($categorize_point)){
                    $editedParams[] = "categorize_point";
                    $configuration->categorize_point            = (int) $categorize_point + 0;
                }
                if(isset($vote_down_point)){
                    $editedParams[] = "vote_down_point";
                    $configuration->vote_down_point             = (int) $vote_down_point + 0;
                }
                if(isset($vote_up_point)){
                    $editedParams[] = "vote_up_point";
                    $configuration->vote_up_point               = (int) $vote_up_point + 0;
                }
                if(isset($voter_point)){
                    $editedParams[] = "voter_point";
                    $configuration->voter_point                 = (int) $voter_point + 0;
                }
                if(isset($referral_point)){
                    $editedParams[] = "referral_point";
                    $configuration->referral_point              = (int) $referral_point + 0;
                }
                if(isset($kick_time)){
                    $editedParams[] = "kick_time";
                    $configuration->kick_time                   = (int) $kick_time + 0;
                }
                if(isset($max_health)){
                    $editedParams[] = "max_health";
                    $configuration->max_health                  = (int) $max_health + 0;
                }
                if(isset($is_on_translate)){
                    $editedParams[] = "is_on_translate";
                    $configuration->is_on_translate             = $is_on_translate;
                }
                if(isset($is_on_alternative)){
                    $editedParams[] = "is_on_alternative";
                    $configuration->is_on_alternative           = $is_on_alternative;
                }
                if(isset($is_on_vote)){
                    $editedParams[] = "is_on_vote";
                    $configuration->is_on_vote                  = $is_on_vote;
                }
                if(isset($is_on_categorize)){
                    $editedParams[] = "is_on_categorize";
                    $configuration->is_on_categorize            = $is_on_categorize;
                }
                if(isset($point_value)){
                    $editedParams[] = "point_value";
                    $configuration->point_value                 = $point_value;
                }
                if(isset($redeem_time)){
                    $editedParams[] = "redeem_time";
                    $configuration->redeem_time                 = $redeem_time;
                }

                if(isset($editedParams)){
                    $result = $configuration->save();
                    $message    = $message." Edited parameters : {".implode(', ', $editedParams)."}";
                } else {
                    $message    = $message." Nothing changed.";
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

    public function getAction() {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Get action configurations success.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $config = \App\Configuration::first();

                if ($config->is_on_translate) {
                    $result[]   = 'translate';
                }
                if ($config->is_on_alternative) {
                    $result[]   = 'alternative';
                }
                if ($config->is_on_vote) {
                    $result[]   = 'vote';
                }
                if ($config->is_on_categorize) {
                    $result[]   = 'categorize';
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

    public function getRedeemTime() {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Get redeem_time configurations success.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $config = \App\Configuration::first();

                $result = array(
                    'redeem_time'   => $config->redeem_time,
                    'redeem_value'  => $config->point_value,
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

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }
}
