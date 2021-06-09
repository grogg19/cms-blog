<?php
use App\Model\Subscriber;
use App\Model\Post;
/**
 * @var $timeToSend
 * @var Post $post
 * @var Subscriber $subscriber;
 */
?>
######################## Начало письма ###########################
Адресат: <?= $subscriber->email . PHP_EOL ?>
Время отправки: <?= $timeToSend . PHP_EOL ?>
###################### Заголовок письма ##########################
На сайте добавлена новая запись: <?= $post->title . PHP_EOL ?>
######################### Тело письма ############################

Новая статья: <?= $post->title . PHP_EOL?>

<?= $post->excerpt . PHP_EOL?>

Читать - <?= SITE_ROOT . '/post/' . $post->slug .PHP_EOL ?>

Отписаться от рассылки - <?= SITE_ROOT . '/manage-subscribes/unsubscribe-by-link?email=' . $subscriber->email .'&code=' . $subscriber->hash . PHP_EOL ?>
######################### Конец письма ###########################
------------------------------------------------------------------
