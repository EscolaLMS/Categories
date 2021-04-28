<?php

namespace EscolaLms\Categories\Http\Controllers;

use EscolaLms\Categories\Dtos\CategoryCreateDto;
use EscolaLms\Categories\Http\Requests\CategoryCreateRequest;
use EscolaLms\Categories\Http\Requests\CategoryDeleteRequest;
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
     * @param CategoryDeleteRequest $categoryDeleteRequest
     * @param Category $category
     * @return JsonResponse
     */
    public function delete(Category $category, CategoryDeleteRequest $categoryDeleteRequest): JsonResponse
    {
        $this->categoryService->delete($category->getKey());

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
     * @param CategoryCreateRequest $categoryCreateRequest
     * @return JsonResponse
     */
    public function create(CategoryCreateRequest $categoryCreateRequest): JsonResponse
    {
        $categoryDto = CategoryCreateDto::instantiateFromRequest($categoryCreateRequest);
        $success = (bool)$this->categoryService->save($categoryDto);
        return new JsonResponse(['success' => $success], $success ? 200 : 422);
    }
}
