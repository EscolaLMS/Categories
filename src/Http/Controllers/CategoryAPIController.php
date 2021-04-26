<?php

namespace EscolaLms\Categories\Http\Controllers;

use EscolaLms\Categories\Dtos\CategoryCreateDto;
use EscolaLms\Categories\Http\Requests\CategoryUpdateRequest;
use EscolaLms\Categories\Http\Resources\CategoryResource;
use EscolaLms\Categories\Http\Resources\CategoryTreeResource;
use EscolaLms\Categories\Models\Category;
use EscolaLms\Categories\Http\Controllers\Swagger\CategorySwagger;
use EscolaLms\Categories\Repositories\Contracts\CategoriesRepositoryContract;
use EscolaLms\Categories\Services\Contracts\CategoryServiceContracts;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(title="Escola LMS", version="0.0.1")
 **/
class CategoryAPIController extends EscolaLmsBaseController implements CategorySwagger
{
    private CategoriesRepositoryContract $categoryRepository;
    private CategoryServiceContracts $categoryService;

    public function __construct(CategoriesRepositoryContract $categoryRepository, CategoryServiceContracts $categoryService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryService = $categoryService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $categories = $this->categoryRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return CategoryResource::collection($categories)->response();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function tree(Request $request): JsonResponse
    {
        $categories = $this->categoryRepository->allRoots(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return CategoryTreeResource::collection($categories)->response();
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        return (new CategoryResource($category))->response();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->categoryService->delete($id);

        return response()->json(null, 200);
    }

    /**
     * @param Category $category
     * @param CategoryUpdateRequest $request
     * @return JsonResponse
     */
    public function update(Category $category, CategoryUpdateRequest $request): JsonResponse
    {
        $categoryDto = new CategoryCreateDto(
            $category->getKey(),
            $request->input('name'),
            $request->file('icon'),
            $request->input('icon_class'),
            $request->boolean('is_active'),
            $request->input('parent_id')
        );
        $success = (bool)$this->categoryService->save($categoryDto);
        return new JsonResponse(['success' => $success], $success ? 200 : 422);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $categoryDto = CategoryCreateDto::instantiateFromRequest($request);
        $success = (bool)$this->categoryService->save($categoryDto);
        return new JsonResponse(['success' => $success], $success ? 200 : 422);
    }
}
