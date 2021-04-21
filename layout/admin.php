<?php
/**
 * Шаблон Админки
 */

use App\View;
use App\Controllers\UserController;


    (new View('admin_header', [
        'title' => !empty($title) ? $title : "",
        'user' => (new UserController())->getCurrentUser()
    ]))->render();

    ?>
    <!-- START: MainContent -->
    <?php

    if (isset($view)) {
        (new View($view, (isset($data)) ? $data : []))->render();
    } else {
        (new View('404', ['title' => 'Страница не найдена', 'message' => 'Страница не найдена']))->render();
    }

    ?>
    <!-- END: MainContent -->
    <?php
    (new View('footer'))->render();

//}
?>
