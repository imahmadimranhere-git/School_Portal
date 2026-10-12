<?php
require 'config.php';
$title = 'Teachers';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $pdo->prepare('DELETE FROM teachers WHERE id = ?')->execute([(int)$_POST['delete']]);
        flash('Teacher delete ho gaya.');
    } else {
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('Naam aur sahi email zaroori hain.', 'danger');
        } else {
            try {
                $pdo->prepare('INSERT INTO teachers (name, email, phone, subject) VALUES (?, ?, ?, ?)')
                    ->execute([$name, $email, $phone, $subject]);
                flash('Teacher register ho gaya.');
            } catch (PDOException $ex) {
                flash('Ye email pehle se register hai.', 'danger');
            }
        }
    }
    redirect('teachers.php');
}

$rows = $pdo->query('SELECT * FROM teachers ORDER BY id DESC')->fetchAll();
require 'header.php';
?>
<div class="row g-4">
  <div class="col-lg-4">
    <div class="panel">
      <h2><i class="bi bi-person-vcard-fill"></i> Register teacher</h2>
      <form method="post">
        <div class="mb-3"><label class="form-label">Full name</label>
          <input name="name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Phone</label>
          <input name="phone" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Subject</label>
          <input name="subject" class="form-control"></div>
        <button class="btn btn-primary w-100"><i class="bi bi-check2-circle me-1"></i> Register teacher</button>
      </form>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="panel">
      <h2><i class="bi bi-person-badge-fill"></i> All teachers (<?= count($rows) ?>)</h2>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead><tr><th>Name</th><th>Subject</th><th>Email</th><th>Phone</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= e($r['name']) ?></td>
              <td><?= e($r['subject']) ?></td>
              <td><?= e($r['email']) ?></td>
              <td><?= e($r['phone']) ?></td>
              <td class="text-end">
                <form method="post" onsubmit="return confirm('Is teacher ko delete karein?')">
                  <button name="delete" value="<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash3-fill"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$rows): ?><tr><td colspan="5" class="text-muted">Abhi koi teacher nahi. Left form se pehla teacher register karein.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require 'footer.php'; ?>
