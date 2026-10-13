<?php
require 'functions.php';

$title = 'Login';

if (post()) {
    $email = input('email');
    $password = is_string($_POST['password'] ?? null)
        ? $_POST['password'] : '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($email) > 100 ||
        !valid($password, 72)) {
        $error = 'Enter a valid email and password.';
    } else {
        $user = query(
            'SELECT * FROM users WHERE email=?',
            [$email]
        )->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last'] = time();
            $_SESSION['token'] = bin2hex(random_bytes(32));

            done('Welcome, ' . $user['full_name'] . '!', home());
        }

        $error = 'Incorrect email or password.';
    }
}

require 'header.php';
?>

<section class="card auth">
    <h1>Login</h1>

    <form method="post">
        <?php token(); ?>

        <label for="email">Email Address</label>
        <input id="email" name="email" type="email" maxlength="100"
            value="<?= e(input('email')) ?>" autocomplete="username" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password"
            maxlength="72" autocomplete="current-password" required>

        <button class="btn btn-primary w-100">Login</button>
    </form>

    <p class="mt-3 mb-0">
        New student? <a href="register.php">Register here</a>
    </p>
</section>

<?php require 'footer.php'; ?>