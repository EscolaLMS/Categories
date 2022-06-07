<?php

namespace EscolaLms\Categories\Http\Requests;

use EscolaLms\Categories\Enums\ConstantEnum;
use EscolaLms\Categories\Models\Category;
use EscolaLms\Files\Rules\FileOrStringRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        $category = Category::find($this->getCategoryId());

        return isset($user) ? $user->can('update', $category) : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $prefixPath = ConstantEnum::DIRECTORY . '/' . $this->getCategoryId();

        return [
            'icon' => [new FileOrStringRule(['image'], $prefixPath)],
        ];
    }

    private function getCategoryId()
    {
        return $this->route('category');
    }
}
