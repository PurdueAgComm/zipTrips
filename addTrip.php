<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");
?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Create a zipTrip</h1>
          <ol class="breadcrumb">
  			<li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="cPanel.php">Control Panel</a></li>
            <li class="active">Create a zipTrip</li>
           </ol>
        </div>
     	</div>


		<div class="row">
			<div class="col-lg-6 col-md-6">
				<?php
			  	    if ($_SESSION["success"] != "") {
				        echo "<div class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Success!</strong> " . $_SESSION["success"] . "</p></div>";
				        $_SESSION["success"] = "";
					}

				    if ($_SESSION["error"] != "") {
				        echo "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Error!</strong> " . $_SESSION["error"] . "</p></div>";
				        $_SESSION["error"] = "";
					}
				?>


				<p>On this page, you'll create a zipTrip and provide general information for that zipTrip. On the next page, you'll provide information for each individual showing for the zipTrip. Remember, a zipTrip is independent from their showing.</p>
	     		<form class="form-horizontal" action="functions/doTrip.php" method="post" role="form">
				  <div class="form-group <?php if($_SESSION['errorTitle']) echo 'has-error'; ?>">
				    <label for="addTitle" class="col-sm-2 control-label">Title</label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" value="<?php echo $_SESSION['addTitle']; ?>"  id="addTitle" name="addTitle" placeholder="Title of zipTrip">
				    </div>
				  </div>

				  <div class="form-group <?php if($_SESSION['errorDescription']) echo 'has-error'; ?>">
				    <label for="addDescription" class="col-sm-2 control-label">Description</label>
				    <div class="col-sm-8">
				      <textarea type="text" rows="8" class="form-control" id="addDescription" name="addDescription" placeholder="Tell us about the zipTrip. This description will be used throughout the website."><?php echo $_SESSION['addDescription']; ?></textarea>
				    </div>
				  </div>

				  <div class="form-group <?php if($_SESSION['errorGrade']) echo 'has-error'; ?>">
				    <label for="addGrade" class="col-sm-2 control-label">Targets</label>
				    <div class="col-sm-8">
				      <div class="input-group">
				   			<select class="form-control" id="addGrade" name="addGrade">
					   			<?php
									$gradeArray = array("Choose a Grade", "6th",  "7th", "8th", "High School");
									foreach($gradeArray as $value) {
										if ($_SESSION['addGrade'] == $value) {
											echo "<option selected='selected' value='".$value."'> " .$value. "</option>";
										} else {
											echo "<option value='".$value."'> ".$value."</option>";
										}
									}
								?>
							</select>
							<span class="input-group-addon">graders</span>
						</div>
				    </div>
				  </div>




				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-8">
				      <input name="action" value="create" type="hidden" />
				      <button type="submit" class="btn btn-success">Create Trip</button>
				    </div>
				  </div>
				</form>
			</div>
		</div>





<?php
include_once("includes/footer.php");
?>
