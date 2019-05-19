<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><p><?=htmlspecialchars($category['name']);?></p></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <form class="form container<?php if (count($errors)){print ' form--invalid';} ?>" action="sign_up.php" method="post" autocomplete="off">
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item<?php if(!empty($errors['email'])){print ' form__item--invalid';}?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=htmlspecialchars($fields['email']);?>">
            <span class="form__error"><?php if(!empty($errors['email'])){print $errors['email'];}?></span>
        </div>
        <div class="form__item<?php if(!empty($errors['password'])){print ' form__item--invalid';}?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">
            <span class="form__error"><?php if(!empty($errors['password'])){print $errors['password'];}?></span>
        </div>
        <div class="form__item<?php if(!empty($errors['name'])){print ' form__item--invalid';}?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=htmlspecialchars($fields['name']);?>">
            <span class="form__error"><?php if(!empty($errors['name'])){print $errors['name'];}?></span>
        </div>
        <div class="form__item<?php if(!empty($errors['message'])){print ' form__item--invalid';}?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=htmlspecialchars($fields['message']);?></textarea>
            <span class="form__error"><?php if(!empty($errors['message'])){print $errors['message'];}?></span>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
</main>