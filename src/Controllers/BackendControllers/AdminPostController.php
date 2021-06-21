<?php
/**
 *  AdminPostController
 */

namespace App\Controllers\BackendControllers;

use App\Cookie\Cookie;
use App\Exception\NotFoundException;
use App\Model\Post;
use App\Notification\Notification;
use App\Notification\Type\NotificationToLog;
use App\Parse\Yaml;
use App\Redirect;
use App\Renderable;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Request\Request;
use App\Validate\Validator;
use App\Model\Image;
use App\Image\ImageManager;
use App\Controllers\ToastsController;
use App\View;
use Exception;
use \Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use App\Uploader\Upload;
use App\Model\User;

use function Helpers\checkToken;
use function Helpers\generateToken;
use function Helpers\cleanJSTags;
use function Helpers\parseRequestUri;
use function Helpers\getDateTimeForDb;

/**
 * Class AdminPostController
 * @package App\Controllers\BackendControllers
 */
class AdminPostController extends AdminController
{
    /**
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * AdminPostController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->postRepository = new PostRepository();
        $this->user = (new UserRepository())->getUserById($this->session->get('userId'));

        if(!empty($this->session->get('postId'))) {
            $this->onCloseCleanImage();
        }

        $this->auth->checkPermissons(['admin', 'content-manager']); // проверка роли
    }

    /**
     * Вывод страницы со списком статей
     * @var $quantity -  Количество записей на страницу
     * @return Renderable
     */
    public function listPosts(): Renderable
    {

        $quantity = (!empty($_GET['quantity'])) ? filter_var($_GET['quantity'], FILTER_SANITIZE_STRING) : 20;

        if($this->user->role->code = 'admin') {
            $posts = $this->postRepository->getAllPosts('desc', $quantity);
        } else {
            $posts = $this->postRepository
                ->getPostsByUserId($this->user->id, $quantity);
        }

        if($posts instanceof LengthAwarePaginator) {
            $query = (!empty($quantity)) ? '?quantity=' . $quantity : '';
            $posts->setPath('posts' . $query);
        }
        $title = 'Список статей блога';

        $data = [
            'view' => 'admin.posts.list_posts',
            'data' => [
                'title' => $title,
                'posts' => $posts,
                'token' => generateToken(),
                'quantity' => $quantity
            ],
            'title' => $title
        ];

        return (new View('admin', $data));
    }

    /**
     * Вывод страницы создания нового поста
     * @return Renderable
     */
    public function createPost(): Renderable
    {
        if(!empty(Cookie::getArray('uploadImages'))) {
            (new ImageManager())->imageDestructor(Cookie::getArray('uploadImages'));
            Cookie::delete('uploadImages');
        }

        $data = [
            'view' => 'admin.posts.create_post',
            'data' => [
                'form' => $this->getFields(),
                'token' => generateToken(),
                'imgConfig' => $this->session->get('config')->getConfig('images')
            ],
            'title' => 'Создание новой статьи'
        ];

        return (new View('admin', $data));
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

        // если postId == 0, редирект на страницу создания поста
        if($postId == 0) {
            Redirect::to('/admin/blog/posts/create');
        }

        // получаем экземпляр поста с postId
        $post = $this->postRepository->getPostById($postId);

        // если такого поста нет, выбрасываем исключение 404
        if($post == null) {
            throw new NotFoundException('Такого поста не существует');
        }

        if($post->user_id == $this->session->get('userId') || $this->user->role->permissions == 1)
        {
            $data = [
                'view' => 'admin.posts.edit_post',
                'data' => [
                    'form' => $this->getFields(),
                    'post' => $post,
                    'token' => generateToken(),
                    'imgConfig' => $this->session->get('config')->getConfig('images')
                ],
                'title' => 'Редактирование статьи | ' . $post->title
            ];

            return (new View('admin', $data));

        } else {
            (new ToastsController())->setToast('info', 'Вам доступны для редкатирования только ваши статьи');
            Redirect::to('/admin/blog/posts');
        }
        return null;
    }

