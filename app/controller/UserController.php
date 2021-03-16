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
use App\Controller\HelpController;

class UserController
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
     * Получение и вывод данных о всех пользователях на главную страницу с пагинацией
     */
    public function pageUsers($vars)
    {
        $quantityRecords = 6;

        $totalItems = $this->qb->getAll('users'); //получаем общее кол-во записей

        $users = $this->qb->getAllPag('users', $quantityRecords, $vars['num'] ?? 1);
        $usersInfo = $this->qb->getAllPag('users_data', $quantityRecords, $vars['num'] ?? 1);
        $usersLinks = $this->qb->getAllPag('users_links', $quantityRecords, $vars['num'] ?? 1);

        $usersData = $this->hc->copyingArray($this->hc->copyingArray($users, $usersInfo), $usersLinks);

        $itemsPerPage = $quantityRecords; //сколько постов будет на странице
        $currentPage = $vars['num'] ?? 1; //какая страница сейчас открыта
        $urlPattern = '/page-users/(:num)';

        $paginator = new Paginator(count($totalItems), $itemsPerPage, $currentPage, $urlPattern);

        echo $this->templates->render('user/page_users',
            [
                'auth' => $this->auth,
                'usersData' => $usersData,
                'paginator' => $paginator
            ]);
    }

    /**
     * Получение и вывод данных о всех пользователях на главную страницу без пагинации
     */
    public function pageAllUsers()
    {
        //Получение данных всех пользователей
        $users = $this->qb->getAll('users');
        $users_info = $this->qb->getAll('users_data');
        $users_links = $this->qb->getAll('users_links');

        //соединение всех полученных данных по каждому пользователю в один массив
        $usersData = $this->hc->copyingArray($users, $users_info);
        $usersData = $this->hc->copyingArray($usersData, $users_links);

        //вывод данных в шаблон страницы
        echo $this->templates->render('user/page_all_users',
            ['auth' => $this->auth,
             'usersData' => $usersData]);
    }

    /**
     * Вывод страницы с информацией о конкретном пользователе.
     */
    public function showProfile($vars)
    {
        //Получение информании о конкретном пользователе из БД по его id
        $users = $this->qb->getOne('users', $vars['id']);
        $users_info = $this->qb->getOne('users_data', $vars['id']);
        $users_links = $this->qb->getOne('users_links', $vars['id']);

        //соединение всех полученных данных о пользователе в один массив
        $userData = $this->hc->copyingArray($users, $users_info);
        $userData = $this->hc->copyingArray($userData, $users_links);

        echo $this->templates->render('user/page_profile', ['auth' => $this->auth, 'userData' => $userData]);
    }

    /**
     * Вывод страницы редактирования информации пользователя
     */
    public function showEdit($vars)
    {
        $this->hc->checkingLogin('login');

        //Список доступных ролей для назначения пользователям
        $roles = [
            '1' => 'Администратор',
            '2' => 'Пользователь',
        ];

        //Получение данных одного пользователя по его id
        $users = $this->qb->getOne('users', $vars['id']);
        $users_info = $this->qb->getOne('users_data', $vars['id']);

        //соединение всех полученных данных пользователя в один массив
        $userData = $this->hc->copyingArray($users, $users_info);

        echo $this->templates->render('user/page_edit', ['auth' => $this->auth, 'userData' => $userData, 'roles' => $roles]);
    }

    /**
     * Прием и обработка введенной информации со страницы редактирования данных пользователя.
     */
    public function postEdit()
    {
        //получение текущей(действующей) роли пользователя
        $old_role = $this->qb->getOne('users', $_POST['id'])[0]['roles_mask'];

        //Снимаем с пользователя старую роль и назначаем новую/выбранную роль
        $this->hc->changeRole($old_role, $_POST['user_role'], $_POST['id']);

        //удаляем ненужный элемент из массива POST
        unset($_POST['user_role']);

        //обновляем информацию о пользователе в таблице БД, новыми данными
        $this->qb->update($_POST, $_POST['id'], 'users_data');

        flash()->success('User data changed');
        header('Location: /page-edit/' . $_POST['id']);die();
    }

    /**
     * Вывод страницы смены аватара
     */
    public function showMedia($vars)
    {
        $this->hc->checkingLogin('login');

        $userData = $this->qb->getOne('users_data', $vars['id']);
        echo $this->templates->render('user/page_media', ['auth' => $this->auth, 'userData' => $userData]);
    }

    /**
     * Изменение аватара пользователя
     */
    public function postMedia()
    {
        //директория в которой хранится файл аватара для удаления
        $delete_dir_avatar = $_SERVER['DOCUMENT_ROOT'] . '/img/avatar/';

        //получаем из БД название файла для удаления
        $delete_file_avatar = $this->qb->getOne('users_data', $_POST['id'])[0]['user_avatar'];

        //если у пользователя на аватаре не "заглушка", то удаляем этот файл
        if ($delete_file_avatar != 'avatar-demo.png') {
            unlink($delete_dir_avatar . $delete_file_avatar);
        }

        //Загрузка аватара в директорию и обновление имени аватара в таблице БД
        $this->qb->update(['user_avatar' => $this->hc->setAvatar($_POST['id'], $_FILES['user_avatar'])], $_POST['id'], 'users_data');

        flash()->success("The user's avatar has been successfully changed.");
        header('Location: /page-media/'. $_POST['id']);die();
    }

    /**
     * Страница редактирования ссылкок соц. сетей пользователя
     */
    public function showSocial($vars)
    {
        $this->hc->checkingLogin('login');

        $userData = $this->qb->getOne('users_links', $vars['id']);
        echo $this->templates->render('user/page_social', ['auth' => $this->auth, 'userData' => $userData]);
    }

    /**
     * Прием и обработка введенной информации со страницы редактирования ссылкок соц. сетей пользователя
     */
    public function postSocial()
    {
        $this->qb->update($_POST, $_POST['id'], 'users_links');
        flash()->success('The user\'s social media links were successfully changed.');
        header('Location: /page-social/' . $_POST['id']);die();
    }

    /**
     * Вывод страницы смены email и password пользователя
     */
    public function showSecurity($vars)
    {
        $this->hc->checkingLogin('login');

        $userData = $this->qb->getOne('users', $vars['id']);
        echo $this->templates->render('user/page_security', ['auth' => $this->auth, 'userData' => $userData]);
    }

    /**
     * Изменение email пользователя
     */
    public function postSecurityEmail()
    {
        //проверка: если текущий пользователь меняет именно свой email или администратор меняет email какого-либо ползователя
        if ($this->auth->id() == $_POST['id'] or $this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {

            //поиск нового email среди имеющихся пользователей в таблице 'users'
            $resultFindUser = $this->qb->getUserByEmail($_POST['email']);

            //если есть пользователь с таким email, то операция будет прервана и в ответ вернется ошибка
            if (count($resultFindUser) == 1) {
                flash()->error("This email already exists. " . $_POST['email']);
                header('Location: /page-security/' . $_POST['id']);die();
            }

            //если email уникален, то перезаписываем его пользователю
            $this->qb->update(['email' => $_POST['email']], $_POST['id'], 'users');
            flash()->success("There is no such email address. " . $_POST['email']);
            header('Location: /page-security/' . $_POST['id']);die();
        } else {
            flash()->error("You are trying to change someone else's email address.");
            header('Location: /page-security/' . $_POST['id']);die();
        }
    }

    /**
     * Изменение password пользователя
     */
    public function postSecurityPassword()
    {
        //Проверка: если текущий пользователь пытается изменить свой пароль
        if ($this->auth->id() == $_POST['id']) {
            try {
                $this->auth->changePassword($_POST['old_password'], $_POST['new_password']);
                flash()->success("Password has been changed.");
                header('Location: /page-security/' . $_POST['id']);die();
            }
            catch (\Delight\Auth\NotLoggedInException $e) {
                flash()->error('Not logged in');
                header('Location: /page-security/' . $_POST['id']);die();
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Invalid password(s)');
                header('Location: /page-security/' . $_POST['id']);die();
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
                header('Location: /page-security/' . $_POST['id']);die();
            }

            //Проверка: если администратор пытается изменить пароль другому пользователю
        } elseif ($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            try {
                //изменение пароля пользователю по его id
                $this->auth->admin()->changePasswordForUserById($_POST['id'], $_POST['new_password']);
                flash()->success("Password has been changed.");
                header('Location: /page-security/' . $_POST['id']);die();
            }
            catch (\Delight\Auth\UnknownIdException $e) {
                flash()->error('Unknown ID');
                header('Location: /page-security/' . $_POST['id']);die();
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Invalid password');
                header('Location: /page-security/' . $_POST['id']);die();
            }

        } else {
            flash()->error("You are trying to change another user's password.");
            header('Location: /page-security/' . $_POST['id']);die();
        }
    }

    /**
     * Вывод страницы смены статуса пользователя
     */
    public function showStatus($vars)
    {
        $this->hc->checkingLogin('login');

        //Список доступных статусов для назначения пользователям
        $statuses = [
            'online' => 'Онлайн',
            'away' => 'Отошел',
            'busy' => 'Не беспокоить'
        ];

        $userData = $this->qb->getOne('users_data', $vars['id']);
        echo $this->templates->render('user/page_status', ['auth' => $this->auth, 'userData' => $userData, 'statuses' => $statuses]);
    }

    /**
     * Обработка введенной информации со страницы изменения статуса пользователя
     */
    public function postStatus()
    {
        $this->qb->update($_POST, $_POST['id'], 'users_data');
        flash()->success('User status changed');
        header('Location: /page-status/' . $_POST['id']);die();
    }

}