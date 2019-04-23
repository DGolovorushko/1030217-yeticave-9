<?php
    date_default_timezone_set('Europe/Moscow');

    $cur_date = date_create("now");
    $next_mon = date_create("tomorrow");
    $diff = date_diff($cur_date, $next_mon);
    $rest_time = date_interval_format($diff, "%H:%I");
?>
<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $value): ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><p><?=htmlspecialchars($value);?></p></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($announcements as $value): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=htmlspecialchars($value['category']);?></span>
                    <h3 class="lot__title"><a class="text-link" href=<?=htmlspecialchars($value['url']);?>><?=htmlspecialchars($value['name']);?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=numberFormat(htmlspecialchars($value['price']));?></span>
                        </div>

                        <div class="<?php print((date_interval_format($diff, "%H")==0) ? ("lot__timer timer--finishing") : ("lot__timer timer")) ?>")>
                           <?=$rest_time?>
                        </div>

                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>