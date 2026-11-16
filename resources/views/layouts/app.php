<?php /** @var string $isi */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($judul ?? 'Program') ?> - Preloved.U</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(url('style.css')) ?>">
  <link rel="stylesheet" href="<?= e(url('program.css')) ?>">
</head>
<body>
  <header>
    <div class="logo"><span class="logo-icon">🛍️</span><span>Preloved.U</span></div>
    <nav>
      <ul>
        <li><a href="<?= e(url('index.php')) ?>">Beranda</a></li>
        <li><a href="<?= e(url('programs')) ?>">Program</a></li>
      </ul>
    </nav>
  </header>

  <main class="program-page">
    <?= $isi ?>
  </main>

  <footer><p>&copy; <?= date('Y') ?> Preloved.U</p></footer>
</body>
</html>
