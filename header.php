<?php
$current = basename($_SERVER['PHP_SELF']);
$links = [
  'index.php'    => ['Dashboard',   'bi-grid-1x2-fill'],
  'students.php' => ['Students',    'bi-people-fill'],
  'teachers.php' => ['Teachers',    'bi-person-badge-fill'],
  'courses.php'  => ['Courses',     'bi-journal-bookmark-fill'],
  'enroll.php'   => ['Enrollments', 'bi-clipboard2-check-fill'],
];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'School Portal') ?> | School Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="assets/style.css" rel="stylesheet">
</head>
<body>

<div class="container pt-3">
  <nav class="navbar navbar-expand-lg pillbar">
    <a class="navbar-brand" href="index.php">
      <span class="brand-badge"><i class="bi bi-mortarboard-fill"></i></span>
      School Portal
    </a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Menu">
      <i class="bi bi-list fs-2"></i>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto gap-lg-1 mt-3 mt-lg-0">
        <?php foreach ($links as $file => [$label, $icon]): ?>
          <li class="nav-item">
            <a class="nav-link <?= $current === $file ? 'active' : '' ?>" href="<?= $file ?>">
              <i class="bi <?= $icon ?>"></i> <?= $label ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </nav>
</div>

<main class="container py-4">
<?php if (!empty($_SESSION['flash'])): [$m, $t] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
  <div class="alert alert-<?= e($t) ?> alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
    <i class="bi <?= $t === 'danger' ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' ?>"></i>
    <div><?= e($m) ?></div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>
