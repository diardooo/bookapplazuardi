<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\Authors;

use Illuminate\http\Request;

use Illuminate\Support\Facades\DB;


class AuthorsController extends BaseController
{
    public function index()
    {
        return Authors::all();
    }

    public function getid(Request $request,$id){
        $result = DB::select("SELECT * FROM authors WHERE id = $id");
        if(empty($result)){
            return response()->json(['message'=> 'Authors Not Found'], 404);
        }
        else{
        return $result;
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'biography' => 'required'
        ]);

        $authors = Authors::create(
            $request->only(['name','gender','biography'])
        );

        return response()->json([
            'created' => true,
            'data' => $authors
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $authors = Authors::findOrFail($id);
        } catch (ModelNotFoundException $e){
            return response()->json([
                'message' => 'book not found'
            ], 404);
        }

        $authors->fill(
            $request->only(['name','gender','biography'])
        );

        $authors->save();

        return response()->json([
            'updated' => true,
            'data' => $authors
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $authors = Authors::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'book not found'
                ]
                ],404);
        }

        $authors->delete();

        return response()->json([
            'deleted' => true
        ],200);
    }

        
}