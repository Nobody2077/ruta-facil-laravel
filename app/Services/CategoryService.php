<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    public function all(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function store(array $validated): Category
    {
        $validated['slug'] ??= Str::slug($validated['name']) . '-' . Str::random(4);

        return Category::create($validated);
    }

    public function update(Category $category, array $validated): Category
    {
        $validated['slug'] ??= Str::slug($validated['name']) . '-' . Str::random(4);

        $category->update($validated);

        return $category;
    }

    public function destroy(Category $category): void
    {
        $category->delete();
    }
}
