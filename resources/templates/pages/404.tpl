{extends file='layouts/main.tpl'}

{block name='title'}Ошибка{/block}

{block name='body'}
    <div class="card">
        <div class="card-body">
            <h1>{$message}</h1>
        </div>
    </div>
{/block}