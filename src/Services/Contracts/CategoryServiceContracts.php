<?php

namespace EscolaLms\Categories\Services\Contracts;

use EscolaLms\Categories\Dtos\CategoryCreateDto;

interface CategoryServiceContracts
{
    public function getList(?string $search = null);

    public function find(?int $id = null);

    public function save(CategoryCreateDto $blogDto): string;

    public function delete(int $id): void;

    public function slugify(string $name): string;
}
