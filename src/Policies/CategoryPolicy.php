<?php


namespace EscolaLms\Categories\Policies;

use EscolaLms\Categories\Models\Category;
use EscolaLms\Core\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function update(User $user, Category $category)
    {
        return $user->can('update category');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('create category');
    }

    /**
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function delete(User $user, Category $category)
    {
        return $user->can('delete category');
    }
}
