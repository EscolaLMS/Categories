<?php

namespace EscolaLms\Categories\Http\Requests;

use EscolaLms\Categories\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
{
    public function authorize()
    {
        $user = auth()->user();
        return isset($user) ? $user->can('create', Category::class) : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255']
        ];
    }
}
