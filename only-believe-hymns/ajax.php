<?php 
	error_reporting(0); # ini_set('max_input_vars',2000); echo ini_get('max_input_vars');

	if(isset($_POST['search'])) {
		$folder = "HYMNS"; $song = ucwords($_POST['song']); $allow_edit = $_POST['allow_edit'];
		$books = glob("$folder/*$song*.pps");
		 $tot_books = count($books);
		   $n = 0;  if(!empty($books)) foreach($books as $book) { 
							$sep = explode("/",$book);
							$name = $sep[1]; 
							
							$dec = explode("_",$name); 
							$name2 = $dec[2]; 
					?>				
						<tr> 					
							<td style=""> <a href="<?php echo $book; ?>" style="font-size:24px;"><?php echo $name; ?></a>  </td>
							<?php if($allow_edit == "yes" ) {?>
							<td> 
								<input type="hidden" name="oldbook[]"  value="<?php echo $name;?>" />
								<input type="text" name="book[]" class="form-control" value="<?php echo ucwords($name);?>"  style="min-width:500px; height:40px; font-size:20px" />
							</td>
							<td><button type="button" data-text="<?php echo $name;?>" onclick="processUpdate($(this).attr('data-text'),$(this).closest('tr').find('input:text').val())" class="btn btn-dark btn-rounded  btn-lg hymn-update" > Update </button></td>
							<?php } // end $allow_edit ?>
						</tr>
					
					<?php $n++; } # end foreach 
					else {
						echo "<tr><td class='text-danger' style='font-size:24px;'> No Song Matches <strong> '$song' </strong></td></tr>";
					}
			} 
	
	if(isset($_POST['update_hymn'])) {
		$folder = "HYMNS";  
		$oldsong = $_POST['old']; $newsong = ucwords($_POST['news']);
			$x = "$folder/".$oldsong;
			 $y = "$folder/".$newsong; 
			 @rename($x,$y); 	
			echo "Update Successful"; 
		
	}
	
	
	
?>