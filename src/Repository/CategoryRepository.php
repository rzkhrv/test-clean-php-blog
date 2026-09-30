<?php

declare(strict_types=1);

namespace App\Repository;

use App\Data\CategoryData;

class CategoryRepository extends BaseRepository
{
    public function findAllWithPosts(): array
    {
        $query = $this->pdo->query("
            SELECT DISTINCT c.*
            FROM categories c
            INNER JOIN post_categories pc ON c.id = pc.category_id
            ORDER BY c.id
        ");

        return array_map(
            fn (array $row): CategoryData => $this->hydrate($row),
            $query->fetchAll()
        );
    }

    /**
     * @param array $row
     * @return CategoryData
     */
    protected function hydrate(array $row): CategoryData
    {
        return new CategoryData(
            id: (int) $row['id'],
            name: $row['name'],
            description: $row['description'],
        );
    }
}