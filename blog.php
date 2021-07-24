<?php

require_once 'app/helpers.php';
session_start();

$page_title = 'Blog';

$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
mysqli_query($link, "SET NAMES utf8");
$sql = "SELECT u.first_name,u.last_name,up.profile_image, p.*, DATE_FORMAT(p.date, '%d/%m/%Y %H:%i:%s') pdate FROM posts p 
        JOIN users u ON u.id = p.user_id 
        JOIN users_profile up ON u.id = up.user_id 
        ORDER BY p.date DESC";

$result = mysqli_query($link, $sql);

?>

<?php include 'tpl/header.php'; ?>
<main class="min-h700">
  <div class="container">
    <section>
      <div class="row">
        <div class="col mt-5">
          <h1 class="font-weight-bold letter-spacing">India Travellers blog</h2>
            <?php if (user_auth()) : ?>
              <a class="btn btn-danger mt-4" href="add_post.php"><i class="fas fa-plus-circle mr-2"></i>Add new post</a>
            <?php else : ?>
              <p class="mt-4">Still not a member of our community? <a class="text-decoration-none" href="signup.php">Click Here!</a></p>
            <?php endif; ?>
        </div>
      </div>
      <div class="row">
        <?php while ($post = mysqli_fetch_assoc($result)) : ?>
          <?php $first = ucfirst(strtolower($post['first_name'])); ?>
          <?php $last = ucfirst(strtolower($post['last_name'])); ?>
          <div class="col-12 mt-4">
            <div class="card">
              <div class="card-header">
                <img width="40" src="images/<?= $post['profile_image'] ?>" class="rounded-circle mr-3">
                <a class="text-decoration-none full-name" href="user_profile.php?uid=<?= $post['user_id'] ?>">
                  <span><?= htmlentities($first); ?></span>
                  <span><?= htmlentities($last); ?></span>
                </a>
                <span class="float-right"><?= $post['pdate']; ?></span>
              </div>
              <div class="card-body">
                <h4><?= htmlentities($post['title']); ?></h4>
                <p><?= str_replace("\n", '<br>', htmlentities($post['article'])); ?></p>
              </div>
              <div class="card-footer text-muted">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) : ?>
                  <div class="float-right dropdown">
                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="edit_post.php?pid=<?= $post['id'] ?>"><i class="text-primary fas fa-edit"></i> Edit post</a>
                      <a class="btn-delete-post dropdown-item" href="delete_post.php?pid=<?= $post['id'] ?>"><i class="text-danger fas fa-trash"></i> Delete post</a>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </section>
  </div>
</main>
<?php include 'tpl/footer.php' ?>