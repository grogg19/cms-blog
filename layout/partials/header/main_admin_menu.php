<?php
use App\Model\User;
/**
 * @var User $user
 */
?>
<ul class="dx-nav dx-nav-align-left">
    <li class="dx-drop-item">
        <a href="/admin/blog/posts">Блог</a>
	    <ul class="dx-navbar-dropdown">
            <li>
                <a href="/admin/blog/posts">Все посты</a>
            </li>
            <li>
                <a href="/admin/blog/posts/create">Создать пост</a>
            </li>
        </ul>
    </li>
    <li class="dx-drop-item">
        <a href="/admin/static-pages">Страницы</a>
	    <ul class="dx-navbar-dropdown">
		    <li>
			    <a href="/admin/static-pages">Список страниц</a>
		    </li>
            <li>
                <a href="/admin/static-pages/add">Добавить страницу</a>
            </li>
        </ul>
    </li>
	<li class="dx-drop-item">
		<a href="/pravila-polzovaniya-sajtom">Пользовательское соглашение</a>
	</li>
</ul>