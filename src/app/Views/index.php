<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Файловый менеджер</title>
</head>

<body>
    <main class="main">
        <div class="managment">
            <form id="control-form" class="managment__form" enctype="multipart/form-data" action="index.php" method="post">
                <input type="hidden" name="action" value="">
                <div class="managment__input-wrapper">
                    <label for="input" class="managment__input-label">Наименование директории</label>
                    <input class="managment__input" id="directory-name" name="dirname" autocomplete="off">
                </div>
                <div class="btn-wrapper">
                    <button class="btn" id="add-folder-btn" type="button" disabled>
                        <span class="btn__text">Добавить папку</span>
                    </button>
                    <input type="file" id="file-upload" name="file" style="display: none;">
                    <button class="btn" id="add-file-btn" type="button" disabled>
                        <span class="btn__text">Добавить файл</span>
                    </button>
                    <button class="btn" id="delete-btn" type="button" disabled>
                        <span class="btn__text">Удалить</span>
                    </button>
                </div>
            </form>
            <div class="files" id="file-structure">
                <?= $treeHtml ?>
            </div>
        </div>
        <div class="content">
            <div class="content__wrapper">
                <div class="content__choused">
                    <p class="content__title">Выбрано: <span class="content__path"></span></p>
                    <span id="selected-file" class="content__current-folder"></span>
                </div>
                <button id="download-btn" class="btn" type="button" disabled>
                    <span class="btn__text">Скачать</span>
                </button>
            </div>
            <div class="content__preview-wrapper">
                <div id="image-preview" class="content__image">
                    <img src="assets/img/no-photo.png" alt="No Image">
                </div>
            </div>
        </div>
    </main>
    <script src="assets/scripts.js"></script>
</body>

</html>
