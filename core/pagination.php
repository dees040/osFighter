<?php
/**
 * paginate.php
 * 
 * The paginate class is meant to simplify the task of creating
 * pagination of the fields like the users table on the website.
 *
 * Written by: Siggles
 * Last Updated: September 17, 2011
 */

class Paginate
{
	//$orderby parameter is optional
	function paginatePage($limit, $tableName, $orderby='username'){
    global $database;	
    
    // As this page is included $targetpage is itself
	$targetpage = $_SERVER['PHP_SELF']."?id=".$_GET['id']."&"; 
	
	//Get number of results first 
	  
	$sql = "SELECT COUNT(*) as num FROM $tableName";
	$result = $database->connection->prepare($sql);
	$result->execute(); 
	$total_pages = $result->fetchColumn(); 
	
	$stages = 3;
	if (isset($_GET['page'])){
		$page = mysql_escape_string($_GET['page']);
		$start = ($page - 1) * $limit; 
	} else {
		$start = 0;
	}

	// Get data from database
	if (isset($orderby)) { $orderby = "ORDER BY ".$orderby; }
	$sql = "SELECT * FROM $tableName $orderby LIMIT $start, $limit";
	$result = $database->connection->prepare($sql);
	$result->execute(); 
   	
		// Initial page num setup
	if (!isset($page)) { $page = 1; }
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	$paginate = '';
	if($lastpage > 1)
	{	

		$paginate .= "<div class='paginate'>";
		// Previous
		if ($page > 1){
			$paginate.= "<a href='$targetpage"."page=$prev'>previous</a>";
		}else{
			$paginate.= "<span class='disabled'>previous</span>";	}
				
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<span class='current'>$counter</span>";
				}else{
					$paginate.= "<a href='$targetpage"."page=$counter'>$counter</a>";}					
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage"."page=$counter'>$counter</a>";}					
				}
				$paginate.= "...";
				$paginate.= "<a href='$targetpage"."page=$LastPagem1'>$LastPagem1</a>";
				$paginate.= "<a href='$targetpage"."page=$lastpage'>$lastpage</a>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<a href='$targetpage"."page=1'>1</a>";
				$paginate.= "<a href='$targetpage"."page=2'>2</a>";
				$paginate.= "...";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage"."page=$counter'>$counter</a>";}					
				}
				$paginate.= "...";
				$paginate.= "<a href='$targetpage"."page=$LastPagem1'>$LastPagem1</a>";
				$paginate.= "<a href='$targetpage"."page=$lastpage'>$lastpage</a>";		
			}
			// End only hide early pages
			else
			{
				$paginate.= "<a href='$targetpage"."page=1'>1</a>";
				$paginate.= "<a href='$targetpage"."page=2'>2</a>";
				$paginate.= "...";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage"."page=$counter'>$counter</a>";}					
				}
			}
		}
					
			// Next
		if ($page < $counter - 1){ 
			$paginate.= "<a href='$targetpage"."page=$next'>next</a>";
		}else{
			$paginate.= "<span class='disabled'>next</span>";
			}
			
		$paginate.= " - ".$total_pages." Results</div>";			
        }
 // pagination
 echo $paginate;
 return $result;
	}
};

/* Create pagination */
$pagination = new Paginate;