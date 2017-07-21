
<?php
include_once("includes/header.php");

$sql = "SELECT * FROM tblTrip JOIN tblTripShow ON tblTrip.intTripID = tblTripShow.intTripID WHERE isArchived=0 AND tblTrip.isFeatured=1 ORDER BY tblTripShow.dateBegin LIMIT 1";
$result = mysql_query($sql);
$featured = mysql_fetch_array($result);

$sql = "SELECT * FROM tblTrip WHERE isArchived=0";
$result = mysql_query($sql);
$numTrips = mysql_num_rows($result);

$sqlBanner = "SELECT * FROM tblTrip WHERE isBanner=1 AND isArchived=0";
$resultBanner = mysql_query($sqlBanner);
$numTrips = mysql_num_rows($resultBanner);

?>

    <div id="myCarousel" class="carousel slide hidden-xs">
      <!-- Indicators -->
        <ol class="carousel-indicators">
          <?php
          for($i=0; $i<=$numTrips-1; $i++) {
            if($i == 0) {
              echo "<li data-target='#myCarousel' data-slide-to='" . $i . "' class='active'></li>";
            }
            else {
              echo "<li data-target='#myCarousel' data-slide-to='" . $i . "'></li>";
            }
          }
          ?>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">

        <?php
        $i=1;
        while($trip = mysql_fetch_array($resultBanner)) {
          if($i==1) {
        ?>
              <div class="item active">
                <a href="<?= "joinTrip.php?tripID=" . $trip["intTripID"] ?>">
                  <div class="fill" style="background-image:url('<?= $trip["strPhoto600"]?>');"></div>
                  <span class="sr-only"><?= $trip["strTitle"] ?></span>
                </a>
                  <div class="carousel-caption">
                 <!--  <h2><?= $trip["strTitle"] ?></h2>
                  <p><?= $trip["strBlurb"]?></p> -->
                </div>
              </div>
          <?php }
          else { ?>
              <div class="item">
              <a href="<?= "joinTrip.php?tripID=" . $trip["intTripID"] ?>">
                <div class="fill" style="background-image:url('<?= $trip["strPhoto600"]?>');"></div>
                <span class="sr-only"><?= $trip["strTitle"] ?></span>
              </a>
                <div class="carousel-caption">
                 <!--  <h2><?= $trip["strTitle"] ?></h2>
                  <p><?= $trip["strBlurb"]?></p> -->
                </div>
              </div>
          <?php } $i++; } ?>
          </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
          <span class="icon-prev"></span>
          <span class="sr-only">Previous Slide</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
          <span class="icon-next"></span>
          <span class="sr-only">Next Slide</span>
        </a>
    </div>


    <div class="section highlight-blue text-center">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <h3>Take your students on a FREE, fun, fact-filled science field trip&mdash;without leaving school!</h3>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container -->
    </div><!-- /.section-colored -->



    <div class="section">
      <div class="container">

        <?php if(empty($_SESSION["strRole"])) { ?>
        <div class="row">
          <div class="col-lg-5 col-md-5">
            <h3><i class="fa fa-plus"></i> Brand New? Register to create an account.</h3>
            <p>Register for an account to access future trips, past trips, and online resources.</p>
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
                          <button class='btn btn-default add-on' type="button" id="togglePasswordField" style="display:none;"><i rel="tooltip" title="Show password" class='fa fa-eye'></i><span class="sr-only">Toggle Password</span></button>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-8">
                    <input name="action" value="register" type="hidden" />
                    <button id="indexRegister" type="submit" class="btn btn-success">Register</button> &nbsp;&nbsp;<a href="signin.php">Already signed up?</a>
                  </div>
                </div>
            </form>
          </div>

           <div class="col-lg-2 col-md-2 visible-lg visible-md" style="text-align:center;">
            <img src="images/lineDivider.png" alt="line dividing the two forms" />
           </div>

          <div class="col-lg-5 col-md-5">
            <h3><i class="fa fa-sign-in"></i> Returning visitor? Log in here to access your account.</h3>
            <p>Welcome back! Log in with the email and password you used to register.</p>
            <form class="form-horizontal" action="functions/doAccount.php" method="post" role="form">
              <div class="form-group <?php if($_SESSION['errorEmail']) echo 'has-error'; ?>">
                <label for="signInEmail2" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-8">
                  <input type="text" value="<?php echo $_SESSION['signInEmail']; ?>" class="form-control" id="signInEmail2" name="signInEmail" placeholder="Email">
                </div>
              </div>
              <div class="form-group <?php if($_SESSION['errorPassword']) echo 'has-error'; ?>">
                <label for="signInPassword2" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-8">
                  <div class="input-group">
                      <input type="password" class="form-control" id="signInPassword2" name="signInPassword" placeholder="Password">
                      <div class="input-group-btn">
                        <button class='btn btn-default add-on' type="button" id="togglePasswordField2" style="display:none;"><i rel="tooltip" title="Show password" class='fa fa-eye'></i><span class="sr-only">Toggle Password</span></button>
                      </div>
                    </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                  <input name="action" value="login" type="hidden" />
                    <button id="indexLogin" type="submit" class="btn btn-success">Log In</button> &nbsp;&nbsp;<a href="recover.php">Forgot password?</a>
                </div>
              </div>
            </form>
          </div>
        </div><!-- /.row -->
        <?php } else { ?>
        <div class="row">
          <div class="col-lg-12">

            <a href="dashboard.php" class="btn btn-block btn-lg btn-default">You're logged in. Visit Your Dashboard <i class="fa fa-arrow-right"></i></a>

          </div>
        </div>
        <?php } ?>
      </div><!-- /.container -->
    </div><!-- /.section -->



     <div class="section"  style="background-color: #efefef;">
      <div class="container">
        <div class="row">
          <div class="col-lg-5 col-md-5 col-sm-5 hidden-xs">
            <img class="img-responsive img-thumbnail" src="<?= $featured["strPhoto400"]?>" alt="<?= $featured["strTitle"]?> zipTrip" />
          </div>
          <div class="col-lg-7 col-md-7 col-sm-7">
            <h2><i class="fa fa-star"></i> Featured Trip: <?= $featured["strTitle"]?></h2>
            <p><?= $featured["strDescription"];?></p>
            <a id="indexLearnMore" class="btn btn-lg btn-block btn-success" href="joinTrip.php?tripID=<?= $featured["intTripID"]; ?>">Learn more</a>
         </div>
        </div><!-- /.row -->
      </div><!-- /.container -->
    </div><!-- /.section -->


    <div class="section">
      <div class="container">
         <div class="col-lg-12">
            <h2>Explore more zipTrips&trade;</h2>
            <hr>
          </div>

        <?php
        $i = 0;
        $count = 0;
        while($trip = mysql_fetch_array($result)) {
          ($i == 0) ? $row = "<div class='row'>" : $row = "";
          echo $row;
        ?>
          <div class="col-xs-11 col-md-4 hidden-sm hidden-xs">
            <div class="thumbnail pull-left col-xs-12">
               <img src="<?=$trip["strPhoto400"] ?>" alt="<?= $trip["strTitle"]?>" >
                  <div class="caption">
                    <h4 style="text-align: center;"><?=  $trip["strTitle"] ?></h4>
                    <?php
                      $sqlShow = "SELECT * FROM tblTripShow WHERE intTripID=" . $trip["intTripID"] . " ORDER BY dateBegin";
                      $resultShow = mysql_query($sqlShow) or die("sdf");
                      $show = mysql_fetch_array($resultShow);
                    if(!empty($show["intTripShowID"])) {
                    ?>
                    <p><a href="joinTrip.php?tripID=<?= $trip["intTripID"]?>" class="btn btn-default btn-block" role="button"><span style="color: #2a6496;">Learn more</span></a></p>
                    <?php } else { ?>
                  <p><a href="#" class="btn btn-default disabled btn-block" role="button"><i class="fa fa-times"></i> No Shows Available</a></p>
                    <?php } ?>
                  </div>
            </div>
          </div>

          <div class="panel panel-default hidden-md hidden-lg hidden-xl">
            <div class="panel-heading">
              <h2 class="panel-title"><i class="fa fa-rocket"></i> <strong><?= $trip["strTitle"]?></strong></h2>
            </div>
            <div class="panel-body">
              <div class="col-sm-5 hidden-xs">
                 <img src="<?=$trip["strPhoto250"] ?>" class="img-responsive" alt="<?= $trip["strTitle"]?>" >
            </div>
            <div class="col-xs-12 col-sm-7">
              <h4>About <?= $trip["strTitle"];?></h4>
              <p><?= $trip["strDescription"] ?></p>
               <?php
                $sqlShow = "SELECT * FROM tblTripShow WHERE intTripID=" . $trip["intTripID"] . " ORDER BY dateBegin";
                $resultShow = mysql_query($sqlShow) or die("sdf");
                $show = mysql_fetch_array($resultShow);
                  if(!empty($show["intTripShowID"])) {
                  ?>
                  <p><a href="joinTrip.php?tripID=<?= $trip["intTripID"]?>" class="btn btn-default btn-block" role="button">Learn more</a></p>
                  <?php } else { ?>
                <p><a href="#" class="btn btn-default disabled btn-block" role="button"><i class="fa fa-times"></i> No Shows Available</a></p>
                  <?php } ?>
            </div>
            </div>
          </div>

        <?php
        $count++;
          if($i==0) {
            $i=3;
          } else if ($i == 1) {
          echo "</div>";
          echo "<br />";
          }

        $i--;
        $count++;

        }

        if($numTrips%3 != 0) {
          echo "</div>";
        }

        ?>
      </div>
    </div>

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


(function() {
  try {

    // switch the password field to text, then back to password to see if it supports
    // changing the field type (IE9+, and all other browsers do). then switch it back.
    var passwordField = document.getElementById('signInPassword2');
    passwordField.type = 'text';
    passwordField.type = 'password';

    // if it does support changing the field type then add the event handler and make
    // the button visible. if the browser doesn't support it, then this is bypassed
    // and code execution continues in the catch() section below
    var togglePasswordField = document.getElementById('togglePasswordField2');
    togglePasswordField.addEventListener('click', togglePasswordFieldClicked2, false);
    togglePasswordField.style.display = 'inline';

  }
  catch(err) {

  }
})();

function togglePasswordFieldClicked2() {
  var passwordField = document.getElementById('signInPassword2');
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


<?php
include_once("includes/footer.php");
?>
