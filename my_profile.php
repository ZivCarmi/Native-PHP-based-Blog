<?php

require_once 'app/helpers.php';
session_start();

if (!user_auth()) {

  header('location: ./');
  exit;
}

// Checking if query string exist and numeric

if (isset($_GET['my_profile']) && is_numeric($_GET['my_profile'])) {

  $my_profile = filter_input(INPUT_GET, 'my_profile', FILTER_SANITIZE_STRING);


  // What to show to the user in My Profile page
  if ($my_profile) {
    $uid = $_SESSION['user_id'];
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
    $my_profile = mysqli_real_escape_string($link, $my_profile);

    $sql = "SELECT u.first_name, u.last_name, u.email, u.password, up.profile_image, DATE_FORMAT(up.date, '%d/%m/%Y') udate, DATE_FORMAT(up.birth_day, '%d/%m/%Y') ubday, up.nickname, up.gender, up.country 
    FROM users u 
    JOIN users_profile up ON up.user_id = $uid 
    AND u.id = $uid LIMIT 1";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
      $profile = mysqli_fetch_assoc($result);

      $first = ucfirst(strtolower($profile['first_name']));
      $last = ucfirst(strtolower($profile['last_name']));
      $bday_date = ($profile['ubday'] == '00/00/0000') ? 'Not specified' : $profile['ubday'];
      if ($profile['gender'] == 'f') {
        $gender = 'Female';
      } elseif ($profile['gender'] == 'm') {
        $gender = 'Male';
      } else {
        $gender = 'Not specified';
      }
    } else {
      header('location: ./');
      exit;
    }
  } else {
    header('location: blog.php');
    exit;
  }
} else {
  header('location: blog.php');
  exit;
}

$page_title = 'My Profile';

$errors = [
  'nickname' => '',
  'first_name' => '',
  'last_name' => '',
  'must_password' => '',
  'new_password' => '',
];


// Checks if user clicked on profile_save
if (isset($_POST['profile_save'])) {

  $nickname = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
  // $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING);
  // $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
  $form_valid = true;


  if ($nickname && (mb_strlen($nickname) < 2 ||  mb_strlen($nickname) > 70)) {
    $errors['nickname'] = '* Nickname must contain at least 2 to 70 chars';
    $form_valid = false;
  }

  if (!$first_name || mb_strlen($first_name) < 2 || mb_strlen($first_name) > 70) {
    $errors['first_name'] = '* First name must contain at least 2 to 70 chars';
    $form_valid = false;
  }

  if (!$last_name || mb_strlen($last_name) < 2 || mb_strlen($last_name) > 70) {
    $errors['last_name'] = '* Last name must contain at least 2 to 70 chars';
    $form_valid = false;
  }


  if ($form_valid) {
    $nickname = mysqli_real_escape_string($link, $nickname);
    $first_name = mysqli_real_escape_string($link, $first_name);
    $last_name = mysqli_real_escape_string($link, $last_name);
    $country = mysqli_real_escape_string($link, $country);
    // $birthday = mysqli_real_escape_string($link, $birthday);
    // $gender = mysqli_real_escape_string($link, $gender);
    // $sql = "UPDATE users_profile SET birth_day = $birthday, nickname = $nickname, gender = $gender, country = $country";
    $sql_name = "UPDATE users SET first_name = '$first_name', last_name = '$last_name' WHERE id = $my_profile";
    $result = mysqli_query($link, $sql_name);
    $sql_profile = "UPDATE users_profile SET nickname = '$nickname', country = '$country' WHERE user_id = $my_profile";
    $result = mysqli_query($link, $sql_profile);
    header("location: my_profile.php?my_profile=$uid");
    exit;
  }
}



