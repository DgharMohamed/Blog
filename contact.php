<?php
$pageTitle = 'Contact - AlgoWire';
$activePage = 'contact';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name !== '' && $email !== '' && $message !== '') {
        $success = true;
    }
}

require 'partials/header.php';
?>
<link rel="stylesheet" href="css/contact.css">
<main class="main">
    <div class="container">
        <section class="contact-page">
            <div class="contact-grid">
                <div class="contact-content">
                    <h1 class="contact-title">Let's Connect</h1>
                    <p class="contact-description">Send us a message and we will get back to you.</p>
                    <?php if ($success): ?>
                    <p class="contact-description" style="color:#4ade80;">Message sent successfully.</p>
                    <?php endif; ?>
                </div>
                <div class="contact-form-wrap">
                    <form class="contact-form" method="post" action="contact.php">
                        <div class="form-group"><label for="name">Name</label><input type="text" id="name" name="name" required></div>
                        <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
                        <div class="form-group"><label for="message">Message</label><textarea id="message" name="message" rows="5" required></textarea></div>
                        <button type="submit" class="btn btn--primary contact-submit">Send Message</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</main>
<?php require 'partials/footer.php'; ?>
