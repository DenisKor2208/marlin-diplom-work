### Приложение "Список пользователей".
### Проект реализован с помощью готовых компонентов.

Установка:
```
git clone https://github.com/DenisKor2208/marlin-diplom-work.git
```

Возможности **обычного пользователя**:
* Регистрация
* Авторизация
* Редактирование:
  * Свои данные
  * Ссылки на соц. сети
  * Свой аватар
  * Свой статус
  * Свой email и пароль
* Просмотр профилей других пользователей(нажатие на аватар)

Возможности **администратора**:
* То же, что и обычный пользователь
* Редактирование данных других пользователей
* Создание новый пользователей
* Удаление профилей других пользователей

Дополнительно:
* Реализованна пагинация по 6 пользователей на страницу.
* Настройки подключения к БД расположенны в файле **public/index.php** с 22 по 28 строку.
* Дамп БД с тестовым набором пользователей расположен в файле: **marlin_exam_three.sql** 
* Реализованна возможность прямого назначения текущего пользователя администратором на случай пустой БД. Для этого необходимо перейти по адресу:
```
/appoint-admin
```
Для того, чтобы убрать эту возможность, просто удалите следующее:
* из файла public/index.php - 77 строчку
* из файла app/controller/HelpController.php со 136 по 156 строчку.

Пользователи из БД:

Администратор:
* chtil@list.ru

Обычные пользователи:
* alita.gray@ebay.com
* arica.grace@smartweb.com
* bagena881@rambler.ru
* dr.cook55@smartweb.eu
* jim.ketty@laksltd.com
* jimmy.fellan@smartweb.com
* oliver.kop@gmail.com
* sarah.mcbrook@smartweb.com

#### Пароль администратора: 54321
#### Пароль обычных пользователей: 12345