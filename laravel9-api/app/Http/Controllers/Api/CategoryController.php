<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class CategoryController extends Controller
{
   public function index($user_id)
    {
        $categories = Category::where('user_id',$user_id)->get();
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

    public function store(Request $request,$user_id){
        $validator = Validator::make($request->all(),[
            'name'=> 'required|string|max:191',
        ]);

        if($validator->fails()){

            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ],422);
        }else{
            $category = Category::where('name', $request->name)
                            ->where('user_id', $user_id)
                            ->first();
            if(!$category){
                $categories = Category::create([
                    'name' => $request->name,
                    'user_id' => $user_id,
                ]);
    
                if($categories){
                    return response()->json([
                        'status'=> 200,
                        'message' => "Categoria cadastrada com sucesso"
                    ],200);
                }else{
                    return response()->json([
                        'status'=> 500,
                        'message' => "Erro no cadastro de categoria"
                    ],500);
                }
            }
            else{
                    return response()->json([
                        'status'=> 409,
                        'message' => "Categoria ja cadastrada"
                    ],409);
            }
        }
    }

    public function destroy($user_id,$category_id){
        $category = Category::where('id', $category_id)
                            ->where('user_id', $user_id)
                            ->first();
        if($category){
            $category->delete();
            return response()->json([
                'status'=> 200,
                'message' => "Categoria deletada com sucesso"
            ],200);
        }else{
            return response()->json([
                'status'=> 404,
                'message' => "Erro ao apagar categoria"
            ],404);
        }
    }
}

