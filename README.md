# Проект CMS Blog

<h6>Установка для Docker:</h6>
<ol>
  <li>Создаем папку проекта, например cms-blog</li>
  <li>В этой папке делаем <code>git clone https://github.com/grogg19/cms-blog .</code></li>
  <li>Подгружаем зависимости vendor <code>composer install</code></li>
  <li>Запускаем сборку Dockerfile <code>docker build -t cms-blog .</code></li>
  <li>Дальше подключаем образ cms-blog через клиент либо командой:<br>
    <code>docker run --name cms-blog -p 80:80 -p 3306:3306 -v /путь/к/папке/проекта:/home/www cms-blog</code>
  </li>
  <li>запускаем localhost</li>
  <li>profit!</li>
</ol>

<p>В случае возниконовения ошибки доступа к файлам нужно сделать права 0777 для папок:</p>
<ul>
  <li>/upload/avatars</li>
  <li>/upload/images</li>
  <li>/static-pages</li>
  <li>/logs</li>
</ul>
