<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Category;
use App\CategoryItem;

class CategoryController extends Controller
{
    public function getAllCategory(){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Get All Category Success.";
        $isError            = FALSE;
        $editedParams       = null;
        
        
        if(!$isError) {
            try {                
//                $result = Category::with('category_items')->get();
                $result     = array();
                
                foreach(Category::with('category_items')->get() as $value) {
                    $result[$value->_id]    = array(
                        'category_group'    => $value->category_group,
                        'data'              => $value->category_items
                    );
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

    public function getCategoryItem($id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Get Category Item for id = ".$id." Success.";
        $isError            = FALSE;
        $editedParams       = null;
        

        if(!$isError) {
            try {          
                $result = Category::where('_id','=',$id)->with('category_items')->first();
                if(is_null($result)){
                    throw new \Exception("Category with id = ".$id." not found", 1);
                }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }else{
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

    public function createCategoryGroup(Request $request){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Creating Category group Success with id =.";
        $isError            = FALSE;
        $editedParams       = null;
        
        $validator = \Validator::make($request->all(),[
            'category_group_name'=>'required'
        ]);

        if(!$validator->fails()) {
            try {
                $input  = $request->all();
                $result = Category::create(array(
                    'category_group'=>$input['category_group_name'],
                ));

                CategoryItem::create(array(
                    'category_name'   =>'other',
                    'description'     =>null,
                    'icon_selected'   =>null,
                    'icon_unselected' =>null,
                    'category_id'     =>$result->_id,
                ));

                $message = $message.' '.$result->_id;
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }else{
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

    public function createCategoryItem(Request $request){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "";
        $isError            = FALSE;
        $editedParams       = null;
        
        $validator = \Validator::make($request->all(),[
            'category_group_id' =>'required|exists:categories,_id',
            'category_name'     =>'required',
        ]);

        if(!$validator->fails()) {
            try {
                $input          = $request->all();
                $category_group = Category::find($input['category_group_id']);

                $result = CategoryItem::create(array(
                    'category_name'   =>$input['category_name'],
                    'description'     =>null,
                    'icon_selected'   =>null,
                    'icon_unselected' =>null,
                    'category_id'     =>$category_group->_id,
                ));

                $message = "Creating CategoryItem under group id = ".$input['category_group_id']." Success with id =. ".$result->_id;
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }else{
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

    public function updateCategoryGroup(Request $request,$id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Editing Category Item for id = ".$id." Success.";
        $isError            = FALSE;
        $editedParams       = null;
        
        $validator = \Validator::make($request->all(),[
            'category_group_name'=>'required',
        ]);

        if(!$validator->fails()) {
            try {          
                $input    = $request->all();
                $category = Category::find($id);
                if(is_null($category)){
                    throw new \Exception("Category with id = ".$id." not found", 1);
                }else{
                    $category->category_group = $input['category_group_name'];
                    $result                   = $category->save();
                }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }else{
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

    public function updateCategoryItem(Request $request,$id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Edit category items account ($id) success.";
        $isError            = FALSE;
        $editedParams       = null;
        
        $validator = \Validator::make($request->all(),[
            // 'icon_selected'     => 'image',
            // 'icon_unselected'   => 'image',
        ]);
        
        if(!$validator->fails()) {
            try {                
                $input           = $request->all();                
                $category_name   = (isset($input['category_name']))   ? $input['category_name']           : null;
                $description     = (isset($input['description']))      ? $input['description']            : null;
                $icon_selected   = ($request->hasFile('icon_selected')) ? $request->file('icon_selected') : null;
                $icon_unselected = ($request->hasFile('icon_unselected')) ? $request->file('icon_unselected') : null;


                $category_items = CategoryItem::find($id);

                if(is_null($category_items)){
                    throw new \Exception("Category Item with id = ".$id." not found", 1);
                }else{

                    if(isset($category_name)){
                        $editedParams[] = "category_name";
                        $category_items->category_name = $category_name;
                    }
                    if(isset($description)){
                        $editedParams[] = "description";
                        $category_items->description    = $description;
                    }


                    // Lakukan upload disini..
                    if(isset($icon_selected)){
                        //delete file lama

                        $editedParams[]     = "icon_selected";
                        $destinationPath    = 'resources/img/'.$category_items->category_id;
                        $extension          = $icon_selected->getClientOriginalExtension();
                        $filename           = $category_items->_id."_selected".".".$extension;
                        
                        if(!File::exists($destinationPath)) {
                            File::makeDirectory($destinationPath);
                        }
                        
                        
                        File::delete($destinationPath."/".$category_items->icon_selected);
                        // lakukan upload
                        $icon_selected->move($destinationPath, $filename);
                        // simpan informasinya
                        $category_items->icon_selected = $filename;
                        // \Image::make($icon_selected)->encode('jpg',80)->save($destinationPath."/".$filename);
                    }

                    if(isset($icon_unselected)){
                        // delete file lama

                        $editedParams[]  = "icon_unselected";
                        $destinationPath = 'resources/img/'.$category_items->category_id;
                        $extension          = $icon_unselected->getClientOriginalExtension();
                        $filename           = $category_items->_id."_unselected".".".$extension;
                        
                        if(!File::exists($destinationPath)) {
                            File::makeDirectory($destinationPath);
                        }
                        
                        File::delete($destinationPath."/".$category_items->icon_unselected);
                        // lakukan upload
                        $icon_unselected->move($destinationPath, $filename);
                        // simpan informasinya
                        $category_items->icon_unselected = $filename;
                        // \Image::make($icon_unselected)->encode('jpg',80)->save($destinationPath."/".$filename);
                    }

                    
                    if(isset($editedParams)){
                        $category_items->save();
                        $message    = $message." Edited parameters : {".implode(', ', $editedParams)."}";
                    } else {
                        $message    = $message." Nothing changed.";
                    }
                    $result = $category_items;
                }                
            } catch (Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }else{
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

public function deleteCategoryGroup($id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Deleting Category Item for id = ".$id." Success.";
        $isError            = FALSE;
        $editedParams       = null;
        

        if(!$isError) {
            try {          
                $category_items = Category::find($id)->category_items;                

                // Hapus setiap sub-kategori
                foreach ($category_items as $c) {
                    // Hapus dulu file dari disk 
                    $category_group_id   = $c->category_id;

                    // hapus juga file dari disk
                    $destinationPath     = 'resources/img/'.$category_group_id."/";
                    $filename_selected   = $id."_selected.jpg";
                    $filename_unselected = $id."_unselected.jpg";
                    // $tobedeleted[0]      = $destinationPath.$filename_selected;
                    // $tobedeleted[1]      = $destinationPath.$filename_unselected;

                    // $deletefile          = File::delete($tobedeleted);
                    // if(!$deletefile){
                    //     throw new \Exception("Failed to delete files", 1);
                    // }

                    // $delete = CategoryItem::destroy($c->_id);
                    // if(!$delete){
                    //     throw new \Exception("failed to delete from database", 1);
                    // }
                }

                // hapus folder dulu sebelum hapus data dari database
                // File::deleteDirectory($destinationPath);

                $delete = Category::destroy($id);
                
                if ($delete == 0) {
                    throw new \Exception("Category with id= ".$id." not found.");
                }

            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }else{
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

    public function deleteCategoryItem(Request $request,$id){
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Deleting Category Item for id = ".$id." Success.";
        $isError            = FALSE;
        $editedParams       = null;
        

        if(!$isError) {
            try {          
                $category = CategoryItem::find($id);

                if (is_null($category)) {
                    throw new \Exception("Category Item with id= ".$id." not found.");
                }else{
                    // hapus dari database
                    $category_group_id   = $category->category_id;

                    // hapus juga file dari disk
                    $destinationPath     = 'resources/img/'.$category_group_id."/";
                    $filename_selected   = $id."_selected.jpg";
                    $filename_unselected = $id."_unselected.jpg";
                    $tobedeleted[0]      = $destinationPath.$filename_selected;
                    $tobedeleted[1]      = $destinationPath.$filename_unselected;

                    $deletefile          = File::delete($tobedeleted);

                    $delete              = CategoryItem::destroy($id);
                    if(!$delete){
                        throw new \Exception("failed to delete from database", 1);
                    }
                }

            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage();
            }
        }else{
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
