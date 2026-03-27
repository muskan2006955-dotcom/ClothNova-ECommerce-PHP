<?php
// reset_password.php (no-token version — game first, then reset by email)
// at top of reset_password.php
session_start();
// allow_reset is set by forgot_password.php (timestamp). Optional check:
if (!isset($_SESSION['allow_reset'])) {
    // user opened reset page directly - redirect to forgot page
    header("Location: forgot_password.php");
    exit;
}
$prefillEmail = isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : '';

include "db.php"; // ensure path correct

$message = "";
$showForm = false;

// If password reset submitted (after game)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stage']) && $_POST['stage'] === 'reset_submit') {
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Enter a valid email.";
    } elseif (strlen($pass) < 6) {
        $message = "❌ Password must be at least 6 characters.";
    } elseif ($pass !== $confirm) {
        $message = "❌ Passwords do not match.";
    } else {
        // Check user exists
        $emailEsc = $conn->real_escape_string($email);
        $r = $conn->query("SELECT user_id FROM users WHERE email = '$emailEsc' LIMIT 1");
        if ($r && $r->num_rows > 0) {
            $uid = (int)$r->fetch_assoc()['user_id'];
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            if ($conn->query("UPDATE users SET password = '$hash' WHERE user_id = $uid")) {
                $message = "✅ Password updated successfully. <a href='login.php'>Login now</a>.";
            } else {
                $message = "❌ Database error: " . $conn->error;
            }
        } else {
            $message = "❌ Email not found in our records.";
            // show form again so user can try with correct email
            $showForm = true;
        }
    }
}

// If user completed the game client-side, JS will send stage=show_form to request form display (we can also rely on client-side show).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stage']) && $_POST['stage'] === 'show_form') {
    $showForm = true;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Reset Password — Fun Challenge</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: linear-gradient(90deg,#fffaf0,#ffe6f0); }
    .card { border-radius: 12px; box-shadow: 0 6px 24px rgba(0,0,0,0.08); }
    #resetForm { display: <?php echo $showForm ? 'block' : 'none'; ?>; }
    .blank-input { width: 100%; max-width: 320px; display:block; margin-bottom:8px; }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card p-4">
        <h4 class="mb-3 text-center">Fun Challenge — Unlock Reset</h4>

        <?php if ($message): ?>
          <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (!$showForm): ?>

          <p class="text-muted">Complete the small challenge below to unlock the reset form. This helps confirm you're a human 😊</p>

          <!-- GAME: fill missing letters for weekdays -->
          <div id="gameArea">
            <div class="mb-2">
              <small class="text-muted">Type the full weekday name for each masked word.</small>
            </div>

            <?php
            // Prepare masked words server-side (so answers come from server)
            $weekdays = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
            shuffle($weekdays);
            $showDays = array_slice($weekdays, 0, 5); // pick 5
            $masked = [];
            foreach ($showDays as $d) {
                $len = strlen($d);
                $numRemove = max(1, (int)ceil($len * 0.35));
                $indexes = range(0,$len-1);
                shuffle($indexes);
                $removeIdx = array_slice($indexes, 0, $numRemove);
                sort($removeIdx);
                $chars = str_split($d);
                $mask = '';
                foreach ($chars as $i=>$c) {
                    $mask .= in_array($i, $removeIdx) ? '_' : $c;
                }
                $masked[] = ['orig'=>$d, 'mask'=>$mask];
            }

            foreach ($masked as $i=>$m) {
                $id = "dayInput" . $i;
                echo "<div class='mb-2'>";
                echo "<label class='form-label'>".htmlspecialchars($m['mask'])."</label>";
                echo "<input id='$id' class='form-control blank-input' placeholder='Type full day'>";
                echo "</div>";
            }
            ?>

            <div class="d-flex gap-2 mt-3">
              <button id="checkBtn" class="btn btn-primary">Check</button>
              <button id="revealBtn" class="btn btn-outline-secondary">Show Answers</button>
              <div id="gameMsg" class="ms-3 align-self-center text-success" style="display:none;">✅ Correct — form unlocked.</div>
            </div>
          </div>

          <!-- when JS validates correct, it will reveal the form (no page reload required) -->
          <?php else: ?>
        <?php endif; ?>

        <!-- RESET FORM (hidden until game complete or server-side $showForm true) -->
        <div id="resetForm" class="mt-4">
          <h5>Reset Password</h5>
          <form method="POST" onsubmit="return validatePasswords();">
            <input type="hidden" name="stage" value="reset_submit">
            <div class="mb-3">
              <label class="form-label">Email (registered)</label>
<input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($prefillEmail); ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input id="password" name="password" type="password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <input id="confirm_password" name="confirm_password" type="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Reset Password</button>
          </form>
        </div>

        <p class="mt-3 text-center text-muted"><a href="login.php">Back to Login</a></p>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  // Answers array from server
  const answers = <?php
    $ans = array_map(function($m){ return $m['orig']; }, $masked);
    echo json_encode($ans);
  ?>;

  // inputs
  const inputs = answers.map((_,i) => document.getElementById('dayInput'+i));
  const checkBtn = document.getElementById('checkBtn');
  const revealBtn = document.getElementById('revealBtn');
  const gameMsg = document.getElementById('gameMsg');
  const resetForm = document.getElementById('resetForm');

  function norm(s){ return (s||'').trim().toLowerCase(); }

  if (checkBtn) {
    checkBtn.addEventListener('click', function(){
      let allOk = true;
      for (let i=0;i<inputs.length;i++){
        const val = inputs[i].value || '';
        if (norm(val) !== norm(answers[i])) {
          allOk = false;
          inputs[i].classList.add('is-invalid');
        } else {
          inputs[i].classList.remove('is-invalid');
          inputs[i].classList.add('is-valid');
        }
      }
      if (allOk) {
        gameMsg.style.display = 'inline-block';
        // reveal reset form
        setTimeout(function(){
          resetForm.style.display = 'block';
          resetForm.scrollIntoView({behavior:'smooth', block:'center'});
        }, 350);
      } else {
        alert('Kuch ghalat hain — highlighted fields check karo.');
      }
    });
  }

  if (revealBtn) {
    revealBtn.addEventListener('click', function(){
      if (!confirm('Are you sure? This will reveal answers.')) return;
      for (let i=0;i<inputs.length;i++){
        inputs[i].value = answers[i];
        inputs[i].classList.remove('is-invalid');
        inputs[i].classList.add('is-valid');
      }
      gameMsg.style.display = 'inline-block';
      setTimeout(function(){ resetForm.style.display = 'block'; resetForm.scrollIntoView({behavior:'smooth'}); }, 250);
    });
  }

  window.validatePasswords = function(){
    const pw = document.getElementById('password').value.trim();
    const cpw = document.getElementById('confirm_password').value.trim();
    if (pw.length < 6) { alert('Password must be at least 6 characters.'); return false; }
    if (pw !== cpw) { alert('Passwords do not match.'); return false; }
    // allow submit
    return true;
  };
})();
</script>
</body>
</html>
