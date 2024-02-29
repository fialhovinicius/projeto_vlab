<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

/**
 * @OA\Post(
 *     path="/api/users",
 *     summary="Cadastrar Usuário",
 *     description="Endpoint para cadastrar um novo usuário.",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados do usuário",
 *         @OA\JsonContent(
 *             required={"name", "cpf", "email", "password"},
 *             @OA\Property(property="name", type="string", example="Vinicius Fialho", description="Nome completo do usuário"),
 *             @OA\Property(property="cpf", type="string", example="10607446439", description="CPF do usuário"),
 *             @OA\Property(property="email", type="string", format="email", example="vaf2@cin.ufpe.br", description="Endereço de e-mail do usuário"),
 *             @OA\Property(property="password", type="string", example="password", description="Senha do usuário")
 *         )
 *     ),
 *     @OA\Get(
 *     path="/api/users",
 *     summary="Listar Usuários",
 *     description="Endpoint para listar todos os usuários cadastrados.",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\MediaType(
 *             mediaType="application/json"
 *         )
 *     )
 * ),
 * @OA\Put(
 *     path="/api/users/{id}/edit",
 *     summary="Editar Usuário",
 *     description="Endpoint para editar um usuário existente.",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do usuário a ser editado",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados do usuário a serem atualizados",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     description="Nome do usuário"
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     format="email",
 *                     description="E-mail do usuário"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string",
 *                     description="Senha do usuário"
 *                 ),
 *                 @OA\Property(
 *                     property="cpf",
 *                     type="string",
 *                     description="CPF do usuário"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuário editado com sucesso",
 *         @OA\MediaType(
 *             mediaType="application/json"
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usuário não encontrado",
 *         @OA\MediaType(
 *             mediaType="application/json"
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação dos dados de entrada",
 *         @OA\MediaType(
 *             mediaType="application/json"
 *         )
 *     )
 * ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\MediaType(
 *             mediaType="application/json"
 *         )
 *     )
 * )
 */
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'cpf' => 'required|string|max:11|unique:users',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Usuário cadastrado com sucesso',
            'user' => $user
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,' . $id,
            'password' => 'required|string|max:191',
            'cpf' => 'required|digits:11|unique:users,cpf,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'Usuário não encontrado'
            ], 404);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->cpf = $request->input('cpf');
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'Usuário editado com sucesso',
            'user' => $user
        ], 200);
    }

}