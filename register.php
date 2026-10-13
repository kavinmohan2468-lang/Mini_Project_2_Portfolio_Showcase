<?php
require 'functions.php';

$title = 'Create Account';

if (post()) {
    $name = input('full_name');
    $email = input('email');
    $password = is_string($_POST['password'] ?? null)
        ? $_POST['password'] : '';
    $confirm = is_string($_POST['confirm'] ?? null)
        ? $_POST['confirm'] : '';

    if (!valid($name, 100) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($email) > 100) {
        $error = 'Enter your full name and a valid email.';
    } elseif (
        strlen($password) < 8 ||
        strlen($password) > 72 ||
        str_contains($password, "\0") ||
        trim($password) === '' ||
        $password !== $confirm
    ) {
        $error = 'Use 8-72 password bytes and matching passwords.';
    } else {
        try {
            query(
                'INSERT INTO users (full_name,email,password) VALUES (?,?,?)',
                [$name, $email, password_hash($password, PASSWORD_DEFAULT)]
            );

            done('Registration successful. Please log in.', 'login.php');
        } catch (PDOException $e) {
            $error = $e->getCode() == '23000'
                ? 'This email is already registered.'
                : 'Registration failed. Try again.';
        }
    }
}

require 'header.php';
?>

<section class="card auth">
    <h1>Create Account</h1>

    <form method="post">
        <?php token(); ?>

        <label for="full_name">Full Name</label>
        <input id="full_name" name="full_name" maxlength="100"
            value="<?= e(input('full_name')) ?>" required>

        <label for="email">Email Address</label>
        <input id="email" name="email" type="email" maxlength="100"
            value="<?= e(input('email')) ?>" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password"
            minlength="8" maxlength="72" autocomplete="new-password" required>

        <label for="confirm">Confirm Password</label>
        <input id="confirm" name="confirm" type="password"
            minlength="8" maxlength="72" autocomplete="new-password" required>

        <button class="btn btn-primary w-100">Register</button>
    </form>

    <p class="mt-3 mb-0">
        Already registered? <a href="login.php">Log in</a>
    </p>
</section>

<?php require 'footer.php'; ?>