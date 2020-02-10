<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'task_list_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'status' => [
                'required',
                Rule::in([
                    Task::STATUS_DEFAULT,
                    Task::STATUS_COMPLETED,
                    Task::STATUS_DISABLED
                ]),
            ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'task_list_id.required' => '所属するタスクリストを選択してください。',
            'task_list_id.integer' => '所属するタスクリストIDは数字で入力してください。',
            'name.required' => 'タスク名を入力してください。',
            'name.string' => 'タスク名は文字列で入力してください。',
            'status.required' => 'タスクのステータスは指定の数字を入力してください。',
            'status.in' => 'タスクのステータスは指定の数字を入力してください。',
        ];
    }
}
