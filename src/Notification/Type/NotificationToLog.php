<?php

namespace App\Notification\Type;

use App\Notification\NotificationType;
use App\View;
use SplFileObject;

class NotificationToLog implements NotificationType
{
    private SplFileObject $logFile;

    /**
     * NotificationToLog constructor.
     * @param string $pathToLogFile
     */
    public function __construct($pathToLogFile = APP_DIR . DIRECTORY_SEPARATOR . 'logs')
    {
        $this->logFile = new SplFileObject($pathToLogFile . DIRECTORY_SEPARATOR .'mail.log', 'a+');
    }

    /**
     * @param \App\Model\Post $post
     * @param \App\Model\Subscriber $subscriber
     */
    public function make($post, $subscriber)
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
