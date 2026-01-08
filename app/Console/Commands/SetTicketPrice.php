<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class SetTicketPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:set-price {price=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Установить цену всех билетов на указанную сумму (по умолчанию 10 рублей)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $price = (float) $this->argument('price');
        
        $this->info("Установка цены всех билетов на {$price} рублей...");
        
        $tickets = Ticket::all();
        $count = 0;
        
        foreach ($tickets as $ticket) {
            $ticket->update(['price' => $price]);
            $count++;
            $this->line("  - Билет '{$ticket->name}' обновлен: {$price} ₽");
        }
        
        $this->info("Готово! Обновлено билетов: {$count}");
        
        return Command::SUCCESS;
    }
}

