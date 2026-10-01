{extends file='layouts/main.tpl'}

{block name='title'}Главная{/block}

{block name='body'}
    {foreach $categories as $category}
        <div class="category">
            <h2>{$category->name}</h2>
            <p>{$category->description}</p>

            <div class="category-posts">
                {foreach $posts[$category->id] as $post}
                    <div>
                        <a href="/post/{$post->id}">
                            <img src="assets/img/{$post->imagePath}" class="post-img"  alt="{$post->name}"/>
                        </a>
                        <h3>{$post->name}</h3>
                        <p>{$post->description}</p>
                        <div class="post-views">Просмотров: {$post->viewsCount}</div>
                    </div>
                {/foreach}
            </div>

            <a href="/category/{$category->id}" class="btn">Все статьи</a>
        </div>
    {/foreach}
{/block}