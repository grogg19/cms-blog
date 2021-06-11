<?php

namespace App\Notification\Type;

use App\Model\Post;
use App\Model\Subscriber;
use App\Notification\NotificationType;
use App\View;
use SplFileObject;

/**
 * Class NotificationToLog
 * @package App\Notification\Type
 */
class NotificationToLog implements NotificationType
{
    /**
     * @var SplFileObject
     */
    private SplFileObject $logFile;

    /**
     * NotificationToLog constructor.
     * @param string $pathToLogFile
     */
    public function __construct(string $pathToLogFile = APP_DIR . DIRECTORY_SEPARATOR . 'logs')
    {
        $this->logFile = new SplFileObject($pathToLogFile . DIRECTORY_SEPARATOR .'mail.log', 'a+');
    }

    /** генерирует уведомление по шаблону
     * @param Post $post
     * @param Subscriber $subscriber
     */
    public function make(Post $post, Subscriber $subscriber)
    {
        ob_start();
        (new View('mails.text_template', [
            'post' => $post,
            'subscriber' => $subscriber,
            'timeToSend' => $post->published_at
        ]))->render();

        $out = ob_get_contents();

        ob_end_clean();

        $this->saveToLogFile($out);
    }


    /**
     * записывает данные в лог-файл
     * @param string $data
     */
    private function saveToLogFile(string $data)
    {
        $this->logFile->fwrite($data);
    }

}
