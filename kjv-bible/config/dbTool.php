<?php
 @session_start();
	
	class DbTool{
		
			private $conn;
			
			public function __construct(){  
						
			try{
				
				$DSN = "mysql:host=".DB_HOST.";dbname=".DB_NAME; 
				$this->conn = new PDO($DSN,DB_USER,DB_PASS,null); # PARAMETERS DEFINED IN CONFIG.PHP 
				$this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				$this->code = $this->conn->errorCode();
				$this->getConn();	
				}
				catch(PDOException $er){					
					 $this->code = $_SESSION['error_code'] = $er->getCode(); 
					 $this->message = $_SESSION['error_msg'] = $er->getMessage(); 
					throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
				}	
			}			
			/// 
		public	function getConn(){

				return $this->conn;	
			}
			
		public function __destruct(){
				
				$this->conn = NULL; //  echo "but now am dead";
			}
		
	/// 
		 public function insert($table, array $data)
				{
					try{
			 	
				$db = new DbTool();	$conn = $db->getConn();			 		
				
				$columns = array_map(function($column){return "$column";}, array_keys($data)); 
				
					 $values = array_fill(0, count($data), '?');
					// construct the query    
				$sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', 
					$table,
					join(',', $columns),
					join(',', $values)
			);			
    //  prepare and execute it
	
   		 $stmt = $conn->prepare($sql);
   		 $stmt->execute(array_values($data));	
		 
		 $this->message = $stmt->rowCount()." record inserted";
		 $this->code = $stmt->errorCode();
		 $this->status = true;  
			return $conn->lastInsertId();
		  }
			 catch(PDOException $er){
					 $this->code = $_SESSION['error_code'] = $er->getCode(); 
					 $this->message = $_SESSION['error_msg'] = $er->getMessage(); 
					throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
			 }
		} 
		# 
		#
		
		 function updateTb($table, $data,$where ){
		 
		 $db = new DbTool();	$conn = $db->getConn();			 		
				
		   /// 
		if(empty($where)){
				$whereSql = '';			
			}
			else{			
				# split array	
				foreach($where as $column=>$value){
				$strings[]= "".$column."='".$value."'";
			}
			$whereSql = " WHERE ".implode(" AND ", $strings);			
			}								
			# statrt actual sql statement
			$sql = "UPDATE ".$table." SET ";
			#loop and build thecolumn
			$set = array();
			foreach($data as $column=>$value){
			$set[]= "".$column."='".$value."'";
			}
			$sql.=implode(',',$set);
			#append the whereclause
			$sql.= $whereSql; //  $whereSql;
			
			$exe = $conn->prepare($sql);
			$exe->execute();
			
			if($exe->rowCount()>0) return $exe->rowCount()." data updated successfully";
			
			else return  "no data found for update";		
	   }
		
		
		public function update($table, $data,$where, $operator = "AND" ){
		 
		 try{
			 			 
			 $db = new DbTool(); 
			 $conn = $db->getConn();
			   /// 
			if(empty($where)){
					$whereSql = '';			
				}
				else{			
					# split array	
					foreach($where as $column=>$value){
					$strings[]= "".$column."='".$value."'";
				}
				$whereSql = " WHERE ".implode(" $operator ", $strings);			
				}								
				# start actual sql statement
				$sql = "UPDATE ".$table." SET ";
				#loop and build the column
				$set = array();
				foreach($data as $column=>$value){
				$set[]= "".$column."='".$value."'";
				}
				 $sql.=implode(',',$set);
				#append the whereclause
				$sql.= $whereSql; //  $whereSql;
				
				$exe = $contc->prepare($sql);
				$exe->execute();
				
				$output = "";
				
				if($exe->rowCount()>0) $output = $exe->rowCount()." data updated";
								
				else $output =  "no data found for update";		
				
				$this->message = $output;
				
				$this->code = $exe->errorCode();
				
				$this->status = true; 
				
				return $output;
		 }
		 
		 catch(PDOExeption $er){
			 		$this->code = $_SESSION['error_code'] = $er->getCode(); 
					$this->message = $_SESSION['error_msg'] = $er->getMessage(); 
					throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
		 }
		 
	   }
		#			
		  public function select($table, $where="",$order="", $operator = "AND", $direction = " DESC "){
			 
			 try{
	   	 			$db = new DbTool();	$conn = $db->getConn();
		  $wheres = empty($where)?"":array_map(function($elem){ return "$elem = ?";},array_keys($where));
			
			if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
			else $ord = "";		
			
		  $str = sprintf("SELECT * FROM %s %s %s %s ",$table,empty($where)?"":"WHERE",join(" $operator ",$wheres),$ord);
		  
		  $stm = $conn->prepare($str);
 		
			$stm->execute(array_values($where));
			
		 $this->message = $stm->rowCount()." record(s) found";
		 $this->code = $stm->errorCode();
		 $this->status = true; 
			
			$stm->setFetchMode(PDO::FETCH_ASSOC);
						
			return $res = $stm->fetchAll();
			
			 }
			 catch(PDOException $er){
					$this->code = $_SESSION['error_code'] = $er->getCode(); 
					$this->message = $_SESSION['error_msg'] = $er->getMessage(); 
					throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
			 }
		  }
		  #
		  /****************************************************************************/
		  //////////////////////////////////////////////////////////////////////////////
		  function not_exists($table1,$table2,$where1,$where2,$whereEq=null,$operator = "AND", $order="", $direction = " DESC " ){
		
			 try{  
				 $db = new DbTool(); 
				 $conn = $db->getConn();		 
			   /// 
			   if(!empty($where1)){		 
				foreach($where1 as $column=>$value){
				$string1[]= "".$column."='".$value."'";
				} // rows mapped							
				$whereSqlA = " WHERE ".join(" $operator ", $string1); 
				$f1Op = $operator;
				}
				else {$whereSqlA = ""; $f1Op = " WHERE "; }
			
				$sqlA = sprintf("SELECT * FROM  %s %s",$table1,$whereSqlA);
				
				/////////////////////////////////////////////////////////////////////
				
				if(!empty($where2)){		 
				foreach($where2 as $column=>$value){
				$string2[]= "".$column."='".$value."'";
				} // rows mapped							
				$whereSqlB = " WHERE ".join(" $operator ", $string2); 
				$f2Op = $operator;
				}
				else {$whereSqlB = ""; $f2Op = " WHERE "; }
			
				$sqlB = sprintf("SELECT * FROM  %s %s",$table2,$whereSqlB);
					
				/////////////////////////////////////////////
				
				if(!empty($whereEq)){		 
				foreach($whereEq as $field){
				$string3[]= "".$table1.".".$field."=".$table2.".".$field;
				} // rows mapped							
				$whereSqlC = " ".join(" $operator ", $string3); 
				}
				else {$whereSqlC = "";}
					
				//
				if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
				else $ord = "";		
				//
				
				  $sqlC = $sqlA." ".$f1Op." NOT EXISTS ( ".$sqlB." ".$f2Op." ".$whereSqlC." )".$ord ;
 
					$stm = $conn->prepare($sqlC);
					
					$stm->execute();									
						 $this->message = $stm->rowCount()." record(s) found";
						 $this->code = $stm->errorCode();
						 $this->status = true; 											
							$stm->setFetchMode(PDO::FETCH_ASSOC);							
								return $res = $stm->fetchAll();
							 
					 }
					
			catch (PDOException $e){
				$this->code = $_SESSION['error_code'] = $er->code; 
					 $this->message = $_SESSION['error_msg'] = $er->message; 
					throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
			}		
	   }
		  /**********************************************************/
	###############################   /****************************************************************************/
		  //////////////////////////////////////////////////////////////////////////////
		  function exists($table1,$table2,$where1,$where2,$whereEq=null,$operator = "AND", $order="", $direction = " DESC " ){
		
			 try{  
				 $db = new DbTool(); 
				 $conn = $db->getConn();		 
			   /// 
			   if(!empty($where1)){		 
				foreach($where1 as $column=>$value){
				$string1[]= "".$column."='".$value."'";
				} // rows mapped							
				$whereSqlA = " WHERE ".join(" $operator ", $string1); 
				$f1Op = $operator;
				}
				else {$whereSqlA = ""; $f1Op = " WHERE "; }
			
				$sqlA = sprintf("SELECT * FROM  %s %s",$table1,$whereSqlA);
				
				/////////////////////////////////////////////////////////////////////
				
				if(!empty($where2)){		 
				foreach($where2 as $column=>$value){
				$string2[]= "".$column."='".$value."'";
				} // rows mapped							
				$whereSqlB = " WHERE ".join(" $operator ", $string2); 
				$f2Op = $operator;
				}
				else {$whereSqlB = ""; $f2Op = " WHERE "; }
			
				$sqlB = sprintf("SELECT * FROM  %s %s",$table2,$whereSqlB);
					
				/////////////////////////////////////////////
				
				if(!empty($whereEq)){		 
				foreach($whereEq as $field){
				$string3[]= "".$table1.".".$field."=".$table2.".".$field;
				} // rows mapped							
				$whereSqlC = " ".join(" $operator ", $string3); 
				}
				else {$whereSqlC = "";}
					
				//
				if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
				else $ord = "";		
				//
				
				  $sqlC = $sqlA." ".$f1Op." EXISTS ( ".$sqlB." ".$f2Op." ".$whereSqlC." )".$ord ;
 
					$stm = $conn->prepare($sqlC);
					
					$stm->execute();									
						 $this->message = $stm->rowCount()." record(s) found";
						 $this->code = $stm->errorCode();
						 $this->status = true; 											
							$stm->setFetchMode(PDO::FETCH_ASSOC);							
								return $res = $stm->fetchAll();
							 
					 }
					
			catch (PDOException $e){
				$this->code = $_SESSION['error_code'] = $er->code; 
					 $this->message = $_SESSION['error_msg'] = $er->message; 
					throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
			}		
	   }
		  /**********************************************************/
	############################### 
		  // selection function with different equators < > = <= >= 
		
	/**********************************************************/
	############################### 
	  function selections($table, $wheres, $whereEqt, $operators, $order="",  $direction = " DESC "){
			  
			  try{
				  
				  $db = new DbTool();	$conn = $db->getConn();
				
				$cw = count($wheres); $ce = count($whereEqt); $co = count($operators);
				  if($cw!= $ce)
					  throw new Exception(" The total numbers of Whereclause must matched with the numbers of WhereEquator : ".$cw." by ".$ce." by ".$co." given ");
					 
					 if(($cw-1)!= $co)
					  throw new Exception(" The total numbers of operators ( OR AND, NOR ) must be 1 less than the  WhereEquator : ".$cw." by ".$ce." by ".$co." given ");
				  
					$whereSql = '';	 				  
							if(!empty($wheres)){			
								# split array
									$i = 0; 									
									foreach($wheres as $column=>$value){
										$strings[] =  $column." ".$whereEqt[$i]." '".$value."'";
										$i++;
										}
										// match string with operators
										$data = [];
										 foreach(array_map(null,$strings,$operators) as $parts)
										 {
											 $data = array_merge($data,$parts);
										 }
										 /* alternative code
										 /*********************************** 
										 $data = array_reduce(array_map(null,$strings,$operators),
										 function($strings,$operators){
												return array_merge($strings,$operators);
											},array());
										****************************************/
										 $whereSql = implode(" ",$data);
										}
			  
			if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
				else $ord = "";		
			
			$str = sprintf("SELECT * FROM %s %s %s %s ",$table,empty($wheres)?"":" WHERE ",$whereSql,$ord);
		  
			$stm = $conn->prepare($str);
			
			$stm->execute();									
				 $this->message = $stm->rowCount()." record(s) found";
				 $this->code = $stm->errorCode();
				 $this->status = true; 											
					$stm->setFetchMode(PDO::FETCH_ASSOC);							
						return $res = $stm->fetchAll();
								
			  }
			  catch(Exception $e){
				  echo $e;
			  }
			  
			 }
	#############################################################
	############################### field comparison selections 
	  
		function field_comp_selections($table, $fieldcomp,$fieldEqt,$fieldOp, $joinOp, $wheres, $whereEqt, $operators, $order="",  $direction = " DESC "){
			  try{
				  
				  $db = new DbTool();	$conn = $db->getConn();
				
				$cw = count($wheres); $ce = count($whereEqt); $co = count($operators);
				  if($cw!= $ce)
					  throw new Exception(" The total numbers of Whereclause must matched with the numbers of WhereEquator : ".$cw." by ".$ce." by ".$co." given ");
					 
					 if(($cw-1)!= $co)
					  throw new Exception(" The total numbers of operators ( OR AND, NOR ) must be 1 less than the  WhereEquator : ".$cw." by ".$ce." by ".$co." given ");
				  
				  $whereField = ''; 
					$i = 0;
					foreach($fieldcomp as $c=>$v){
							if($i>0 && $i< count($fieldcomp)) $whereField.= " ".$fieldOp[$i-1]." ";
						$whereField.=  $c." ".$fieldEqt[$i]." ".$v." ";						
					$i++;		
					}
					// end not null whereField 
					
					  
					$whereSql = '';	 				  
							if(!empty($wheres)){			
								# split array
									$i = 0; 									
									foreach($wheres as $column=>$value){
										$strings[] =  $column." ".$whereEqt[$i]." '".$value."'";
										$i++;
										}
										// match string with operators
										$data = [];
										 foreach(array_map(null,$strings,$operators) as $parts)
										 {
											 $data = array_merge($data,$parts);
										 }										 
										 $whereSql = implode(" ",$data);
										}
			  
			if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
				else $ord = "";		
			
			// $str = sprintf("SELECT * FROM %s %s %s %s ",$table,empty($wheres)?"":" WHERE ",$whereSql,$ord);
		  $str = sprintf("SELECT * FROM %s %s %s %s %s %s",$table,empty($fieldcomp)?"":" WHERE ",$whereField, $joinOp,$whereSql,$ord);
			$stm = $conn->prepare($str);
			
			$stm->execute();									
				 $this->message = $stm->rowCount()." record(s) found";
				 $this->code = $stm->errorCode();
				 $this->status = true; 											
					$stm->setFetchMode(PDO::FETCH_ASSOC);							
						return $res = $stm->fetchAll();
								
			  }
			  catch(Exception $e){
				  echo $e;
			  }
			  
			 }
			 #############################################################
		   
	###################  distinct field comparison selections 
	  
		function distinct_field_comp_selections($field, $table, $fieldcomp,$fieldEqt,$fieldOp, $joinOp, $wheres, $whereEqt, $operators, $order="",  $direction = " DESC "){
			  try{
				  
				  $db = new DbTool();	$conn = $db->getConn();
				
				$cw = count($wheres); $ce = count($whereEqt); $co = count($operators);
				  if($cw!= $ce)
					  throw new Exception(" The total numbers of Whereclause must matched with the numbers of WhereEquator : ".$cw." by ".$ce." by ".$co." given ");
					 
					 if(($cw-1)!= $co)
					  throw new Exception(" The total numbers of operators ( OR AND, NOR ) must be 1 less than the  WhereEquator : ".$cw." by ".$ce." by ".$co." given ");
				  
				  $whereField = ''; 
					$i = 0;
					foreach($fieldcomp as $c=>$v){
							if($i>0 && $i< count($fieldcomp)) $whereField.= " ".$fieldOp[$i-1]." ";
						$whereField.=  $c." ".$fieldEqt[$i]." ".$v." ";						
					$i++;		
					}
					// end not null whereField 
					
					  
					$whereSql = '';	 				  
							if(!empty($wheres)){			
								# split array
									$i = 0; 									
									foreach($wheres as $column=>$value){
										$strings[] =  $column." ".$whereEqt[$i]." '".$value."'";
										$i++;
										}
										// match string with operators
										$data = [];
										 foreach(array_map(null,$strings,$operators) as $parts)
										 {
											 $data = array_merge($data,$parts);
										 }										 
										 $whereSql = implode(" ",$data);
										}
			  
			if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
				else $ord = "";		
			
			// $str = sprintf("SELECT * FROM %s %s %s %s ",$table,empty($wheres)?"":" WHERE ",$whereSql,$ord);
		  $str = sprintf("SELECT DISTINCT %s FROM %s %s %s %s %s %s",$field,$table,empty($fieldcomp)?"":" WHERE ",$whereField, $joinOp,$whereSql,$ord);
			$stm = $conn->prepare($str);
			
			$stm->execute();									
				 $this->message = $stm->rowCount()." record(s) found";
				 $this->code = $stm->errorCode();
				 $this->status = true; 											
					$stm->setFetchMode(PDO::FETCH_ASSOC);							
						return $res = $stm->fetchAll();
								
			  }
			  catch(Exception $e){
				  echo $e;
			  }
			  
			 }
			 #############################################################
		   
		   
		   
	####################################################################################################
	#########################
	  function distinct_selections($field, $table, $wheres, $whereEqt, $operators, $order="",  $direction = " DESC "){
			  
			  try{
				  
				  $db = new DbTool();	$conn = $db->getConn();
				
				$cw = count($wheres); $ce = count($whereEqt); $co = count($operators);
				  if($cw!= $ce)
					  throw new Exception(" The total numbers of Whereclause must matched with the numbers of WhereEquator : ".$cw." by ".$ce." by ".$co." given ");
					 

					 if(($cw-1)!= $co)
					  throw new Exception(" The total numbers of operators ( OR AND, NOR ) must be 1 less than the  WhereEquator : ".$cw." by ".$ce." by ".$co." given ");
				  
					$whereSql = '';	 				  
							if(!empty($wheres)){			
								# split array
									$i = 0; 									
									foreach($wheres as $column=>$value){
										$strings[] =  $column." ".$whereEqt[$i]." '".$value."'";
										$i++;
										}
										// match string with operators
										$data = [];
										 foreach(array_map(null,$strings,$operators) as $parts)
										 {
											 $data = array_merge($data,$parts);
										 }
										 /* alternative code
										 /*********************************** 
										 $data = array_reduce(array_map(null,$strings,$operators),
										 function($strings,$operators){
												return array_merge($strings,$operators);
											},array());
										****************************************/
										 $whereSql = implode(" ",$data);
										}
			  
			if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
				else $ord = "";		
			
			$str = sprintf("SELECT DISTINCT %s FROM %s %s %s %s ",$field, $table,empty($wheres)?"":" WHERE ",$whereSql,$ord);
		  
			$stm = $conn->prepare($str);
			
			$stm->execute();									
				 $this->message = $stm->rowCount()." record(s) found";
				 $this->code = $stm->errorCode();
				 $this->status = true; 											
					$stm->setFetchMode(PDO::FETCH_ASSOC);							
						return $res = $stm->fetchAll();
								
			  }
			  catch(Exception $e){
				  echo $e;
			  }
			  
			 }
			 #############################################################
		   
		   /************************/ 
			#########################
	  function equal_field_distinct_selections($field, $table, $wheres, $whereEqt, $operators, $order="",  $direction = " DESC "){
			  
			  try{
				  
				  $db = new DbTool();	$conn = $db->getConn();
				
				$cw = count($wheres); $ce = count($whereEqt); $co = count($operators);
				  if($cw!= $ce)
					  throw new Exception(" The total numbers of Whereclause must matched with the numbers of WhereEquator : ".$cw." by ".$ce." by ".$co." given ");
					 
					 if(($cw-1)!= $co)
					  throw new Exception(" The total numbers of operators ( OR AND, NOR ) must be 1 less than the  WhereEquator : ".$cw." by ".$ce." by ".$co." given ");
				  
					$whereSql = '';	 				  
							if(!empty($wheres)){			
								# split array
									$i = 0; 									
									foreach($wheres as $column=>$value){
										$strings[] =  $column." ".$whereEqt[$i]." '".$value."'";
										$i++;
										}
										// match string with operators
										$data = [];
										 foreach(array_map(null,$strings,$operators) as $parts)
										 {
											 $data = array_merge($data,$parts);
										 }
										 /* alternative code
										 /*********************************** 
										 $data = array_reduce(array_map(null,$strings,$operators),
										 function($strings,$operators){
												return array_merge($strings,$operators);
											},array());
										****************************************/
										 $whereSql = implode(" ",$data);
										}
			  
			if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
				else $ord = "";		
			
			$str = sprintf("SELECT DISTINCT %s FROM %s %s %s %s ",$field, $table,empty($wheres)?"":" WHERE ",$whereSql,$ord);
		  
			$stm = $conn->prepare($str);
			
			$stm->execute();									
				 $this->message = $stm->rowCount()." record(s) found";
				 $this->code = $stm->errorCode();
				 $this->status = true; 											
					$stm->setFetchMode(PDO::FETCH_ASSOC);							
						return $res = $stm->fetchAll();
								
			  }
			  catch(Exception $e){
				  echo $e;
			  }
			  
			 }
			 #############################################################
	
			
	public function select_distinct($field,$table,array $where,$order="", $operator = "AND",$direction = " DESC "){
			 
			 try{
	   	  
		  $db = new DbTool();	$conn = $db->getConn(); 

//		  $field = empty($field)?"*":array_map(function($elem){ return "$elem,";},array_keys($field));//
		  $wheres = empty($where)?"":array_map(function($elem){ return "$elem = ?";},array_keys($where));
			
			//if(!empty($order)) $ord = " ORDER BY ".$order[0];
			if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
			else $ord = "";		
			
		  $str = sprintf("SELECT DISTINCT %s FROM %s %s %s %s",$field,$table,empty($where)?"":"WHERE",join(" $operator ",$wheres),$ord);
		  
		  $stm = $conn->prepare($str);
 		
			$stm->execute(array_values($where));

			$this->message = $stm->rowCount()." record(s) found";
			$this->code = $stm->errorCode();
			$this->status = true; 
			
			$stm->setFetchMode(PDO::FETCH_ASSOC);
			
			return $res = $stm->fetchAll();

			 }
			 catch(PDOException $er){
					$this->code = $_SESSION['error_code'] = $er->getCode(); 
					$this->message = $_SESSION['error_msg'] = $er->getMessage(); 
	 				throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
				 }
		  }
		  #
		
		public function select_Multi_Distinct(array $field,$table,array $where,$order="", $operator = "AND", $direction = "DESC"){
			 try{
	   	  $db = new DbTool();	$conn = $db->getConn(); 

//		  $field = empty($field)?"*":array_map(function($elem){ return "$elem,";},array_keys($field));//
		  $wheres = empty($where)?"":array_map(function($elem){ return "$elem = ?";},array_keys($where));
			
			if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
			else $ord = "";		
			
		 $str = sprintf("SELECT DISTINCT %s FROM %s %s %s %s",join(", ",$field),$table,empty($where)?"":"WHERE",join(" $operator ",$wheres),$ord);
		 
		  $stm = $conn->prepare($str);
 		
			$stm->execute(array_values($where));

			$this->message =  $stm->rowCount()." record(s) found ";
			$this->code = $stm->errorCode();
			$this->status = true; 
			
			$stm->setFetchMode(PDO::FETCH_ASSOC);
			
			return $res = $stm->fetchAll();
			 
			 }
			 catch(PDOException $er){
			 		$this->code = $_SESSION['error_code'] = $er->getCode(); 
					$this->message = $_SESSION['error_msg'] = $er->getMessage(); 
					throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
			 }
		  }
		  		  
		 ###############################
		
		
		public function getMax($table, $field, $wheres, $operator = "AND"){
			
			try{
				
			$vsn = new DbTool();
			$vcon = $vsn->getConn();	
		$wheres = empty($wheres)?"":array_map(function($elem){ return "$elem = ?";},array_keys($wheres));
			$str = sprintf("SELECT MAX(%s) AS max FROM %s",$field,$table,empty($where)?"":"WHERE",join(' AND ',$wheres));
			$exe = $vcon->prepare($str);
			$exe->execute();
			
			$this->message =  " found ". $exe->fetch(PDO::FETCH_ASSOC);
			$this->code = $stm->errorCode(); 
			$this->status = true; 
					
			return $fin = $exe->fetch(PDO::FETCH_ASSOC);	// final
			
			}
			catch (PDOException $er){
				$_SESSION['error_code'] = $er->code; 
				$this->code = $_SESSION['error_code'] = $er->code; 
					 $this->message = $_SESSION['error_msg'] = $er->message; 
					throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
			}
		}
		///
		public function getMin($table,$field,$wheres, $operator = "AND"){
			
			try{
				
			$vsn = new DbTool();
			$vcon = $vsn->getConn();	
		$wheres = empty($wheres)?"":array_map(function($elem){ return "$elem = ?";},array_keys($wheres));
			$str = sprintf("SELECT MIN(%s) AS min FROM %s",$field,$table,empty($where)?"":"WHERE",join(' AND ',$wheres));
			
			$exe = $vcon->prepare($str);
			$exe->execute();
			
			$this->message =  " found ". $exe->fetch(PDO::FETCH_ASSOC);
			$this->code = $stm->errorCode(); 
			$this->status = true; 
			
			return $fin = $exe->fetch(PDO::FETCH_ASSOC);	
			
			}
			catch (PDOException $er){
			$this->code = $_SESSION['error_code'] = $er->code; 
					 $this->message = $_SESSION['error_msg'] = $er->message; 
					throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
			}
		}
		 
		   
		public function deleteRow($table,$where, $operator = "AND" ){
		 
		 try{  // NemauGu300563
		 
		 $db = new DbTool(); 
		 $conn = $db->getConn();		 
		   /// 
			if(!empty($where)){		 
				foreach($where as $column=>$value){
				$strings[]= "".$column."='".$value."'";
				} // rows mapped				
			
				$whereSql = " WHERE ".join(" $operator ", $strings);	
				
						$sql = sprintf("DELETE FROM  %s %s",$table,$whereSql); 
					 
					 	$exe = $conn->prepare($sql);
						
						$exe->execute();
						
						$counts = $exe->rowCount();
						
						if($counts==0)
						$this->message =  " no record was deleted ";
						else $this->message =  $counts."  record(s) deleted ";
						$this->code = $exe->errorCode(); 
						$this->status = true; 
					
					 return;
					
						}											
					}
					
			catch (PDOException $e){
				$this->code = $_SESSION['error_code'] = $er->code; 
					 $this->message = $_SESSION['error_msg'] = $er->message; 
					throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
			}		
	   }
	   
	   ######################################################################
		/*********************************************************************/
		public function regExpSearch($table, array $criterials,$order="", $direction = " DESC ", $limit='100000'){
				 try{
					  $dbm = new DbTool();
					  $conn = $dbm->getConn();
					  $wheres = empty($criterials)?"":array_map(function($elem){ return "$elem REGEXP ?";},array_keys($criterials));					
					  if(!empty($order)) $ord = " ORDER BY ".join(" , ",$order)." ". $direction ;
						else $ord = "";						
					  $str = sprintf("SELECT * FROM %s %s %s %s",$table,empty($criterials)?"":"WHERE",join(' OR ',$wheres),$ord);
					  $stm = $conn->prepare($str);			
					  $stm->execute(array_values($criterials));				
					  return  $res = $stm->fetchAll();			  
				 }
				 catch(PDOException $er){
						echo $er->getMessage(); 
				 }
		  }
		 
		######################################################################
		######################################################################
		
	   /// check for error 	   
	   public function checkError(){
		  // $dbTool = new DbTool();
		   if($this->status != true){		   

						$_SESSION['error_code'] = $this->code; 
						$_SESSION['error_msg'] = $thisl->message; 
						throw new Exception(" Error Code  ".$this->code.", Error Message: ".$this->message);
					}			
	   }
	   #
	   
	   function getFields(array $string, array $fieldname){
				if(!empty($string)){ 
				foreach($string as $rows){
					foreach($fieldname as $f){
						$fields[$f][] = $rows[$f];	
					}
				}	return $fields;
				}	# end if 		
				else return null; 
			}
		#
		
	function iLabel($value){
		$label = "";
		if($value==1){
			$label = "A";
			}
			if($value==2){
			$label = "B";
			}
				if($value==3){
				$label = "C";
				}
					if($value==4){
					$label = "D";
					}
						if($value==5){
						$label = "E";
						}
						
 			return $label;			
		}
	  
	###
	
		
		/***************************/
		
		function readColor($val){
		if($val<=15 ){
			echo " progress-bar right bg-red";
		}
		elseif($val<=25){
			echo "progress-bar right bg-orange";
		}
		elseif($val<=100){
			echo " progress-bar right bg-green ";
		}
		 
	}
	
	function readPercent($val){
		if($val<=20 ){
			echo " btn-danger";
		}
		elseif($val<=40){
			echo "btn-warning";
		}
		elseif($val<=100){
			echo " btn-success ";
		}	
	}
	
	/******************************/
	function readTableStyle($val){
		if($val<=20 ){
			echo "danger";
		}
		elseif($val<=40){
			echo "warning";
		}
		elseif($val<=100){
			echo "success ";
		}
		 
	}
	
		/*********************/ 
		function n2a($val){
		if($val=='1'){
			return 'A';
		}
		elseif($val=='2'){
			return 'B';
		}
		elseif($val=='3'){
			return 'C';
		}
		elseif($val=='4'){
			return 'D';
		}
		elseif($val=='5'){
			return 'E';
		}
		else { 
		return '';
		}
	}
	/********************************/
	// generate string from array 
	function remap(array $var){
		 $i=0; $n = count($var); $str = "";
			foreach($var as $k=>$v){
			$str .= $k."=".$v." ";	
			if($i<$n-1) $str.= "&amp;";
			$i++;
			}
		return $str; 
	}
	
	/**************************************************************************/
		public function resort(array $data){
			
			$ork = array_keys($data);  // original array keys 
			$aVal = array(); $n = 0;  // array values 
					foreach($data as $k=>$v){
						$aVal[] = $data[$ork[$n]][0];
						$n++;
					}
			return $output = array_combine($ork,$aVal);
		}
		/*********************************************************************/	 
		 public function clean($text,$type='string'){
			 switch($type){
				 case "email": return filter_var($text,FILTER_SANITIZE_EMAIL);  break; 
				 case "html": return htmlspecialchars($text);  break; 
				 default : return filter_var($text,FILTER_SANITIZE_STRING); break; 
			 }
		} 
		 
		#############################################
		/*******************************************/
		
		  
		
}	// end of class DbTool
		?>