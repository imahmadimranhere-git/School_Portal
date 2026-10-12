<?php
require 'config.php';
$title = 'Dashboard';
$counts = [
  'Students'    => ['students.php', 'bi-people-fill',            $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn()],
  'Teachers'    => ['teachers.php', 'bi-person-badge-fill',      $pdo->query('SELECT COUNT(*) FROM teachers')->fetchColumn()],
  'Courses'     => ['courses.php',  'bi-journal-bookmark-fill',  $pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn()],
  'Enrollments' => ['enroll.php',   'bi-clipboard2-check-fill',  $pdo->query('SELECT COUNT(*) FROM enrollments')->fetchColumn()],
];
$recent = $pdo->query(
    'SELECT s.name AS student, c.code, c.title, e.enrolled_at
     FROM enrollments e
     JOIN students s ON s.id = e.student_id
     JOIN courses c ON c.id = e.course_id
     ORDER BY e.id DESC LIMIT 5'
)->fetchAll();
require 'header.php';
?>
<h1 class="h3 page-title"><i class="bi bi-grid-1x2-fill"></i> Dashboard</h1>

<div class="row g-3 mb-4">
  <?php foreach ($counts as $label => [$url, $icon, $n]): ?>
    <div class="col-6 col-lg-3">
      <a class="stat-link" href="<?= $url ?>">
        <div class="panel stat-card">
          <div class="stat-icon"><i class="bi <?= $icon ?>"></i></div>
          <div>
            <div class="stat"><?= (int)$n ?></div>
            <div class="text-muted"><?= $label ?></div>
          </div>
        </div>
      </a>
    </div>
  <?php endforeach; ?>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="panel">
      <h2><i class="bi bi-clock-history"></i> Latest enrollments</h2>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead><tr><th>Student</th><th>Course</th><th>Date</th></tr></thead>
          <tbody>
          <?php foreach ($recent as $r): ?>
            <tr>
              <td><?= e($r['student']) ?></td>
              <td><span class="code-chip"><?= e($r['code']) ?></span> <?= e($r['title']) ?></td>
              <td class="text-muted"><?= e(date('d M Y', strtotime($r['enrolled_at']))) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$recent): ?>
            <tr><td colspan="3" class="text-muted">Abhi koi enrollment nahi. Enrollments page se pehla student enroll karein.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="panel">
      <h2><i class="bi bi-lightning-charge-fill"></i> Quick actions</h2>
      <div class="d-grid gap-2">
        <a href="students.php" class="btn btn-ghost text-start"><i class="bi bi-person-plus-fill me-2"></i> Register student</a>
        <a href="teachers.php" class="btn btn-ghost text-start"><i class="bi bi-person-vcard-fill me-2"></i> Register teacher</a>
        <a href="courses.php"  class="btn btn-ghost text-start"><i class="bi bi-journal-plus me-2"></i> Add course</a>
        <a href="enroll.php"   class="btn btn-primary text-start"><i class="bi bi-clipboard2-plus-fill me-2"></i> Enroll student</a>
      </div>
    </div>
  </div>
</div>
<?php require 'footer.php'; ?>
