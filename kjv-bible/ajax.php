<?php error_reporting(0); require "connector.php";	require "bible_to_sql.php"; @session_start(); 	
	
	if(isset($_POST['save_bible_passage'])) {
		 $passage = $dbm->clean($_POST['passage']);		 
		 if(!isset($_SESSION['passages'])) $_SESSION['passages'] = array(); 
		 if(!in_array($passage,$_SESSION['passages'])) array_push($_SESSION['passages'],$passage);
		 echo "Bible Verse Saved Successfully";
	}
	/*********************************/
	if(isset($_POST['get_bible_passage'])) {
		if(isset($_SESSION['passages']))  {
			$passages = array_map(function($text){
				return $text = "<li>".ucwords($text)."</li>";  
			},$_SESSION['passages']);
			echo "<ol style='line-height:2em;'>";
			foreach($passages as $text){
				echo $text;
			}
			echo "</ol>";
			echo "<span onclick='delete_bible_ref()' class='pointer text-danger' > &nbsp; &nbsp; &nbsp; Delete All &nbsp; &nbsp; <i class='fa fa-trash'> </i>  </span>"; 
		}  
		else echo "<ul><li class='text-danger'><span> No Saved Bible References  </span></li></ul>"; 
	}
	/*********************************/
	if(isset($_POST['delete_bible_passage'])) {
		if(isset($_SESSION['passages']))  {
			unset($_SESSION['passages']); 
		} 
	}
	
	/*******/
	# save_message_as_draft:"all", variables:variables
	/*********************************/
	if(isset($_POST['save_message_as_draft'])) {
		 $passages = $_SESSION['passages']; 	 $passages = empty($passages)?"":implode("**",$passages);
		 $variables = $_POST['variables'];	// [date,_notes,preacher,topic,note_title]; 
		
		 $keys = array('date_c','messages','preacher','topic','note_title');
		 $data = array_combine($keys,$variables); 
		 $data = array_merge($data,array('bible_ref'=>$passages));
		 $table = "messages";
		 $exists = $dbm->select($table,array('finalized'=>'no')); 
		 if(empty($exists)){
			 $dbm->insert($table,$data); 
			 echo "Note Successfully saved ";
		 }
		 else {
			 $dbm->updateTb($table,$data,array('finalized'=>'no')); 
			 echo "Note Successfully Updated ";
		 } 
	}
	
	
	# save_message_as_final:"all", variables:variables
	/*******/
	# save_message_as_draft:"all", variables:variables
	/*********************************/
	if(isset($_POST['save_message_as_final'])) {
		 $passages = $_SESSION['passages'];  $passages = empty($passages)?"":implode("**",$passages); 
		 $variables = $_POST['variables'];	// [date,_notes,preacher,topic,note_title]; 
		 $variables = array_map(function($text){ return htmlspecialchars($text); },$variables);
		 $keys = array('date_c','messages','preacher','topic','note_title');
		 $data = array_combine($keys,$variables); 
		 $data = array_merge($data,array('bible_ref'=>$passages,'finalized'=>'yes'));
		 $table = "messages";
		 $exists = $dbm->select($table,array('finalized'=>'no')); 
		 if(empty($exists)){
			 $dbm->insert($table,$data); 
			 echo json_encode(array('success',"Note Successfully Saved  and Finalized"));  unset($_SESSION['passages']);
		 }
		 else {
			 $dbm->updateTb($table,$data,array('finalized'=>'no')); 
			 echo json_encode(array('success',"Note Successfully Updated  and Finalized"));  unset($_SESSION['passages']);
		 } 
	}
		# count_total_messages:"all", 
	/*********************************/
	if(isset($_POST['count_total_messages'])) {
		 $table = "messages";
		 $total = $mydbm->num_rows("SELECT * FROM $table");
		 echo $total; 
	}
	
	
	
	
	if(isset($_POST['load_books'])) {
		 $books = $dbm->select('key_english',array(''));
		 echo "<ul style='list-style:none; margin:0em; padding:0em;'>";
		 if(!empty($books))foreach($books as $k=>$v){ 
			$id =  $books[$k]['b'];
		 ?>
			  <li onclick=" setIds('<?php echo addZeros($id,2); ?>','#vid1'),set_active_list('div.book_ref',this), load_chp('<?php echo $id; ?>','div.chp_ref')" class="pl-1"><?php echo $books[$k]['n']; ?></li>
			  <?php 
		 }
		 echo "</ul>"; 		 
	}
	
	/************************/
		
	if(isset($_POST['load_chapters'])) {
		 $book_id = $_POST['book_id'];
		 $chapters = $mydbm->runBaseQuery("SELECT DISTINCT MAX(c) as last from t_kjv WHERE b = $book_id ");		 
		 echo "<ul style='list-style:none; margin:0em; padding:0em;'>";
		 $len = range(1,$chapters[0]['last']); foreach($len as $ch) {
		 // $id =  $books[$k]['b'];
		 ?>
			<li onclick="setIds('<?php echo addZeros($ch,3); ?>','#vid2'), set_active_list('div.chp_ref',this), load_vs('<?php echo $book_id;?>','<?php echo $ch;?>','div.vs_ref')" class="pl-l"><?php echo $ch; ?></li>
			  <?php  
		 }
		 echo "</ul>"; 		 
	}
	/************************/
	
	# load_verses:"all", book_id:book_id, chapter:chapter
	if(isset($_POST['load_verses'])) {
		 $book_id = $_POST['book_id'];  $chapter = $_POST['chapter'];  
		 $verses = $mydbm->runBaseQuery("SELECT DISTINCT MAX(v) as last from t_kjv WHERE b = $book_id AND c = $chapter  ");		 
		 echo "<ul style='list-style:none; margin:0em; padding:0em;'>";
		 $len = range(1,$verses[0]['last']); foreach($len as $vs) {
		 // $id =  $books[$k]['b'];
		 ?>
			<li onclick="set_active_list('div.vs_ref',this), setIds('<?php echo addZeros($vs,3); ?>','#vid3'), show_verse('.verse_ref')" class="pl-l"><?php echo $vs; ?></li>
			  <?php  
		 }
		 echo "</ul>"; 		 
	}
	
	
	# show_verse:"all", id:id
	if(isset($_POST['show_verse'])) {
		 $id = $_POST['id'];  $table = "t_kjv";
		 $verses = $mydbm->runBaseQuery("SELECT * FROM $table WHERE id = $id ");		 
		
		if(!empty($verses)){  
			$bk = $verses[0]['b'];
			$ch = $verses[0]['c'];
			$vs = $verses[0]['v'];
			$text =  $verses[0]['t']; 
			
			# book name
			$bk_sql = $mydbm->runBaseQuery("SELECT n FROM key_english WHERE b = $bk "); 
			$bk_name = $bk_sql[0]['n'];
			
			#max verse
			$sql = $mydbm->runBaseQuery("SELECT DISTINCT MAX(v) as last from t_kjv WHERE b = $bk AND c = $ch  ");	
			$mxV = $sql[0]['last']; 
			
			$head =  " <h3> <b> $bk_name  $ch : $vs </b> </h3>"; 
			
			echo $head; 
			
			# show full scripture 
			
			for($i = 1; $i <= $mxV; $i++){
				
				$id = addZeros($bk,2)."".addZeros($ch,3)."".addZeros($i,3); 
				$newVerse = get_verse($id);
				$text = "<p> $i. ". $newVerse[0]['t'] ." </p>";
				
				if($i == $vs) $text = "<p class='active'><b> $text </b> </p> ";

				echo $text; 
			} 
			 
			}
			else { echo "Unknown Reference "; }
		}
	 
	 // read_text_verse:"all", book:texts 
	 if(isset($_POST['read_text_verse'])) {
		 $default_text = "";
		 $default_version = "t_kjv";
		//split at commas
		$refText = empty($_POST['book'])?$default_text:$_POST['book']; 
		$version = empty($_POST['v'])?$default_version:$_POST['v'];		
		$references = explode(",",$refText);
		 
		#  echo $refText; 
		## search where the scripture is 
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
					print "<span class='text-danger'>Did not understand your input. </span>";
				}
				$stmt->close();
			}
			$mysqli->close(); 
		## end search 		 
	 }
	  
	 // search_bible_words :"all", texts:texts
	 if(isset($_POST['search_bible_words'])) {
		 $default_text = "";
		 $default_version = "t_kjv";  $table = "t_kjv";
		//split at commas
		 $refText = empty($_POST['texts']) ? $default_text : $dbm->clean($_POST['texts']); 		 
		 $version = empty($_POST['v']) ? $default_version : $dbm->clean($_POST['v']);		
		 $fields = $mydal->TableFields($table);
		 $booknames = $dbm->getFields($dbm->select('key_english',array('')),$mydal->TableFields('key_english')); 
		 $verses = $mydbm->runBaseQuery("SELECT * FROM $table WHERE t LIKE  '%$refText%' ");
		 $verses = empty($verses)?null:$dbm->getFields($verses,$fields);
		 $books = empty($verses)?null:array_unique($verses['b']);
		 # re_map the bible names
		 $bnk = $booknames['b']; $bnv = $booknames['n']; $newbiblename = array_combine($bnk,$bnv); 
		 
		 $bks = empty($books)?0:count($books); 
		 $vs = empty($verses)?0:count($verses['id']);
		 
		 echo "<small><i> found $vs verse(s) &nbsp; &nbsp; in &nbsp; &nbsp;  $bks book(s) </i>  </small> </br/></br/> "; 
		
		 $listed_books  = array(); 
		 $index = 0;	 
		 if(!empty($verses)) {  
			 foreach($verses['id'] as $bbid){
				 
				if(!in_array($verses['b'][$index], $listed_books)){
					$bbname = $newbiblename[$verses['b'][$index]]; 
					array_push($listed_books,$verses['b'][$index]);
					echo "<b>".$bbname ; echo "</b><hr style='margin:0em'/>";
				}  
					$chp = $verses['c'][$index]; 
					$vss = $verses['v'][$index]; 
					$txt = $verses['t'][$index]; 
					$txt = highlight($txt,$refText);
				 echo "<p><small><b>".$chp.":</b>$vss &nbsp;".$txt." <small><i>(".$bbname.")</i></small> </small></p>";
				 
				 $index++;
			}
		 } 
		 else {
			 print "<span class='text-danger'> cannot find '$refText' </span>";
		 }
		 
		## end search 		 
	 }
	 
	 
	
	function addZeros($input,$max) {
			$len = strlen($input); 
			for ($len; $len < $max; $len++) {
				$input = "0".$input;
			} 
			return  $input; 
		}
	 
	
	function get_verse($refId){ 
		 $mydbm = new DBController(); 
		 $table = "t_kjv";
		 $verse = $mydbm->runBaseQuery("SELECT * FROM $table WHERE id = $refId ");		 
		
		return empty($verse)?"Unknown Reference":$verse;
		
	}
	function highlight($astring,$aword){		
		return preg_replace('@\b('.$aword.')\b@si','<strong style="background-color:yellow">$1</strong>',$astring);
	}
	
?>