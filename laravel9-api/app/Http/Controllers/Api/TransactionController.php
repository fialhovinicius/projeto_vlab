<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index() {

        $transactions = Transaction::all();
        if($transactions->count() > 0)
        {
            return response()->json([
                'status' => 200,
                'transactions' => $transactions,
            ],200);

        }   else{
                return response()->json([
                    'status' => 404,
                    'transactions' => 'Sem registro de transacoes',
                ],404);

            }
    }

    
    public function show($id){
        $transactions = Transaction::find($id);
        if($transactions){
        return response()->json([
            'status'=> 200,
            'message' => $transactions,
        ],200);
        }
        else{
            return response()->json([
                'status'=> 404,
                'message' => "Transacao nao encontrada"
            ],404);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'type'=> 'required|string|max:191',
            'value' => 'required|numeric|between:0,999999.99'
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
    

    public function destroy($id){
        $transactions = Category::find($id);
        if($transactions){
            $transactions->delete();
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
