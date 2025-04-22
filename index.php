<?php
// Set the cookie if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = trim($_POST['key'] ?? '');
    $value = trim($_POST['value'] ?? '');

    if ($key !== '' && $value !== '') {
        // Set cookie to expire in 30 days
        setcookie($key, $value, time() + (30 * 24 * 60 * 60));
        // Redirect to refresh and apply cookie
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cookie Creator</title>
</head>
<body>
    <h2>Create a Cookie</h2>
    <form method="post">
        <label>Key: <input type="text" name="key" required></label><br><br>
        <label>Value: <input type="text" name="value" required></label><br><br>
        <button type="submit">Create Cookie</button>
    </form>

    <h2>Existing Cookies</h2>
    <?php if (!empty($_COOKIE)): ?>
        <ul>
            <?php foreach ($_COOKIE as $k => $v): ?>
                <li><strong><?= htmlspecialchars($k) ?>:</strong> <?= htmlspecialchars($v) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No cookies set yet.</p>
    <?php endif; ?>
</body>
</html>
