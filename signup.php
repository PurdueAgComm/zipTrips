
<?php 
include_once('includes/header.php'); 
include_once('functions/user-functions.php');

?>

<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Register <small>Create an account and start exploring zipTrips&trade; today!</small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php">Home</a></li>
            <li class="active">Register</li>
          </ol>
        </div>
      </div>



        <div class="row">
          <div class="col-lg-6 col-md-6">

			
          	<?php
          	($_SESSION["isRegistrationEnabled"]) ? $disabled = "" : $disabled = "<div class='alert alert-warning'>Sign Ups are currently closed.</div>";
          	
          	echo $disabled;

	  	    if ($_SESSION["success"] != "") {
		        echo "<div class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Success!</strong> " . $_SESSION["success"] . "</p></div>";
		        $_SESSION["success"] = "";
			}

		    if ($_SESSION["error"] != "") {
		        echo "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Error!</strong> " . $_SESSION["error"] . "</p></div>";
		        $_SESSION["error"] = "";
			}
			?>


          	<p>Register for an account to access future trips, past trips, and online resources.</p>
          	<p>

          	</p>
			<form class="form-horizontal" action="functions/doAccount.php" method="post" role="form">
			  <div class="form-group <?php if($_SESSION['errorEmail']) echo 'has-error'; ?>">
			    <label for="signInEmail" class="col-sm-2 control-label">Email</label>
			    <div class="col-sm-8">
			      <input type="email" class="form-control" value="<?php echo $_SESSION['signInEmail']; ?>"  id="signInEmail" name="signInEmail" placeholder="Email">
			    </div>
			  </div>
			  <div class="form-group <?php if($_SESSION['errorPassword']) echo 'has-error'; ?>">
			    <label for="signInPassword" class="col-sm-2 control-label">Password</label>
			    <div class="col-sm-8">
			    	<div class="input-group">
			      		<input type="password" class="form-control" id="signInPassword" name="signInPassword" placeholder="Password">
			      		<div class="input-group-btn">
			      			<button class='btn btn-default add-on' type="button" id="togglePasswordField" style="display:none;"><i rel="tooltip" title="Show password" class='fa fa-eye'></i></button>
			      		</div>
			      	</div>
			    </div>
			  </div>
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-8">
			      <input name="action" value="register" type="hidden" />
			      <button type="submit" <?php if(!$_SESSION["isRegistrationEnabled"]) echo "disabled" ?> class="btn btn-success">
			      	<?php 
			      	if(!$_SESSION["isRegistrationEnabled"]) { 
			      		echo "<i class='fa fa-ban'></i> Registration Closed"; 
			      	} 
			      	else { 
			      		echo "Register"; 
			      	}  
			      	?>

				  </button> &nbsp;&nbsp;<a href="signin.php">Already signed up?</a>
			    </div>
			  </div>
			</form>
		</div>
		<div class="col-lg-6 col-md-6">
			<img src="https://dev.www.purdue.edu/ziptrips/images/kids.jpg" class="thumbnail pull-right" alt="group of students posing for camera" />
		</div>


<?php 
	$_SESSION["errorEmail"] = 0;
	$_SESSION["errorPassword"] = 0;
?>


<script>
(function() {
	try {

		// switch the password field to text, then back to password to see if it supports
		// changing the field type (IE9+, and all other browsers do). then switch it back.
		var passwordField = document.getElementById('signInPassword');
		passwordField.type = 'text';
		passwordField.type = 'password';
		
		// if it does support changing the field type then add the event handler and make
		// the button visible. if the browser doesn't support it, then this is bypassed
		// and code execution continues in the catch() section below
		var togglePasswordField = document.getElementById('togglePasswordField');
		togglePasswordField.addEventListener('click', togglePasswordFieldClicked, false);
		togglePasswordField.style.display = 'inline';
		
	}
	catch(err) {

	}
})();
function togglePasswordFieldClicked() {
	var passwordField = document.getElementById('signInPassword');
	var value = passwordField.value;
	if(passwordField.type == 'password') {
		passwordField.type = 'text';
		togglePasswordField.innerHTML = "<i rel='tooltip' title='Hide password' class='fa fa-eye-slash'></i>";
	}
	else {
		passwordField.type = 'password';
		togglePasswordField.innerHTML = "<i rel='tooltip' title='Show password' class='fa fa-eye'></i>";
		
	}
	passwordField.value = value;
} 
</script>


<?php include_once('includes/footer.php'); ?>