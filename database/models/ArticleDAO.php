<?php


class ArticleDAO { //article data acceess objet

    private PDOStatement $statementReadAll;
    private PDOStatement $statementReadOne;
    private PDOStatement $statementCreateOne;
    private PDOStatement $statementUpdateOne;
    private PDOStatement $statementDeleteOne;
    private PDOStatement $statementReadUserAll;

    function __construct(
        private PDO $pdo
        )
        {
            $this->statementReadAll = $this->pdo->prepare(
                'SELECT article.*, user.firstname, user.lastname FROM article JOIN user ON article.author = user.id'
            );
            $this->statementReadOne = $this->pdo->prepare(
                'SELECT article.*, user.firstname, user.lastname FROM article JOIN user ON article.author = user.id WHERE article.id=:id'
            );
            $this->statementCreateOne = $this->pdo->prepare(
                'INSERT INTO article (title, category, content, image, author) VALUES (:title, :category, :content, :image, :author)'        );
            $this->statementUpdateOne = $this->pdo->prepare(
                'UPDATE article SET title=:title, category=:category, content=:content, image=:image, author=:author WHERE id=:id'
            );
            $this->statementDeleteOne = $this->pdo->prepare(
                'DELETE FROM article WHERE id=:id'
            );

            $this->statementReadUserAll = $this->pdo->prepare(
                'SELECT * FROM article WHERE author=:authorId'
            );
        }

    public function getAll() {
        $this->statementReadAll->execute();
        return $this->statementReadAll->fetchAll();
    }

    public function getArticlesForCurrentUser($userId) {
        $this->statementReadUserAll->bindValue(':authorId', $userId);
        $this->statementReadUserAll->execute();
        return $this->statementReadUserAll->fetchAll();
    }

    public function getOne(int $id) {
        $this->statementReadOne->bindValue(':id', $id);
        $this->statementReadOne->execute();
        return $this->statementReadOne->fetch();
    }

    public function createOne($article) {
        $this->statementCreateOne->bindValue(':title', $article['title']);
        $this->statementCreateOne->bindValue(':category', $article['category']);
        $this->statementCreateOne->bindValue(':content', $article['content']);
        $this->statementCreateOne->bindValue(':image', $article['image']);
        $this->statementCreateOne->bindValue(':author', $article['author']);
        $this->statementCreateOne->execute();
        //je renvoie l'article qui vient d'etre creer
        return $this->getOne($this->pdo->lastInsertid());
    }

    public function deleteOne(int $id) {
        $this->statementDeleteOne->bindValue(':id', $id);
        $this->statementDeleteOne->execute();
        return $id;
    }

    public function updateOne($article, $id) {
        $this->statementUpdateOne->bindValue(':title', $article['title']);
        $this->statementUpdateOne->bindValue(':category', $article['category']);
        $this->statementUpdateOne->bindValue(':content', $article['content']);
        $this->statementUpdateOne->bindValue(':image', $article['image']);
        $this->statementCreateOne->bindValue(':author', $article['author']);
        $this->statementUpdateOne->bindValue(':id', $id);
        $this->statementUpdateOne->execute();
        return $article;
        
    }


}

global $pdo;
return new ArticleDAO($pdo);