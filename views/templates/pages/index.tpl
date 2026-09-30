{extends file='layouts/main.tpl'}

{block name='title'}Главная{/block}

{block name='body'}
    {foreach $categories as $category}
        <div class="category">
            <h2>{$category->name}</h2>
            <p>{$category->description}</p>

            <div class="category-posts"></div>

            <a href="/category/{$category->id}" class="btn">Все статьи</a>
        </div>
    {/foreach}
{/block}