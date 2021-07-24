<?php

require_once 'app/helpers.php';
session_start();


if (isset($_GET['uid']) && is_numeric($_GET['uid'])) {

  $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
  $uid = filter_input(INPUT_GET, 'uid', FILTER_SANITIZE_STRING);
  $uid = mysqli_real_escape_string($link, $uid);

  if ($uid) {
    $sql = "SELECT u.first_name,u.last_name, u.email, up.profile_image, DATE_FORMAT(up.date, '%d/%m/%Y') udate, DATE_FORMAT(up.birth_day, '%d/%m/%Y') bday, up.nickname, up.gender, up.country FROM users u 
    JOIN users_profile up ON up.user_id = $uid 
    AND u.id = $uid";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
      $profile = mysqli_fetch_assoc($result);
      $page_title = htmlentities($profile['first_name']) . ' ' . htmlentities($profile['last_name']);

      $first = ucfirst(strtolower($profile['first_name']));
      $last = ucfirst(strtolower($profile['last_name']));
      $bday_date = ($profile['bday'] == '00/00/0000') ? 'Not specified' : $profile['bday'];
      if ($profile['gender'] == 'f') {
        $gender = 'Female';
      } elseif ($profile['gender'] == 'm') {
        $gender = 'Male';
      } else {
        $gender = 'Not specified';
      }
    } else {
      header('location: index.php');
      exit;
    }
  } else {
    header('location: index.php');
    exit;
  }
} else {
  header('location: index.php');
  exit;
}

?>

<?php include 'tpl/header.php'; ?>

<main>
  <div class="container">
    <div class="row">
      <div class="profile-header my-3">
        <h1 class="display-3"><?= htmlentities($first) . "'s" ?> Profile</h1>
      </div>
    </div>
  </div>
  <div class="container-fluid p-0">
    <div class="container p-0 border">
      <img class="bgc-profile-img d-block w-100" src="images/carousel3.jpg">
    </div>
    <div class="container mt-3 shadow border p-1">
      <div class="mx-auto profile-image-block">
        <img src="images/<?= $profile['profile_image']; ?>" class="w-100 mx-auto rounded-circle user-profile-img">
      </div>
      <div class="text-center">
        <h2 class="p-2 font-weight-bold"><?= htmlentities($first) . ' ' . htmlentities($last) ?></h2>
        <h4 class="p-2 font-weight-bold"><?= htmlentities($profile['nickname']) ?></h4>
      </div>
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active text-dark" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Overview &nbsp;<i class="fas fa-home text-primary"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact &nbsp;<i class="far fa-envelope text-primary"></i></a>
        </li>
      </ul>
      <div class="tab-content mt-2 p-2" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="d-flex">
            <div class="col-6 pl-4">
              <div class="mb-5">
                <h6 class="lead">Bio / Description</h6>
                <div class="pl-2">
                  <div class="mb-4 border">
                    <p class="p-1">User Bio goes here!</p>
                  </div>
                </div>
              </div>
              <div class="mb-5">
                <h6 class="lead">Statistics</h6>
                <div class="pl-2">
                  <div class="border p-1">
                    <p class="p-1 font-weight-bold m-0">Total posts: &nbsp;12</p>
                    <p class="p-1 font-weight-bold m-0">Sum posts per day: &nbsp;2</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6 pl-4">
              <h6 class="lead">User Details</h6>
              <div class="p-1">
                <p class="p-2 font-weight-bold">Joined Date: &nbsp;<?= htmlentities($profile['udate']) ?></p>
                <hr class="w-75">
                <p class="p-2 font-weight-bold">Birth-Day: &nbsp; <?= htmlentities($bday_date); ?></p>
                <hr class="w-75">
                <p class="p-2 font-weight-bold">Gender: &nbsp; <?= htmlentities($gender); ?></p>
                <hr class="w-75">
                <p class="p-2 font-weight-bold">Country: &nbsp; <?= htmlentities($profile['country']); ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
          <div class="col-6 pl-4">
            <h6 class="lead">Contact Details</h6>
            <div class="p-1">
              <p class="p-2 font-weight-bold">Email Address: &nbsp;<?= htmlentities($profile['email']); ?></p>
              <hr class="w-75">
              <p class="p-2 font-weight-bold">Country: &nbsp;<?= htmlentities($profile['country']) ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include 'tpl/footer.php' ?>