<?php
include_once("includes/header.php");
?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Recover Your Account</h1>
          <ol class="breadcrumb">
  			<li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">Recover Your Account</li> 
           </ol>
        </div>
     	</div>


		<div class="row">
			 <div class="col-sm-8">   
             
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

<!-- Check if the URL includes the user's email and key -->
     <?php
if (empty($_GET['email']) && (empty($_GET['key']))) {?>

                    <form class="form-horizontal" action="functions/doAccount.php" method="post" rol="form">
                        <div class="form-group">
	         			    <label for="addEmail" class="col-sm-4 control-label">Enter your email</label>
                   			    <div class="col-sm-8">
                                  <input type="text" class="form-control" name="recoverEmail" placeholder="Login Email" value="<?php $_SESSION['recoverEmail']; ?>" >
                                    <input name="action" value="recover" type="hidden" />

		                         	  <div class="form-group">
		                        	    <div class="col-sm col-sm-8"><br />
                                           <button type="submit" name="recover" value="Submit" class="btn btn-success">Request Password</button>
		                        	    </div>   
		                         	  </div>
                   			    </div>
                         </div>
                    </form>
                    <?php
                    }?>
                    
                    
 <!-- Check if the URL includes the user's email and key -->
     <?php
if (!empty($_GET['email']) || (!empty($_GET['key']))) {?>

                    <form class="form-horizontal" action="functions/doAccount.php" method="post" rol="form">
                        <div class="form-group">
                            <div class="input-group" <?php if($_SESSION['errorNewPass']) echo 'has-error'; ?>>
	         			    <label for="nPass" class="col-sm-4 control-label">New password</label>
                   			    <div class="col-sm-8">
                                 <input type="password" class="form-control" name="newPass" placeholder="New Password" value="<?php $_SESSION['newPass']; ?>" >
         			            </div>
                            <label for="cPass" class="col-sm-4 control-label">Confirm password</label>
                   			    <div class="col-sm-8">
                                    <input type="password" class="form-control" name="confirmPass" placeholder="Confirm Password" value="<?php $_SESSION['confirmPass']; ?>" >  
                                      <input name="action" value="updatePass" type="hidden" />
                                      <input name="hiddenEmail" value="<?php $_GET['email']; ?>" type="hidden" />

		                         	  <div class="form-group">
		                        	    <div class="col-sm col-sm-8"><br />
                                           <button type="submit" name="newPassButton" value="Submit" class="btn btn-success">Submit</button>
		                        	    </div>  
		                         	  </div>
                   			    </div>
                           </div>                                
                        </div>                        
                    </form>
                    <?php
                    }?>                   
                    

                    
                    
<!-- Close the page divs -->                    
			 </div>
		</div>       
    
     
        
        
                           <?php
						   
						  $_SESSION['getEmail'] = $_GET['email'];

						  $_SESSION['getKey'] = $_GET['key'];
						  
						   
                           ?>  
        
        
        
        
        
        
        
<?php
include_once("includes/footer.php");
?>