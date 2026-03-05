<?php
require_once __DIR__ . "/config.php";

// Must be logged in
if (empty($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
// Must be admin
if (strtolower($_SESSION["role"] ?? "") !== "admin") {
    header("Location: dashboard.php");
    exit;
}

$search = trim($_GET["search"] ?? "");

// Toggle active
if (isset($_GET["toggle"])) {
    $uid = (int)$_GET["toggle"];

    // don't allow changing yourself
    if ($uid === (int)$_SESSION["user_id"]) {
        header("Location: admin_users.php?err=self");
        exit;
    }

    $st = $conn->prepare("UPDATE users SET is_active = IF(is_active=1,0,1) WHERE id=?");
    $st->bind_param("i", $uid);
    $st->execute();
    header("Location: admin_users.php");
    exit;
}

// Change role
if (isset($_POST["set_role"])) {
    $uid = (int)($_POST["user_id"] ?? 0);
    $newRole = strtolower(trim($_POST["role"] ?? "user"));
    if (!in_array($newRole, ["admin","user"], true)) $newRole = "user";

    if ($uid === (int)$_SESSION["user_id"]) {
        header("Location: admin_users.php?err=self");
        exit;
    }

    $st = $conn->prepare("UPDATE users SET role=? WHERE id=?");
    $st->bind_param("si", $newRole, $uid);
    $st->execute();
    header("Location: admin_users.php");
    exit;
}

// Query users
$sql = "SELECT id, full_name, email, role, is_active, created_at FROM users";
$params = [];
$types = "";

if ($search !== "") {
    $sql .= " WHERE full_name LIKE ? OR email LIKE ?";
    $like = "%$search%";
    $params = [$like, $like];
    $types = "ss";
}
$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if ($types !== "") $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

require_once __DIR__ . "/partials/header.php";
?>

<div class="container mt-4" style="max-width:1100px;">

  <h2 class="section-title">Manage Users</h2>

  <?php if(($_GET["err"] ?? "") === "self"): ?>
    <div class="alert alert-warning">You can’t change your own role/status.</div>
  <?php endif; ?>

  <div class="card">
    <form method="GET" class="mb-3">
      <div class="row g-2">
        <div class="col-md-10">
          <input class="form-control" name="search" placeholder="Search name or email..."
                 value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2">
          <button class="btn btn-primary w-100">Search</button>
        </div>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered bg-white mb-0">
        <thead class="table-dark">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th style="width:120px;">Role</th>
            <th style="width:120px;">Status</th>
            <th style="width:320px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($u = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($u["full_name"]) ?></td>
              <td><?= htmlspecialchars($u["email"]) ?></td>
              <td><?= strtoupper(htmlspecialchars($u["role"])) ?></td>
              <td>
                <?php if ((int)$u["is_active"] === 1): ?>
                  <span class="badge bg-success">Active</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Disabled</span>
                <?php endif; ?>
              </td>
              <td class="d-flex gap-2 flex-wrap">
                <a class="btn btn-sm btn-outline-danger"
                   href="admin_users.php?toggle=<?= (int)$u["id"] ?>"
                   onclick="return confirm('Toggle Active/Disabled for this user?');">
                   Toggle Active
                </a>

                <form method="POST" class="d-flex gap-2 align-items-center m-0">
                  <input type="hidden" name="user_id" value="<?= (int)$u["id"] ?>">
                  <select class="form-select form-select-sm" name="role" style="width:120px;">
                    <option value="user" <?= ($u["role"]==="user"?"selected":"") ?>>user</option>
                    <option value="admin" <?= ($u["role"]==="admin"?"selected":"") ?>>admin</option>
                  </select>
                  <button class="btn btn-sm btn-outline-primary" name="set_role" value="1">
                    Set Role
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    <a class="btn btn-outline-primary" href="admin_dashboard.php">Back to Admin Dashboard</a>
  </div>

</div>

<?php require_once __DIR__ . "/partials/footer.php"; ?>