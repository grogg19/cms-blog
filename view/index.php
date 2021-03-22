<?php
(new App\View('header', ['title' => $parameters['title']]))->render(); // вставляем хедер
?>
<body>
<h1><?= (isset($parameters['title'])) ? $parameters['title'] : "" ?></h1>
</body>
<?php
(new App\View('footer'))->render(); // Вставляем футер
?>
