<?php
require 'db.php';
$pageTitle = 'Home - AlgoWire';
$activePage = 'index';

$featured = $pdo->query("
    SELECT a.*, c.name category_name, c.slug category_slug, au.name author_name, au.avatar author_avatar
    FROM articles a
    JOIN categories c ON c.id = a.category_id
    JOIN authors au ON au.id = a.author_id
    WHERE a.status = 'published' AND a.is_featured = 1
    ORDER BY a.created_at DESC
    LIMIT 3
")->fetchAll();

$latest = $pdo->query("
    SELECT a.*, c.name category_name, c.slug category_slug
    FROM articles a
    JOIN categories c ON c.id = a.category_id
    WHERE a.status = 'published'
    ORDER BY a.created_at DESC
    LIMIT 8
")->fetchAll();

$categories = $pdo->query("
    SELECT c.name, c.slug, COUNT(a.id) total
    FROM categories c
    LEFT JOIN articles a ON a.category_id = c.id AND a.status = 'published'
    GROUP BY c.id
    ORDER BY total DESC
")->fetchAll();

require 'partials/header.php';
?>
<link rel="stylesheet" href="css/cards.css">
<main class="main">
    <div class="container">
        <section class="featured">
            <div class="section-header"><h2 class="section-title">Featured Articles</h2></div>
            <?php if (!empty($featured)): $hero = $featured[0]; ?>
            <div class="featured__grid">
                <article class="card-hero fade-in">
                    <img src="<?= htmlspecialchars($hero['cover_image']) ?>" alt="<?= htmlspecialchars($hero['title']) ?>" class="card-hero__image">
                    <div class="card-hero__overlay"></div>
                    <div class="card-hero__content">
                        <div class="tags">
                            <a href="category.php?slug=<?= urlencode($hero['category_slug']) ?>" class="tag tag--filled"><?= htmlspecialchars($hero['category_name']) ?></a>
                            <span class="tag tag--outlined">Featured</span>
                        </div>
                        <h3 class="card-hero__title"><a href="article.php?slug=<?= urlencode($hero['slug']) ?>"><?= htmlspecialchars($hero['title']) ?></a></h3>
                        <p class="card-hero__excerpt"><?= htmlspecialchars($hero['excerpt']) ?></p>
                        <div class="meta">
                            <div class="meta__author"><img src="<?= htmlspecialchars($hero['author_avatar']) ?>" class="meta__avatar"><span><?= htmlspecialchars($hero['author_name']) ?></span></div>
                            <div class="meta__item"><?= (int)$hero['read_time'] ?> min read</div>
                            <div class="meta__item"><?= number_format((int)$hero['views']) ?> views</div>
                        </div>
                    </div>
                </article>
                <div class="featured__sidebar">
                    <?php foreach (array_slice($featured, 1) as $item): ?>
                    <article class="card-side fade-in">
                        <div class="card-side__image-wrap">
                            <img src="<?= htmlspecialchars($item['cover_image']) ?>" class="card-side__image" alt="<?= htmlspecialchars($item['title']) ?>">
                        </div>
                        <div class="card-side__content">
                            <h4 class="card-side__title"><a href="article.php?slug=<?= urlencode($item['slug']) ?>"><?= htmlspecialchars($item['title']) ?></a></h4>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </section>

        <section class="latest-posts">
            <div class="content-grid">
                <div>
                    <div class="section-header"><h2 class="section-title">Latest Posts</h2><span class="section-count"><?= count($latest) ?> articles</span></div>
                    <div class="articles-grid">
                        <?php foreach ($latest as $post): ?>
                        <article class="card-grid fade-in">
                            <div class="card-grid__image-wrap">
                                <img src="<?= htmlspecialchars($post['cover_image']) ?>" class="card-grid__image" alt="<?= htmlspecialchars($post['title']) ?>">
                                <div class="card-grid__tag"><a href="category.php?slug=<?= urlencode($post['category_slug']) ?>" class="tag tag--filled"><?= htmlspecialchars($post['category_name']) ?></a></div>
                            </div>
                            <div class="card-grid__content">
                                <h3 class="card-grid__title"><a href="article.php?slug=<?= urlencode($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                                <p class="card-grid__excerpt"><?= htmlspecialchars($post['excerpt']) ?></p>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <aside class="sidebar">
                    <div class="widget">
                        <h3 class="widget__title">Categories</h3>
                        <ul class="categories-list">
                            <?php foreach ($categories as $cat): ?>
                            <li class="categories-list__item">
                                <a href="category.php?slug=<?= urlencode($cat['slug']) ?>"><?= htmlspecialchars($cat['name']) ?></a>
                                <span class="categories-list__count"><?= (int)$cat['total'] ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
            </div>
        </section>
    </div>
</main>
<?php require 'partials/footer.php'; ?>
