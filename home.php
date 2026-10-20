<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$avatar = $_SESSION['avatar'] ?? 'default-avatar.svg';
$username = $_SESSION['username'] ?? 'Użytkownik';

// Zabezpieczenie dla STARYCH UŻYTKOWNIKÓW
// Jeśli użytkownik z jakiegoś powodu nie ma katalogu, stwórz go teraz
if (isset($_SESSION['user_dir'])) {
    if (!file_exists($_SESSION['user_dir'])) {
        mkdir($_SESSION['user_dir'], 0777, true);
    }
}
?>
<!DOCTYPE html>
<html lang="pl" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/sd.png">
    <title>Strona główna</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="twoj_css.css?v=2">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php require_once 'header.php'; ?>

<main class="container">
    <section class="sekcja1">
        <h1>Strona główna</h1>
        <h2>Zadanie 5 - myCloud</h2>

        <div class="card mt-4 mb-4 shadow-sm border-0">
            <div class="card-body text-center p-5">
                <img src="media/avatars/<?php echo htmlspecialchars(basename($avatar)); ?>"
                     alt="Avatar użytkownika" class="avatar mb-3" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
                <h3 class="mt-3">Witaj, <?php echo htmlspecialchars($username); ?>!</h3>
                <p class="text-muted">Jesteś zalogowany. Możesz teraz korzystać z menu.</p>
                <a href="cloud.php" class="btn btn-primary mt-3">Przejdź do dysku myCloud</a>
            </div>
        </div>

    </section>
</main>

<?php require_once 'footer.php'; ?>

</body>
</html>