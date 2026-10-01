{extends file='layouts/main.tpl'}

{block name='title'}{$category->name}{/block}

{block name='body'}
    <div class="category">
        <h2>{$category->name}</h2>
        <p>{$category->description}</p>

        <div>
            <span>Сортировка:</span>
            <div>
                <a href="/category/{$category->id}?sortBy=date&sortDirection={$sortDirection === 'desc' ? 'asc' : 'desc'}">
                    По дате
                </a>
                <a href="/category/{$category->id}?sortBy=views&sortDirection={$sortDirection === 'desc' ? 'asc' : 'desc'}">
                    По просмотрам
                </a>
            </div>
        </div>

        <div class="category-posts">
            {foreach $posts as $post}
                <div>
                    <a href="/post/{$post->id}">
                        <img src="/assets/img/{$post->imagePath}" class="post-img"  alt="{$post->name}"/>
                    </a>
                    <h3>{$post->name}</h3>
                    <p>{$post->description}</p>
                    <div class="post-views">Просмотров: {$post->viewsCount}</div>
                </div>
            {/foreach}
        </div>

        <nav>
            {if $currentPage > 1}
                <a href="/category/{$category->id}?sortBy={$sortBy}&sortDirection={$sortDirection}&page={$currentPage - 1}"
                    >Назад</a>
            {/if}

            <span>
                Страница {$currentPage} из {$totalPages}
            </span>

            {if $currentPage < $totalPages}
                <a href="/category/{$category->id}?sortBy={$sortBy}&sortDirection={$sortDirection}&page={$currentPage + 1}"
                   >Вперёд</a>
            {/if}
        </nav>
    </div>
{/block}