<?php

namespace App\StaticPages;

use App\StaticPages\PageCompatible as PageCompatible;

/**
 * Class Page
 * @package App\StaticPages
 */
class Page
{
    /**
     * @var mixed|string
     */
    private $parameters = [];

    /**
     * @var \App\StaticPages\PageCompatible
     */
    private PageCompatible $compatibleDataObject;

    /**
     * Page constructor.
     * @param \App\StaticPages\PageCompatible|null $compatibleDataObject
     */
     public function __construct(PageCompatible $compatibleDataObject = null)
    {
        if ($compatibleDataObject !== null) {
            $this->compatibleDataObject = $compatibleDataObject;
            $this->parameters = $compatibleDataObject->getData();
        }
    }

    /**
     * Возвращает параметры страницы
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Возвращает конкретный параметр страницы
     * @param string $parameter
     * @return string
     */
    public function getParameter(string $parameter): string
    {
        return $this->parameters[$parameter];
    }

    /**
     * Возвращает содержимое страницы
     * @return string
     */
    public function getHtmlContent(): string
    {
        return htmlspecialchars_decode($this->parameters['htmlContent']);
    }

    /**
     * Устанавливает параметры страницы
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * Определяет значение свойства HtmlContent
     * @param string $htmlContent
     */
    public function setHtmlContent(string $htmlContent): void
    {
        $this->parameters['htmlContent'] = $htmlContent;
    }

    /**
     * Метод создает новую страницу
     * @param \App\StaticPages\PageCompatible $compatibleDataObject
     */
    public function makePage(PageCompatible $compatibleDataObject)
    {
        $compatibleDataObject->create($this->parameters['url']);
        $compatibleDataObject->saveData($this->parameters);
    }

    /**
     * Метод сохраняет данные страницы
     */
    public function savePage(): string
    {
        return $this->compatibleDataObject->saveData($this->parameters)
            ? 'Данные страницы успешно сохранены' : 'Данные сохранить невозможно';
    }

    /**
     * Возвращает URL страницы
     * @return string
     */
    public function getUrl(): string
    {
        return $this->parameters['url'];
    }

    /**
     * Возвращает массив из имени файла + его расширение
     * @return array
     */
    public function getFileName(): array
    {
        list($fileName, $fileExtension) = explode('.', $this->compatibleDataObject->file->getFilename());
        return [
            'name' => $fileName,
            'ext' => $fileExtension
        ];
    }


    /**
     * Удаление страницы
     * @return string
     */
    public function deletePage(): string
    {
        return $this->compatibleDataObject->delete() ? 'Страница успешно удалена' : 'При удалении страницы произошла ошибка';
    }

    /**
     * Проверка существующего url
     * @param string $url
     * @return bool
     */
    public function checkExistUrl(string $url): bool
    {
        return $this->compatibleDataObject->checkExistUrl($url);
    }

}
