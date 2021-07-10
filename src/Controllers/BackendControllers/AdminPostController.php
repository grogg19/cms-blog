<?php
/**
 *  AdminPostController
 */

namespace App\Controllers\BackendControllers;

use App\Cookie\Cookie;
use App\Exception\NotFoundException;
use App\FormRenderer;
use App\Model\Post;
use App\Notification\Notification;
use App\Notification\Type\NotificationToLog;
use App\Parse\Yaml;
use App\Redirect;
use App\Renderable;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Validate\Validator;
use App\Image\ImageManager;
use App\View;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use App\Uploader\Upload;

/**
 * Class AdminPostController
 * @package App\Controllers\BackendControllers
 */
class AdminPostController extends AdminController
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * AdminPostController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->postRepository = new PostRepository();

        if (!empty($this->session->get('postId'))) {
            $this->onCloseCleanImage();
        }

        if (!$this->auth->checkPermissons(['admin', 'content-manager'])) {

            $this->toast->setToast('info', 'У вас недостаточно прав для этого действия');

            Redirect::to('/');
        }
    }

    /**
     * Вывод страницы со списком статей
     * @var $quantity -  Количество записей на страницу
     * @return Renderable
     */
    public function listPosts(): Renderable
    {
        $quantity = (!empty($_GET['quantity'])) ? filter_var($_GET['quantity'], FILTER_SANITIZE_STRING) : 20;
        $user = (new UserRepository())->getCurrentUser();

        if ($user->role->code == 'admin') {
            $posts = $this->postRepository->getAllPosts('desc', $quantity);
        } else {
            $posts = $this->postRepository
                ->getPostsByUserId($user->id, $quantity);
        }

        if ($posts instanceof LengthAwarePaginator) {
            $query = (!empty($quantity)) ? '?quantity=' . $quantity : '';
            $posts->setPath('posts' . $query);
        }

        $dataListPosts = [
            'title' => 'Список статей блога',
            'posts' => $posts,
            'token' => generateToken(),
            'quantity' => $quantity
        ];

        if ($quantity !== 'all') {
            $dataListPosts['paginator'] = $posts;
        }

        return new View('admin.posts.list_posts', $dataListPosts);
    }

    /**
     * Вывод страницы создания нового поста
     * @return Renderable
     */
    public function createPost(): Renderable
    {
        if (!empty(Cookie::getArray('uploadImages'))) {
            (new ImageManager())->imageDestructor(Cookie::getArray('uploadImages'));
            Cookie::delete('uploadImages');
        }

        $form = $this->getFields();

        $formFields = (new FormRenderer($form['fields']))->render();

        $dataPost = [
            'title' => 'Создание новой статьи',
            'form' => $form,
            'token' => generateToken(),
            'imgConfig' => $this->session->get('config')->getConfig('images'),
            'formFields' => $formFields
        ];

        return new View('admin.posts.create_post', $dataPost);
    }

    /**
     * Выводит форму поста для редактирования
     * @return Renderable|null
     * @throws NotFoundException
     */
    public function editPost(): ?Renderable
    {
        $uriData = parseRequestUri();

        $postId = ((int) $uriData[3] && $uriData[4] == 'edit') ? $uriData[3] : 0;
        $user = (new UserRepository())->getCurrentUser();

        // если postId == 0, редирект на страницу создания поста
        if ($postId == 0) {
            Redirect::to('/admin/blog/posts/create');
        }

        // получаем экземпляр поста с postId
        $post = $this->postRepository->getPostById($postId);

        // если такого поста нет, выбрасываем исключение 404
        if ($post == null) {
            throw new NotFoundException('Такого поста не существует');
        }

        if ($post->user_id == $this->session->get('userId') || $user->role->permissions == 1)
        {
            $form = $this->getFields();

            $formFields = (new FormRenderer($form['fields']))->render($post);

            $dataPost = [
                'title' => 'Редактирование статьи | ' . $post->title,
                'form' => $form,
                'post' => $post,
                'token' => generateToken(),
                'imgConfig' => $this->session->get('config')->getConfig('images'),
                'formFields' => $formFields
            ];

            return new View('admin.posts.edit_post', $dataPost);

        } else {
            $this->toast->setToast('info', 'Вам доступны для редактирования только ваши статьи');
            Redirect::to('/admin/blog/posts');
        }
        return null;
    }

    /**
     * Метод валидирует данные, отправляет на сохранение
     * @return string
     * @throws \App\Exception\ValidationException
     */
     public function savePost(): string
    {
        if (!checkToken()) {
            return $this->toast->getToast('warning', 'Неверный токен, обновите страницу');
        }

        // Валидируем данные формы
        $validator = new Validator($this->request->post(), Post::class);
        $validateResult = $validator->makeValidation();

        // Если валидация полей не прошла и вернулся массив с ошибками
        if (!empty($validateResult)) {

            // то возвращаем этот массив обработчику в клиент в формате json
            return json_encode($validateResult);

            // Если валидация прошла успешно
        } else {
            // Записываем значения в свойства модели
            // и пробуем сохранить
            try {

                $user = (new UserRepository())->getCurrentUser();
                $post = $this->postRepository->saveToDb($this->request, $user);

                if (empty($this->request->post('idPost'))) {
                    $this->mailNotification($post);
                }

                $this->toast->setToast('success', 'Пост успешно сохранён');

                return json_encode(['url' => '/admin/blog/posts']);

            } catch (QueryException $e) {
                return $this->toast->getToast('warning', 'Ошибка сохранения в БД: '. $e->getMessage());
            }
        }

    }

    /**
     * Метод выводит массив генерируемых полей в форме
     * @return array
     */
    public function getFields(): array
    {
        return (new Yaml())->parseFile(__DIR__ . '/../../Model/Post/fields.yaml');

    }

    /**
     * Удаление поста
     * @return string
     */
    public function deletePost(): string
    {
        $post = $this->postRepository->getPostById((int) $this->request->post('postId'));
        try {
            foreach ($post->images as $image) {
                $this->imgDelete($image->file_name);
            }
            $this->postRepository->deletePost($post);
        } catch (Exception $exception) {
            // Возвращаем флеш-сообщение об ошибке
            return $this->toast->getToast('warning', 'Ошибка удаления поста! Сообщение: ' . $exception->getMessage());
        }

        // Генерируем флеш-сообщение об успешном удалении
        $this->toast->setToast('success', 'Пост успешно удалён.');

        return json_encode(['url' => '/admin/blog/posts']);
    }

    /**
     * Загрузка изображений
     */
    public function imgUpload(): string
    {

        if (!empty($this->request->post('imgToDelete')))
        {
            return $this->imgDelete($this->request->post('imgToDelete'));
        }

        if (!empty($this->request->files())) {
            $imageUploader = new Upload($this->request->files());

            return json_encode([$imageUploader->upload()]);
        }
        return $this->toast->getToast('warning', 'Отсутствуют файлы для загрузки');

    }

    /**
     * @param $fileName
     * @return string
     */
    public function imgDelete($fileName): string
    {
        if (!empty(Cookie::getArray('uploadImages'))) {

            $cookieArray = Cookie::getArray('uploadImages');

            if (($key = array_search($fileName, $cookieArray)) !== false) {
                unset($cookieArray[$key]);
                Cookie::setArray('uploadImages', $cookieArray);
            }
        }
        return (new ImageManager())->imageDestructor([$fileName]);
    }

    /**
     * Возвращает картинки из хранилища
     * @return string
     */
    public function getImages(): string
    {
        return (new ImageManager())->getImageNameFromStorages();
    }

    /**
     * Очистка куки изображений при выходе
     */
    public function onCloseCleanImage(): void
    {
        if (!empty(Cookie::getArray('uploadImages'))) {
            (new ImageManager())->cacheImageClean();
            $this->session->remove('postBusy');
        }
    }

    /**
     * метод отправляет уведомления подписчикам
     * @param Post $post
     */
    public function mailNotification(Post $post)
    {
        $typeNotification = new NotificationToLog();
        $notification = new Notification($typeNotification, $post);

        $notification->sendNotification();
    }

}
