<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class PostListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages()
    {
        return [
            'search.string' => 'The search parameter must be a string.',
            'search.max' => 'The search parameter may not be greater than 255 characters.',
            'page.integer' => 'The page parameter must be an integer.',
            'page.min' => 'The page parameter must be at least 1.',
            'limit.integer' => 'The limit parameter must be an integer.',
            'limit.min' => 'The limit parameter must be at least 1.',
            'limit.max' => 'The limit parameter may not be greater than 100.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

}
