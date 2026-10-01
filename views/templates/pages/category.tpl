{extends file='layouts/main.tpl'}

{block name='title'}{$category->name}{/block}

{block name='body'}
    <div class="category">
        <h2>{$category->name}</h2>
        <p>{$category->description}</p>

        <div class="category-posts">
            {foreach $posts as $post}
                <div>
                    <a href="/post/{$post->id}">
                        <img src="/assets/img/{$post->imagePath}" class="post-img"  alt="{$post->name}"/>
                    </a>
                    <h3>{$post->name}</h3>
                    <p>{$post->description}</p>
                </div>
            {/foreach}
        </div>
    </div>
{/block}