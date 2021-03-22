<?php
(new App\View('header', ['title' => $parameters['title']]))->render(); // вставляем хедер
?>
    <body>
    <h1><?= (isset($parameters['message'])) ? $parameters['message'] : "" ?></h1>
    </body>
<?php
(new App\View('footer'))->render(); // Вставляем футер
?>
