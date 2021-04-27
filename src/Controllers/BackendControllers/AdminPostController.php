<?php
/**
 *  AdminPostController
 */

namespace App\Controllers\BackendControllers;

use App\Config;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Cookie\Cookie;
use App\Model\Post;
use App\Parse\Yaml;
use App\Redirect;
use App\Request\Request;
use App\Validate\Validator;
use App\View;
use App\Model\Image;
use App\Controllers\BackendControllers\AdminController as AdminController;
use App\Controllers\BackendControllers\AdminImageController as AdminImageController;

use Illuminate\Database\QueryException;
use App\Uploader\Upload;

use function Helpers\checkToken;
use function Helpers\parseRequestUri;
use function Helpers\getDateTimeForDb;

class AdminPostController extends AdminController
{
    /**
     * @var PostController
     */
    protected $post;
    protected $user;

    /**
     * AdminPostController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->post = new PostController();
        $this->user = (new UserController())->getUserById($this->session->get('userId'));

        if(!empty($this->session->get('postId'))) {
            $this->onCloseCleanImage();
        }
    }

    /**
     * Вывод страницы со списком статей
     * @return View
     */
    public function listPosts()
    {
        $permissions = explode(',', $this->user->role->permissions);
        if(in_array('1', $permissions))
        {
            $posts = (new PostController())->getAllPosts();

        } else {

            $posts = (new PostController())
                ->getPostsByUserId($this->user->id);

        }

        return new View('admin', [
            'view' => 'admin.list_posts',
            'data' => [
                'posts' => $posts
            ],
            'title' => 'Список постов блога'
        ]);
    }

    /**
     * Вывод страницы создания нового поста
     * @return View
     */
    public function createPost()
    {
        if(!empty(Cookie::getArray('uploadImages'))) {
            (new AdminImageController())->imageDestructor(Cookie::getArray('uploadImages'));
            Cookie::delete('uploadImages');
        }

        return new View('admin', [
            'view' => 'admin.create_post',
            'data' => [
                'form' => $this->getFields(),
                'token' => \Helpers\generateToken()
            ],
            'title' => 'Создание новой статьи'
        ]);
    }

    /**
     * Выводит форму поста для редактирования
     * @return View
     */
    public function editPost()
    {
        $uriData = parseRequestUri();

        $id = ((int) $uriData[3] && $uriData[4] == 'edit') ? $uriData[3] : 0;

        if($id == 0) {
            Redirect::to('/admin/blog/posts/create');
        }

        $post = (new PostController())->getPostById($id);

        if($post->user_id == $this->session->get('userId') || $this->user->role->permissions == 1)
        {
            return new View('admin', [
                'view' => 'admin/edit_post',
                'data' => [
                    'form' => $this->getFields(),
                    'post' => $post,
                    'token' => \Helpers\generateToken(),
                    'imgUploadConfig' => Config::getInstance()->getConfig('images')
                ],
                'title' => 'Редактирование статьи | ' . $post->title
            ]);
        } else {
            // Пока редирект ведет на create, но должен на самом деле редиректиться на список статей пользователя
            Redirect::to('/admin/blog/posts/create');
        }
    }

    /**
     * Запись данных в модель
     * @param Request $request
     */
    public function saveToDb(Request $request)
    {
        if(!empty($request->post('idPost'))) {

            $post = (new PostController())->getPostById((int) $request->post('idPost'));

        } else {

            $post = (new PostController())->createNewPost();
            $post->user_id = $this->user->id;
        }

        $post->title = $request->post('title');
        $post->slug = $request->post('slug');
        $post->excerpt = $request->post('excerpt');
        $post->content = $request->post('content');
        $post->published = (!empty($request->post('published')) && $request->post('published') == 'on') ? 1 : 0;
        $post->published_at = (!empty($request->post('published_at'))) ? getDateTimeForDb($request->post('published_at')) : "";

        $post->save();

        if(!empty(Cookie::getArray('uploadImages')) && $this->session->get('postBusy') == true) {

            $sort = 0;
            $configImages = Config::getInstance()->getConfig('images');

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

            Image::insert($data);

            $this->session->set('postBusy', false);
            Cookie::delete('uploadImages');
        }
    }

    /**
     * Метод валидирует данные, отправляет на сохранение
     * @return string
     */
    public function savePost()
    {
        // Валидируем данные формы
        if(checkToken()) {
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
                    return json_encode([
                        'url' => '/admin/blog/posts'
                    ]);

                } catch (QueryException $e) {
                    return json_encode(['message' => 'Ошибка сохранения в БД', 'error' => $e]);
                }
            }
        }
    }

    /**
     * Метод выводит массив генерируемых полей в форме
     * @return array
     */
    public function getFields()
    {
        return (new Yaml())->parseFile(__DIR__ . '/../../Model/Post/fields.yaml');

    }

    /**
     * @return false|string
     */
    public function imgUpload(): false|string
    {

        if(!empty($this->request->post('imgToDelete')))
        {
            return $this->imgDelete($this->request->post('imgToDelete'));
        }

        if(!empty($this->request->files())) {
            $imageUploader = new Upload($this->request->files());
            return $imageUploader->upload();
        }
        return json_encode(['errorMsg' => 'Файлов не завезли']);

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
        return (new AdminImageController())->imageDestructor([$fileName]);
    }

    /**
     * @return false|string
     */
    public function getImages(): false|string
    {
        return (new AdminImageController())->getImageNameFromStorages();
    }

    /**
     * Очистка куки изображений при выходе
     */
    public function onCloseCleanImage(): void
    {
        if(!empty(Cookie::getArray('uploadImages'))) {
            (new AdminImageController())->cacheImageClean();
            $this->session->remove('postBusy');
        }
    }

}
