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

class RegisterController
{

    private $templates,
        $qb,
        $pdo,
        $queryFactory,
        $auth;

    public function __construct(Engine $engine, QueryBuilder $qb, PDO $pdo, QueryFactory $queryFactory, Auth $auth, HelpController $hc)
    {
        $this->templates = $engine;
        $this->qb = $qb;
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
        $this->auth = $auth;
        $this->hc = $hc;
    }

    /**
     * Вывод страницы регистрации пользователей
     */
    public function showRegistration()
    {
        echo $this->templates->render('register');
    }

    /**
     * Прием и обработка введенной информации со страницы регистрации пользователей.
     * Пользователю при регистрации по умолчанию присваивается роль обычного пользователя - "USER".
     */
    public function postRegistration()
    {
        try {
            $userId = $this->auth->register($_POST['email'], $_POST['password']);

            //Создание в остальных таблицах полей с начальными данными для пользователя с id:"$userId"
            $this->qb->insert(['id' => $userId], 'users_data');
            $this->qb->insert(['id' => $userId], 'users_links');

            //Присвоение роли новому зарегистрированному пользователю с id: "$userId"
            $this->auth->admin()->addRoleForUserById($userId, \Delight\Auth\Role::USER);

            flash()->success("You have successfully registered. " . $userId);
            header('Location: /login');die();
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            flash()->error('Unknown user ID');
        }

        echo $this->templates->render('register');

    }

    /**
     * Вывод страницы создания пользователя.
     */
    public function showCreateUser()
    {
        $this->hc->checkingLogin('login');
        $this->hc->checkingRole();

        echo $this->templates->render('admin/create_user', ['auth' => $this->auth]);
    }

    /**
     * Прием и обработка введенной информации со страницы создания пользователя.
     */
    public function postCreateUser()
    {
        try {
            $userId = $this->auth->admin()->createUser($_POST['security']['email'], $_POST['security']['password']);

            $this->auth->admin()->addRoleForUserById($userId, \Delight\Auth\Role::USER);

            //Добавление в массив POST недостающих, для создания таблиц, ключей с данными.
            $_POST['inform']['id'] = $userId;
            $_POST['inform']['user_avatar'] =
                (empty($_FILES['user_avatar']['name'])) ? 'avatar-demo.png' : ($this->hc->setAvatar($userId, $_FILES['user_avatar']));
            $_POST['link']['id'] = $userId;

            //Создание дополнительных таблиц для нового пользователя с id: "$userId"
            $this->qb->insert($_POST['inform'], 'users_data');
            $this->qb->insert($_POST['link'], 'users_links');

            flash()->success("You have successfully created a user. " . $userId);
            header('Location: /');die();
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
        }

        echo $this->templates->render('admin/create_user', ['auth' => $this->auth]);

    }

    /**
     * Удаление пользователя
     */
    public function deleteUser($vars)
    {
        $this->hc->checkingLogin('login');
        $this->hc->checkingRole();

        try {

            //директория для удаления аватара
            $delete_dir_avatar = $_SERVER['DOCUMENT_ROOT'] . '/img/avatar/';

            //получение из БД название файла для удаления
            $delete_file_avatar = $this->qb->getOne('users_data', $vars['id'])[0]['user_avatar'];

            //если у пользователя на аватаре не "заглушка", то удаляем этот файл
            if ($delete_file_avatar != 'avatar-demo.png') {
                unlink($delete_dir_avatar . $delete_file_avatar);
            }

            //удаляем данные о пользователе из таблиц
            $this->auth->admin()->deleteUserById($vars['id']);
            $this->qb->delete('users_data', $vars['id']);
            $this->qb->delete('users_links', $vars['id']);

            flash()->success("The user with id:" . $vars['id'] . " was successfully deleted.");
            header('Location: /');die();
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown ID');
        }
    }
}