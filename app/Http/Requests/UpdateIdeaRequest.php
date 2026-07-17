<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\IdeaStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIdeaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(IdeaStatus::class)],
            'links' => ['nullable', 'array'],
            'links.*' => ['url', 'max:255'],
            'steps' => ['nullable', 'array'],
            'steps.*.description' => ['required', 'string', 'max:255'],
            'steps.*.completed' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
