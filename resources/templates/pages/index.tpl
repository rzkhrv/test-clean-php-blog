{extends file='layouts/main.tpl'}

{block name='title'}Главная{/block}

{block name='body'}
    {foreach $categories as $category}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h2>{$category->name}</h2>
                    <p>{$category->description}</p>
                </div>
                <a href="/category/{$category->id}" class="btn">Все статьи</a>
            </div>

            <div class="card-body">
                <div class="posts-grid">
                    {foreach $posts[$category->id] as $post}
                        <a class="post-item" href="/post/{$post->id}">
                            <div class="post-img">
                                <img src="/assets/img/{$post->imagePath}" alt="{$post->name}"/>
                            </div>
                            <div class="post-info">
                                <h3>{$post->name}</h3>
                                <p>{$post->description}</p>

                                <div class="post-meta">
                                    <div class="post-views">Просмотров: {$post->viewsCount}</div>
                                    <span class="post-date">Дата: {$post->createdAt->format('Y-m-d H:i:s')}</span>
                                </div>
                            </div>
                        </a>
                    {/foreach}
                </div>
            </div>
        </div>
    {/foreach}
{/block}