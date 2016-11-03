<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Category;
use App\Models\CategoryItem;

class CategoryItemController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, $category_id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrieve all category items from category $category_id success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $limit              = (isset($input['limit']))     ? $input['limit']    : null;
        $offset             = (isset($input['offset']))    ? $input['offset']   : null;

        if (!$isError) {
            try {
                if (Category::find($category_id)) {
                    $result     = CategoryItem::where('category_id', $category_id)->take($limit)->skip($offset)->get();
                } else { throw new \Exception("Category with id $category_id not found."); }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
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
    public function store(Request $request, $category_id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Store new category item for category $category_id success.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $description        = (isset($input['description']))    ? $input['description']     : null;
        $category_name      = (isset($input['category_name']))  ? $input['category_name']   : null;

        if (!isset($category_name) || $category_name == '') { $missingParams[] = "category_name"; }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if (!$isError) {
            try {
                $categoryitem       = CategoryItem::create(array(
                    'description'   => $description,
                    'category_id'   => $category_id,
                    'category_name' => $category_name,
                ));

                $result     = array('_id' => $categoryitem->_id);
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
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
    public function show($category_id, $id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Retrive category with id $id from category $category_id success.";
        $isError            = FALSE;
        $missingParams      = null;

        if(!$isError) {
            try {
                $result     = CategoryItem::where('category_id', $category_id)->where('_id', $id)->first();
                if (!$result) { throw new \Exception("Category Item with id $id from Category $category_id not found."); }
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
    public function update(Request $request, $category_id, $id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Update category item with id $id from category $category_id success.";
        $isError            = FALSE;
        $editedParams       = null;

        $input              = $request->all();
        $description        = (isset($input['description']))    ? $input['description']     : null;
        $category_name      = (isset($input['category_name']))  ? $input['category_name']   : null;

        if (!$isError) {
            try {
                $categoryitem   = CategoryItem::find($id);
                if ($categoryitem) {
                    if (isset($description) && $description !== '') { $editedParams[] = "description"; $categoryitem->description = $description; }
                    if (isset($category_name) && $category_name !== '') { $editedParams[] = "category_name"; $categoryitem->category_name = $category_name; }

                    if (isset($editedParams)) {
                        $categoryitem->save();

                        $message    = $message." Changed data : {".implode(', ', $editedParams)."}";
                    } else {
                        $message    = $message." No data changed.";
                    }
                } else {
                    throw new \Exception("Category Item with id $id from Category $category_id not found.");
                }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
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
    public function destroy($category_id, $id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Delete category item with id $id from category $category_id success.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $categoryitem   = CategoryItem::find($id);
                if ($categoryitem) {
                    if ($categoryitem->category_name != 'other') { $categoryitem->delete(); } else { throw new \Exception("Can't delete other from category $category_id."); }
                } else {
                    throw new \Exception("Category Item with id $id from Category $category_id not found.");
                }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
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
