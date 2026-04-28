<?php
require 'db.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
if ($slug === '') {
    die('Article not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = (int)($_POST['article_id'] ?? 0);
    $authorName = trim($_POST['author_name'] ?? '');
    $authorEmail = trim($_POST['author_email'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($articleId > 0 && $authorName !== '' && $content !== '') {
        $stmt = $pdo->prepare("INSERT INTO comments (article_id, author_name, author_email, content, is_approved) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([$articleId, $authorName, $authorEmail ?: null, $content]);
        header('Location: article.php?slug=' . urlencode($slug));
        exit;
    }
}

$stmt = $pdo->prepare("
    SELECT a.*, c.name category_name, c.slug category_slug, au.name author_name, au.avatar author_avatar, au.bio author_bio
    FROM articles a
    JOIN categories c ON c.id = a.category_id
    JOIN authors au ON au.id = a.author_id
    WHERE a.slug = ? AND a.status = 'published'
    LIMIT 1
");
$stmt->execute([$slug]);
$article = $stmt->fetch();
if (!$article) {
    die('Article not found.');
}

$pdo->prepare("UPDATE articles SET views = views + 1 WHERE id = ?")->execute([$article['id']]);

$comments = $pdo->prepare("SELECT author_name, content, created_at FROM comments WHERE article_id = ? AND is_approved = 1 ORDER BY created_at DESC");
$comments->execute([$article['id']]);
$comments = $comments->fetchAll();

$pageTitle = $article['title'] . ' - AlgoWire';
$activePage = '';
require 'partials/header.php';
?>
<link rel="stylesheet" href="css/article.css">
<main class="main">
    <div class="container">
        <article class="article-page">
            <img src="<?= htmlspecialchars($article['cover_image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="article-page__cover">
            <header class="article-page__header">
                <div class="tags"><a href="category.php?slug=<?= urlencode($article['category_slug']) ?>" class="tag tag--filled"><?= htmlspecialchars($article['category_name']) ?></a></div>
                <h1 class="article-page__title"><?= htmlspecialchars($article['title']) ?></h1>
                <div class="meta">
                    <div class="meta__author"><img src="<?= htmlspecialchars($article['author_avatar']) ?>" class="meta__avatar"><span><?= htmlspecialchars($article['author_name']) ?></span></div>
                    <div class="meta__item"><?= (int)$article['read_time'] ?> min read</div>
                    <div class="meta__item"><?= number_format((int)$article['views'] + 1) ?> views</div>
                </div>
            </header>
            <div class="article-page__content"><?= $article['content'] ?></div>

            <div class="author-box">
                <img src="<?= htmlspecialchars($article['author_avatar']) ?>" class="author-box__avatar">
                <div>
                    <h4 class="author-box__name"><?= htmlspecialchars($article['author_name']) ?></h4>
                    <p class="author-box__bio"><?= htmlspecialchars($article['author_bio']) ?></p>
                </div>
            </div>

            <section class="comments">
                <h3 class="comments__title">Comments (<?= count($comments) ?>)</h3>
                <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="comment__header">
                        <span class="comment__author"><?= htmlspecialchars($comment['author_name']) ?></span>
                        <span class="comment__date"><?= date('Y-m-d', strtotime($comment['created_at'])) ?></span>
                    </div>
                    <p class="comment__body"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                </div>
                <?php endforeach; ?>

                <div class="comment-form">
                    <h4 class="comment-form__title">Leave a Comment</h4>
                    <form method="post" action="article.php?slug=<?= urlencode($article['slug']) ?>">
                        <input type="hidden" name="article_id" value="<?= (int)$article['id'] ?>">
                        <div class="form-row">
                            <div class="form-group"><label for="author_name">Name</label><input type="text" id="author_name" name="author_name" required></div>
                            <div class="form-group"><label for="author_email">Email</label><input type="email" id="author_email" name="author_email"></div>
                        </div>
                        <div class="form-group"><label for="content">Comment</label><textarea id="content" name="content" required></textarea></div>
                        <button type="submit" class="btn btn--primary">Post Comment</button>
                    </form>
                </div>
            </section>
        </article>
    </div>
</main>
<?php require 'partials/footer.php'; ?>
