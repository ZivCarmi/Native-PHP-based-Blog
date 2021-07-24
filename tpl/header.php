<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
  <link rel="icon" href="images/favicon32.png" type="image/png">
  <title><?= $page_title ?></title>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light shadow p-3">
      <div class="container">
        <a class="navbar-brand" href="./"><img class="mr-2" src="images/logo3.png" width="70" height="32">India Travellers</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse letter-spacing navbarSupportedContent" id="">
          <ul class="navbar-nav ml-lg-3">
            <li class="nav-item mr-3">
              <a class="nav-link text-dark font-weight-600" href="about.php">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark font-weight-600" href="blog.php">Blog</a>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto">
            <?php if (!user_auth()) : ?>
              <li class="nav-item mr-3">
                <a class="nav-link text-dark font-weight-600" href="signin.php"><i class="fas fa-sign-in-alt fa-lg text-primary"></i> Sign-in</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-dark font-weight-600" href="signup.php">Sign-up</a>
              </li>
            <?php else : ?>
              <li class="nav-item mr-3 pointer">
                <div class="dropdown header-drop">
                  <a class="nav-link text-dark font-weight-600 mr-3 dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img width="32" height="32" class="rounded-circle mr-2" src="images/<?= $_SESSION['user_image'] ?>">
                    Welcome, &nbsp;<?= htmlentities($_SESSION['user_name']); ?></a>
                  <div class="dropdown-menu dropback" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item profile-drop" href="my_profile.php?my_profile=<?= $_SESSION['user_id']; ?>"><i class="far fa-id-card fa-lg mr-2 p-2"></i>My Profile</a>
                  </div>
                </div>
              </li>
              <li class="nav-item m-lg-auto">
                <a class="nav-link text-dark font-weight-600" href="logout.php"><i class="fas fa-sign-out-alt fa-lg mr-1 text-info"></i>Log-Out</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
      <div class="collapse navbar-collapse letter-spacing navbarSupportedContent utilities-div p-0">
        <ul class="nav utilities-ul">
          <li class="nav-item footer-li d-inline mr-2">
            <a class="nav-link instagram-link" href="https://www.instagram.com/zivcarmi/" target="_blank" title="Instagram"><i class="fab fa-instagram fa-lg text-dark"></i></a>
          </li>
          <li class="nav-item footer-li d-inline">
            <a class="nav-link facebook-link" href="https://www.facebook.com/ziv.carmi.5" target="_blank" title="Facebook"><i class="fab fa-facebook-f fa-lg text-dark"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <div class="wrapper">