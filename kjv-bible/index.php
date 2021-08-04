<?php 
	error_reporting(0);  	
	require("bible_to_sql.php");
	@session_start(); 
?>
	
<!DOCTYPE html>
<html lang="en">
	<head>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" /> 
	<link rel="stylesheet" href="assets/css/bootstrap-datepicker.css" /> 
	
	<link rel="stylesheet" href="assets/css/styles.css" /> 
	<link rel="shortcut icon" href="imgs/icon.jpg">
	<link rel="stylesheet" href="assets/fontawesome/css/all.min.css" /> 
	<script src="assets/js/jquery.min.js"></script>
	<title>Bible Search</title>
		<meta charset="utf-8" />	
	</head>
	
	<body> 
		
		<div class="row mb-5">
				<div class="col-md-12 col-sm-12 offset-0"  style="background-color:#FFF; min-height:700px;  box-shadow:  5px 5px 5px 5px #888888; ">					 
					<div class="card"> 
					<div class="card-header mt-5 pt-5">				
					
					 	<?php require "navbar.php"; ?>
					</div>	
						<div class="card-body ">
							<div class="row ">    
								
								<div class="col-md-3 col-sm-12 ">
									<div class="">
										<div class="col-sm-6 float-left bg-info " style="border-left:1px solid #ddd; border-right:1px solid #ddd; ">
											<label class="bold text-white ">BOOK </label>
										 </div>
										<div class="col-sm-3 float-left bg-info"  style="border-left:1px solid #ddd; border-right:1px solid #ddd; ">
											<label class="bold text-white">CHP.. </label> 
										</div>
										<div class="col-sm-3 float-left bg-info" style="border-left:1px solid #ddd; border-right:1px solid #ddd;">
											<label class="bold text-white">VERSE </label> 
										</div> 
									</div> 
									
									<div class="">
										<div class="col-sm-6 float-left section  pt-2 pb-2" style="border-left:1px solid #ddd; border-right:1px solid #ddd; height:450px; overflow:scroll;">											 
											<div class="book_ref "></div>
										</div>
										<div class="col-sm-3 float-left section pt-2 pb-2"  style="border-left:1px solid #ddd; border-right:1px solid #ddd; height:450px;  overflow:scroll; ">
										 <div class="chp_ref "></div>
										</div>
										<div class="col-sm-3 float-left section pt-2 pb-2" style="border-left:1px solid #ddd; border-right:1px solid #ddd;  height:450px;  overflow:scroll;">
											 <div class="vs_ref"></div>
										</div> 
									</div>  
									 
								</div>
								
								<div class="col-md-9 col-sm-12 verse"  style="border-left:15px solid #eeefff; border-top:15px solid #eeefff;">
									<form action="index.php" action="GET">
											<!-- <select name="v" selected="selected" value="<?php echo $version ?>">
												<?php 
													// Get the list of bible versions
													$stmt = $mysqli->prepare("SELECT `table`, version FROM bible_version_key");
													$stmt->execute();
													$result = $stmt->get_result();

													while ($row = $result->fetch_row()) {
														echo "<option value=\"$row[0]\"";
														// Make dropdown list select the currently selected version
														if ($row[0] === $version) {
															echo " selected=\"selected\"";
														}

														echo ">$row[1]</option>";
														// print_r($row);
													}
												?>
											</select>

											<label for="b">Reference(s): </label>
											<input type="text" name="b" value="<?php echo $refText; ?>" /><input type="submit" value="Search" /><br />
											-->
											<input type="hidden" id="vid1" value="" style="width:100px;" />
											<input type="hidden" id="vid2" value="" style="width:100px;" />
											<input type="hidden" id="vid3" value="" style="width:100px;" />
										</form>
										 
										<div class="verse_ref m-2 p-2" style=" height:550px;  overflow:scroll;"></div>
										
									<?php 
										//return results
										# addZeros(2,2);
										
										# print_r($_SESSION['passages']); 
										
										/***
										foreach ($references as $r) {
													
											$ret = new bible_to_sql($r, NULL, $mysqli);
											//echo "sql query: " . $ret->sql() . "<br />";
											//SELECT * FROM bible.t_kjv WHERE id BETWEEN 01001001 AND 02001005
											$sqlquery = "SELECT * FROM " . $version . " WHERE " . $ret->sql();
											$stmt = $mysqli->prepare($sqlquery);
											$stmt->execute();
											$result = $stmt->get_result();
											if ($result->num_rows > 0) {
												//$row = $result->fetch_array(MYSQLI_NUM);
												//0: ID 1: Book# 2:Chapter 3:Verse 4:Text
												
												  print "<article><header><strong>{$ret->getBook()} {$ret->getChapter()}</strong></header>";
												
												while ($row = $result->fetch_row()) { # print "<div class=\"versenum\">${row[3]}</div>";
												 print " <div class=\"versetext\">${row[3]}. ${row[4]} $default_text</div><br />";
												}
												print "</article>";
												
											} else {
												print "Did not understand your input.";
											}
											$stmt->close();
										}
										$mysqli->close(); ***/
									?>
								</div>

								</div><!-- ./ row  -->
						</div>
						
					</div>
	
		 
		
			</div> <!-- ./ col-md-6-->
			</div> <!-- ./ row -->
			
	</body>
	<footer class="footer bg-dark">
		
	  <div class="row">
		<div class="col-md-8 offset-2 mt-3 mb-3 pt-3 pb-3">
		<span class="text-white d-block text-center text-sm-left d-sm-inline-block font-16"> Copyright © 2021 &nbsp;    End-Time Message Believers Ministry, Ilorin Church, Kwara State
		  <a href="https://facebook.com/mayorjo4ever" target="_blank"> : mayorjo4ever </a>. All rights reserved. </span>						 
		</span>
	  </div> </div> 
	</footer>
	
	<?php require "modal.php"; ?>
	
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/script.js"></script>
	<script src="assets/fontawesome/js/all.min.js"></script>
	<link rel="stylesheet" href="assets/fontawesome/css/all.min.css" /> 
	
</html>
