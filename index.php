<?php

require_once 'app/helpers.php';
session_start();

$page_title = 'India Travellers';

?>

<?php include 'tpl/header.php'; ?>
<main>
  <div class="container-fluid">
    <section>
      <div class="row">
        <div class="col-12 text-center my-5">
          <h1 class="display-3">Welcome to India Travellers</h1>
          <h5 class="col-12 mt-4 lh1-5">India Travellers is a blog site that contains everything you need <br> to know about India! Ever thought about travelling to India? <br> Well if you did you're in the right place. India Travellers is the place that you need,<br> here you can ask all your questions and share your experiences with other bloggers.</h5>
          <?php if (!user_auth()) : ?>
            <div class="mt-4">
              <a href="signup.php" class="btn btn-outline-success btn-lg">Join us today!</a>
            </div>
          <?php else : ?>
            <div class="mt-4">
              <a href="blog.php" class="btn btn-outline-info btn-lg">Our blog!</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </div>
  <div class="container-fluid p-0">
    <section>
      <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1" class=""></li>
          <li data-target="#myCarousel" data-slide-to="2" class=""></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="images/carousel1.jpg" class="d-block w-100 brightness-60" height="561">
            <div class="container">
              <div class="carousel-caption">
                <h1>Put your colors on and join with thousands of people to celebrate the Holi</h1>
                <p><a class="btn btn-lg btn-primary mt-3" href="#" role="button">Read More</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img src="images/carousel2.jpg" class="d-block w-100 brightness-60" height="561">
            <div class="container">
              <div class="carousel-caption">
                <h1>They say the Taj Mahal never failed to impress someone. Don't belive?<br>Try it yourself!</h1>
                <p><a class="btn btn-lg btn-primary mt-3" href="#" role="button">Learn more</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img src="images/carousel3.jpg" class="d-block w-100 brightness-60" height="561">
            <div class="container">
              <div class="carousel-caption">
                <h1>You won't take your eyes down for a second! India enriched with an endless beautiful view wherever you go...</h1>
                <p><a class="btn btn-lg btn-primary mt-3" href="#" role="button">Browse gallery</a></p>
              </div>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </section>
  </div>
  <div class="container-fluid">
    <section>
      <div class="col-md-9 mx-auto mt-5">
        <div class="row text-center">
          <div class="col-sm-6 col-xl-4">
            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 140x140">
              <title>Placeholder</title>
              <rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text>
            </svg>
            <h2>South Recommendation</h2>
            <h6 class="lh1-5">South India can offer a lot of beautiful places that includes beaches, views or just a normal town.<br>From our experience in India we can recommend you some cool places to spend your time correctly!</h6>
            <p><a class="btn btn-secondary" href="./" role="button">View details »</a></p>
          </div>
          <div class="col-sm-6 col-xl-4">
            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 140x140">
              <title>Placeholder</title>
              <rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text>
            </svg>
            <h2>North Recommendation</h2>
            <h6 class="lh1-5">North India mostly known in his beautiful views of the green mountains with the small towns and of course the great and welcoming Indian people.If north India is in your plans you better check our offers!</h6>
            <p><a class="btn btn-secondary" href="./" role="button">View details »</a></p>
          </div>
          <div class="col-12 col-xl-4">
            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 140x140">
              <title>Placeholder</title>
              <rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text>
            </svg>
            <h2>A Must Recommend</h2>
            <h6 class="lh1-5">We are going to give you the places where you must be!<br> This content will be refreshed on a weekly basis, don't forget to check it out!</h6>
            <p><a class="btn btn-secondary" href="./" role="button">View details »</a></p>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>
<?php include 'tpl/footer.php' ?>