    /**
     * Запись данных в модель
     * @param Request $request
     */
    private function saveToDb(Request $request): void
    {

        if(!empty($request->post('idPost'))) {
            $post = $this->postRepository->getPostById((int) $request->post('idPost'));
        } else {
            $post = $this->postRepository->createNewPost();
            $post->user_id = $this->user->id;
        }

        $post->title = filter_var($request->post('title'), FILTER_SANITIZE_STRING);
        $post->slug = filter_var(trim($request->post('slug'), '/'), FILTER_SANITIZE_STRING);
        $post->excerpt = filter_var($request->post('excerpt'), FILTER_SANITIZE_STRING);
        $post->content = cleanJSTags( (string) $request->post('content'));
        $post->published = (!empty($request->post('published')) && $request->post('published') == 'on') ? 1 : 0;
        $post->published_at = (!empty($request->post('published_at'))) ? getDateTimeForDb($request->post('published_at')) : "";

        $post->save();

        if(empty($request->post('idPost'))) {
            $this->mailNotification($post);
        }

        if(!empty(Cookie::getArray('uploadImages')) && $this->session->get('postBusy') == true) {

            $sort = 0;
            $configImages = $this->session->get('config')->getConfig('images');

            foreach (Cookie::getArray('uploadImages') as $imageFileName) {
                $pathToFile = $_SERVER['DOCUMENT_ROOT'] . $configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName;

                if(file_exists($pathToFile)) {
                    $data[] = [
                        'file_name' => $imageFileName,
                        'post_id' => $post->id,
                        'sort' => $sort++
                    ];
                }
            }

            Image::insert($data); // Добавляем изображения в БД

            $this->session->set('postBusy', false); // снимаем метку блокировки поста
            Cookie::delete('uploadImages'); // Чистим список загруженных изображений в куках
        }
    }

    /**
     * Метод валидирует данные, отправляет на сохранение
     * @return string
     * @throws \App\Exception\ValidationException
     */
     public function savePost(): string
    {
        if(!checkToken()) {
            return (new ToastsController())->getToast('warning', 'Неверный токен, обновите страницу');
        }

        // Валидируем данные формы
        $validator = new Validator($this->request->post(), Post::class);
        $validateResult = $validator->makeValidation();

        // Если валидация полей не прошла и вернулся массив с ошибками
        if(!empty($validateResult)) {

            // то возвращаем этот массив обработчику в клиент в формате json
            return json_encode($validateResult);

            // Если валидация прошла успешно
        } else {
            // Записываем значения в свойства модели
            // и пробуем сохранить
            try {

                $this->saveToDb($this->request);
                (new ToastsController())->setToast('success', 'Пост успешно сохранён');

                return json_encode(['url' => '/admin/blog/posts']);

            } catch (QueryException $e) {
                return (new ToastsController())->getToast('warning', 'Ошибка сохранения в БД: '. $e->getMessage());
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
            return (new ToastsController())->getToast('warning', 'Ошибка удаления поста! Сообщение: ' . $exception->getMessage());
        }

        (new ToastsController())->setToast('success', 'Пост успешно удалён.');

        return json_encode(['url' => '/admin/blog/posts']);
    }

    /**
     * Загрузка изображений
     */
    public function imgUpload(): string
    {

        if(!empty($this->request->post('imgToDelete')))
        {
            return $this->imgDelete($this->request->post('imgToDelete'));
        }

        if(!empty($this->request->files())) {
            $imageUploader = new Upload($this->request->files());

            return json_encode([$imageUploader->upload()]);
        }
        return (new ToastsController())->getToast('warning', 'Отсутствуют файлы для загрузки');

    }

    /**
     * @param $fileName
     * @return string
     */
    public function imgDelete($fileName): string
    {
        if(!empty(Cookie::getArray('uploadImages'))) {

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
     * @return false|string
     */
    public function getImages(): false|string
    {
        return (new ImageManager())->getImageNameFromStorages();
    }

    /**
     * Очистка куки изображений при выходе
     */
    public function onCloseCleanImage(): void
    {
        if(!empty(Cookie::getArray('uploadImages'))) {
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
