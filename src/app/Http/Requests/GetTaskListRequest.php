<?php

namespace App\Http\Requests;

use App\Domain\ValueObject\QueryParamTaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetTaskListRequest extends FormRequest
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
            'status' => [
                'nullable',
                'string',
                Rule::in(QueryParamTaskStatus::RULES),
            ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'status.string' => '状態は文字列で指定してください。',
            'status.in' => sprintf(
                '状態は%sのいずれかを入力してください。',
                implode(', ', QueryParamTaskStatus::RULES)
            ),
        ];
    }
}
