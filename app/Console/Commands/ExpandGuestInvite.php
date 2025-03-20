<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Skud\Services\TerminalConnectionService;
use Modules\Visitor\Entities\Guest;

class ExpandGuestInvite extends Command
{

    public function __construct(private TerminalConnectionService $terminalConnectionService)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guest:expand-invite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expand Guest Invite';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $guests = Guest::query()
            ->with(['invites'])
            ->whereNotNull('photo')
            ->whereIn('id', [93, 233, 344, 360, 361, 442,
                            485, 637, 638, 672, 673, 674,
                            684, 685, 795, 796, 1124,
                            1390, 1464,])
            ->get();

        $bar = $this->output->createProgressBar(count($guests));
        $bar->start();
        foreach ($guests as $key => $guest) {
            $invite = $guest->invites()->latest()->first();
            $invite->update([
                'status' => 1,
                'end' => '2024-12-31 23:59:59'
            ]);
            $responseTerminal = $this->terminalConnectionService->create($guest, $invite->terminals, $invite->id);
            $this->info(json_encode($responseTerminal));

            $bar->advance();
        }
        $bar->finish();
        $this->info('Finish!');
    }
}
