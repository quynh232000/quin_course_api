<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TransactionService;
use Log;


class FetchTransactionHistory extends Command
{
    protected $signature = 'transactions:get-history';
    protected $description = 'Command description';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        try {
            $transactionService = new TransactionService();
            $transactionService->fetchTransactionHistory();

            $this->info('Transaction history fetched successfully.');
        } catch (\Exception $e) {
            Log::error('Error fetching transaction history: ' . $e->getMessage());
            $this->error('Failed to fetch transaction history.');
        }
    }
}
