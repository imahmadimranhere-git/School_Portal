<?php
require 'config.php';
$title = 'Courses';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $pdo->prepare('DELETE FROM courses WHERE id = ?')->execute([(int)$_POST['delete']]);
        flash('Course delete ho gaya.');
    } else {
        $code    = trim($_POST['code'] ?? '');
        $ctitle  = trim($_POST['title'] ?? '');
        $fee     = (float)($_POST['fee'] ?? 0);
        $teacher = (int)($_POST['teacher_id'] ?? 0) ?: null;
        if ($code === '' || $ctitle === '' || $fee < 0) {
            flash('Code, title aur sahi fee zaroori hain.', 'danger');
        } else {
            try {
                $pdo->prepare('INSERT INTO courses (code, title, fee, teacher_id) VALUES (?, ?, ?, ?)')
                    ->execute([$code, $ctitle, $fee, $teacher]);
                flash('Course add ho gaya.');
            } catch (PDOException $ex) {
                flash('Ye course code pehle se maujood hai.', 'danger');
            }
        }
    }
    redirect('courses.php');
}

$teachers = $pdo->query('SELECT id, name FROM teachers ORDER BY name')->fetchAll();
$rows = $pdo->query(
    'SELECT c.*, t.name AS teacher_name,
            (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) AS seats
     FROM courses c LEFT JOIN teachers t ON t.id = c.teacher_id
     ORDER BY c.id DESC'
)->fetchAll();
require 'header.php';
?>
<div class="row g-4">
  <div class="col-lg-4">
    <div class="panel">
      <h2><i class="bi bi-journal-plus"></i> Add course</h2>
      <form method="post">
        <div class="mb-3"><label class="form-label">Course code</label>
          <input name="code" class="form-control" placeholder="CS101" required></div>
        <div class="mb-3"><label class="form-label">Title</label>
          <input name="title" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Fee</label>
          <input type="number" step="0.01" min="0" name="fee" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Teacher</label>
          <select name="teacher_id" class="form-select">
            <option value="">Abhi assign na karein</option>
            <?php foreach ($teachers as $t): ?>
              <option value="<?= (int)$t['id'] ?>"><?= e($t['name']) ?></option>
            <?php endforeach; ?>
          </select></div>
        <button class="btn btn-primary w-100"><i class="bi bi-plus-circle-fill me-1"></i> Add course</button>
      </form>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="panel">
      <h2><i class="bi bi-journal-bookmark-fill"></i> All courses (<?= count($rows) ?>)</h2>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead><tr><th>Code</th><th>Title</th><th>Teacher</th><th>Fee</th><th>Students</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><span class="code-chip"><?= e($r['code']) ?></span></td>
              <td><?= e($r['title']) ?></td>
              <td><?= e($r['teacher_name'] ?? '—') ?></td>
              <td><?= number_format($r['fee'], 2) ?></td>
              <td><?= (int)$r['seats'] ?></td>
              <td class="text-end">
                <form method="post" onsubmit="return confirm('Is course ko delete karein?')">
                  <button name="delete" value="<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash3-fill"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$rows): ?><tr><td colspan="6" class="text-muted">Abhi koi course nahi. Left form se pehla course add karein.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require 'footer.php'; ?>
