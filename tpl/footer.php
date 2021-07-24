<footer class="footer">
  <div class="container">
    <div class="row ml-4">
      <div class="col-12 mb-3">
        <a class="" href="./"><img src="images/logo3.png" width="110" height="55"></a>
        <button class="float-right" id="back-to-top" title="Go to top"><i class="fas fa-chevron-circle-up fa-3x"></i></button>
      </div>
      <nav class="navbar navbar-expand-lg col-6 col-lg-12 p-0 nav-footer">
        <ul class="navbar-nav">
          <li class="nav-item mr-5 footer-li">
            <a class="nav-link text-dark" href="about.php">About</a>
          </li>
          <li class="nav-item mr-5 footer-li">
            <a class="nav-link text-dark" href="blog.php">Blog</a>
          </li>
          <?php if (!user_auth()) : ?>
            <li class="nav-item mr-5 footer-li">
              <a class="nav-link text-dark" href="signin.php">Sign-in</a>
            </li>
            <li class="nav-item mr-5 footer-li">
              <a class="nav-link text-dark" href="signup.php">Sign-up</a>
            </li>
          <?php else : ?>
            <li class="nav-item mr-5 footer-li">
              <a class="nav-link text-dark font-weight-bold mr-3" href="my_profile.php?my_profile=<?= $_SESSION['user_id']; ?>">My Profile</a>
            </li>
            <li class="nav-item mr-5 footer-li">
              <a class="nav-link text-dark" href="logout.php">Log-Out</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
      <nav class="navbar col-6 col-lg-12 p-0 nav-footer">
        <ul class="navbar-nav ml-2">
          <li class="nav-item footer-li">
            <a class="nav-link text-dark" href="./">Report an Issue</a>
          </li>
          <li class="nav-item footer-li">
            <a class="nav-link text-dark" href="./">Contact Us</a>
          </li>
        </ul>
        <div class="col-12 p-0">
          <ul class="nav justify-content-start justify-content-lg-end">
            <li class="nav-item footer-li d-inline">
              <a class="nav-link" href="https://www.instagram.com/zivcarmi/" target="_blank" title="Instagram"><i class="fab fa-instagram fa-lg text-dark"></i></a>
            </li>
            <li class="nav-item footer-li d-inline">
              <a class="nav-link" href="https://www.facebook.com/ziv.carmi.5" target="_blank" title="Facebook"><i class="fab fa-facebook-f fa-lg text-dark"></i></a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
</footer>
<div class="col-12 text-center text-light">
  <p class="m-0 copyright">
    India Travellers &copy; Copyright <?= date('Y'); ?> All rights Reserved
  </p>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</div>
</body>

</html>