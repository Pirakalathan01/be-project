<?php

namespace App\Http\Controllers;

use App\Contracts\Services\UserServiceInterface;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 */
class UserController extends Controller
{
    /**
     * @var UserServiceInterface
     */
    private UserServiceInterface $userService;

    /**
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return UserCollection
     */
    public function index(Request $request): UserCollection
    {
        return new UserCollection($this->userService->paginate(
            $request->get('per_page', 15),
            [
                'id',
            ]
        ));
    }

    /**
     * @param Request $request
     * @return UserCollection
     */
    public function all(Request $request): UserCollection
    {
        return new UserCollection($this->userService->all(
            [
                'id',
            ]
        ));
    }

    /**
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {

        $user = $this->userService->create($request->validated());
        return response()->json([
            'message' => 'User created successfully',
            'User' => new UserResource($user),
        ], 201);
    }

    /**
     * @param string $user
     * @return UserResource
     */
    public function show(string $user): UserResource
    {
        $user = $this->userService->find($user);
        return new UserResource($user);
    }

    /**
     * @param UpdateUserRequest $request
     * @param string $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, string $user): JsonResponse
    {
        $this->userService->update($user, $request->validated());
        return response()->json([
            'message' => 'User updated successfully',
        ], 200);
    }

    /**
     * @param DeleteUserRequest $request
     * @param string $user
     * @return JsonResponse
     */
    public function destroy(DeleteUserRequest $request, string $user): JsonResponse
    {
        $this->userService->delete($user);
        return response()->json([
            'message' => 'User deleted successfully',
        ], 200);
    }
}
