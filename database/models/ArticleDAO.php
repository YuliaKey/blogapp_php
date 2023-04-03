<?php

$pdo = require_once '../database.php';

class ArticleDAO {

    private PDOStatement $statementReadAll;
    private PDOStatement $statementReadOne;
    private PDOStatement $statementCreateOne;
    private PDOStatement $statementUpdateOne;
    private PDOStatement $statementDeleteOne;

    function __construct(
        private PDO $pdo
    )
    {
        $this->statementReadAll = $this->$pdo->prepare(
            'SELECT * FROM article'
        );
        $this->statementReadOne = $this->$pdo->prepare(
            'SELECT * FROM article WHERE id=:id'
        );
        $this->statementCreateOne = $this->$pdo->prepare(
            'INSERT INTO article (title, category, content, image) VALUES (:title, :category, :content, :image)'        );
        $this->statementUpdateOne = $pdo->prepare(
            'UPDATE article SET title=:title, category=:category, content=:content, image=:image WHERE id=:id'
        );
        $this->statementDeleteOne = $this->$pdo->prepare(
            'DELETE FROM article WHERE id=:id'
        );
    }

    public function getAll() {
        $this->statementReadAll->execute();
        return $this->statementReadAll->fetchAll();
    }

    public function getOne(int $id) {
        $this->statementReadOne->bindValue(':id', $id);
        $this->statementReadOne->execute();
        return $this->statementReadOne->fetch();
    }

    public function crerateOne($srticle) {
        $this->statementCreateOne->bindOne(':title', $article['title']);
        $this->statementCreateOne->bindOne(':caregory', $article['caregory']);
        $this->statementCreateOne->bindOne(':content', $article['content']);
        $this->statementCreateOne->bindOne(':image', $article['image']);
        $this->statementCreateOne->execute();
        //je renvoie l'article qui vient d'etre creer
        return $this->pdo->lastInsertid();
    }

    public function deleteOne(int $id) {
        $this->statementDeleteOne->bindValue(':id', $id);
        $this->statementDeleteOne->execute();
        return $id;
    }

    public function updateOne($article, $id) {
        $this->statementUpdateOne->bindOne(':title', $article['title']);
        $this->statementUpdateOne->bindOne(':caregory', $article['caregory']);
        $this->statementUpdateOne->bindOne(':content', $article['content']);
        $this->statementUpdateOne->bindOne(':image', $article['image']);
        $this->statementUpdateOne->bindOne(':id', $id);
        $this->statementUpdateOne->execute();
        return $article;
        
    }


}

$pdo = require_once './database/database.php';
return new ArticleDAO($pdo);