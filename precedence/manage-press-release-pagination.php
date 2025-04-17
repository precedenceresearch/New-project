<?php 
require_once("classes/cls-press-release.php");

$obj_press = new Press();

if (!isset($_SESSION['ifg_admin'])) {
    header("Location:login");
}

$limitcond = "10";
$m="";
$page = $_GET['page'];
$condition="";
if($page=="1")
{
   $nextoffset="0";
}
else
{
   $nextoffset=($page-1) * $limitcond;  
} 
$fields = "*";
$orderbyfreshleads="`id` DESC";
$limit ="$nextoffset,$limitcond";
$press_details = $obj_press->getPressDetails($fields, $condition, $orderbyfreshleads, $limit,'', 0);
$all_press_cnt=$obj_press->getPressDetails($fields, $condition, $orderbyfreshleads, '', 0);
$total_records = count($all_press_cnt);  
$total = $total_records;

//////////////////////*Paging Code start*/////////////////////////


if($total>0)
{
$total_links=ceil($total/$limitcond);
$previous_link = '';
$next_link = '';
$page_link = '';

//echo $total_links;

if($total_links > 4)
{ 
  if($page < 5)
  {
    for($count = 1; $count <= 5; $count++)
    {
      $page_array[] = $count;
    }
    $page_array[] = '...';
    $page_array[] = $total_links;
  }
  else
  {
    $end_limit = $total_links - 5;
    if($page > $end_limit)
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $end_limit; $count <= $total_links; $count++)
      {
        $page_array[] = $count;
      }
    }
    else
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $page - 1; $count <= $page + 1; $count++)
      {
        $page_array[] = $count;
      }
      $page_array[] = '...';
      $page_array[] = $total_links;
    }
  }
}
else
{
  for($count = 1; $count <= $total_links; $count++)
  {
    $page_array[] = $count;
  }
}
//print_r($page_array);
for($count = 0; $count < count($page_array); $count++)
{
  if($page == $page_array[$count])
  {
    $page_link .= '
    <li class="pageitem" id="'.$page_array[$count].'">
      <a class="page-link active1" href="#" data-id="'.$page_array[$count].'" data-sort="'.$m.'">'.$page_array[$count].' <span class="sr-only"></span></a>
    </li>
    ';
    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li class="pageitem" id="'.$previous_id.'"><a class="page-link" href="javascript:void(0)" data-id="'.$previous_id.'" data-sort="'.$m.'">Previous</a></li>';
    }
    else
    {
      $previous_link = '';
    }
    $next_id = $page_array[$count] + 1;
    if($page_array[$count] >= $total_links)
    {
      $next_link = '';
    }
    else
    {
      $next_link = '<li class="pageitem" id="'.$next_id.'"><a class="page-link" href="javascript:void(0)" data-id="'.$next_id.'" data-sort="'.$m.'">Next</a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {
      $page_link .= '
      <li class="pageitem disabled">
          <a class="page-link" href="#" data-sort="'.$m.'">...</a>
      </li>
      ';
    }
    else
    { 
      $page_link .= '
      <li class="pageitem" id="'.$page_array[$count].'"><a class="page-link" href="javascript:void(0)" data-id="'.$page_array[$count].'" data-sort="'.$m.'">'.$page_array[$count].'</a></li>
      ';
      
    }
  }
}

}
/*Paging Code End*/
?>
<style>
input[type="checkbox"] {
	 height: 0;
	 width: 0;
	 visibility: hidden;
}
 #toggle_label {
	 cursor: pointer;
	 text-indent: -9999px;
	 width: 45px;
	 height: 25px;
	 background: #f81708;
	 display: block;
	 border-radius: 100px;
	 position: relative;
	 content: "No";
	 color:#fff;
	 margin-top: 6px;
}
 #toggle_label:after {
	 content: "No";
	 position: absolute;
	 top: 4px;
	 left: 5px;
	 width: 18px;
	 height: 18px;
	 background: #fff;
	 border-radius: 90px;
	 transition: 0.3s;
}
 input:checked + #toggle_label {
	 background: #34a615;
	  
}
 input:checked + #toggle_label:after {
	 left: calc(100% - 5px);
	 transform: translateX(-100%);
}
 #toggle_label:active:after {
	 width: 130px;
}
</style>
 <table class="table table-hover table-nowrap leadbox-listing">
    <thead class="thead-light">
        <tr>
            <th scope="col">Sr.No</th>
            <th scope="col">Press Release ID</th>
            <th scope="col">Press Release Title</th>
            <th scope="col">Created Date</th>
            <th>Action</th>
        </tr>
    </thead>

