<pre>
<?php

    require __DIR__.'/database/database.php';
    /**
     * @var AuthDAO
     */
    $authDAO = require './database/models/AuthDAO.php';
    $currentUser = $authDAO->isLoggedIn();

    $articleDAO = require './database/models/ArticleDAO.php';
    $articles = $articleDAO->getAll();

    $categories =[];
    $selectedCategory = '';

    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $selectedCategory = $_GET['cat'] ?? '';

    if(count($articles)) {
        $catmap = array_map(fn ($article) => $article['category'], $articles);
        $categories = array_reduce($catmap, function ($acc, $cat) {
            if(isset($acc[$cat])) {
                $acc[$cat] ++;
            } else {
                $acc[$cat] = 1;
            }
            return $acc;
        }, []);

        $articlesPerCategory = array_reduce($articles, function($acc, $article) {
            if(isset($acc[$article['category']])) {
                $acc[$article['category']] = [...$acc[$article['category']], $article];
            } else {
                $acc[$article['category']] = [$article];
            }
            return $acc;
        }, []);

        // print_r($articlesPerCategory);  
        //articlespercayegory = {'technologie' : [{}, {}, {}]},
        //articlespercayegory = {'nature' : [{}, {}, {}]},
        //articlespercayegory = {'politique' : [{}, {}, {}]},

    }
?>
</pre>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "includes/head.php" ?>
    <link rel="stylesheet" href="public/css/index.css">
    <title>Blog App</title>
</head>
<body>
    <div class="container">
        <?php require_once "includes/header.php" ?>
        <div class="content">
            <div class="newsfeed-container">
                <ul class="category-container">
                    <li class=<?= $selectedCategory ? '' : 'cat-active' ?>><a href="/">
                        Tous les articles <span class="small">( <?= count($articles) ?> )</span>
                    </a></li>
                    <?php foreach($categories as $category => $nbArticles) : ?>
                        <li class=<?= $selectedCategory === $category ? 'cat-active' : '' ?>><a href="/?cat=<?= $category ?>">
                            <?= $category ?> <span class="small">( <?= $nbArticles ?> )</span>
                        </a></li>
                    <?php endforeach; ?>
                    
                </ul>
                <div class="feed-container">
                    <?php if(!$selectedCategory) : ?>
                        <?php foreach ($categories as $category => $num) : ?>
                            <h1 class="p-10"><?= $category ?> </h1>
                            <div class="articles-container">
                                <?php foreach($articlesPerCategory[$category] as $article) :  ?>
                                    <a href="/show-article.php?id=<?= $article['id'] ?>" class="article block">
                                        <div class="overflow">
                                            <div class="img-container" style="background-image: url(<?= $article['image'] ?>)"  ></div>
                                        </div>
                                        <h2><?= $article['title'] ?></h2>
                                        <div class="article-author">
                                            <p><?= $article['firstname'].' '.$article['lastname'] ?></p>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <h1><?= $selectedCategory ?></h1>
                        <div class="articles-container">
                            <?php foreach($articlesPerCategory[$selectedCategory] as $article) : ?>
                                <a href="/show-article.php?id=<?= $article['id'] ?>" class="article block">
                                    <div class="overflow">
                                        <div class="img-container" style="background-image: url(<?= $article['image'] ?>)"></div>
                                    </div>
                                    <h2><?= $article['title'] ?></h2>
                                    <div class="article-author">
                                        <p><?= $article['firstname'].' '.$article['lastname'] ?></p>
                                    </div>
                                </a>
                            <?php endforeach ; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            
        </div>
        <?php require_once "includes/footer.php" ?>
    </div>
</body>
</html>