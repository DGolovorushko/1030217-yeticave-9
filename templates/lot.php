<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $value): ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><?=htmlspecialchars($value['name']);?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2><?=htmlspecialchars($item['title']);?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?=$item['image']?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?=$item['category'];?></span></p>
                <p class="lot-item__description"><?=$item['description'];?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="<?php print((date_interval_format(date_diff(date_create("now"), $item['finish_date']), "%H")==0) ? ("lot__timer timer--finishing") : ("lot__timer timer")) ?>")>
                        <?=time_left(date_create("now"), $item['finish_date'])?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?=$item['last_price'];?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?=$item['last_price']+$item['step'];?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="<?=$item['last_price']+$item['step'];?>">
                            <!--<span class="form__error">Введите наименование лота</span>-->
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <div class="history">
                    <h3>История ставок (<span><?=count($bets);?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($bets as $bet): ?>
                            <tr class="history__item">
                                <td class="history__name"><?=htmlspecialchars($bet['name']);?></td>
                                <td class="history__price"><?=htmlspecialchars($bet['bet_sum']);?> р</td>
                                <td class="history__time"><?=htmlspecialchars($bet['bet_date']);?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>