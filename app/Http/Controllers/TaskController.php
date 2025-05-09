<?php

namespace App\Http\Controllers;

use App\Contracts\Services\TaskServiceInterface;
use App\Http\Requests\Task\DeleteTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\User\UserResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 */
class TaskController extends Controller
{
    /**
     * @var TaskServiceInterface
     */
    private TaskServiceInterface $taskService;

    /**
     * @param TaskServiceInterface $taskService
     */
    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @param Request $request
     * @return TaskCollection
     */
    public function index(Request $request): TaskCollection
    {
        return new TaskCollection($this->taskService->paginate(
            $request->get('per_page', 15),
            [
                'id',
                'title',
                'description',
                'status'
            ]
        ));
    }

    /**
     * @param StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->validated());
        return response()->json([
            'message' => 'Task created successfully',
            'Task' => new TaskResource($task),
        ], 201);
    }

    /**
     * @param string $task
     * @return TaskResource
     */
    public function show(string $task): TaskResource
    {
        $task = $this->taskService->find($task);
        return new TaskResource($task);
    }

    /**
     * @param UpdateTaskRequest $request
     * @param string $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, string $task): JsonResponse
    {
        $this->taskService->update($task, $request->validated());
        return response()->json([
            'message' => 'Task updated successfully',
        ], 200);
    }

    /**
     * @param UpdateTaskStatusRequest $request
     * @param string $task
     * @return JsonResponse
     */
    public function updateStatus(UpdateTaskStatusRequest $request, string $task): JsonResponse
    {
        $this->taskService->update($task, $request->validated());
        return response()->json([
            'message' => 'Task status updated successfully',
        ], 200);
    }

    /**
     * @param DeleteTaskRequest $request
     * @param string $task
     * @return JsonResponse
     */
    public function destroy(DeleteTaskRequest $request, string $task): JsonResponse
    {
        $this->taskService->delete($task);
        return response()->json([
            'message' => 'Task deleted successfully',
        ], 200);
    }
}
