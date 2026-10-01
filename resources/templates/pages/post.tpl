{extends file='layouts/main.tpl'}

{block name='title'}{$post->name}{/block}

{block name='body'}
    <div class="card post-detail">
        <div class="card-body">
            <img src="/assets/img/{$post->imagePath}" class="post-detail-image" alt="{$post->name}"/>
            <div class="post-detail-content">
                <h1 class="post-detail-title">{$post->name}</h1>
                <div class="post-categories">
                    {foreach $categories as $category}
                        <a href="/category/{$category->id}" class="post-category">{$category->name}</a>
                    {/foreach}
                </div>
                <div class="post-detail-text">{$post->text}</div>
            </div>
            <div class="post-meta">
                <div class="post-views">Просмотров: {$post->viewsCount}</div>
                <span class="post-date">Дата: {$post->createdAt->format('Y-m-d H:i:s')}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h2>Похожие статьи</h2>
            </div>
        </div>

        <div class="card-body">
            <div class="posts-grid">
                {foreach $relatedPosts as $relatedPost}
                    <a class="post-item" href="/post/{$relatedPost->id}">
                        <div class="post-img">
                            <img src="/assets/img/{$relatedPost->imagePath}" alt="{$relatedPost->name}"/>
                        </div>
                        <div class="post-info">
                            <h3>{$relatedPost->name}</h3>
                            <p>{$relatedPost->description}</p>

                            <div class="post-meta">
                                <div class="post-views">Просмотров: {$relatedPost->viewsCount}</div>
                                <span class="post-date">Дата: {$relatedPost->createdAt->format('Y-m-d H:i:s')}</span>
                            </div>
                        </div>
                    </a>
                {/foreach}
            </div>
        </div>
    </div>
{/block}