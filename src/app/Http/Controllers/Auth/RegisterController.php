<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Repository\TaskListRepositoryInterface;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\UseCase\InitTaskListUseCase;
use App\Domain\UseCase\InitTaskUseCase;
use App\Domain\ValueObject\TaskListId;
use App\Domain\ValueObject\UserId;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @var TaskListRepositoryInterface
     */
    private $taskListRepository;

    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * Create a new controller instance.
     *
     * @param TaskRepositoryInterface $taskRepository
     * @param TaskListRepositoryInterface $taskListRepository
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        TaskListRepositoryInterface $taskListRepository
    ) {
        $this->middleware('guest');
        $this->taskListRepository = $taskListRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $userId = new UserId($user->getAttribute('id'));

            $initTaskListUseCase = new InitTaskListUseCase($this->taskListRepository);
            $taskList = $initTaskListUseCase($userId);

            $taskListId = new TaskListId($taskList->getAttribute('id'));

            $initTasksUseCase = new InitTaskUseCase($this->taskRepository);
            $initTasksUseCase($userId, $taskListId);

            return $user;
        });
    }
}
