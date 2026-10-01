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
        <div class="post-views">Просмотров: {$post->viewsCount}</div>

        <div class="category">
            <h2>Похожие статьи</h2>
            <div class="category-posts">
                {foreach $relatedPosts as $relatedPost}
                    <div>
                        <a href="/post/{$relatedPost->id}">
                            <img src="/assets/img/{$relatedPost->imagePath}" class="post-img"  alt="{$relatedPost->name}"/>
                        </a>
                        <h3>{$relatedPost->name}</h3>
                        <p>{$relatedPost->description}</p>
                        <div class="post-views">Просмотров: {$relatedPost->viewsCount}</div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
{/block}