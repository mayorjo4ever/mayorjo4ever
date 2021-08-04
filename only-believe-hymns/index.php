<?php 
	error_reporting(0);  
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" /> 
		<link rel="shortcut icon" href="imgs/icon.jpg">
		<script src="assets/js/jquery.min.js"></script>
		<title> Only Believe Song Book </title>
		<style> 
			body { background-image:url('imgs/eagle4.jpg'); background-color: #888888;   }
			/** css switch style sheet **/
			/* The switch - the box around the slider */
				.switch {
				  position: relative;
				  display: inline-block;
				  width: 60px;
				  height: 34px;
				}

				/* Hide default HTML checkbox */
				.switch input {
				  opacity: 0;
				  width: 0;
				  height: 0;
				}

				/* The slider */
				.slider {
				  position: absolute;
				  cursor: pointer;
				  top: 0;
				  left: 0;
				  right: 0;
				  bottom: 0;
				  background-color: #dcdcdc;
				  -webkit-transition: .4s;
				  transition: .4s;
				}

				.slider:before {
				  position: absolute;
				  content: "";
				  height: 26px;
				  width: 26px;
				  left: 4px;
				  bottom: 4px;
				  background-color: white;
				  -webkit-transition: .4s;
				  transition: .4s;
				}

				input:checked + .slider {
				  background-color:  #000000;  /* #50c378; */
				}

				input:focus + .slider {
				  box-shadow: 0 0 1px #000000;  /* #50c378; */
				}

				input:checked + .slider:before {
				  -webkit-transform: translateX(26px);
				  -ms-transform: translateX(26px);
				  transform: translateX(26px);
				}

				/* Rounded sliders */
				.slider.round {
				  border-radius: 34px;
				}

				.slider.round:before {
				  border-radius: 50%;
				}

			/** end switch style **/ 
		</style>
	</head>
	
 <body> 
	<form method="post">
			<div class="row">
				<div class="col-md-8 col-sm-12 offset-2" style="padding-top:20px; margin-top:10px; height:200px;  background-color:#FFF; background:url('imgs/eagle.jpg') no-repeat center; box-shadow: 6px 5px 5px 5px #666888; ">
					&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  <a href="../kjv-bible" class="text-white  btn btn-primary">Open Bible </a> 
				</div>
					
			</div>
			<div class="row mb-5">
				<div class="col-md-8 col-sm-12 offset-2"  style="background-color:#FFF; min-height:400px;  box-shadow:  5px 5px 5px 5px #888888; ">					 
						
					<table class="table" style="width:100%; margin-top:30px;" align="center"> 
						
						<tbody> 
							<tr>  
								<td> <div class="form-group">
									<label class="label-control font-weight-bold"> Search Hymn / Song Title  </label>
									<input type="text" value="<?php echo $_POST['song']; ?>" class="form-control border-dark" name="song" id="song" style="min-height:40px; font-size:24px" /> 
									</div>
								</td>
								<td>  
									<div class="form-group"> <label class="label-control">  &nbsp; </label> <br/>
										<button type="submit" class="btn btn-dark btn-lg"  id="search" name="search"> Search </button> 
									</div>
								 </td>
								<td>  
									<label class="switch mt-5"> 
									  <input type="checkbox" value="yes" > 
									  <span class="slider round"></span>
									</label> &nbsp; &nbsp;  <span class="small"> <strong> <i> Enable Edit </i></strong> </span>
								</td>
							</tr> 
						 
						</tbody>			
					</table>
					
					<table class="table" style="width:100%" align="center"> 
						 
						<tbody class="result"> 
							
								 
						</tbody>
					</table>
			
			</div> <!-- ./ col-md-6-->
			</div> <!-- ./ row -->
		</form>
	
	<footer class="footer bg-dark">
		
	  <div class="row">
		<div class="col-md-8 offset-2 mt-3 mb-3 pt-3 pb-3">
		<span class="text-white d-block text-center text-sm-left d-sm-inline-block font-16"> Copyright © 2021 &nbsp;    End-Time Message Believers Ministry, Ilorin Church, Kwara State
		  <a href="" target="_blank"> : <?php echo $system_info['dev_by']; ?></a>. All rights reserved. </span>						 
		</span>
	  </div> </div> 
	</footer>
	
	<script>
		$(function(){
			 $('button#search').on('click',function(evt){
				 evt.preventDefault(); 
				 label = $('label.report'); 
				 input_var = $('input#song').val(); 
				 allow_edit = $('input:checkbox:checked').val(); 
				 // alert(allow_edit);
				  $.ajax({
						url: "ajax.php",
						type: "POST",
						data: { search:"this",song:input_var, allow_edit:allow_edit},
						cache: false,
						beforeSend : function (){ console.log(allow_edit);  }, 
						success: function(response) {
							$('tbody.result').html(response);
							// 
						}
					  }); 
				  
			 }); // encode 
			 
			 //////////
			  $('input#song').on('keyup',function(evt){
				//  evt.preventDefault();  
				 input_var = $(this).val(); 
				 if(input_var !="") 
					 $('button#search').click(); 
				  
			 }); // encode  
			 
			 $('input:checkbox').on('click',function(){ $('button#search').click();  });
		});
		
		function processUpdate(old,news){
			$.ajax({
				url: "ajax.php",
				type: "POST",
				data: { update_hymn:"this",old : old, news : news },
				cache: false,

				success: function(response) {
					alert(response); 
				}
			  }); 
		}
	</script>
	 
	
	</body>
	
	</html>