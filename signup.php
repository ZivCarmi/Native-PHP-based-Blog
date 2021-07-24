<?php

require_once 'app/helpers.php';
session_start();

if (isset($_SESSION['user_id'])) {
  header('location: ./');
  exit;
}

$page_title = 'Sign-Up';
$errors = [
  'fname' => '',
  'lname' => '',
  'email' => '',
  'password' => '',
  'nickname' => '',
  'submit' => '',
];

// if user clicked on submit button
if (isset($_POST['submit'])) {

  if (isset($_SESSION['csrf_token']) && isset($_POST['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf_token']) {

    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $fname = mysqli_real_escape_string($link, $fname);

    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $lname = mysqli_real_escape_string($link, $lname);

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $email = mysqli_real_escape_string($link, $email);

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $password = mysqli_real_escape_string($link, $password);

    $nickname = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $nickname = mysqli_real_escape_string($link, $nickname);

    $form_valid = true;
    $profile_image = 'default_profile.png';
    define('MAX_FILE_UPLOAD', 1024 * 1024 * 5);

    if (!$fname || mb_strlen($fname) < 2 || mb_strlen($fname) > 70) {
      $errors['fname'] = '* First name must contain at least 2 to 70 chars';
      $form_valid = false;
    }

    if (!$lname || mb_strlen($lname) < 2 || mb_strlen($lname) > 70) {
      $errors['lname'] = '* Last name must contain at least 2 to 70 chars';
      $form_valid = false;
    }

    if (!$email) {
      $errors['email'] = '* A valid Email is required';
      $form_valid = false;
    } elseif (email_exists($link, $email)) {
      $errors['email'] = '* Email is already taken';
      $form_valid = false;
    }

    if (!$password || strlen($password) < 6 || strlen($password) > 20) {
      $errors['password'] = '* Password must contain at least 6 to 20 chars';
      $form_valid = false;
    }

    if ($nickname && (mb_strlen($nickname) < 2 || mb_strlen($nickname) > 70)) {
      $errors['nickname'] = '* Nickname must contain at least 2 to 70 chars';
      $form_valid = false;
    }

    if (isset($_FILES['image']['error']) && $_FILES['image']['error'] == 0) {

      if (isset($_FILES['image']['size']) && $_FILES['image']['size'] <= MAX_FILE_UPLOAD) {

        if (isset($_FILES['image']['name'])) {

          $allowed_ex = ['jpg', 'jpeg', 'bmp', 'gif', 'png'];
          $details = pathinfo($_FILES['image']['name']);
          if (in_array(strtolower($details['extension']), $allowed_ex)) {

            if (isset($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {

              $profile_image = date('Y.m.d.H.i.s') . '-' . $_FILES['image']['name'];
              move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $profile_image);
            }
          }
        }
      }
    }

    if ($form_valid) {
      $password = password_hash($password, PASSWORD_BCRYPT);
      $sql = "INSERT INTO users VALUES (null, '$fname', '$lname', '$email', '$password')";
      $result = mysqli_query($link, $sql);

      if ($result && mysqli_affected_rows($link) > 0) {
        $new_user_id = mysqli_insert_id($link);
        $nickname = $_POST['nickname'];
        $sql = "INSERT INTO users_profile VALUES (null, $new_user_id, '$profile_image', NOW(), 'NULL', '$nickname', 'n', 'Not specified')";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_affected_rows($link) > 0) {
          $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
          $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
          $_SESSION['user_id'] = $new_user_id;
          $_SESSION['user_name'] = $fname;
          $_SESSION['user_lname'] = $lname;
          $_SESSION['user_image'] = $profile_image;
          header('location: ./');
          exit;
        }
      }
    }
  }
  $token = csrf();
} else {
  $token = csrf();
}

?>

<?php include 'tpl/header.php'; ?>
<main class="min-h700">
  <div class="container">
    <section>
      <div class="row">
        <div class="col mt-5">
          <h1 class="display-3">Sign up for a new account</h1>
        </div>
      </div>
    </section>
    <section>
      <form action="" method="POST" autocomplete="off" novalidate="novalidate" enctype="multipart/form-data">
        <div class="row">
          <div class="col-lg-6 mt-4">
            <input type="hidden" name="csrf_token" value="<?= $token; ?>">
            <div class="form-group">
              <label for="fname">* First Name:</label>
              <input value="<?= old('fname') ?>" type="fname" name="fname" id="fname" class="form-control" novalidate>
              <span class="text-danger"><?= $errors['fname'] ?></span>
            </div>
            <div class="form-group">
              <label for="lname">* Last Name:</label>
              <input value="<?= old('lname') ?>" type="lname" name="lname" id="lname" class="form-control" novalidate>
              <span class="text-danger"><?= $errors['lname'] ?></span>
            </div>
            <div class="form-group">
              <label for="email">* Email:</label>
              <input value="<?= old('email') ?>" type="email" name="email" id="email" class="form-control" novalidate>
              <span class="text-danger"><?= $errors['email'] ?></span>
            </div>
            <div class="form-group">
              <label for="password">* Password:</label>
              <input type="password" name="password" id="password" class="form-control">
              <span class="text-danger"><?= $errors['password'] ?></span>
            </div>
          </div>
          <div class="col-lg-6 align-self-center">
            <div class="form-group">
              <label for="nickname">Nickname:</label>
              <input value="<?= old('nickname') ?>" type="nickname" name="nickname" id="nickname" class="form-control" novalidate>
              <p class="nickname-p">* Nickname is not required, however it will makes you look cooler... :)</p>
              <span class="text-danger"><?= $errors['nickname'] ?></span>
            </div>
            <div class="form-group">
              <label for="image-field">Profile Image:</label>
              <div class="input-group mb-3">
                <div class="custom-file">
                  <input type="file" name="image" class="custom-file-input" id="image-field" aria-describedby="inputGroupFileAddon01">
                  <label class="custom-file-label" for="image-field">Choose file</label>
                </div>
              </div>
            </div>
          </div>
          <input type="submit" name="submit" value="Sign-Up" class="btn btn-primary signup-submit mx-auto mt-3">
        </div>
      </form>
    </section>
  </div>
</main>
<?php include 'tpl/footer.php' ?>