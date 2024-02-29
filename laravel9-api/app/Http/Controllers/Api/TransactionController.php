<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/transactions/{user_id}",
     *     summary="Listar transações de um usuário",
     *     description="Listar todas as transações feitas por um usuário.",
     *     tags={"Transactions"},
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
     *         description="Lista de transações do usuário"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhuma transação encontrada para o usuário"
     *     )
     * )
     */
    public function index($user_id) {
        $transactions = Transaction::where('user_id', $user_id)->get();
        if ($transactions->count() > 0) {
            return response()->json([
                'status' => 200,
                'transactions' => $transactions,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Nenhuma transação encontrada para o usuário',
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/transactions/{user_id}",
     *     summary="Criar transação",
     *     description="Criar uma nova transação para o usuário.",
     *     tags={"Transactions"},
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
     *                     property="type",
     *                     type="string",
     *                     example="Despesa"
     *                 ),
     *                 @OA\Property(
     *                     property="value",
     *                     type="number",
     *                     format="float",
     *                     example=100.50
     *                 ),
     *                 @OA\Property(
     *                     property="category_id",
     *                     type="integer",
     *                     example=1
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transação criada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação dos dados"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
    public function store(Request $request, $user_id) {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:191',
            'value' => 'required|numeric|between:0,999999.99',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ], 422);
        } else {
            $transaction = Transaction::create([
                'type' => $request->type,
                'value' => $request->value,
                'category_id' => $request->category_id,
                'user_id' => $user_id,
            ]);

            if ($transaction) {
                return response()->json([
                    'status' => 200,
                    'message' => "Transação criada com sucesso",
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Erro na criação da transação",
                ], 500);
            }
        }
    }

    /**
 * @OA\Get(
 *     path="/api/transactions",
 *     summary="Listar todas as transações de todos os usuários",
 *     description="Listar todas as transações feitas por todos os usuários.",
 *     tags={"Transactions"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de todas as transações de todos os usuários"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Nenhuma transação encontrada"
 *     )
 * )
 */
public function list(Request $request) {
    $query = Transaction::query();

    // Verifica se o parâmetro category_id foi fornecido e filtra as transações pelo ID da categoria
    if ($request->category_id != "") {
        $query->where('category_id', $request->category_id);
    }

    // Verifica se o parâmetro type foi fornecido e filtra as transações pelo tipo de transação
    if ($request->type != "") {
        $query->where('type', $request->type);
    }

    $transactions = $query->get();

    if ($transactions->count() > 0) {
        return response()->json([
            'status' => 200,
            'transactions' => $transactions,
        ], 200);
    } else {
        return response()->json([
            'status' => 404,
            'message' => 'Nenhuma transação encontrada',
        ], 404);
    }
}
    /**
     * @OA\Delete(
     *     path="/api/transactions/{user_id}/delete/{transaction_id}",
     *     summary="Apagar transação",
     *     description="Apagar uma transação do usuário.",
     *     tags={"Transactions"},
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
     *         name="transaction_id",
     *         in="path",
     *         description="ID da transação",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transação apagada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transação não encontrada"
     *     )
     * )
     */
    public function destroy($user_id, $transaction_id) {
        $transaction = Transaction::where('id', $transaction_id)
                            ->where('user_id', $user_id)
                            ->first();
        if ($transaction) {
            $transaction->delete();
            return response()->json([
                'status' => 200,
                'message' => "Transação deletada com sucesso",
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Transação não encontrada",
            ], 404);
        }
    }
}
