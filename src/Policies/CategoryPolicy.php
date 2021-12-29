<?php


namespace EscolaLms\Categories\Policies;

use EscolaLms\Categories\Enums\CategoriesPermissionsEnum;
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
        return $user->can(CategoriesPermissionsEnum::CATEGORY_UPDATE);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can(CategoriesPermissionsEnum::CATEGORY_CREATE);
    }

    /**
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function delete(User $user, Category $category)
    {
        return $user->can(CategoriesPermissionsEnum::CATEGORY_DELETE);
    }
}
