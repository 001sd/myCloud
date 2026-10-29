<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// ZMIANA: Skrypt sam buduje ścieżkę na podstawie loginu, zamiast polegać na starej sesji
$base_dir = "users_cloud/" . $_SESSION['username'];

// Profilaktyczne tworzenie katalogu, gdyby użytkownik wszedł tu bezpośrednio z linku
if (!file_exists($base_dir)) {
    mkdir($base_dir, 0777, true);
}

$current_dir = $base_dir;

if (isset($_GET['dir'])) {
    $requested_dir = basename($_GET['dir']);
    if (is_dir($base_dir . '/' . $requested_dir)) {
        $current_dir = $base_dir . '/' . $requested_dir;
    }
}

if (isset($_FILES['fileToUpload'])) {
    $target_file = $current_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    header("Location: cloud.php" . ($current_dir != $base_dir ? "?dir=".basename($current_dir) : ""));
    exit;
}

if (isset($_POST['new_folder']) && $current_dir == $base_dir) {
    $new_folder_name = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['new_folder']);
    if (!empty($new_folder_name)) {
        @mkdir($base_dir . '/' . $new_folder_name, 0777, true);
    }
    header("Location: cloud.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/sd.png">
    <title>MyCloud - Dysk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="twoj_css.css?v=2">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="twoj_js.js?v=2"></script>
</head>
<body>

<?php require_once 'header.php'; ?>

<main class="container-fluid mt-5 pt-5">
    <section class="sekcja1">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Dysk użytkownika: <?php echo htmlspecialchars($_SESSION['username']); ?></h4>
            </div>
            <div class="card-body p-4">
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-success text-white">Prześlij plik</div>
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data">
                                    <input type="file" name="fileToUpload" class="form-control mb-3" required>
                                    <button type="submit" class="btn btn-success w-100">Wrzuć na serwer</button>
                                </form>
                            </div>
                        </div>

                        <?php if ($current_dir == $base_dir): ?>
                        <div class="card mb-4 border-secondary">
                            <div class="card-header bg-secondary text-white">Nowy folder</div>
                            <div class="card-body">
                                <form method="post">
                                    <input type="text" name="new_folder" class="form-control mb-3" placeholder="Nazwa folderu..." required>
                                    <button type="submit" class="btn btn-secondary w-100">Utwórz folder</button>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-8">
                        <div class="card border-dark">
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <span>Zawartość: <strong><?php echo str_replace($base_dir, 'Katalog Główny', $current_dir); ?></strong></span>
                                <?php if ($current_dir != $base_dir): ?>
                                    <a href="cloud.php" class="btn btn-sm btn-light">Wróć</a>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle m-0">
                                        <tbody>
                                            <?php
                                            if (file_exists($current_dir)) {
                                                $items = array_diff(scandir($current_dir), array('.', '..'));
                                                if (count($items) > 0) {
                                                    
                                                    // Najpierw wyświetlamy foldery
                                                    foreach ($items as $item) {
                                                        $path = $current_dir . '/' . $item;
                                                        if (is_dir($path)) {
                                                            echo "<tr>";
                                                            echo "<td><a href='?dir={$item}' class='text-decoration-none text-warning fw-bold fs-5'>📁 {$item}</a></td>";
                                                            echo "<td class='text-end'><a href='usun.php?item={$item}&type=dir' class='btn btn-sm btn-danger' onclick=\"return confirm('Usunąć ten folder i całą jego zawartość?');\">Usuń</a></td>";
                                                            echo "</tr>";
                                                        }
                                                    }

                                                    // Następnie wyświetlamy pliki z inteligentnym podglądem
                                                    foreach ($items as $item) {
                                                        $path = $current_dir . '/' . $item;
                                                        if (!is_dir($path)) {
                                                            $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                                                            
                                                            echo "<tr>";
                                                            echo "<td class='py-3'>";
                                                            echo "<div class='fw-bold text-info mb-2'>{$item}</div>";
                                                            
                                                            // Rozpoznawanie typów plików
                                                            if (in_array($ext, ['mp3', 'wav'])) {
                                                                echo "<audio controls src='{$path}' style='height: 40px; outline: none; width: 100%; max-width: 400px;'></audio>";
                                                            } elseif (in_array($ext, ['mp4', 'webm'])) {
                                                                echo "<video controls src='{$path}' style='max-height: 150px; max-width: 100%; border-radius: 8px;'></video>";
                                                            } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                                                echo "<a href='{$path}' target='_blank'><img src='{$path}' alt='{$item}' style='max-height: 90px; border-radius: 5px; object-fit: cover;'></a>";
                                                            } elseif ($ext == 'pdf') {
                                                                echo "<a href='{$path}' target='_blank' class='btn btn-sm btn-outline-info'>📄 Podgląd PDF</a>";
                                                            } else {
                                                                echo "<a href='{$path}' target='_blank' download class='btn btn-sm btn-outline-light'>⬇️ Pobierz plik</a>";
                                                            }
                                                            
                                                            echo "</td>";
                                                            
                                                            $link_dir = ($current_dir != $base_dir) ? "&subdir=".basename($current_dir) : "";
                                                            echo "<td class='text-end align-middle'><a href='usun.php?item={$item}&type=file{$link_dir}' class='btn btn-sm btn-danger' onclick=\"return confirm('Na pewno usunąć ten plik?');\">Usuń</a></td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='2' class='text-center text-muted py-4'>Twój katalog jest pusty.</td></tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<?php require_once 'footer.php'; ?>

</body>
</html>
