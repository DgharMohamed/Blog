<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require '../db.php';

function make_slug($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
    $text = trim($text, '-');
    return $text ?: 'article';
}

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$authors = $pdo->query("SELECT id, name FROM authors ORDER BY name")->fetchAll();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $coverImage = trim($_POST['cover_image'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $authorId = (int)($_POST['author_id'] ?? 0);
    $readTime = (int)($_POST['read_time'] ?? 5);
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';

    if ($title === '' || $excerpt === '' || $content === '' || $categoryId <= 0 || $authorId <= 0) {
        $error = 'Please fill all required fields.';
    } else {
        $baseSlug = make_slug($title);
        $slug = $baseSlug;
        $i = 1;
        $check = $pdo->prepare("SELECT id FROM articles WHERE slug = ? LIMIT 1");
        while (true) {
            $check->execute([$slug]);
            if (!$check->fetch()) {
                break;
            }
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        $stmt = $pdo->prepare("
            INSERT INTO articles
            (title, slug, content, excerpt, cover_image, category_id, author_id, is_featured, views, read_time, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?)
        ");
        $stmt->execute([$title, $slug, $content, $excerpt, $coverImage, $categoryId, $authorId, $isFeatured, $readTime, $status]);
        $message = 'Article added successfully.';
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AlgoWire</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/components.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<div class="admin-layout">
    <header class="admin-header">
        <h1 class="admin-header__title"><span>&gt;_</span> AlgoWire Admin</h1>
        <div style="display:flex;align-items:center;gap:12px;">
            <nav class="admin-nav">
                <a href="../index.php">View Blog</a>
                <a href="dashboard.php" class="active">New Article</a>
                <a href="logout.php">Logout</a>
            </nav>
            <button class="navbar__icon-btn" id="themeToggle" aria-label="Toggle theme">
                <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>
        </div>
    </header>

    <main class="admin-content">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__value"><?= count($categories) ?></div>
                <div class="stat-card__label">Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__value"><?= count($authors) ?></div>
                <div class="stat-card__label">Authors</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__value">1</div>
                <div class="stat-card__label">Logged Admin</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__value">+</div>
                <div class="stat-card__label">Create Post</div>
            </div>
        </div>

        <section style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:var(--space-xl);">
            <h2 style="margin-bottom:var(--space-lg);">Add New Article</h2>
            <?php if ($message): ?><p style="color:#4ade80; margin:0 0 12px;"><?= htmlspecialchars($message) ?></p><?php endif; ?>
            <?php if ($error): ?><p style="color:#ff6b6b; margin:0 0 12px;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="excerpt">Excerpt *</label>
                <textarea id="excerpt" name="excerpt" required></textarea>
            </div>
            <div class="form-group">
                <label for="content">Content (HTML allowed) *</label>
                <textarea id="content" name="content" rows="10" required></textarea>
            </div>
            <div class="form-group">
                <label for="cover_image">Cover Image URL</label>
                <input type="text" id="cover_image" name="cover_image">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="category_id">Category *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="author_id">Author *</label>
                    <select id="author_id" name="author_id" required>
                        <option value="">Select author</option>
                        <?php foreach ($authors as $author): ?>
                            <option value="<?= (int)$author['id'] ?>"><?= htmlspecialchars($author['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="read_time">Read Time (min)</label>
                    <input type="number" id="read_time" name="read_time" min="1" value="5">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="is_featured" value="1"> Featured article</label>
            </div>
            <button type="submit" class="btn btn--primary">Publish Article</button>
        </form>
        </section>
    </main>
</div>
<script src="../js/main.js"></script>
</body>
</html>
