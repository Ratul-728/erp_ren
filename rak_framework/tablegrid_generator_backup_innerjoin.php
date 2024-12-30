<?php
function generateTableGrid($data,$rowPerPage)
{
	
	
	//unset($data);
//########### Start paging
if($rowPerPage == 'all'){$rowPerPage = '5000';}


require_once('pagination.php');		
	
	


	
	
//	print_r($data);
	
	echo '<div class="table">';
	
	
	if($totalRecords > 0){
		
		
	$columns = $data['columns'];
	
	array_pop($data); //removed last column information from $data;		
	
	echo '<div class="tr">';
	foreach($columns as  $value)
	{
		echo '<div class="th">'.$value.'</div>';
	}
	echo '<div class="th">Actions</div>';
	echo '</div>';
	
	for ($i=$startIndex; $i<$endIndex; $i++) {
		echo '<div class="tr">';
		foreach($columns as $key => $value)
		{
			echo '<div class="td">'.$data[$i][$key].'</div>';
		}
			echo '<div class="td">';
			//echo .$data[$i]['id'].'</div>';
				echo '<a title="Detail" href="'.$_SERVER['PHP_SELF'].'?click_action=detail&postaction=detail&id='.$data[$i]['id'].'" class="detail">Detail</a>';
				echo '<a title="Edit" href="'.$_SERVER['PHP_SELF'].'?click_action=edit&postaction=edit&id='.$data[$i]['id'].'" class="edit">Edit</a>';
				echo '<a title="Delete" href="'.$_SERVER['PHP_SELF'].'?postaction=delete&id='.$data[$i]['id'].'" onclick="return confirm(\'Are you sure you want to delete this record?\')" class="delete">Delete</a>';
			echo '</div>';
		
		echo '</div>';
	

	  $nextPage = $pageIndex+1;
	  $prevPage = $pageIndex-1;
	}
	
}
else
{
	echo '<div class="tr"><div class="td">No data found</div></div>';
}	
	
	
	echo '</div>';
	




?>
<div class="table">
  <div class="tr">
    <div class="td">Total Item<?=($totalRecords > 1)?"s":""?>: <?=$totalRecords?></div>
    <div class="td">
    
        <div class="table">
          <div class="tr">
            <div class="td">Show</div>
            <div class="td">
            
              <form action="<?=$_SERVER['PHP_SELF']?>" name="frmRowPerPage" method="post">
              <input type="hidden" name="cat_id" value="<?=$cat_id?>">
                <select name="rowPerPage" onchange="document.frmRowPerPage.submit()">
                	<option value="10" <?=($rowPerPage == 10)?'selected':''?>>10</option>
                    <option value="20" <?=($rowPerPage == 20)?'selected':''?>>20</option>
                    <option value="50" <?=($rowPerPage == 50)?'selected':''?>>50</option>
                    <option value="100" <?=($rowPerPage == 100)?'selected':''?>>100</option>
                    <option value="all" <?=($rowPerPage == '5000')?'selected':''?>>All</option>
                </select>
                </form>            
            
            </div>
            <div class="td"> records per page</div>
          </div>
        </div>    
    
    </div>
    <div class="td">
    
              <?php
			   if($totalRecords > 0){?>
                Showing <?=$startIndex+1?> to <?=$endIndex?>&nbsp;&nbsp;
                <?php }?>      
    
    </div>
    
    <div class="td">
    
			<?php
                /*if($pageIndex){
                $queryString = '&pageIndex='.$pageIndex;
                }*/
                if($rowPerPage){
                $queryString .= '&rowPerPage='.$rowPerPage;
                }
                
                
            ?>
            <?=($pageIndex > 1)?'<a href="'.$_SERVER['PHP_SELF'].'?pageIndex='.$prevPage.$queryString.'" class="back">Previous</a>':'<a href="javascript:void();" class="disabled">Previous</a>'?>
            <?=($totalPages > $pageIndex)?'<a href="'.$_SERVER['PHP_SELF'].'?pageIndex='.$nextPage.$queryString.'" class="next">Next</a>':'<a href="javascript:void();" class="disabled">Next</a>'?>   
    
    </div>    
  </div>
</div>

<?php
}
?>