<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        if($users->count() > 0)
        {
            return response()->json([
                'status' => 200,
                'users' => $users,
            ],200);

        }   else{
                return response()->json([
                    'status' => 200,
                    'users' => 'Sem registro de usuarios',
                ],200);

            }
    }

    public function store(Request $request){

        //if ($request->user()->isAdmin()) {
            $validator = Validator::make($request->all(),[
                'name'=> 'required|string|max:191',
                'email'=> 'required|email|max:191|unique:users',
                'password'=> 'required|string|max:191',
                'cpf' => 'required|digits:11|unique:users',
            ]);
    
            if($validator->fails()){
    
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ],422);
            }else{
                $users = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password'=> $request->password,
                    'cpf' => $request->cpf,
                    'timestamp' => $request->timestamp,
                ]);
    
                if($users){
                    return response()->json([
                        'status'=> 201,
                        'message' => "Usuario cadastrado com sucesso"
                    ],201);
                }else{
                    return response()->json([
                        'status'=> 500,
                        'message' => "Erro no cadastro de usuario"
                    ],500);
                }
            }
        //} else {
        //    return response()->json(['message' => 'Unauthorized'], 403);
        //}
       
    }

    public function show($id){
        $user = User::find($id);
        if($user){
        return response()->json([
            'status'=> 200,
            'message' => $user
        ],200);
        }
        else{
            return response()->json([
                'status'=> 200,
                'message' => "Usuario nao encontrado"
            ],404);
        }
    }

    public function edit($id){
        $user = User::find($id);
        if($user){
        return response()->json([
            'status'=> 200,
            'message' => $user
        ],200);
        }
        else{
            return response()->json([
                'status'=> 200,
                'message' => "Usuario nao encontrado"
            ],200);
        }
    }

    public function update(Request $request, int $id){
        $validator = Validator::make($request->all(),[
            'name'=> 'required|string|max:191',
            'email'=> 'required|email|max:191|unique:users',
            'password'=> 'required|string|max:191',
            'cpf' => 'required|digits:11|unique:users',
        ]);

        if($validator->fails()){

            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ],422);
        }else{

            $users = User::find($id);


            if($users){
                $users->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password'=> $request->password,
                    'cpf' => $request->cpf,
                ]);

                return response()->json([
                    'status'=> 204,
                    'message' => "Usuario atualizado com sucesso"
                ],204);
            }else{
                return response()->json([
                    'status'=> 404,
                    'message' => "Erro ao atualizar usuario"
                ],404);
            }
        }
    }

        /**
     * @OA\Delete(
     *     path="/api/users/{id}/delete",
     *     summary="Apagar usuário",
     *     description="Apagar um usuário pelo seu ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário a ser apagado",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function destroy($id) {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json([
                'status' => 200,
                'message' => "Usuário deletado com sucesso"
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Usuário não encontrado"
            ], 404);
        }
    }
}
