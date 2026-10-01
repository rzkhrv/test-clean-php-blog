<?php

namespace App\Foundation;

use PDO;

class Seeder
{
    public function __construct(
        private readonly PDO $db
    ) {}

    public function run(): void
    {
        $this->clearOldData();

        $this->seedCategories();
        $this->seedTags();
        $this->seedPosts();
    }

    private function clearOldData(): void
    {
        $this->db->exec("DELETE FROM posts");
        $this->db->exec("DELETE FROM categories");
        $this->db->exec("DELETE FROM tags");
    }

    private function seedCategories(): void
    {
        $prepared = $this->db->prepare("
            INSERT INTO categories (id, name, description) VALUES (?, ?, ?)
        ");

        $categories = $this->getDemoCategories();

        foreach ($categories as $c) {
            $prepared->execute($c);
        }
    }

    private function getDemoCategories(): array
    {
        return [
            [1, 'Технологии', 'Новости из мира IT и технологий'],
            [2, 'Путешествия', 'Истории и гайды по путешествиям'],
            [3, 'Спорт', 'Последние спортивные новости'],
        ];
    }

    private function seedPosts(): void
    {
        $preparedPost = $this->db->prepare("
            INSERT INTO posts (id, image_path, name, description, text) 
            VALUES (?, ?, ?, ?, ?)
        ");

        $preparedRelation = $this->db->prepare("
            INSERT INTO post_categories (post_id, category_id) 
            VALUES (?, ?)
        ");

        $preparedTags = $this->db->prepare("
            INSERT INTO post_tags (post_id, tag_id) 
            VALUES (?, ?)
        ");

        $demoTags = $this->getDemoTags();

        $posts = $this->getDemoPosts();
        foreach ($posts as $post) {
            $preparedPost->execute([
                $post['id'],
                $post['image_path'],
                $post['name'],
                $post['description'],
                $post['text'],
            ]);

            foreach ($post['category_ids'] as $category_id) {
                $preparedRelation->execute([
                    $post['id'],
                    $category_id,
                ]);
            }

            $currentTags = $demoTags;
            shuffle($currentTags);
            $currentTags = array_slice($currentTags, 0, rand(1, 4));

            foreach ($currentTags as $tag) {
                $preparedTags->execute([
                    $post['id'],
                    $tag['id'],
                ]);
            }
        }
    }

    private function getDemoPosts(): array
    {
        return [
            [
                'id' => 1,
                'image_path' => 'img1.jpg',
                'name' => 'PHP 8.1 Features',
                'description' => 'Обзор новых фишек',
                'text' => 'Текст про PHP...',
                'category_ids' => [1],
            ],
            [
                'id' => 2,
                'image_path' => 'img2.jpg',
                'name' => 'Топ-5 мест в горах',
                'description' => 'Куда поехать',
                'text' => 'Текст про горы...',
                'category_ids' => [2],
            ],
            [
                'id' => 3,
                'image_path' => 'img3.jpg',
                'name' => 'Docker для начинающих',
                'description' => 'Введение в контейнеры',
                'text' => 'Текст про Docker...',
                'category_ids' => [1, 2],
            ],
            [
                'id' => 4,
                'image_path' => 'img4.jpg',
                'name' => 'Symfony vs Laravel',
                'description' => 'Сравнение фреймворков',
                'text' => 'Текст про фреймворки...',
                'category_ids' => [1],
            ],
        ];
    }

    private function seedTags(): void
    {
        $prepared = $this->db->prepare("
            INSERT INTO tags (id, value) VALUES (?, ?)
        ");

        $tags = $this->getDemoTags();

        foreach ($tags as $t) {
            $prepared->execute([$t['id'], $t['value']]);
        }
    }

    private function getDemoTags(): array
    {
        return [
            [
                'id' => 1,
                'value' => 'Котик 1',
            ],
            [
                'id' => 2,
                'value' => 'Котик 2',
            ],
            [
                'id' => 3,
                'value' => 'Котик 3',
            ],
            [
                'id' => 4,
                'value' => 'Котик 4',
            ],
            [
                'id' => 5,
                'value' => 'Котик 5',
            ],
            [
                'id' => 6,
                'value' => 'Котик 6',
            ],
            [
                'id' => 7,
                'value' => 'Котик 7',
            ]
        ];
    }
}