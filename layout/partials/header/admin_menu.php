<?php
/**
 * Админское меню
 */
?>
<li>
    <div class="dropdown dx-dropdown dx-dropdown-signin">
        <a class="dx-nav-signin" href="javascript:void();" role="button" id="dropdownSignin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="dx-nav-signin-img"><?=(!empty($user->avatar)) ? '<img src="/upload/avatars/' . $user->avatar . '" alt="">' : '<img src="/layout/assets/images/avatar-1.png" alt="">' ?></span>
            <span class="dx-nav-signin-name"><?=(\Helpers\session()->get('userName')) ?? "No name"?></span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownSignin" >
            <li>
                <a href="/admin/account"><span class="icon pe-7s-user"></span> Аккаунт</a>
            </li>
            <li>
                <a href="/admin/blog/posts"><span class="icon pe-7s-news-paper"></span> Статьи блога</a>
            </li>
	        <li>
		        <a href="/admin/static-pages"><span class="icon pe-7s-copy-file"></span> Статические страницы</a>
	        </li>
	        <?php if($user->is_superuser === 1) { ?>
            <li>
                <a href="/admin/user-manager"><span class="icon pe-7s-users"></span> Управление пользователями</a>
            </li>
	        <?php } ?>
            <li>
                <a href="account-settings.html"><span class="icon pe-7s-config"></span> Настройки</a>
            </li>
            <li>
                <a href="/logout"><span class="icon pe-7s-back"></span> Выход</a>
            </li>
        </ul>
    </div>
</li>
