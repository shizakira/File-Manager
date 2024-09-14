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
        <div class="management">
            <form class="management__form" enctype="multipart/form-data" method="post">
                <input class="management__hidden-input" type="hidden" name="action" value="">
                <div class="management__input-wrapper">
                    <label for="input" class="management__input-label">Наименование директории</label>
                    <input class="management__input" id="directory-name" name="dirname" autocomplete="off">
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
            <div class="files">
                <?= $treeHtml ?>
            </div>
        </div>
        <div class="content">
            <div class="content__wrapper">
                <div class="content__chosen">
                    <p class="content__title">Выбрано: <span class="content__path"></span></p>
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
