<?php

require_once 'app/helpers.php';
session_start();

if (isset($_SESSION['user_id'])) {
  header('location: ./');
  exit;
}

$page_title = 'Sign-In';
$errors = [
  'email' => '',
  'password' => '',
  'submit' => ''
];

// if client clicked on submit button
if (isset($_POST['submit'])) {

  if (isset($_SESSION['csrf_token']) && isset($_POST['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf_token']) {

    // Collect client data to variables
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!$email) {

      $errors['email'] = '* A valid Email is required';
    } elseif (!$password) {
      $errors['password'] = '* Please enter your password';
    } else {

      $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
      $email = mysqli_real_escape_string($link, $email);
      $password = mysqli_real_escape_string($link, $password);
      $sql = "SELECT u.*, up.profile_image FROM users u 
      JOIN users_profile up ON u.id = up.user_id 
      WHERE email = '$email' LIMIT 1";
      $result = mysqli_query($link, $sql);

      if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
          $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
          $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['user_name'] = $user['first_name'];
          $_SESSION['user_lname'] = $user['last_name'];
          $_SESSION['user_image'] = $user['profile_image'];
          header('location: ./');
          exit;
        } else {
          $errors['submit'] = '* Wrong email or password';
        }
      } else {
        $errors['submit'] = '* Wrong email or password';
      }
    }
  }

  $token = csrf();
} else {
  $token = csrf();
}

?>

<?php include 'tpl/header.php'; ?>
<main>
  <div class="container">
    <section>
      <div class="row">
        <div class="col my-5">
          <h1 class="display-3">Sign in with your account</h1>
        </div>
      </div>
    </section>
    <section>
      <div class="row">
        <div class="col-lg-5 p-2 mr-auto signin-box shadow d-flex">
          <div class="signin-inside align-self-center w-100">
            <div class="mt-3">
              <p class="titlebenefit">Still not a member of India Travellers?</p>
            </div>
            <form method="POST" autocomplete="off" novalidate="novalidate">
              <input type="hidden" name="csrf_token" value="<?= $token; ?>">
              <div class="form-group mt-4">
                <input value="<?= old('email') ?>" type="email" name="email" id="email" class="form-control" placeholder="Email Address" novalidate>
                <span class="text-white"><?= $errors['email'] ?></span>
              </div>
              <div class="form-group mt-4">
                <input type="password" name="password" id="password" placeholder="Password" class="form-control">
                <span class="text-white"><?= $errors['password'] ?></span>
              </div>
              <div class="text-center mt-4">
                <input type="submit" name="submit" value="Sign-In" class="btn btn-outline-dark">
              </div>
              <div class="text-center">
                <span class="text-light"><?= $errors['submit'] ?></span>
              </div>
            </form>
          </div>
        </div>
        <div class="divider mx-auto my-2"></div>
        <div class="col-lg-5 p-2 ml-auto signup-benefits shadow-lg">
          <div class="">
            <p class="titlebenefit my-4">Still not a member of India Travellers?</p>
          </div>
          <div class="mt-4">
            <p class="font-weight-600 benefits"><i class="far fa-id-card mx-2 mr-5"></i>Manage your own profile!</p>
          </div>
          <div class="mt-4">
            <p class="font-weight-600 benefits"><i class="fas fa-users mx-2 mr-5"></i>Join to a great community!</p>
          </div>
          <div class="mt-4">
            <p class="font-weight-600 benefits"><i class="far fa-question-circle mx-2 mr-5"></i>Get answers for all of your questions!</p>
          </div>
          <div class="my-3 p-1 text-center">
            <a class="btn btn-outline-dark" href="signup.php">Join now!</a>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>
<?php include 'tpl/footer.php' ?>