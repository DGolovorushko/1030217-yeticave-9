<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><?=htmlspecialchars($category['name']);?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <form enctype="multipart/form-data" class="form form--add-lot container <?php if (count($errors)){print ' form--invalid';} ?>" action="add.php" method="post"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item<?php if(!empty($errors['lot-name'])){print ' form__item--invalid';}?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=htmlspecialchars($fields['lot-name']);?>">
                <span class="form__error"><?=htmlspecialchars($errors['lot-name']);?></span>
            </div>
            <div class="form__item<?php if(!empty($errors['category'])){print ' form__item--invalid';}?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category">
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $category): ?>
                        <option<?php if ($category['name']==$fields['category']){print (' selected');} ?>><?=htmlspecialchars($category['name']);?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?=htmlspecialchars($errors['category']);?></span>
            </div>
        </div>
        <div class="form__item form__item--wide<?php if(!empty($errors['message'])){print ' form__item--invalid';}?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите описание лота"><?=htmlspecialchars($fields['message']);?></textarea>
            <span class="form__error"><?=htmlspecialchars($errors['message']);?></span>
        </div>
        <div class="form__item form__item--file<?php if(!empty($errors['message'])){print ' form__item--invalid';}?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" value="">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
            <span class="form__error"><?=htmlspecialchars($errors['lot-img']);?></span>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small<?php if(!empty($errors['lot-rate'])){print ' form__item--invalid';}?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=htmlspecialchars($fields['lot-rate']);?>">
                <span class="form__error"><?=htmlspecialchars($errors['lot-rate']);?></span>
            </div>
            <div class="form__item form__item--small<?php if(!empty($errors['lot-step'])){print ' form__item--invalid';}?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=htmlspecialchars($fields['lot-step']);?>">
                <span class="form__error"><?=htmlspecialchars($errors['lot-step']);?></span>
            </div>
            <div class="form__item<?php if(!empty($errors['lot-date'])){print ' form__item--invalid';}?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=htmlspecialchars($fields['lot-date']);?>">
                <span class="form__error"><?=htmlspecialchars($errors['lot-date']);?></span>
            </div>
        </div>
        <?php if (count($errors)): ?>
            <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <?php endif;?>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>