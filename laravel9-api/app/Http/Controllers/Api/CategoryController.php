<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories/{user_id}",
     *     summary="Listar categorias",
     *     description="Listar todas as categorias de um usuário",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorias do usuário"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhuma categoria encontrada para o usuário"
     *     )
     * )
     */
    public function index($user_id)
    {
        $categories = Category::where('user_id', $user_id)->get();
        if ($categories->count() > 0) {
            return response()->json([
                'status' => 200,
                'categories' => $categories,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'categories' => 'Sem registro de categorias',
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/categories/{user_id}",
     *     summary="Criar categoria",
     *     description="Criar uma nova categoria para o usuário",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Nova Categoria"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoria cadastrada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação dos dados"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Categoria já cadastrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
    public function store(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        } else {
            $category = Category::where('name', $request->name)
                ->where('user_id', $user_id)
                ->first();
            if (!$category) {
                $category = Category::create([
                    'name' => $request->name,
                    'user_id' => $user_id,
                ]);

                if ($category) {
                    return response()->json([
                        'status' => 200,
                        'message' => "Categoria cadastrada com sucesso"
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 500,
                        'message' => "Erro no cadastro de categoria"
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 409,
                    'message' => "Categoria já cadastrada"
                ], 409);
            }
        }
    }
    
    /**
     * @OA\Delete(
     *     path="/api/categories/{user_id}/delete/{category_id}",
     *     summary="Apagar categoria e suas transações associadas",
     *     description="Apaga uma categoria e todas as transações associadas a ela.",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="path",
     *         description="ID da categoria",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoria e transações associadas deletadas com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoria não encontrada"
     *     )
     * )
     */
    public function destroy($user_id, $category_id) {
        $category = Category::where('id', $category_id)
                            ->where('user_id', $user_id)
                            ->first();
        
        if ($category) {
            // Exclui todas as transações associadas à categoria
            Transaction::where('category_id', $category_id)->delete();
            
            // exclui a categoria
            $category->delete();
            
            return response()->json([
                'status' => 200,
                'message' => "Categoria e suas transações associadas foram deletadas com sucesso"
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Categoria não encontrada"
            ], 404);
        }
    }
}