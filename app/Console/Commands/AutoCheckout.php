<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoCheckout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $attendances = \App\Models\Attendance::whereDate('date', now())
            ->whereNull('check_out_time')
            ->get();

        foreach ($attendances as $attendance) {
            if (now()->gte(\Carbon\Carbon::parse('11:00'))) {
                $attendance->update([
                    'check_out_time' => '11:00:00'
                ]);
            }
        }
    }
}
