<?php

namespace App\Controller;

use App\exceptions\AccountIsBlockedException;
use App\exceptions\NotEnoughMoneyException;
use App\model\QueryBuilder;
use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use Exception;
use JasonGrimes\Paginator;
use League\Plates\Engine;
use Faker\Factory;
use PDO;
use SimpleMail;
use Tamtamchik\SimpleFlash\Flash;

class HelpController
{

    private $templates,
            $qb,
            $pdo,
            $queryFactory,
            $auth;

    public function __construct(Engine $engine, QueryBuilder $qb, PDO $pdo, QueryFactory $queryFactory, Auth $auth)
    {
        $this->templates = $engine;
        $this->qb = $qb;
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
        $this->auth = $auth;
    }

    /**
     * Функция добавления элементов второго массива в конец первого массива.
     * Элементы с одиноковыми ключами перезаписываются данными из второго массива.
     */
    public function copyingArray($arr_main, $arr_to_add)
    {
        for ($i = 0; $i < count($arr_main); $i++) {
            foreach ($arr_to_add[$i] as $key => $value) {
                $arr_main[$i][$key] = $value;
            }
        }
        return $arr_main;
    }

    /**
     * Назначение роли пользователю.
     */
    public function changeRole($old_role, $new_role, $user_id)
    {
        try {
            //добавляем новую роль
            $this->auth->admin()->addRoleForUserById($user_id, $new_role);

            //удаляем старую роль
            $this->auth->admin()->removeRoleForUserById($user_id, $old_role);
            return true;
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown user ID');
        }
    }

    /**
     * Загрузка аватара в директорию.
     * Возвращается имя загруженного аватара.
     */
    public function setAvatar($user_id, $avatar_file)
    {
        //директория загрузки
        $upload_dir_avatar = $_SERVER['DOCUMENT_ROOT'] . '/img/avatar/';

        //создаем уникальное имя файла
        $avatar_name = 'avatar_' . $user_id . '_' . date("dmY");

        //берем расширение файла
        $avatar_file_info = pathinfo($avatar_file['name']);
        $avatar_extention = $avatar_file_info['extension'];

        //создаем название файла с уникальным именем и прежним расширением
        $avatar_full_name = $avatar_name . "." . $avatar_extention;

        //формируем конечный путь загрузки файла
        $upload_file = $upload_dir_avatar . $avatar_full_name;

        //загружаем файл
        move_uploaded_file($avatar_file['tmp_name'], $upload_file);

        return $avatar_full_name;
    }

    /**
     * Вспомогательный метод по работе со статусом пользователя.
     * Принимает статус пользователя содержащийся в БД, а назад возвращает часть соответствующего класса для тега.
     */
    public static function status($value)
    {
        switch ($value) {
            case 'online':
                return "success";
            case 'away':
                return "warning";
            case 'busy':
                return "danger";
        }
    }

    /**
     * Проверка является ли текущий пользователь администратором.
     * Если нет, то перенаправляем на указанную страницу.
     */
    public function checkingRole($path = null)
    {
        if (!($this->auth->hasRole(\Delight\Auth\Role::ADMIN))) {
            header('Location: /' . $path);
            exit();
        }
    }

    /**
     * Проверка, авторизован ли текущий посетитель.
     * Если нет, то перенаправляем на указанную страницу.
     */
    public function checkingLogin($path = null)
    {
        if (!($this->auth->isLoggedIn() || $this->auth->isRemembered())) {
            header('Location: /' . $path);
            exit();
        }
    }

    /**
     * Назначение текущего пользователя вошедшего в систему администратором.
     */
    public function appointAdmin()
    {
        $this->checkingLogin('login');

        $old_role = $this->qb->getOne('users', $this->auth->getUserId())[0]['roles_mask'];

        try {
            //добавляем новую роль
            $this->auth->admin()->addRoleForUserById($this->auth->getUserId(), 1);

            //удаляем старую роль
            $this->auth->admin()->removeRoleForUserById($this->auth->getUserId(), $old_role);
            header('Location: /');
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown user ID');
        }
    }


}