if (isset($_POST['security_save'])) {

  $must_password = filter_input(INPUT_POST, 'must_password', FILTER_SANITIZE_STRING);
  $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
  $form_valid2 = true;

  if (!$must_password) {
    $errors['must_password'] = 'You must enter your current password';
    $form_valid2 = false;
  } elseif ($must_password != password_verify($must_password, $profile['password'])) {
    $errors['must_password'] = 'Password does not match your current password';
    $form_valid2 = false;
  }

  if ($new_password && (strlen($new_password) < 6 || strlen($new_password) > 20)) {
    $errors['new_password'] = 'Password must contain at least 6 to 20 chars';
    $form_valid2 = false;
  } elseif (password_verify($new_password, $profile['password'])) {
    $errors['new_password'] = 'You have used this password in the past, please choose another password';
    $form_valid2 = false;
  }

  if ($form_valid2) {
    $must_password = mysqli_real_escape_string($link, $must_password);
    $new_password = mysqli_real_escape_string($link, $new_password);
    $new_password = password_hash($new_password, PASSWORD_BCRYPT);
    $sql_security = "UPDATE users SET password = '$new_password' WHERE id = $my_profile";
    $result = mysqli_query($link, $sql_security);
  }
}

?>

<?php include 'tpl/header.php'; ?>
<main>
  <div class="container">
    <div class="row">
      <div class="profile-header my-5 ml-4">
        <h1 class="display-3">My Profile</h1>
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
          <a class="nav-link text-dark" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Account Settings &nbsp;<i class="fas fa-user-cog text-primary"></i></a>
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
                <p class="p-2 font-weight-bold">Birth-Day: &nbsp;<?= htmlentities($bday_date); ?></p>
                <hr class="w-75">
                <p class="p-2 font-weight-bold">Gender: &nbsp;<?= htmlentities($gender) ?></p>
                <hr class="w-75">
                <p class="p-2 font-weight-bold">Country: &nbsp;<?= htmlentities($profile['country']) ?></p>
                <hr class="w-75">
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <div class="row">
            <div class="col-3 p-0">
              <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="fas fa-user">&nbsp;</i><span class=""> Edit personal information</span></a>
                <!-- <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Edit Signature</a> -->
                <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false"><i class="fas fa-lock">&nbsp;</i> Change Password</a>
                <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false"><i class="fas fa-user-times">&nbsp;</i> Delete Account</a>
              </div>
            </div>
            <div class="col-9">
              <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active border" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="POST" autocomplete="off" novalidate="novalidate">
                        <div class="p-2 border-bottom">
                          <p class="lead m-0 p-1">My Details</p>
                        </div>
                        <div class="d-flex input-wrapper">
                          <div class="col-5 col-md-3 p-2">
                            <label for="nickname" class="font-weight-bold account-titles">Nickname</label>
                          </div>
                          <div class="col-7 col-md-7">
                            <input type="text" class="form-control" name="nickname" id="nickname" value="<?= htmlentities($profile['nickname']); ?>" autocomplete="off">
                            <span class="text-danger"><?= $errors['nickname'] ?></span>
                          </div>
                        </div>
                        <div class="d-flex input-wrapper ">
                          <div class="col-5 col-md-3 p-2">
                            <label for="fname" class="font-weight-bold account-titles">Name</label>
                          </div>
                          <div class="col-7 col-md-7">
                            <input type="text" class="form-control" name="first_name" id="fname" value="<?= htmlentities($profile['first_name']); ?>" autocomplete="off">
                            <span class="text-danger"><?= $errors['first_name'] ?></span>
                          </div>
                        </div>
                        <div class="d-flex input-wrapper ">
                          <div class="col-5 col-md-3 p-2">
                            <label for="lname" class="font-weight-bold account-titles">Last Name</label>
                          </div>
                          <div class="col-7 col-md-7">
                            <input type="text" class="form-control" name="last_name" id="lname" value="<?= htmlentities($profile['last_name']); ?>" autocomplete="off">
                            <span class="text-danger"><?= $errors['last_name'] ?></span>
                          </div>
                        </div>
                        <div class="p-2 border-top border-bottom">
                          <p class="lead m-0 p-1">Additional Details &nbsp;(Optional)</p>
                        </div>
                        <div class="d-flex input-wrapper">
                          <div class="col-4 col-md-3 p-2">
                            <label for="birthday" class="font-weight-bold d-inline account-titles">Birth Day</label>
                          </div>
                          <div class="col-8 col-md-5">
                            <input type="date" class="form-control" name="birthday" id="birthday">
                          </div>
                        </div>
                        <div class="d-flex input-wrapper">
                          <div class="col-4 col-md-3 p-2">
                            <label class="font-weight-bold d-inline account-titles">Gender &nbsp;</label>
                          </div>
                          <div class="col-8 col-md-5">
                            <label for="r_male" class="d-block">
                              <input type="radio" name="gender" value="<?= htmlentities($gender); ?>" id="r_male">
                              Male
                            </label>
                            <label for="r_female" class="d-block">
                              <input type="radio" name="gender" value="<?= htmlentities($gender); ?>" id="r_female">
                              Female
                            </label>
                            <label for="r_none" class="d-block">
                              <input type="radio" name="gender" value="<?= htmlentities($gender); ?>" id="r_none">
                              Not telling
                            </label>
                          </div>
                        </div>
                        <div class="d-flex input-wrapper">
                          <div class="col-4 col-md-3 p-2">
                            <label for="country" class="lead font-weight-bold">Country &nbsp;</label>
                          </div>
                          <div class="col-8 col-md-5">
                            <select id="country" name="country" class="form-control country-select">
                              <option class="first-option"><?= htmlentities($profile['country']); ?></option>
                              <option value="Afghanistan">Afghanistan</option>
                              <option value="Åland Islands">Åland Islands</option>
                              <option value="Albania">Albania</option>
                              <option value="Algeria">Algeria</option>
                              <option value="American Samoa">American Samoa</option>
                              <option value="Andorra">Andorra</option>
                              <option value="Angola">Angola</option>
                              <option value="Anguilla">Anguilla</option>
                              <option value="Antarctica">Antarctica</option>
                              <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                              <option value="Argentina">Argentina</option>
                              <option value="Armenia">Armenia</option>
                              <option value="Aruba">Aruba</option>
                              <option value="Australia">Australia</option>
                              <option value="Austria">Austria</option>
                              <option value="Azerbaijan">Azerbaijan</option>
                              <option value="Bahamas">Bahamas</option>
                              <option value="Bahrain">Bahrain</option>
                              <option value="Bangladesh">Bangladesh</option>
                              <option value="Barbados">Barbados</option>
                              <option value="Belarus">Belarus</option>
                              <option value="Belgium">Belgium</option>
                              <option value="Belize">Belize</option>
                              <option value="Benin">Benin</option>
                              <option value="Bermuda">Bermuda</option>
                              <option value="Bhutan">Bhutan</option>
                              <option value="Bolivia">Bolivia</option>
                              <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                              <option value="Botswana">Botswana</option>
                              <option value="Bouvet Island">Bouvet Island</option>
                              <option value="Brazil">Brazil</option>
                              <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                              <option value="Brunei Darussalam">Brunei Darussalam</option>
                              <option value="Bulgaria">Bulgaria</option>
                              <option value="Burkina Faso">Burkina Faso</option>
                              <option value="Burundi">Burundi</option>
                              <option value="Cambodia">Cambodia</option>
                              <option value="Cameroon">Cameroon</option>
                              <option value="Canada">Canada</option>
                              <option value="Cape Verde">Cape Verde</option>
                              <option value="Cayman Islands">Cayman Islands</option>
                              <option value="Central African Republic">Central African Republic</option>
                              <option value="Chad">Chad</option>
                              <option value="Chile">Chile</option>
                              <option value="China">China</option>
                              <option value="Christmas Island">Christmas Island</option>
                              <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                              <option value="Colombia">Colombia</option>
                              <option value="Comoros">Comoros</option>
                              <option value="Congo">Congo</option>
                              <option value="The Democratic Republic of The Congo">The Democratic Republic of The Congo</option>
                              <option value="Cook Islands">Cook Islands</option>
                              <option value="Costa Rica">Costa Rica</option>
                              <option value="Cote D'ivoire">Cote D'ivoire</option>
                              <option value="Croatia">Croatia</option>
                              <option value="Cuba">Cuba</option>
                              <option value="Cyprus">Cyprus</option>
                              <option value="Czech Republic">Czech Republic</option>
                              <option value="Denmark">Denmark</option>
                              <option value="Djibouti">Djibouti</option>
                              <option value="Dominica">Dominica</option>
                              <option value="Dominican Republic">Dominican Republic</option>
                              <option value="Ecuador">Ecuador</option>
                              <option value="Egypt">Egypt</option>
                              <option value="El Salvador">El Salvador</option>
                              <option value="Equatorial Guinea">Equatorial Guinea</option>
                              <option value="Eritrea">Eritrea</option>
                              <option value="Estonia">Estonia</option>
                              <option value="Ethiopia">Ethiopia</option>
                              <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                              <option value="Faroe Islands">Faroe Islands</option>
                              <option value="Fiji">Fiji</option>
                              <option value="Finland">Finland</option>
                              <option value="France">France</option>
                              <option value="French Guiana">French Guiana</option>
                              <option value="French Polynesia">French Polynesia</option>
                              <option value="French Southern Territories">French Southern Territories</option>
                              <option value="Gabon">Gabon</option>
                              <option value="Gambia">Gambia</option>
                              <option value="Georgia">Georgia</option>
                              <option value="Germany">Germany</option>
                              <option value="Ghana">Ghana</option>
                              <option value="Gibraltar">Gibraltar</option>
                              <option value="Greece">Greece</option>
                              <option value="Greenland">Greenland</option>
                              <option value="Grenada">Grenada</option>
                              <option value="Guadeloupe">Guadeloupe</option>
                              <option value="Guam">Guam</option>
                              <option value="Guatemala">Guatemala</option>
                              <option value="Guernsey">Guernsey</option>
                              <option value="Guinea">Guinea</option>
                              <option value="Guinea-bissau">Guinea-bissau</option>
                              <option value="Guyana">Guyana</option>
                              <option value="Haiti">Haiti</option>
                              <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                              <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                              <option value="Honduras">Honduras</option>
                              <option value="Hong Kong">Hong Kong</option>
                              <option value="Hungary">Hungary</option>
                              <option value="Iceland">Iceland</option>
                              <option value="India">India</option>
                              <option value="Indonesia">Indonesia</option>
                              <option value="Islamic Republic of Iran">Islamic Republic of Iran</option>
                              <option value="Iraq">Iraq</option>
                              <option value="Ireland">Ireland</option>
                              <option value="Isle of Man">Isle of Man</option>
                              <option value="Israel">Israel</option>
                              <option value="Italy">Italy</option>
                              <option value="Jamaica">Jamaica</option>
                              <option value="Japan">Japan</option>
                              <option value="Jersey">Jersey</option>
                              <option value="Jordan">Jordan</option>
                              <option value="Kazakhstan">Kazakhstan</option>
                              <option value="Kenya">Kenya</option>
                              <option value="Kiribati">Kiribati</option>
                              <option value="Korea, Democratic People's Republic of">Democratic People's Republic of Korea</option>
                              <option value="Republic of Korea">Republic of Korea</option>
                              <option value="Kuwait">Kuwait</option>
                              <option value="Kyrgyzstan">Kyrgyzstan</option>
                              <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                              <option value="Latvia">Latvia</option>
                              <option value="Lebanon">Lebanon</option>
                              <option value="Lesotho">Lesotho</option>
                              <option value="Liberia">Liberia</option>
                              <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                              <option value="Liechtenstein">Liechtenstein</option>
                              <option value="Lithuania">Lithuania</option>
                              <option value="Luxembourg">Luxembourg</option>
                              <option value="Macao">Macao</option>
                              <option value="The Former Yugoslav Republic of Macedonia">The Former Yugoslav Republic of Macedonia</option>
                              <option value="Madagascar">Madagascar</option>
                              <option value="Malawi">Malawi</option>
                              <option value="Malaysia">Malaysia</option>
                              <option value="Maldives">Maldives</option>
                              <option value="Mali">Mali</option>
                              <option value="Malta">Malta</option>
                              <option value="Marshall Islands">Marshall Islands</option>
                              <option value="Martinique">Martinique</option>
                              <option value="Mauritania">Mauritania</option>
                              <option value="Mauritius">Mauritius</option>
                              <option value="Mayotte">Mayotte</option>
                              <option value="Mexico">Mexico</option>
                              <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                              <option value="Republic of Moldova">Republic of Moldova</option>
                              <option value="Monaco">Monaco</option>
                              <option value="Mongolia">Mongolia</option>
                              <option value="Montenegro">Montenegro</option>
                              <option value="Montserrat">Montserrat</option>
                              <option value="Morocco">Morocco</option>
                              <option value="Mozambique">Mozambique</option>
                              <option value="Myanmar">Myanmar</option>
                              <option value="Namibia">Namibia</option>
                              <option value="Nauru">Nauru</option>
                              <option value="Nepal">Nepal</option>
                              <option value="Netherlands">Netherlands</option>
                              <option value="Netherlands Antilles">Netherlands Antilles</option>
                              <option value="New Caledonia">New Caledonia</option>
                              <option value="New Zealand">New Zealand</option>
                              <option value="Nicaragua">Nicaragua</option>
                              <option value="Niger">Niger</option>
                              <option value="Nigeria">Nigeria</option>
                              <option value="Niue">Niue</option>
                              <option value="Norfolk Island">Norfolk Island</option>
                              <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                              <option value="Norway">Norway</option>
                              <option value="Oman">Oman</option>
                              <option value="Pakistan">Pakistan</option>
                              <option value="Palau">Palau</option>
                              <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                              <option value="Panama">Panama</option>
                              <option value="Papua New Guinea">Papua New Guinea</option>
                              <option value="Paraguay">Paraguay</option>
                              <option value="Peru">Peru</option>
                              <option value="Philippines">Philippines</option>
                              <option value="Pitcairn">Pitcairn</option>
                              <option value="Poland">Poland</option>
                              <option value="Portugal">Portugal</option>
                              <option value="Puerto Rico">Puerto Rico</option>
                              <option value="Qatar">Qatar</option>
                              <option value="Reunion">Reunion</option>
                              <option value="Romania">Romania</option>
                              <option value="Russian Federation">Russian Federation</option>
                              <option value="Rwanda">Rwanda</option>
                              <option value="Saint Helena">Saint Helena</option>
                              <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                              <option value="Saint Lucia">Saint Lucia</option>
                              <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                              <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                              <option value="Samoa">Samoa</option>
                              <option value="San Marino">San Marino</option>
                              <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                              <option value="Saudi Arabia">Saudi Arabia</option>
                              <option value="Senegal">Senegal</option>
                              <option value="Serbia">Serbia</option>
                              <option value="Seychelles">Seychelles</option>
                              <option value="Sierra Leone">Sierra Leone</option>
                              <option value="Singapore">Singapore</option>
                              <option value="Slovakia">Slovakia</option>
                              <option value="Slovenia">Slovenia</option>
                              <option value="Solomon Islands">Solomon Islands</option>
                              <option value="Somalia">Somalia</option>
                              <option value="South Africa">South Africa</option>
                              <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                              <option value="Spain">Spain</option>
                              <option value="Sri Lanka">Sri Lanka</option>
                              <option value="Sudan">Sudan</option>
                              <option value="Suriname">Suriname</option>
                              <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                              <option value="Swaziland">Swaziland</option>
                              <option value="Sweden">Sweden</option>
                              <option value="Switzerland">Switzerland</option>
                              <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                              <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                              <option value="Tajikistan">Tajikistan</option>
                              <option value="United Republic of Tanzania">United Republic of Tanzania</option>
                              <option value="Thailand">Thailand</option>
                              <option value="Timor-leste">Timor-leste</option>
                              <option value="Togo">Togo</option>
                              <option value="Tokelau">Tokelau</option>
                              <option value="Tonga">Tonga</option>
                              <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                              <option value="Tunisia">Tunisia</option>
                              <option value="Turkey">Turkey</option>
                              <option value="Turkmenistan">Turkmenistan</option>
                              <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                              <option value="Tuvalu">Tuvalu</option>
                              <option value="Uganda">Uganda</option>
                              <option value="Ukraine">Ukraine</option>
                              <option value="United Arab Emirates">United Arab Emirates</option>
                              <option value="United Kingdom">United Kingdom</option>
                              <option value="United States">United States</option>
                              <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                              <option value="Uruguay">Uruguay</option>
                              <option value="Uzbekistan">Uzbekistan</option>
                              <option value="Vanuatu">Vanuatu</option>
                              <option value="Venezuela">Venezuela</option>
                              <option value="Viet Nam">Viet Nam</option>
                              <option value="Virgin Islands, British">Virgin Islands, British</option>
                              <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                              <option value="Wallis and Futuna">Wallis and Futuna</option>
                              <option value="Western Sahara">Western Sahara</option>
                              <option value="Yemen">Yemen</option>
                              <option value="Zambia">Zambia</option>
                              <option value="Zimbabwe">Zimbabwe</option>
                            </select>
                          </div>
                        </div>
                        <div class="p-3 text-center">
                          <input type="reset" name="reset" class="btn btn-secondary w-25" value="Reset">
                          <input type="submit" name="profile_save" class="btn btn-primary w-25" value="Save">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade border" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                  <div class="row">
                    <div class="col-md-12">
                      <form action="" method="POST" autocomplete="off" novalidate="novalidate">
                        <div class="p-2 border-bottom d-flex">
                          <p class="lead m-0 p-1">Change Password</p><span class="p-1 font-italic password-verify">
                        </div>
                        <div class="d-flex input-wrapper mb-3">
                          <div class="col-5 col-md-3 p-2">
                            <label for="must_password" class="font-weight-bold account-titles">Current Password</label>
                          </div>
                          <div class="col-7 col-md-7">
                            <input type="password" class="form-control" name="must_password" id="must_password" autocomplete="off">
                            <span class="text-danger"><?= $errors['must_password'] ?></span>
                          </div>
                        </div>
                        <div class="d-flex input-wrapper mt-0">
                          <div class="col-5 col-md-3 p-2">
                            <label for="new_password" class="font-weight-bold account-titles">New Password &nbsp;</label>
                          </div>
                          <div class="col-7 col-md-7">
                            <input type="password" class="form-control" name="new_password" id="new_password" autocomplete="off">
                            <span class="text-danger"><?= $errors['new_password'] ?></span>
                          </div>
                        </div>
                        <div class="p-3 text-center">
                          <input type="reset" name="reset" class="btn btn-secondary w-25" value="Reset">
                          <input type="submit" name="security_save" class="btn btn-primary w-25" value="Save">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                  <div class="row">
                    <div class="col-lg-8">
                      <form action="" method="POST" autocomplete="off" novalidate="novalidate">
                        <div class="edit-personal-main">
                          <p class="lead mb-0 ml-1">Permanently Delete Account</p>
                          <hr class="mt-1">
                          <label>
                            You are about to permanently delete your account.<br> Keep in mind that once the deletion process begins, you won't be able to recover your account or retrieve any of the content or information you have added.<br> </label>
                          <input type="submit" class="btn btn-primary float-right" value="Delete Account">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
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