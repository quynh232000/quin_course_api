<?php
namespace App\Services;
use App\Models\BankTransaction;
use Illuminate\Support\Facades\Http;
class TransactionService
{
    public function fetchTransactionHistory()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('API_SEPAY_KEY','EMYGNDWWJOSONPF7587UBMVXENPBBGXTVUUACY092VZJOFEYFYPKZK8PD26HW45A'),
            // 'Authorization' => 'Bearer EMYGNDWWJOSONPF7587UBMVXENPBBGXTVUUACY092VZJOFEYFYPKZK8PD26HW45A',
            'Accept' => 'application/json',
        ])->get('https://my.sepay.vn/userapi/transactions/list');
        if ($response->successful()) {
            $data = $response->json();
            $transactions = $data['transactions'];

            foreach ($transactions as $key => $item) {
                $checkTrans = BankTransaction::where('bank_id', $item['id'])->first();
                if (!$checkTrans) {
                    BankTransaction::create([
                        'bank_id' => $item['id'],
                        'bank_brand_name' => $item['bank_brand_name'],
                        'account_number' => $item['account_number'],
                        'transaction_date' => $item['transaction_date'],
                        'amount_out' => $item['amount_out'],
                        'amount_in' => $item['amount_in'],
                        'accumulated' => $item['accumulated'],
                        'transaction_content' => $item['transaction_content'],
                        'reference_number' => $item['reference_number']
                    ]);
                }
            }
        }
    }
}