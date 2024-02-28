<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
class CategoryController extends Controller
{
   public function index()
    {
        $categories = Category::all();
        if($categories->count() > 0)
        {
            return response()->json([
                'status' => 200,
                'categories' => $categories,
            ],200);

        }   else{
                return response()->json([
                    'status' => 404,
                    'categories' => 'Sem registro de categorias',
                ],404);

            }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=> 'required|string|max:191',
        ]);

        if($validator->fails()){

            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ],422);
        }else{
            $categories = Category::create([
                'name' => $request->name
            ]);

            if($categories){
                return response()->json([
                    'status'=> 200,
                    'massage' => "Categoria cadastrada com sucesso"
                ],200);
            }else{
                return response()->json([
                    'status'=> 500,
                    'massage' => "Erro no cadastro de categoria"
                ],500);
            }
        }
    }

    public function destroy($id){
        $categories = Category::find($id);
        if($categories){
            $categories->delete();
            return response()->json([
                'status'=> 200,
                'massage' => "Categoria deletada com sucesso"
            ],200);
        }else{
            return response()->json([
                'status'=> 404,
                'massage' => "Erro ao apagar categoria"
            ],404);
        }
    }
}

