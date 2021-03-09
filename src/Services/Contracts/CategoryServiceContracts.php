<?php

namespace EscolaLms\Categories\Services\Contracts;

use EscolaLms\Categories\Dtos\CategoryCreateDto;

interface CategoryServiceContracts
{
    public function getList(?string $search = null);

    public function find(?string $id = null);

    public function save(CategoryCreateDto $blogDto): string;

    public function delete(string $id): void;

    public function slugify(string $name): string;
}
