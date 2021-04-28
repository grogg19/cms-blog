<?php

use App\View;
use App\Controllers\UserController;

(new View('header', [
    'title' => (!empty($title)) ? $title : "",
    'user' => (new UserController())->getCurrentUser()
]))->render();

?>
    <!-- START: MainContent -->
<?php

if(isset($view) && isset($data)) {
    (new View($view, $data))->render();
}

?>
    <!-- END: MainContent -->
<?php

(new View('footer', $data))->render();

?>