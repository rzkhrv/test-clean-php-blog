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
        $this->seedPosts();
    }

    private function clearOldData(): void
    {
        $this->db->exec("DELETE FROM posts");
        $this->db->exec("DELETE FROM categories");
    }

    private function seedCategories()
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
}