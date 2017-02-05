<?php
  require_once('../private/initialize.php');

  // Set default values for all variables the page needs.
  $errors = array();
  $first_name = '';
  $last_name = '';
  $email = '';
  $username = '';
  $con = db_connect();

  // if this is a POST request, process the form
  // Hint: private/functions.php can help
  if (is_post_request()) {

    // Confirm that POST values are present before accessing them.
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['username'])) {
      $first_name = h($_POST['first_name']);
      $last_name = h($_POST['last_name']);
      $email = h($_POST['email']);
      $username = h($_POST['username']);

      // Perform Validations
      // Hint: Write these in private/validation_functions.php
      /* Validate First Name Errors */
      if (is_blank($first_name)) {
        array_push($errors, 'First name cannot be blank.');
      }
      if (!has_length($first_name, array(2, 255))) {
        array_push($errors, 'First name must be between 2 and 255 characters.');
      }
      /* Validate Last Name Errors */
      if (is_blank($last_name)) {
        array_push($errors, 'Last name cannot be blank.');
      }
      if (!has_length($last_name, array(2, 255))) {
        array_push($errors, 'Last name must be between 2 and 255 characters.');
      }
      /* Validate Email Errors */
      if (is_blank($email)) {
        array_push($errors, 'Email cannot be blank.');
      }
      if (!has_valid_email_format($email)) {
        array_push($errors, 'Email must be a valid format.');
      }
      /* Validate Username Errors */
      if (is_blank($username)) {
        array_push($errors, 'Username cannot be blank.');
      }
      if (!has_length($username, array(8, 255))) {
        array_push($errors, 'Username must be at least 8 characters.');
      }
      
      /* BONUS 1 Validate Whitelisted Characters */
      if (preg_match('/\A[A-Za-z\s\-,\.\']+\Z/', $first_name) == 0) {
        array_push($errors, 'Username can only contain letters, spaces, and symbols: [-,.\']');
      }
      if (preg_match('/\A[A-Za-z\s\-,\.\']+\Z/', $last_name) == 0) {
        array_push($errors, 'Lastname can only contain letters, spaces, and symbols: [-,.\']');
      }
      if (preg_match('/\A[A-Za-z0-9_@\.]+\Z/', $email) == 0) {
        array_push($errors, 'Email can only contain letters, numbers, and symbols: [_@.]');
      }
      if (preg_match('/\A[A-Za-z0-9_]+\Z/', $username) == 0) {
        array_push($errors, 'Username can only contain letters, numbers, and symbols: [_]');
      }

      /* BONUS 2 Validate Username Uniqueness */
      $sql = sprintf("SELECT username FROM users WHERE username='$username'");
      $q = db_query($con, $sql);
      if (mysqli_num_rows($q) != 0) {
        array_push($errors, 'Username already exists.');
      }

      // if there were no errors, submit data to database
      if (empty($errors)) {

        // Write SQL INSERT statement
        $created_at = date("Y-m-d H:i:s");
        $sql = sprintf("INSERT INTO users (first_name, last_name, email, username, created_at) 
          VALUES ('$first_name', '$last_name', '$email', '$username', '$created_at')");

        // For INSERT statments, $result is just true/false
        $result = db_query($con, $sql);
        if ($result) {
          db_close($con);
          
          //   TODO redirect user to success page
          redirect_to('registration_success.php');
        
        } else {
          // The SQL INSERT statement failed.
          // Just show the error, not the form
          echo db_error($con);
          db_close($con);
          exit;
        }
      }
    }
  }

?>

<?php $page_title = 'Register'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <h1>Register</h1>
  <p>Register to become a Globitek Partner.</p>

  <?php
    // TODO: display any form errors here
    // Hint: private/functions.php can help
    echo display_errors($errors);
  ?>

  <!-- TODO: HTML form goes here -->
  <form action="register.php" method="POST">
    First name:<br>
    <input type="text" name="first_name" value="<?php echo $first_name ?>">
    <br>
    Last name:<br>
    <input type="text" name="last_name" value="<?php echo $last_name ?>">
    <br>
    Email:<br>
    <input type="text" name="email" value="<?php echo $email ?>">
    <br>
    Username:<br>
    <input type="text" name="username" value="<?php echo $username ?>">
    <br><br>
  <input type="submit" value="Submit">
</form> 

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
