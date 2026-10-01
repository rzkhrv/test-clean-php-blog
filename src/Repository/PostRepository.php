<?php

declare(strict_types=1);

namespace App\Repository;

use App\Data\PostData;
use DateTimeImmutable;

class PostRepository extends BaseRepository
{
    /**
     * @param int[] $categoryIds
     * @param int $limit
     * @return array
     */
    public function findLatestByCategoryIds(array $categoryIds, int $limit): array
    {
        if ($categoryIds === []) {
            return [];
        }

        $placeholder = str_repeat('?,', count($categoryIds) - 1).'?';
        $query = $this->pdo->prepare("
            SELECT 
                c.id AS category_id,
                p.id, p.name, p.description, p.text, p.image_path, p.created_at
            FROM categories c
            JOIN LATERAL (
                SELECT p.id, p.name, p.description, p.text, p.image_path, p.created_at
                FROM post_categories pc
                JOIN posts p ON p.id = pc.post_id
                WHERE pc.category_id = c.id
                ORDER BY p.created_at, p.id DESC
                LIMIT ?
            ) AS p ON TRUE
            WHERE c.id IN ($placeholder)
        ");

        $query->execute([$limit, ...$categoryIds]);
        $rows = $query->fetchAll();

        $posts = [];
        foreach ($rows as $row) {
            $posts[$row['category_id']][] = $this->hydrate($row);
        }

        return $posts;
    }
    

    protected function hydrate(array $row): mixed
    {
        return new PostData(
            id: (int)$row['id'],
            name: $row['name'],
            description: $row['description'],
            text: $row['text'],
            imagePath: $row['image_path'],
            createdAt: new DateTimeImmutable($row['created_at']),
        );
    }
}