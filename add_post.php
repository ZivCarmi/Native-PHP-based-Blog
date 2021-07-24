<?php

require_once 'app/helpers.php';
session_start();

if (!user_auth()) {

  header('location: signin.php');
  exit;
}

$page_title = 'Add Post';
$errors = [
  'title' => '',
  'article' => '',
];

if (isset($_POST['submit'])) {

  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $article = filter_input(INPUT_POST, 'article', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $form_valid = true;

  if (!$title || mb_strlen($title) < 2) {
    $form_valid = false;
    $errors['title'] = 'Title must contain at least 2 characters';
  }

  if (!$article || mb_strlen($article) < 2) {
    $form_valid = false;
    $errors['article'] = 'Article must contain at least 2 characters';
  }

  if ($form_valid) {
    $u_id = $_SESSION['user_id'];
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
    mysqli_query($link, "SET NAMES utf8");
    $title = mysqli_real_escape_string($link, $title);
    $article = mysqli_real_escape_string($link, $article);
    $sql = "INSERT INTO posts VALUES(null, '$u_id', '$title', '$article', NOW())";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_affected_rows($link) > 0) {
      header('location: blog.php');
    }
  }
}

?>

<?php include 'tpl/header.php'; ?>
<main class="min-h700">
  <div class="container">
    <section>
      <div class="row">
        <div class="col my-5">
          <h1 class="display-3">Add Post Form</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <form action="" method="POST" novalidate="novalidate" autocomplete="off">
            <div class="form-group">
              <label for="title">* Title</label>
              <input value="<?= old('title') ?>" type="title" name="title" id="title" class="form-control">
              <span class="text-danger"><?= $errors['title']; ?></span>
            </div>
            <div class="form-group">
              <label for="article">* Article</label>
              <textarea class="form-control" name="article" id="article" cols="30" rows="10"><?= old('article') ?></textarea>
              <span class="text-danger"><?= $errors['article']; ?></span>
            </div>
            <div class="text-right">
              <a class="btn btn-secondary ml-1" href="blog.php">Cancel</a>
              <input type="submit" value="Publish" name="submit" class="btn btn-primary">
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>
</main>
<?php include 'tpl/footer.php' ?>