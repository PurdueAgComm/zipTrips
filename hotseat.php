<?php
include_once("includes/header.php");

$sqlFeatured = "SELECT * FROM tblTrip WHERE isFeatured=1";
$resultFeatured = mysql_query($sqlFeatured);
$featured = mysql_fetch_array($resultFeatured);

$sqlShowUpcoming = "SELECT * FROM tblTripShow WHERE intTripID=" . $featured["intTripID"] . " AND dateEnd > NOW() ORDER BY dateBegin ASC LIMIT 1";
$resultShowUpcoming = mysql_query($sqlShowUpcoming);
$numShowsUpcoming = mysql_num_rows($resultShowUpcoming);
$showUpcoming = mysql_fetch_array($resultShowUpcoming);


?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">zipTrips & Hotseat</h1>
          <ol class="breadcrumb">
  			<li><a href="index.php">Home</a></li>
            <li class="active">zipTrips & Hotseat</li> 
           </ol>
        </div>
     	</div>


		<div class="row">
			<div class="col-xs-12">
        <h3>What technology is needed?</h3>
        <p>You can access HotSeat from almost any device with an Internet connection. This
could be a desktop computer or mobile devices such as laptops, cell phones, iPads,
iPods, etc. The idea is for each student in your classroom (or they could work in
groups) to have access to a device that is connected to HotSeat.</p>

        <h3>How do students log on?</h3>
        <p><strong>Login at:</strong> <a href="http://www.purdue.edu/hotseat/open">www.purdue.edu/hotseat/open</a></p>
        <p>Students can use Gmail, Yahoo, AOL, or Facebook accounts to access HotSeat. You
will need to test this ahead of time to make sure your school’s firewall will allow
your students to access HotSeat. Each student will need to create a HotSeat profile.
We ask that students use their school names, instead of their own personal names.</p>
<blockquote>Example:<br/>
First name – Jones Last name – Middle School<br />
First name – Learning Last name ‐ Academy<br />
<small>By using the school name, we will be able to mention your school LIVE on the air.
We will not mention individual student names.</small></blockquote>


        <h3>How do I set up my classroom?</h3>
        <p>Ideally, you will have one computer connected to the LIVE zipTrips web stream.
(Connecting multiple computers will bog down your school’s network and cause the
program to delay, freeze, etc.) Then, project the web stream on to a screen in front
of the classroom.</p>

        <h3>How does HotSeat work?</h3>
        <p>Once your students are logged in to HotSeat, they can submit thoughts and
questions for the zipTrips scientists. Fellow students can then vote on questions
they like. We will ask the questions with the most votes LIVE on the air. Students will need to ‘change topics’ between zipTrips segments/scientists.</p>
        <blockquote>Example: <br />Topic #1 = questions for scientist #1 <br />
        Topic #2 = questions for scientist #2
        </blockquote>

        <h3>Preparation is key!</h3>
        <p>Do not wait until the last minute to use these two programs at your school. Make
sure to download the ‘Connecting to the LIVE Show’ guide from the Purdue zipTrips
website for the web streaming URL. Also, test your connection to the web stream
and HotSeat at least one week before the big event.</p>

<h3>Case Study: Nancy Hanks Elementary</h3>
<p>Nancy Hanks Elementary school in Ferdinand, IN pilot tested zipTrips + HotSeat
with us for the very first time in November 2011.</p>

<h4>Administrative and Parental Support</h4>
<p>he teacher kicked off her school’s participation by sending home a bulletin to
parents to let them know what was going on, to request permission for students to
bring in personal mobile devices from home, and to make sure students had Gmail,
Yahoo, or AOL accounts.</p>

<h4>Technology</h4>

<p>Nancy Hanks does not have mobile devices available for each of their students.
Therefore, each student brought in a laptop, cell phone, iPod, or iPad from home.
The teacher also provided her own devices for the students to use.</p>

<h4>Preparation</h4>

<p>The teacher projected a HotSeat test space (which we will provide to you before the
zipTrip) onto a screen in front of the class a week before the zipTrip. Students were
able to preview the test space and get a feel for how to post ideas and questions into
HotSeat. The school also had to open areas of their firewall to allow access to
HotSeat, Gmail, and Yahoo.</p>

<h4>Day of the zipTrip</h4>

<p>The teacher cut up strips of paper with the HotSeat website address for students’
quick reference during the LIVE show, in case they needed to log out or log back into
HotSeat. The zipTrips web stream was projected onto a Smart Board in front of the
classroom. Each student had a mobile device logged in to HotSeat and was able to
post ideas and questions for scientists during the zipTrip. The teacher and students
reminded one another to ‘switch topics’ between zipTrips segments. The 50
participating students made a total of 294 posts with 1,254 votes on the posted
questions.</p>

         

      </div>
    </div>
<?php
include_once("includes/footer.php");
?>