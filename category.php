<?php
require 'db.php';
$activePage = 'category';
$pageTitle = 'Categories - AlgoWire';

$selectedSlug = $_GET['slug'] ?? '';

$categories = $pdo->query("
    SELECT c.id, c.name, c.slug, COUNT(a.id) total
    FROM categories c
    LEFT JOIN articles a ON a.category_id = c.id AND a.status = 'published'
    GROUP BY c.id
    ORDER BY c.name
")->fetchAll();

$selectedCategory = null;
if ($selectedSlug !== '') {
    $stmt = $pdo->prepare("SELECT id, name, slug FROM categories WHERE slug = ? LIMIT 1");
    $stmt->execute([$selectedSlug]);
    $selectedCategory = $stmt->fetch();
}

if ($selectedCategory) {
    $stmt = $pdo->prepare("
        SELECT a.*, c.name category_name, c.slug category_slug
        FROM articles a
        JOIN categories c ON c.id = a.category_id
        WHERE a.status = 'published' AND a.category_id = ?
        ORDER BY a.created_at DESC
    ");
    $stmt->execute([$selectedCategory['id']]);
    $posts = $stmt->fetchAll();
} else {
    $posts = $pdo->query("
        SELECT a.*, c.name category_name, c.slug category_slug
        FROM articles a
        JOIN categories c ON c.id = a.category_id
        WHERE a.status = 'published'
        ORDER BY a.created_at DESC
    ")->fetchAll();
}

require 'partials/header.php';
?>
<link rel="stylesheet" href="css/cards.css">
<link rel="stylesheet" href="css/authors.css">
<main class="main">
    <div class="container">
        <div class="section-header" style="margin-top: 40px;">
            <h2 class="section-title">Categories</h2>
            <span class="section-count"><?= count($categories) ?> categories</span>
        </div>
        <div class="authors-grid" style="margin-bottom: 48px;">
            <?php foreach ($categories as $cat): ?>
            <div class="author-card fade-in" style="text-align: left;">
                <h3 class="author-card__name" style="color: var(--accent);"><a href="category.php?slug=<?= urlencode($cat['slug']) ?>"><?= htmlspecialchars($cat['name']) ?></a></h3>
                <span class="author-card__stats"><?= (int)$cat['total'] ?> articles</span>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="section-header">
            <h2 class="section-title"><?= $selectedCategory ? htmlspecialchars($selectedCategory['name']) : 'All Posts' ?></h2>
        </div>
        <div class="articles-grid">
            <?php foreach ($posts as $post): ?>
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
</main>
<?php require 'partials/footer.php'; ?>
