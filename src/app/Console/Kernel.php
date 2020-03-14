<?php
declare(strict_types=1);

namespace App\Console;

use App\Domain\Repository\FcmTokenRepository;
use App\Domain\Repository\FcmTokenRepositoryInterface;
use App\Domain\Repository\TaskRepository;
use App\Domain\UseCase\Task\NoticeTaskUseCase;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Kernel constructor.
     *
     * @param Application $app
     * @param Dispatcher $events
     * @param FcmTokenRepositoryInterface $fcmTokenRepository
     */
    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);
    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule = $schedule->call(function () {
            $noticeTaskUseCase = new NoticeTaskUseCase(
                new TaskRepository(),
                new FcmTokenRepository()
            );
            $noticeTaskUseCase();
        });

        if (config('app.env') === 'production') {
            $schedule->when(function () {
                $now = Carbon::now()->format('H');
                return in_array($now, [7, 11, 15, 19, 21]);
            });
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
