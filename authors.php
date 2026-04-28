<?php
require 'db.php';
$pageTitle = 'Authors - AlgoWire';
$activePage = 'authors';

$authors = $pdo->query("
    SELECT au.*, COUNT(a.id) total_articles
    FROM authors au
    LEFT JOIN articles a ON a.author_id = au.id AND a.status = 'published'
    GROUP BY au.id
    ORDER BY total_articles DESC, au.name
")->fetchAll();

require 'partials/header.php';
?>
<link rel="stylesheet" href="css/authors.css">
<main class="main">
    <div class="container">
        <div class="section-header" style="margin-top: 40px;">
            <h2 class="section-title">Our Authors</h2>
            <span class="section-count"><?= count($authors) ?> contributors</span>
        </div>
        <div class="authors-grid" style="grid-template-columns: repeat(2, 1fr);">
            <?php foreach ($authors as $author): ?>
            <div class="author-card fade-in">
                <img src="<?= htmlspecialchars($author['avatar']) ?>" alt="<?= htmlspecialchars($author['name']) ?>" class="author-card__avatar">
                <h3 class="author-card__name"><?= htmlspecialchars($author['name']) ?></h3>
                <p class="author-card__bio"><?= htmlspecialchars($author['bio']) ?></p>
                <span class="author-card__stats"><?= (int)$author['total_articles'] ?> articles</span>
                <?php if (!empty($author['github'])): ?>
                <a href="https://github.com/<?= urlencode($author['github']) ?>" target="_blank" class="meta__item" style="margin-top: 12px; display: inline-flex; color: var(--accent);">@<?= htmlspecialchars($author['github']) ?></a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<?php require 'partials/footer.php'; ?>
