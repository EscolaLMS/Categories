<?php

namespace EscolaLms\Categories\Repositories\Contracts;

use Carbon\Carbon;
use EscolaLms\Categories\Dtos\CategoryCriteriaFilterDto;
use EscolaLms\Categories\Models\Category;
use EscolaLms\Core\Dtos\OrderDto;
use EscolaLms\Core\Dtos\PaginationDto;
use EscolaLms\Core\Repositories\Contracts\ActivationContract;
use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoriesRepositoryContract extends BaseRepositoryContract, ActivationContract
{
    public function allRoots(array $search = [], ?int $skip = null, ?int $limit = null);
    public function listAll(CategoryCriteriaFilterDto $criteriaDto, OrderDto $dto, array $columns = ['*'], ?int $perPage = 15, ?bool $isActive = null): LengthAwarePaginator;
    public function get(int $id): Category;
    public function getByPopularity(PaginationDto $pagination, ?Carbon $from = null, ?Carbon $to = null): Collection;
}
