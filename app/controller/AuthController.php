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

class AuthController
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
     * Вывод страницы авторизации
     */
    public function showLogin()
    {
        if ($this->auth->isLoggedIn() || $this->auth->isRemembered()) {
            header('Location: /');
            exit();
        }

        echo $this->templates->render('login');
    }

    /**
     * Прием и обработка введенной информации со страницы авторизации.
     * Работает функционал нажатия "Запомнить меня".
     */
    public function postLogin()
    {
        //Обработка нажатия "Запомнить меня"
        if (array_key_exists('rememberme', $_POST) and $_POST['rememberme'] == 'on') {
            $rememberDuration = (int) (60 * 60 * 24 * 365.25);
        } else {
            $rememberDuration = null;
        }

        try {
            $this->auth->login($_POST['email'], $_POST['password'], $rememberDuration);
            flash()->success('User is logged in');
            header('Location: /');die();
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Wrong email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Wrong password');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error('Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
        }

        echo $this->templates->render('login');
    }

    /**
     * Выход из системы
     */
    public function logOut()
    {
        $this->auth->logOut();
        $this->showLogin();
    }
}