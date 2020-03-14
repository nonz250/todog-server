<?php
declare(strict_types=1);

namespace App\Domain\UseCase\Auth;


use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\UserEmail;
use App\Domain\ValueObject\UserPassword;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

final class LoginUseCase
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * LoginUseCase constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(LoginRequest $request)
    {
        $userEmail = new UserEmail((string) $request->get('email'));
        $userPassword = new UserPassword((string) $request->get('password'));

        try {

            try {
                $user = $this->userRepository->findByEmail($userEmail);
            } catch (\Exception $e) {
                throw new \Exception('Emailまたはパスワードが違います。');
            }

            if (!Hash::check((string) $userPassword, (string) $user->getAttribute('password'))) {
                throw new \Exception('Emailまたはパスワードが違います。');
            }

            Auth::login($user);

            if (!Auth::check()) {
                throw new \Exception('ログインに失敗しました。');
            }

        } catch (\Exception $e) {
            // FIXME: errorでログを取得する方法を調査する。
            logger($e);
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'user' => Auth::user(),
        ]);
    }
}
