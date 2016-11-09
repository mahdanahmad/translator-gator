<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Category;
use App\Models\CategoryItem;

class CategoryController extends Controller {
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
        $message            = "Retrieve all category success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $limit              = (isset($input['limit']))     ? $input['limit']    : null;
        $offset             = (isset($input['offset']))    ? $input['offset']   : null;

        if (!$isError) {
            try {
                $result     = Category::with('category_items')->take($limit)->skip($offset)->get();

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
        $message            = "Store new category success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $category_group     = (isset($input['category_group'])) ? $input['category_group'] : null;

        if (!isset($category_group) || $category_group == '') { $missingParams[] = "category_group"; }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if (!$isError) {
            try {
                $category   = Category::create(array(
                    'category_group' => $category_group
                ));

                CategoryItem::create(array(
                    'description'   => null,
                    'category_id'   => $category->_id,
                    'category_name' => 'other',
                ));

                $result     = array('_id' => $category->_id);
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
    public function show($id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrive category with id $id success.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $result     = Category::with('category_items')->find($id);
                if (!$result) { throw new \Exception("Category with id $id not found."); }
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
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Update category with id $id success.";
        $isError            = FALSE;
        $editedParams       = null;

        $input              = $request->all();
        $category_group     = (isset($input['category_group'])) ? $input['category_group'] : null;

        if (!$isError) {
            try {
                $category   = Category::find($id);
                if ($category) {
                    if (isset($category_group) && $category_group !== '') { $editedParams[] = "category_group"; $category->category_group = $category_group; }

                    if (isset($editedParams)) {
                        $category->save();

                        $message    = $message." Changed data : {".implode(', ', $editedParams)."}";
                    } else {
                        $message    = $message." No data changed.";
                    }
                } else {
                    throw new \Exception("Category with id $id not found.");
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Delete category with id $id success.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $category   = Category::find($id);
                if ($category) {
                    CategoryItem::where('category_id', $id)->each(function($o) { $o->delete(); });
                    $category->delete();
                } else {
                    throw new \Exception("Category with id $id not found.");
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
}
