<?php

namespace App;

class ArticleRepository
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        //$this->pdo->exec("create table articles (id INTEGER PRIMARY KEY,title TEXT, text TEXT);");
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare('select * from articles where id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('delete from articles where id = ?');
        return $stmt->execute([$id]);
    }

    public function all()
    {
        return $this->pdo->query('select * from articles')->fetchAll();
    }

    public function insert($params)
    {
        $pdo = $this->pdo;

        $fields = implode(', ', array_keys($params));
        $values = implode(', ', array_map(function ($v) use ($pdo) {
            return $pdo->quote($v);
        }, array_values($params)));
        return $pdo->exec("insert into articles ($fields) values ($values)");
    }
}
