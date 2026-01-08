<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class UpdateTicketsInfo extends Command
{
    protected $signature = 'tickets:update-info';
    protected $description = 'Обновить информацию о билетах (названия, описания)';

    public function handle()
    {
        $this->info('Обновление информации о билетах...');

        // Обновляем обычный билет
        $regular = Ticket::where('type', 'regular')->first();
        if ($regular) {
            $regular->update([
                'name' => 'Билет на мастер-класс по подологии',
                'description' => 'Участие в профессиональном мастер-классе по подологии. Включает практические занятия, материалы и сертификат участника.',
            ]);
            $this->line("  ✓ Обновлен: {$regular->name}");
        }

        // Обновляем VIP билет
        $vip = Ticket::where('type', 'vip')->first();
        if ($vip) {
            $vip->update([
                'name' => 'VIP билет на мастер-класс по подологии',
                'description' => 'Расширенная программа мастер-класса с индивидуальными консультациями, дополнительными материалами и VIP-сертификатом. Включает кофе-брейк и общение с экспертами.',
            ]);
            $this->line("  ✓ Обновлен: {$vip->name}");
        }

        $this->info('Готово!');
        return Command::SUCCESS;
    }
}

