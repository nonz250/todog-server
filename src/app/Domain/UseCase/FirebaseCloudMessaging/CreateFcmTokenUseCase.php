<?php
declare(strict_types=1);

namespace App\Domain\UseCase\FirebaseCloudMessaging;


use App\Domain\Repository\FcmTokenRepositoryInterface;
use App\Domain\ValueObject\FcmToken;
use App\Domain\ValueObject\UserId;
use App\Http\Requests\CreateFcmTokenRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class CreateFcmTokenUseCase
{
    /**
     * @var FcmTokenRepositoryInterface
     */
    private $fcmTokenRepository;

    /**
     * CreateFcmTokenUseCase constructor.
     *
     * @param FcmTokenRepositoryInterface $fcmTokenRepository
     */
    public function __construct(FcmTokenRepositoryInterface $fcmTokenRepository)
    {
        $this->fcmTokenRepository = $fcmTokenRepository;
    }

    /**
     * @param CreateFcmTokenRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(CreateFcmTokenRequest $request)
    {
        $userId = new UserId((int) Auth::id());
        $fcmToken = new FcmToken((string) $request->get('token'));
        $result = DB::transaction(function () use ($userId, $fcmToken) {
            return $this->fcmTokenRepository->create($userId, $fcmToken);
        });
        return response()->json([
            $result
        ]);
    }
}
