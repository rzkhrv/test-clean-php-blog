{extends file='layouts/main.tpl'}

{block name='title'}{$post->name}{/block}

{block name='body'}
    <div>
        <img src="/assets/img/{$post->imagePath}" class="post-img"  alt="{$post->name}"/>
        <div class="post-categories">
            {foreach $categories as $category}
                <a href="/category/{$category->id}" class="post-category">{$category->name}</a>
            {/foreach}
        </div>
        <h3>{$post->name}</h3>
        <p>{$post->text}</p>
    </div>
{/block}