<?php
declare(strict_types=1);

namespace App\Console;

use App\Domain\Collection\FcmTokenCollection;
use App\Domain\Collection\UserIdCollection;
use App\Domain\Repository\FcmTokenRepository;
use App\Domain\Repository\FcmTokenRepositoryInterface;
use App\Domain\Repository\TaskRepository;
use App\Domain\UseCase\FirebaseCloudMessaging\SendFcmNotificationUseCase;
use App\Domain\ValueObject\FcmBody;
use App\Domain\ValueObject\FcmClickAction;
use App\Domain\ValueObject\FcmIcon;
use App\Domain\ValueObject\FcmNotification;
use App\Domain\ValueObject\FcmTimeToLeave;
use App\Domain\ValueObject\FcmTitle;
use App\Domain\ValueObject\FcmToken;
use App\Domain\ValueObject\UserId;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            // FIXME: ここUseCaseにする。
            $userIds = Arr::pluck(User::all()->toArray(), 'id');
            $userIdCollection = new UserIdCollection();
            foreach ($userIds as $userId) {
                $userIdCollection->push(new UserId((int) $userId));
            }

            $taskRepository = new TaskRepository();
            $limitedTasks = $taskRepository->findByUserIdsAndLimitDateWithFcmToken($userIdCollection, Carbon::now());

            $fcmTokenRepository = new FcmTokenRepository();

            $result = DB::transaction(function () use ($limitedTasks, $fcmTokenRepository) {
                $fcmTokenCollection = new FcmTokenCollection();

                /** @var Model $limitedTask */
                foreach ($limitedTasks as $limitedTask) {
                    /** @var \App\Models\FcmToken $token */
                    foreach ($limitedTask->token as $token) {
                        try {
                            $fcmNotification = new FcmNotification(
                                new FcmToken((string) $token->getAttribute('token')),
                                new FcmTitle('期限が近いタスクがあります'),
                                new FcmBody($limitedTask->getAttribute('name')),
                                new FcmIcon('/todog-round-icon.png'),
                                new FcmTimeToLeave(60),
                                new FcmClickAction(route('root'))
                            );
                            $sendFcmNotificationUseCase = new SendFcmNotificationUseCase($fcmNotification);
                            $sendFcmNotificationUseCase();
                        } catch (\Exception $e) {
                            Log::info($e->getMessage());
                            $fcmToken = new FcmToken((string) $token->getAttribute('token'));
                            if (!$fcmTokenCollection->existItem($fcmToken)) {
                                $fcmTokenCollection->push($fcmToken);
                            }
                            continue;
                        }
                    }
                }

                /** @var FcmToken $fcmToken */
                foreach ($fcmTokenCollection as $fcmToken) {
                    $fcmTokenRepository->delete($fcmToken);
                }

                return true;
            });
        });
        // if (config('app.env') === 'production') {
        //     $schedule
        //         ->hourly();
        // }
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
