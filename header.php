<?php declare(strict_types=1); /* Ta linia musi być pierwsza */ ?>
<header>
<div class="container">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top mt-0 mb-0 ms-0 me-0 pt-0 pb-0 ps-0 pe-0">
<div class="container-fluid">
<div class="collapse navbar-collapse" id="main_nav">
<ul class="navbar-nav me-auto">
	
	<li class="nav-item me-2">
        <a class="btn btn-outline-light btn-sm mt-1" href="/index.php">
            ← Katalog Zadań
        </a>
    </li>
    <!-- Zakładka Strona główna -->
    <li class="nav-item">
        <a class="nav-link active" href="home.php">Strona główna</a>
    </li>

    <!-- NOWA ZAKŁADKA: Geolokalizacja -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Geolokalizacja</a>
        <ul class="dropdown-menu shadow">
            <li><a class="dropdown-item" href="index1.php">1. Informacje PHP (phpinfo)</a></li>
            <li><a class="dropdown-item" href="index2.php">2. Użytkownik (whoami)</a></li>
            <li><a class="dropdown-item" href="index3.php">3. Procesy serwera (top)</a></li>
            <li><a class="dropdown-item" href="index4.php">4. Lista plików (ls -al)</a></li>
            <li><a class="dropdown-item" href="index5.php">5. Rekordy DNS</a></li>
            <li><a class="dropdown-item" href="index6.php">6. Geolokalizacja IP (JSON)</a></li>
            <!-- Pozioma linia oddzielająca -->
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-primary fw-bold" href="wizyty.php">Rejestr wizyt portalu</a></li>
        </ul>
    </li>
</ul>

<!-- Czerwony przycisk wylogowania -->
<div class="d-flex">
    <a href="logout.php" class="btn btn-danger">Wyloguj się</a>
</div>
</div>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>

</div>
</nav>
</div>
</header>