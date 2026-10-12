<?php
require 'config.php';
$title = 'Enrollments';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $pdo->prepare('DELETE FROM enrollments WHERE id = ?')->execute([(int)$_POST['delete']]);
        flash('Enrollment khatam kar di gayi.');
    } else {
        $sid = (int)($_POST['student_id'] ?? 0);
        $cid = (int)($_POST['course_id'] ?? 0);
        if (!$sid || !$cid) {
            flash('Student aur course dono select karein.', 'danger');
        } else {
            try {
                $pdo->prepare('INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)')->execute([$sid, $cid]);
                flash('Student course mein enroll ho gaya.');
            } catch (PDOException $ex) {
                flash('Ye student is course mein pehle se enrolled hai.', 'danger');
            }
        }
    }
    redirect('enroll.php');
}

$students = $pdo->query('SELECT id, name FROM students ORDER BY name')->fetchAll();
$courses  = $pdo->query('SELECT id, code, title FROM courses ORDER BY code')->fetchAll();
$rows = $pdo->query(
    'SELECT e.id, e.enrolled_at, s.name AS student, c.code, c.title, t.name AS teacher
     FROM enrollments e
     JOIN students s ON s.id = e.student_id
     JOIN courses c ON c.id = e.course_id
     LEFT JOIN teachers t ON t.id = c.teacher_id
     ORDER BY e.id DESC'
)->fetchAll();
require 'header.php';
?>
<div class="row g-4">
  <div class="col-lg-4">
    <div class="panel">
      <h2><i class="bi bi-clipboard2-plus-fill"></i> Enroll student in course</h2>
      <form method="post">
        <div class="mb-3"><label class="form-label">Student</label>
          <select name="student_id" class="form-select" required>
            <option value="">Select student</option>
            <?php foreach ($students as $s): ?>
              <option value="<?= (int)$s['id'] ?>"><?= e($s['name']) ?></option>
            <?php endforeach; ?>
          </select></div>
        <div class="mb-3"><label class="form-label">Course</label>
          <select name="course_id" class="form-select" required>
            <option value="">Select course</option>
            <?php foreach ($courses as $c): ?>
              <option value="<?= (int)$c['id'] ?>"><?= e($c['code'] . ' – ' . $c['title']) ?></option>
            <?php endforeach; ?>
          </select></div>
        <button class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-1"></i> Enroll student</button>
      </form>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="panel">
      <h2><i class="bi bi-clipboard2-check-fill"></i> Current enrollments (<?= count($rows) ?>)</h2>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead><tr><th>Student</th><th>Course</th><th>Teacher</th><th>Date</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= e($r['student']) ?></td>
              <td><?= e($r['code'] . ' – ' . $r['title']) ?></td>
              <td><?= e($r['teacher'] ?? '—') ?></td>
              <td><?= e(date('d M Y', strtotime($r['enrolled_at']))) ?></td>
              <td class="text-end">
                <form method="post" onsubmit="return confirm('Enrollment khatam karein?')">
                  <button name="delete" value="<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-danger" title="Remove"><i class="bi bi-x-circle-fill"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$rows): ?><tr><td colspan="5" class="text-muted">Abhi koi enrollment nahi. Pehle student aur course add karein, phir enroll karein.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require 'footer.php'; ?>
