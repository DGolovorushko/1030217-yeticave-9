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
    <form class="form container<?php if (count($errors)){print ' form--invalid';} ?>" action="login.php" method="post">
        <h2>Вход</h2>
        <div class="form__item<?php if(!empty($errors['email'])){print ' form__item--invalid';}?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=htmlspecialchars($fields['email']);?>">
            <span class="form__error"><?php if(!empty($errors['email'])){print $errors['email'];}?></span>
        </div>
        <div class="form__item<?php if(!empty($errors['password'])){print ' form__item--invalid';}else{print ' form__item--last';}?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">
            <span class="form__error"><?php if(!empty($errors['password'])){print $errors['password'];}?></span>
        </div>
        <span class="form__error form__error--bottom"><?php if(!empty($errors['login'])){print $errors['login'];}?></span>
        <button type="submit" class="button">Войти</button>
    </form>
</main>