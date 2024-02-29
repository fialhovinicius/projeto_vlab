<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    
public function list(Request $request) {
    $query = Transaction::query();

    // Verifica se o parâmetro user_id foi fornecido e filtra as transações por ID de usuário
    if ($request->user_id != "") {
        $query->where('user_id', $request->user_id);
    }

    // Verifica se o parâmetro category_name foi fornecido e filtra as transações pelo nome da categoria
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
            'status' => 200,
            'transactions' => 'Sem registro de transacao',
        ], 200);
    }
}
    public function index($user_id) {

        $transactions = Transaction::where('user_id',$user_id)->get();
        if($transactions->count() > 0)
        {
            return response()->json([
                'status' => 200,
                'transactions' => $transactions,
            ],200);

        }   else{
                return response()->json([
                    'status' => 200,
                    'transactions' => 'Sem registro de transacoes',
                ],200);

            }
    }
    public function store(Request $request, $user_id){
        $validator = Validator::make($request->all(),[
            'type'=> 'required|string|max:191',
            'value' => 'required|numeric|between:0,999999.99',
            'category_id' => 'required|exists:categories,id',
        ]);

        if($validator->fails()){

            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ],422);
        }else{
            $transactions = Transaction::create([
                'type' => $request->type,
                'value' => $request->value,
                'category_id'=> $request->category_id,
                'user_id' => $user_id,
            ]);

            if($transactions){
                return response()->json([
                    'status'=> 200,
                    'message' => "Transacao feita com sucesso"
                ],200);
            }else{
                return response()->json([
                    'status'=> 500,
                    'message' => "Erro na transacao"
                ],500);
            }
        }
    }
    
    public function destroy($user_id,$transaction_id){
        $transaction = Transaction::where('id', $transaction_id)
                            ->where('user_id', $user_id)
                            ->first();
        if($transaction){
            $transaction->delete();
            return response()->json([
                'status'=> 200,
                'message' => "Transacao deletada com sucesso"
            ],200);
        }else{
            return response()->json([
                'status'=> 404,
                'message' => "Erro ao apagar transacao"
            ],404);
        }
    }

}
