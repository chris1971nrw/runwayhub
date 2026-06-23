<?php
require_once 'auth_config.php'; // Sicherstellen, dass die Session gestartet wird

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

        if (authenticate($username, $password)) {
        $target = (strtolower(trim($_SESSION['role'])) === 'admin') ? 'dashboard.php' : 'index.php';
        header('Location: ' . $target);
        exit;
    } else {
        $error = 'Login failed or user not found.';
    }
}


include 'header.php';
?>
<main>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="col-md-4">
                <div class="card p-4 shadow-lg border-0">
                    <h2 class="text-center mb-4">Login</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Benutzername</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Passwort</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Anmelden</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
