{extends file='layouts/main.tpl'}

{block name='title'}Главная{/block}

{block name='body'}
    <ul>
    {foreach $categories as $category}
        <li>{$category->name}</li>
    {/foreach}
    </ul>
{/block}