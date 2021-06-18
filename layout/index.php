<?php

use App\View;
use App\Repository\UserRepository;

(new View('header', [
    'title' => (!empty($title)) ? $title : "",
    'user' => (new UserRepository())->getCurrentUser()
]))->render();

?>
    <!-- START: MainContent -->
<?php

if(!empty($view) && !empty($data)) {
    (new View($view, $data))->render();
}

?>
    <!-- END: MainContent -->
<?php

(new View('footer', $data))->render();

?>