<tbody>
<?php 
    if(isset($press_details) && !empty($press_details))
    {
         if($page == 1)
     {
     $counter = 1;
     }
     else
     {
         $counter = (($limitcond * ($page - 1)) + 1);
     }
    foreach ($press_details as $press_detail) {
         if (strlen($press_detail['title']) > 100)
         {
            $presstitle = substr(str_replace("–","-",$press_detail['title']), 0, 100) . '...';
         }
         else
         {
            $presstitle = str_replace("–","-",$press_detail['title']);
         }
         
?>

<tr>
     <td><a class="text-heading font-semibold" href="#"><?php echo $counter; ?></a></td>
     <td><?php echo $press_detail['id'];?></td>
     <td class="f_name">
       <a href="<?php echo SITEPATH.$press_detail['slug'];?>" target="_blank"> <?php echo $presstitle;?> </a></td>
     <td>
        <?php echo date("F d, Y",strtotime($press_detail['createddate']));?>
     </td>
    
    <td class="text-end">
        <div style="display:flex;"><a class="m-1" title="Edit Category" href="add-press-release?press_id=<?php echo base64_encode($press_detail['id']);?>">
            <img src="<?php echo SITEADMIN."images/writing.png";?>" height="30" width="30"></a>
                <?php $press_detail_status = $press_detail['status']; ?>
                <input type="checkbox" name="switch_published" id="switch<?php echo $press_detail['id'];?>" onclick="action_published(<?php echo $press_detail['id'];?>)" value="<?php echo $press_detail_status; ?>" <?php echo $press_detail_status == "Active" ? "checked" : "unchecked" ?>/>
                <label for="switch<?php echo $press_detail['id'];?>" id="toggle_label"></label>
        
    </td>
</tr>
<?php $counter++; } ?>
<?php } else {?>
<tr><td colspan="6" style="text-align:center;">No Records Found</td></tr>  
<?php }	?>
</tbody>

</table>

<?php if($total>0){?>
<div class="pagination-main">
    <div class="offset-5">
       <nav>
            <ul class="paginationcmp" style="font-size:12px;display:flex;list-style: none;">
            <?php echo $previous_link . $page_link . $next_link;?>
			</ul>
       </nav>
    </div>
</div>           
<?php }?>    
<Style>
    .active1{
        background-color:#3d298a;
        color:#fff;
    }
</Style>
<script type="text/javascript">
	$(document).ready(function() {
		$(".page-link").click(function(){
			var id = $(this).attr("data-id");
			var select_id = $(this).parent().attr("id");
			
			$.ajax({
				url: "<?php echo SITEADMIN; ?>manage-press-release-pagination.php",
				type: "GET",
				data: {
					page : id
				},
				cache: false,
				success: function(dataResult){
					$("#target-content").html(dataResult);
					$(".pageitem").removeClass("active1");
					$("#"+select_id).addClass("active1");
					$(window).scrollTop(0);
				}
			});
		});
		
	});
	function action_published(pressid)
        {
        // var chk_publish =  $('#switch'+campid).val();
            $.ajax({
                  url : "<?php echo SITEADMIN; ?>update-press-status-published.php",
                  type : "POST",
                  data : {pressid:pressid},
                  success: function(data){
                     
                     
                  }
                  
            });
        }
</script>