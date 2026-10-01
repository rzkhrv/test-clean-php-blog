{extends file='layouts/main.tpl'}

{block name='title'}{$category->name}{/block}

{block name='body'}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h2>{$category->name}</h2>
                <p>{$category->description}</p>
            </div>
            <div class="sort">
                <span>Сортировать по:</span>
                <div class="sort-links">
                    <a href="/category/{$category->id}?sortBy=date&sortDirection={$sortDirection === 'desc' ? 'asc' : 'desc'}"
                       class="btn">По дате</a>
                    <a href="/category/{$category->id}?sortBy=views&sortDirection={$sortDirection === 'desc' ? 'asc' : 'desc'}"
                        class="btn">По просмотрам</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="posts-grid">
                {foreach $posts as $post}
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

        <div class="pagination">
            {if $currentPage > 1}
                <a href="/category/{$category->id}?sortBy={$sortBy}&sortDirection={$sortDirection}&page={$currentPage - 1}"
                    class="btn">Назад</a>
            {/if}

            <span>
                Страница {$currentPage} из {$totalPages}
            </span>

            {if $currentPage < $totalPages}
                <a href="/category/{$category->id}?sortBy={$sortBy}&sortDirection={$sortDirection}&page={$currentPage + 1}"
                   class="btn">Вперёд</a>
            {/if}
        </div>
    </div>
{/block}