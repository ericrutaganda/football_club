<?php
declare(strict_types=1);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

$message = '';
$error = '';
$adminPasswordReset = false;
$defaultCredentialsAvailable = false;
try {
    $pdo = Database::connection();
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(150) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','staff') NOT NULL DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $userColumns = $pdo->query('SHOW COLUMNS FROM users')->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('password', $userColumns, true)) {
        $pdo->exec('ALTER TABLE users ADD COLUMN password VARCHAR(255) NULL');
        if (in_array('password_hash', $userColumns, true)) {
            $pdo->exec("UPDATE users SET password = password_hash WHERE password_hash IS NOT NULL AND password_hash <> ''");
        }
    }
    if (in_array('password', $userColumns, true) && in_array('password_hash', $userColumns, true)) {
        $pdo->exec("UPDATE users SET password = password_hash WHERE (password IS NULL OR password = '') AND password_hash IS NOT NULL AND password_hash <> ''");
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS players (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(150) NOT NULL,
        position VARCHAR(50) NOT NULL,
        jersey_no INT NOT NULL,
        dob DATE NULL,
        phone VARCHAR(30) NULL,
        status ENUM('active','injured','inactive') NOT NULL DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS matches (
        id INT AUTO_INCREMENT PRIMARY KEY,
        opponent VARCHAR(150) NOT NULL,
        match_date DATETIME NOT NULL,
        venue VARCHAR(150) NOT NULL,
        competition VARCHAR(120) NULL,
        our_score INT NULL,
        opponent_score INT NULL,
        status ENUM('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS trainings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(150) NOT NULL,
        training_date DATETIME NOT NULL,
        location VARCHAR(150) NOT NULL,
        coach VARCHAR(120) NULL,
        notes TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $idColumn = in_array('id', $userColumns, true) ? 'id' : (in_array('user_id', $userColumns, true) ? 'user_id' : null);
    if ($idColumn === null) {
        throw new RuntimeException('The users table must have an id or user_id column.');
    }
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['Rutaganda Eric']);
    $admin = $stmt->fetch();
    if (!$admin) {
        $hash = password_hash('123456789', PASSWORD_DEFAULT);
        if (in_array('password_hash', $userColumns, true)) {
            $stmt = $pdo->prepare("INSERT INTO users (username,email,password,password_hash,role) VALUES (?,?,?,?, 'admin')");
            $stmt->execute(['Rutaganda Eric', 'rutagandaeric22@gmail.com', $hash, $hash]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username,email,password,role) VALUES (?,?,?, 'admin')");
            $stmt->execute(['Rutaganda Eric', 'rutagandaeric22@gmail.com', $hash]);
        }
        $defaultCredentialsAvailable = true;
    } elseif (!isset($admin['password']) || !is_string($admin['password']) || $admin['password'] === '') {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE {$idColumn} = ?");
        $stmt->execute([password_hash('123456789', PASSWORD_DEFAULT), $admin[$idColumn]]);
        $adminPasswordReset = true;
        $defaultCredentialsAvailable = true;
    }
    $message = 'Installation completed successfully. Your database tables and administrator account are ready.';
} catch (Throwable $e) {
    error_log('Installation failed: ' . $e->getMessage());
    $error = APP_DEBUG
        ? 'Installation failed: ' . $e->getMessage()
        : 'Installation failed. Check the server error log and database settings.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Football Club Installer</title><link rel="stylesheet" href="public/css/style.css"></head>
<body class="auth-page"><main class="auth-card"><h1>Football Club Setup</h1>
<?php if ($message): ?>
<div class="alert success"><?= htmlspecialchars($message) ?></div>
<?php if ($defaultCredentialsAvailable): ?>
<?php if ($adminPasswordReset): ?><p class="muted">The administrator account had no password hash. Its password is now the initial password shown below.</p><?php endif; ?>
<p><b>Username:</b> Rutaganda Eric<br><b>Password:</b> 123456789</p>
<p class="muted">Change this password after login.</p>
<?php else: ?>
<p class="muted">The existing administrator account was preserved. Its password was not changed.</p>
<?php endif; ?>
<p><a class="btn" href="index.php">Go to login</a></p><p class="muted">For security, delete or rename install.php after installation.</p>
<?php else: ?>
<div class="alert danger"><?= htmlspecialchars($error) ?></div><p>Check your database settings in <code>config/config.php</code>.</p>
<?php endif; ?>
</main></body></html>
