<?php
require_once 'auth_config.php';

$error = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic Validation
    if (empty($username) || strlen($username) < 3) {
        $error = 'Der Benutzername muss mindestens 3 Zeichen lang sein.';
    } elseif (empty($password) || strlen($password) < 6) {
        $error = 'Das Passwort muss mindestens 6 Zeichen lang sein.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Email is optional but must be valid if provided
        if (!empty($email)) {
            $error = 'Die E-Mail-Adresse ist ungültig.';
        }
    } else {
        try {
            $db = getDB();
            
            // Check if user already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = 'Dieser Benutzername ist bereits vergeben.';
            } else {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password, 'pilot']);
                
                // Success! Redirect to login or welcome
                header('Location: login.php?registered=true');
                exit;
            }
        } catch (PDOException $e) {
            error_log("Registration Error: " . $e->getMessage());
            $error = 'Ein Fehler ist beim Registrieren aufgetreten.';
        }
    }
}

include 'header.php';
?>
<main>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="col-md-6">
                <div class="card p-4 shadow-lg border-0">
                    <h2 class="text-center mb-4">Registrierung</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Benutzername</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Mail (optional)</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Passwort</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Registrieren</button>
                    </form>
                    <div class="mt-3 text-center">
                        <span>Bereits ein Konto? <a href="login.php">Anmelden</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
```TO_FILE: runwayhub/register.php