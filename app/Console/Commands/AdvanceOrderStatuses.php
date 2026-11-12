<?php
namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class AdvanceOrderStatuses extends Command
{
    protected $signature = 'orders:advance-status';
    protected $description = 'Automatically advance order statuses based on order age';

    public function handle(): void
    {

        Order::where('status', 'New')
            ->where('created_at', '<=', now()->subMinutes(3))
            ->update(['status' => 'In production']);

        Order::where('status', 'In production')
            ->where('created_at', '<=', now()->subMinutes(10))
            ->update(['status' => 'Despatched']);
    }
}