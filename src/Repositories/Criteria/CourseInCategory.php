<?php

namespace EscolaLms\Categories\Repositories\Criteria;

use EscolaLms\Categories\Models\Category;
use EscolaLms\Core\Repositories\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class CourseInCategory extends Criterion
{
    public function __construct(Category $category)
    {
        parent::__construct(null, $category);
    }

    public function apply(Builder $query): Builder
    {
        return $query->where('courses.category_id', $this->value->getKey());
    }
}
