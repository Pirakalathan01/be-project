<?php

namespace App\Http\Requests\Task;

use App\Enums\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateTaskStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $task = $this->route('task');
        $this->merge(['id' => $task]);
        return [
            'id' => [
                'required',
                Rule::exists('tasks')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
            'status' => ['required', new Enum(Status::class)],
        ];
    }
}
