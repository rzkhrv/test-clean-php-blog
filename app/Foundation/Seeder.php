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
        $names = [
            'Технологии', 'Путешествия', 'Спорт',
            'Наука', 'Искусство', 'Музыка',
            'Кино', 'Еда', 'Авто',
            'Бизнес'
        ];

        $categories = [];
        foreach ($names as $i => $name) {
            $categories[] = [$i + 1, $name, "Описание категории: $name"];
        }
        return $categories;
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
        $posts = [];
        $maxCatId = count($this->getDemoCategories());
        $demoImages = ['img1.jpg', 'img2.jpg', 'img3.jpg', 'img4.jpg'];

        for ($i = 1; $i <= 50; $i++) {
            $randomImage = $demoImages[array_rand($demoImages)];
            $randomCategories = array_values(
                array_unique([rand(1, $maxCatId), rand(1, $maxCatId)])
            );

            $posts[] = [
                'id' => $i,
                'image_path' => $randomImage,
                'name' => "Статья #{$i}",
                'description' => "Краткое описание статьи #{$i}",
                'text' => "Полный текст статьи #{$i}. Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                'category_ids' => $randomCategories,
            ];
        }
        return $posts;
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
        $values = [
            'PHP', 'JS', 'Python', 'Дизайн',
            'UI', 'UX', 'Backend', 'Frontend',
            'DevOps', 'Cloud', 'AI', 'ML', 'Web',
            'Mobile', 'GameDev', 'SEO', 'SMM',
            'Маркетинг', 'Финансы', 'Крипта'
        ];

        $tags = [];
        foreach ($values as $i => $value) {
            $tags[] = ['id' => $i + 1, 'value' => $value];
        }
        return $tags;
    }
}