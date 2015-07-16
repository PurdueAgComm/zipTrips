<?php
include_once("includes/header.php");
?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Contact Us</h1>
          <ol class="breadcrumb">
  			<li><a href="index.php">Home</a></li>
            <li class="active">Contact Us</li> 
           </ol>
        </div>
     	</div>


		<div class="row">

      <div class="col-sm-6">
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


        <form class="form-horizontal" action="functions/doContact.php" method="post" role="form">
          <div class="form-group <?php if($_SESSION['errorName']) echo 'has-error'; ?>">
            <label for="contactName" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-8">
              <?php (!empty($_SESSION["userName"])) ? $name = $_SESSION["userName"] : $name = $_SESSION["contactName"]; ?>
              <input type="text" class="form-control" value="<?php echo $name ?>" id="contactName" name="contactName" placeholder="Your Name">
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorEmail']) echo 'has-error'; ?>">
            <label for="contactEmail" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-8">
              <?php (!empty($_SESSION["userEmail"])) ? $email = $_SESSION["userEmail"] : $email = $_SESSION["contactEmail"]; ?>
              <input type="email" class="form-control" value="<?php echo $email ?>" id="contactEmail" name="contactEmail" placeholder="Your Email">
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorGrade']) echo 'has-error'; ?>">
            <label for="contactSubject" class="col-sm-2 control-label">Subject</label>
            <div class="col-sm-8">
                <select class="form-control" id="contactSubject" name="contactSubject">
                  <?php
                  $subjectArray = array("Choose a Subject", "Question",  "Bug Report", "Sponsorship");
                  foreach($subjectArray as $value) {
                    if ($_SESSION['contactSubject'] == $value) {
                      echo "<option selected='selected' value='".$value."'> " .$value. "</option>";
                    } else {
                      echo "<option value='".$value."'> ".$value."</option>";
                    }
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group <?php if($_SESSION['errorMessage']) echo 'has-error'; ?>">
            <label for="contactMessage" class="col-sm-2 control-label">Message</label>
            <div class="col-sm-8">
              <textarea style="height: 120px;" class="form-control" value="<?php echo $_SESSION["contactMessage"]?>" id="contactMessage" name="contactMessage" placeholder="Write your message here..."><?= $_SESSION["contactMessage"] ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="sr-only col-sm-2 control-label" for="submit">Send Message</label>
            <div class="col-sm-8">
              <input id="botChecker" value="" name="botChecker" type="hidden" />
              <button type="submit" id="submit" class="btn btn-success"><i class='fa fa-envelope'></i> Send Message</button>
            </div>
          </div>
        </form>
      </div> <!-- /.col-sm-6 -->
    </div>
<?php
include_once("includes/footer.php");
?>

<script type="text/javascript">
$(document).ready(function(){
    $("#botChecker").val("notabot");
});

</script>