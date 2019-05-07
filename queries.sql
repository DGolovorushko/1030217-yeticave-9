
-- Заполнение таблицы категорий
INSERT INTO categories (name, symbol_code) VALUES ('Доски и лыжи', 'boards');
INSERT INTO categories (name, symbol_code) VALUES ('Ботинки', 'boots');
INSERT INTO categories (name, symbol_code) VALUES ('Одежда', 'clothing');
INSERT INTO categories (name, symbol_code) VALUES ('Инструменты', 'tools');
INSERT INTO categories (name, symbol_code) VALUES ('Разное', 'other');

-- Заполнение таблицы пользователей
INSERT INTO users (registration_date, name, email, password, avatar, contacts) VALUES ('2019-01-31 09:10:01', 'Иван', 'ivan2019@mail.ru', 'secret', NULL, '+79163332211');
INSERT INTO users (registration_date, name, email, password, avatar, contacts) VALUES ('2019-03-29 11:29:19', 'Дарья', 'daria2019@yandex.ru', 'secret', NULL, '+79164443322');
INSERT INTO users (registration_date, name, email, password, avatar, contacts) VALUES ('2019-04-01 23:07:14', 'Геннадий', 'gennadiy2019@gmail.com', 'secret', NULL, '+79165556677');

-- Заполнение таблицы лотов
INSERT INTO items (start_date, description, image, price, finish_date, step, id_author, id_winner, id_category)
VALUES ('2019-04-04 08:08:00', 'Зимняя шапка', NULL, 1200.12, '2019-05-24', 100, 1, NULL, 3);

INSERT INTO items (start_date, description, image, price, finish_date, step, id_author, id_winner, id_category)
VALUES ('2019-04-01 18:04:53', 'Старые лыжи', NULL, 5000, '2019-04-10', 500, 2, 1, 1);

-- Заполнение таблицы ставок
INSERT INTO bets (bet_date, bet_sum, id_user, id_item)
VALUES ('2019-04-07 22:01:49', 4500, 3, 2);
INSERT INTO bets (bet_date, bet_sum, id_user, id_item)
VALUES ('2019-04-07 22:07:52', 5000, 1, 2);

INSERT INTO bets (bet_date, bet_sum, id_user, id_item)
VALUES ('2019-04-12 15:00:00', 1300.12, 2, 1);
INSERT INTO bets (bet_date, bet_sum, id_user, id_item)
VALUES ('2019-04-13 06:57:31', 1400.12, 3, 1);
INSERT INTO bets (bet_date, bet_sum, id_user, id_item)
VALUES ('2019-04-13 07:12:17', 1500.12, 2, 1);

/*
Запросы на чтение данных
*/

-- Получение всех категорий
SELECT * FROM categories;

-- Получение самых новых, открытых лотов
SELECT items.description, items.price AS first_price, items.image, categories.name AS category, MAX(bets.bet_sum) AS last_price
FROM items
    LEFT JOIN categories
        ON items.id_category = categories.id_category
    LEFT JOIN bets
        ON items.id_item = bets.id_item
WHERE items.id_winner IS NULL
GROUP BY items.id_item
ORDER BY items.start_date DESC;

-- Получение лотов по id
SELECT items.*, categories.name AS category FROM items
    LEFT JOIN categories
    ON items.id_category = categories.id_category
WHERE id_item = 2;

-- Обновление названия лота по его id
UPDATE items SET description = 'Пушистая зимняя шапка' WHERE items.id_item = 1;

-- Получение самых свежих ставок для лота по его id
SELECT bet_date, bet_sum FROM bets
WHERE bets.id_item = 2
ORDER BY bets.bet_date DESC;
