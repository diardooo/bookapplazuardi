<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\Book;

use Illuminate\http\Request;

use Illuminate\Support\Facades\DB;


class Controller extends BaseController
{
    public function index()
    {
        return Book::all();
    }
    public function getid(Request $request,$id){
        $result = DB::select("SELECT * FROM books WHERE id = $id");
        if(empty($result)){
            return response()->json(['message'=> 'Books Not Found'], 404);
        }
        else{
        return $result;
        }
    }

    public function store(Request $request){
    $this->validate($request, [
        'title' => 'required',
        'description' => 'required',
        'author' => 'required'
    ]);

    $Books = Book::create(
      $request->only(['title', 'description','author'])
    );

    return response()->json([
      'created' => true,
      'data' => $Books
    ], 201);
  }

  public function update(Request $request, $id)
  {
    try {
      $Books = Book::findOrFail($id);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Books not found'
      ], 404);
    }

    $Books->fill(
      $request->only(['title', 'description', 'author'])
    );
    $Books->save();

    return response()->json([
      'updated' => true,
      'data' => $Books
    ], 200);
  }
  
  public function destroy($id)
  {
    try {
      $Books = Book::findOrFail($id);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'error' => [
          'message' => 'Books not found'
        ]
      ], 404);
    }

    $Books->delete();

    return response()->json([
      'deleted' => true
    ], 200);
  }
}
