<?php

declare(strict_types=1);

namespace App\Repository;

use App\Data\Filter\PostFilter;
use App\Data\PostData;
use App\Enums\PostSortByEnum;
use App\Exceptions\DbNotFoundException;
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
            SELECT c.id AS category_id, p.*
            FROM categories c
            JOIN LATERAL (
                SELECT p.*, COALESCE(pvt.value, 0) AS views_count
                FROM post_categories pc
                JOIN posts p ON p.id = pc.post_id
                LEFT JOIN post_views_total pvt ON pc.post_id = pvt.post_id
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

    public function paginate(PostFilter $filter): array
    {
        $orderQuery = $this->createOrderQuery($filter);

        $query = $this->pdo->prepare("
            SELECT p.*, COALESCE(pvt.value, 0) AS views_count 
            FROM posts p
            JOIN post_categories pc ON p.id = pc.post_id
            LEFT JOIN post_views_total pvt ON p.id = pvt.post_id
            WHERE pc.category_id = ?
            ORDER BY $orderQuery
            LIMIT ?
            OFFSET ?
        ");

        $query->execute([$filter->categoryId, $filter->limit, $filter->offset]);

        return array_map(fn(array $row): PostData => $this->hydrate($row), $query->fetchAll());
    }

    public function get(int $id): PostData
    {
        $query = $this->pdo->prepare("
            SELECT p.*, COALESCE(pvt.value, 0) AS views_count
            FROM posts p
            LEFT JOIN post_views_total pvt ON p.id = pvt.post_id
            WHERE id = ?
        ");
        $query->execute([$id]);
        $row = $query->fetch();

        if ($row === false){
            throw new DbNotFoundException();
        }

        return $this->hydrate($row);
    }

    private function createOrderQuery(PostFilter $filter): string
    {
        $sortDirection = strtoupper($filter->sortDirection->value);

        return match($filter->sortBy) {
            PostSortByEnum::Views => "pvt.views_count $sortDirection",
            PostSortByEnum::Date => "p.created_at $sortDirection",
        };
    }

    protected function hydrate(array $row): mixed
    {
        return new PostData(
            id: (int)$row['id'],
            name: $row['name'],
            description: $row['description'],
            text: $row['text'],
            imagePath: $row['image_path'],
            viewsCount: (int)$row['views_count'],
            createdAt: new DateTimeImmutable($row['created_at']),
        );
    }
}