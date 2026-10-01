<?php

declare(strict_types=1);

namespace App\Repository;

use App\Data\Filter\PostFilter;
use App\Data\Post\PostData;
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

    public function addView(int $postId, string $visitorId): void
    {
        $query = $this->pdo->prepare("
            INSERT IGNORE INTO post_views (post_id, hash) 
            VALUES (?, ?)
        ");

        $query->execute([$postId, $visitorId]);
    }

    public function countByCategoryId(int $categoryId): int
    {
        $query = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM post_categories
            WHERE category_id = ?
        ");

        $query->execute([$categoryId]);

        return (int)$query->fetchColumn();
    }

    /**
     * @return PostData[]
     */
    public function getRelated(int $postId, int $limit = 3): array
    {
        $query = $this->pdo->prepare("
            SELECT p.*, COUNT(*) AS rtc, COALESCE(pvt.value, 0) AS views_count
            FROM post_tags pt1
            JOIN post_tags pt2 ON pt2.tag_id = pt1.tag_id
            JOIN posts p ON p.id = pt2.post_id
            LEFT JOIN post_views_total pvt ON p.id = pvt.post_id
            WHERE pt1.post_id = ? AND pt2.post_id <> ?
            GROUP BY p.id
            ORDER BY rtc DESC, p.id DESC
            LIMIT ?
        ");

        $query->execute([$postId, $postId, $limit]);

        return array_map(fn(array $row): PostData => $this->hydrate($row), $query->fetchAll());
    }

    private function createOrderQuery(PostFilter $filter): string
    {
        $sortDirection = strtoupper($filter->sortDirection->value);

        return match($filter->sortBy) {
            PostSortByEnum::Views => "views_count $sortDirection, p.id $sortDirection",
            PostSortByEnum::Date => "p.created_at $sortDirection, p.id $sortDirection",
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