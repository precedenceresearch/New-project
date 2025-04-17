<?php
require_once("classes/cls-report.php");

if (!isset($_SESSION['ifg_admin']) || $_SESSION['ifg_admin']['role'] != "superadmin") {
    header("Location:login.php");
}

$obj_report = new Report();
$conn = $obj_report->getConnectionObj();

$allquest=explode("¶",$_POST['allquestions']);
$allans=explode("¶",$_POST['allanswers']);

$fields="*";
$condition="`predr_mrfaq`.`rid`='".$_POST['report_id']."'";
$resfaqs = $obj_report->getReportFAQDetails($fields, $condition, '', '', 0);

if(isset($resfaqs) && !empty($resfaqs))
{
    foreach($resfaqs as $resfaq)
    {
        $j=$resfaq['qcnt']+1;
    }
    for($i=0;$i<count($allquest);$i++)
    {
        if($allquest[$i]!="")
        {
            $condtionfaq="`predr_mrfaq`.`rid`='".$_POST['report_id']."'";
            $update_data['q'.$j] = mysqli_real_escape_string($conn, addslashes($allquest[$i]));
            $update_data['a'.$j] = mysqli_real_escape_string($conn, addslashes($allans[$i]));
            $update_data['qcnt'] = mysqli_real_escape_string($conn, $j);
            $res=$obj_report->updateReportFAQ($update_data,$condtionfaq, 0);
        }
        $j++;
    }
}
else
{
    $j=1;
    $insert_data['rid'] = mysqli_real_escape_string($conn, $_POST['report_id']);
    $res1=$obj_report->insertReportFAQ($insert_data, 0);
    
    for($i=0;$i<count($allquest);$i++)
    {
        if($allquest[$i]!="")
        {
            $condtionfaq="`predr_mrfaq`.`rid`='".$_POST['report_id']."'";
            $update_data['q'.$j] = mysqli_real_escape_string($conn, addslashes($allquest[$i]));
            $update_data['a'.$j] = mysqli_real_escape_string($conn, addslashes($allans[$i]));
            $update_data['qcnt'] = mysqli_real_escape_string($conn, $j);
            $res=$obj_report->updateReportFAQ($update_data,$condtionfaq, 0);
        }
        $j++;
    }
}

     
if($res)
{
   
    echo 1;
    $_SESSION['success'] = "<strong>FAQ</strong> has been added successfully";
}
else
{
    $_SESSION['error'] = "Error while adding FAQ";
    echo 0;
}
//header("Location:manage-report");
//exit(0);
?>