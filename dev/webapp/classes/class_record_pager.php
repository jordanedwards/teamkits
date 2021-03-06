    <?php
    /** PHP Pager class
    * @access public */

    class Pager {
      /** MySQL connection identifier
      * @access private
      * data type string */
      var $conId;

      /** SQL query
      * @access private
      * data type string */
      var $query;

      /** number of records per page
      * @access private
      * data type integer */
      var $numRecs;

      /** generated output for records and paging links
      * @access private
      * data type string */
      var $output;

      /** records template file
      * @access private
      * data type string */
      var $template;

      /** ID attribute for styling paging links
      * @access private
      * data type string */
      var $linksId;
		
      /** Show the page numbering at the top of the page
      * @access private
      * data type byte, 1 for yes, 0 for no */		
			var $topPage;
		
      /** Show the page numbering at the bottom of the page
      * @access private
      * data type byte, 1 for yes, 0 for no */				
			var $bottomPage;

      /** Data Manager
      * @access private
      * Loaded on initialization */		
			var $dm;

			var $activeCol;

      /**
      * Pager constructor
      * @access public
      * @param string conId
      * @param string query
      * @param string linksId
      * @param integer numRecs
		* @param integer topPage
		* @param integer bottomPage
      * @param string template
      */	
      function Pager ($query,$linksId,$numRecs=10,$topPage=1,$bottomPage=1,$template='default_record_template.htm'){

			//	require_once('/invoices/classes/class_data_manager.php');	
				$this->dm = new DataManager();
				
				$activeCol = "";
				$this->activeCol = $activeCol;
					// validate connection idenfifier
					//(mysqli_query('SELECT 1',&$conId))?$this->conId=
					//&$conId:die('Invalid connection identifier.');
	
					// validate query *** HAD TO REM THIS OUT BECAUSE IT TRIPS WHEN THERE IS A SUBQUERY
					//(preg_match("/^SELECT/",$query))?$this->query=$query:die('Invalid query<br> '.$query);
					$this->query = $query;
					
					// validate paging links ID
					(!is_numeric($linksId))?$this->linksId=
					 $linksId:die('Invalid ID for paging links '.$linksId);
	
					// validate template file
					(file_exists($template))?$this->template=
					 $template:die('Invalid template file '.$template);
	
					// validate number of records per page
					(is_int($numRecs)&&$numRecs>0)?$this->numRecs=
						$numRecs:die('Invalid number of records '.$numRecs);
	
					// validate toplinks per page
					(is_int($topPage))?$this->topPage=
						$topPage:die('Wrong value for topPage (1 or 0) '.$topPage);			
	
					// validate toplinks per page
					(is_int($bottomPage))?$this->bottomPage=
						$bottomPage:die('Wrong value for bottomPage (1 or 0) '.$bottomPage);	
	
					// initialize output
					$this->output='';
				}
	
			 
	
				/**
				* method displayRecords
				* @access public 
				* @return string output
				* @param integer page
				* @description display paginated records/paging links
				*/
			function displayRecords($page){		
	
				// calculate total number of records
				//if(!$totalRecs=mysqli_num_rows(mysqli_query($this->query))){
				if(!$totalRecs=mysqli_num_rows($this->dm->queryRecords($this->query))){
					//die('Cannot retrieve records from database');
					//Do not kill this since we want to be able to add new if there are none
				}
				
				// calculate number of pages
				$numPages=ceil($totalRecs/$this->numRecs);
				if(!preg_match("/^\d{1,2}$/",$page)||$page<1||$page>$numPages){
					$page=1;
				}
				
				// get result set
				$result=$this->dm->queryRecords($this->query.' LIMIT '.($page-1)*$this->numRecs.','.$this->numRecs);
	
				if (mysqli_num_rows($result) >0){
					// read template file
					$templateContent=file($this->template);
					
					// append template file header to final output
					//$this->output=reset($templateContent);

					$templateRow=reset($templateContent);
					$tempOutput=$templateRow;
					$tempOutput=str_replace('{sort}',$_SERVER['PHP_SELF'].'?page='.$page.'&sort=' ,$tempOutput);
					$this->output.=$tempOutput;	
					
					// move pointer to placeholder line
					$templateRow=next($templateContent);
					$tempOutput=$templateRow;
					
					// Create array of fields:
					$qualified_names = array();
					for ($i = 0; $i < mysqli_num_fields($result); ++$i) {
						$fieldinfo=mysqli_fetch_field_direct($result,$i);
						$table = $fieldinfo->table;
						$field = $fieldinfo->name;
						$qualified_names[$i]["field"]="$table.$field";		
					}
										
					$full_output = "";	
					$result=$this->dm->queryRecords($this->query.' LIMIT '.($page-1)*$this->numRecs.','.$this->numRecs);

					// replace placeholders with actual data:																									
					while($row=mysqli_fetch_array($result,MYSQLI_NUM)):						
						$tempOutput=$templateRow;			
						foreach ($qualified_names as $key=>$val){
							$tempOutput=str_replace('{'.$val["field"].'}',$row[$key],$tempOutput);
						}
							$full_output .= $tempOutput;
					endwhile;
												
					// remove unpopulated placeholders											
					$this->output.=preg_replace("/{.*?}/",'',$full_output);

					// append template file footer to final output
					$tempOutput=end($templateContent);
					
					$this->output.=$tempOutput;			
					
					//*************************************************************************************************
					// create page links
					$pagelinks = '';
					$pagelinks.='	<nav>
  <ul class="pagination">';
          $var_array = array();
          $qs = $_SERVER['QUERY_STRING'];
          if($qs)
          {
              $var = explode('&', $qs);
              foreach($var as $varOne)
              {
                  $name_value = explode('=', $varOne);
                 
                  //remove duplicated vars
                  if(isset($qsAdd) && $qsAdd)
                  {
                      if(!array_key_exists($name_value[0], $varAdd_array))
                      {
                          $var_array[$name_value[0]] = $name_value[1];
                      }
                  }
                  else
                  {
                      $var_array[$name_value[0]] = $name_value[1];
                  }
              }
          }
          
          $delimiter = "&";
          $qs = '';
          foreach($var_array as $key => $value)
          {
              if($key != 'page'){
                $qs .= $delimiter.$key."=".$value;
                $delimiter = "&";
              }
          }
    


					// create first link
					if($page>1){
						$pagelinks.='<li><a href="'.$_SERVER['PHP_SELF'].'?page='.(1).$qs.'"><span aria-hidden="true"></span>First</a></li>';
					}
					
					// create previous link
					if($page>1){
						$pagelinks.='<li><a href="'.$_SERVER['PHP_SELF'].'?page='.($page-1).$qs.'"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>';
					}
					
					$start = 1;
					$end = $numPages;
					// create numerated links
					if ($numPages > 10){
						//Figure the start page
						if ($page > 5){
							$start = $page - 5;
						}
						//Figure the end page
						if ($numPages > $page + 5){ 
							$end = $page + 5;
						}
					}
					for($i=$start;$i<=$end;$i++){
							($i!=$page)?$pagelinks.='<li><a href="'.$_SERVER['PHP_SELF'].'?page='.$i.$qs.'">'.$i.'</a></li>':$pagelinks.='<li><a href="'.$_SERVER['PHP_SELF'].'?page='.$i.$qs.'" class="active_link">'.$i.'</a></li>';
					}
						
					// create next link
					if($page<$numPages){
						$pagelinks.='<li><a href="'.$_SERVER['PHP_SELF'].'?page='.($page+1).$qs.'"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>';
					}
						
					// create last link
					if($page<$numPages){
						$pagelinks.='<li><a href="'.$_SERVER['PHP_SELF'].'?page='.($numPages).$qs.'"><span aria-hidden="true"></span>Last</a></li>';
					}	
					
					
					$pagelinks.='  </ul>
<span style="float:right; margin:20px 0;">' . ((($page-1)*$this->numRecs)+1) . ' to ';

if (($page*$this->numRecs) > $totalRecs){
	$pagelinks.= $totalRecs;
} else {
	$pagelinks.= $page*$this->numRecs;
}

$pagelinks.= ' of ' . $totalRecs . ' Total Records</span></nav>';
					
					//See what needs to be added as paging links
					if ($this->topPage==1){
						$this->output = $pagelinks.$this->output;
					}
					if ($this->bottomPage==1){
						$this->output .= $pagelinks;
					}				
					//End of the page links code
					
					// return final output
					return $this->output;
				}else{
					//No data was returned
					return "No matching data found.";
				}
      }
    }
    ?>