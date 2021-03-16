<?php

namespace App\Model;

use Aura\SqlQuery\QueryFactory;
use PDO;

Class QueryBuilder {

    private $pdo, $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    /**
     * Получение всех записей из таблицы для пагинации
     */
    public function getAllPag($table, $quantityRecords, $page)
    {
        $queryFactory = $this->queryFactory;
        $select = $queryFactory->newSelect();
        $select->cols(['*']) -> from($table)
            -> setPaging($quantityRecords)
            -> page($page)
            -> orderBy(['id ASC']);

        $pdo = $this->pdo;
        $sth = $pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    /**
     * Получение одной записи по email из таблицы "users"
     */
    public function getUserByEmail($email)
    {
        $queryFactory = $this->queryFactory;
        $select = $queryFactory->newSelect();
        $select->cols(['*'])
            ->from('users')
            ->where('users.email = :email')
            ->bindValue('email', $email);
        $pdo = $this->pdo;
        $sth = $pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    /**
     * Получение всех записей из таблицы
     */
    public function getAll($table)
    {
        $queryFactory = $this->queryFactory;
        $select = $queryFactory->newSelect();
        $select->cols(['*']) -> from($table) -> orderBy(['id ASC']) ;

        $pdo = $this->pdo;
        $sth = $pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    /**
     * Получение одной записи из таблицы по id
     */
    public function getOne($table, $id)
    {
        $queryFactory = $this->queryFactory;
        $select = $queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $pdo = $this->pdo;
        $sth = $pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    /**
     * Создание новых записей в таблице
     */
    public function insert($data, $table)
    {
        $queryFactory = $this->queryFactory;
        $insert = $queryFactory->newInsert();
        $insert
            ->into($table)
            ->cols($data);

        $pdo = $this->pdo;
        $sth = $pdo->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());
    }

    /**
     * Обновление записей новыми данными.
     */
    public function update($data, $id, $table)
    {
        $queryFactory = $this->queryFactory;
        $update = $queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        $pdo = $this->pdo;
        $sth = $pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    /**
     * Удаление одной записи по id из таблицы
     */
    public function delete($table, $id)
    {
        $queryFactory = $this->queryFactory;
        $delete = $queryFactory->newDelete();

        $delete
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $pdo = $this->pdo;
        $sth = $pdo->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());
    }

}