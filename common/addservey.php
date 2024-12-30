<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
     header("Location: http://bithut.biz/kini-bechi/servey.php?res=01&msg='New Entry'&id=''");
}
//$id=1;
if ( isset( $_POST['submit'] ) ) {
    $servey_id=1;
    $form_id=1;
    $demographic=1;
    $servey_date= $_REQUEST['servey_dt'];
    $serveyor_id= $_REQUEST['cmbsrvnm'];
    $loctaion_id= $_REQUEST['cmbLoc'];
    $name_of_head= $_REQUEST['headNm'];
    $gender_of_head= $_REQUEST['sex'];
    $age_of_head= $_REQUEST['ageHead'];
    $cell_of_head= $_REQUEST['mobhead'];
    $cell_others= $_REQUEST['mobOther'];
    $mfin_id= $_REQUEST['cmbmfs'];
    $m_fin_number= $_REQUEST['mfsno'];
    $has_nid_pf_head= $_REQUEST['cmbnid'];
    $nid_pf_head=$_REQUEST['nid'];
    $has_nid_of_spouse_head= $_REQUEST['cmbnids'];
    $nid_of_spouse_head=$_REQUEST['nidsp'];
    $tot_family_member= $_REQUEST['membernum']; 
    $tot_infant= $_REQUEST['infantnum'];
    $tot_children= $_REQUEST['childnum'];
    $tot_boy= $_REQUEST['bchildnum'];
    $tot_girl= $_REQUEST['gchildnum'];
    $tot_women= $_REQUEST['ywomennum'];
    $tot_man= $_REQUEST['ymennum'];
    $tot_sr_women= $_REQUEST['sWomennum'];
    $tot_sr_man= $_REQUEST['smennum'];
    $has_disable_member= $_REQUEST['cmbdisable'];
    
    $slmnm= $_REQUEST['slmnm'];
    $blkno= $_REQUEST['blkno'];
    $wrdno= $_REQUEST['wrdno'];
    $ctycorp= $_REQUEST['ctycorp'];
    $present_address=$slmnm.$blkno.$wrdno.$ctycorp ;
    
    $vill= $_REQUEST['vill'];
    $per_union= $_REQUEST['per_union'];
    $upzila= $_REQUEST['upzila'];
    $district= $_REQUEST['district'];
    $permenent_address=$vill.$per_union.$upzila.$district;
                
    $tot_member_wt_profession= $_REQUEST['profnum'];
    $main_proffession= $_REQUEST['mprof'];
    $tot_mon_profession= $_REQUEST['profmon'];
    $secoend_proffession= $_REQUEST['sprof'];
    $monthly_income= $_REQUEST['mincome'];
    $has_scholl_child= $_REQUEST['cmbschoolchild'];
    $do_go_school= $_REQUEST['cmbschoolGoingchild'];
    $not_going_cause= $_REQUEST['cmbschoolnotGoingCause'];
    $has_family_latrin= $_REQUEST['cmblatrine'];
    $do_share_latrin= $_REQUEST['cmblatrineShare'];
    $latrin_problem= $_REQUEST['latrinprob'];
    $has_latrin_water= $_REQUEST['cmblatrineWater'];
    $source_of_drinking_water= $_REQUEST['cmbdrinkWater'];
    $source_of_non_drinking_water= $_REQUEST['cmbnodrinkWater'];
    $has_water_bill= $_REQUEST['cmbWaterbill'];
    $water_bill= $_REQUEST['waterbill'];
    $has_water_logging= $_REQUEST['cmbWaterlog'];
    $how_long_water_logged= $_REQUEST['cmbWaterlogtm'];
    $does_damage_water_log= $_REQUEST['cmbWaterlogdam'];
    $has_wast_mgt= $_REQUEST['cmbWaterlogmgt'];
    $did_simulation= $_REQUEST['cmbgovtsimulat'];
    $simulation_details= $_REQUEST['simulatTime'];
    $did_NGO_simulate= $_REQUEST['cmbngosimulat'];
    $did_govt_served= $_REQUEST['cmbgovtserv'];
    $not_served_reason= $_REQUEST['govtnoservice'];
    $received_services= $_REQUEST['govtservice'];
    $has_helth_service= $_REQUEST['cmbhelthserv'];
    $helth_services= $_REQUEST['helthservice'];
    $has_special_helth_services= $_REQUEST['cmbsphelthserv'];
    $special_helth_services= $_REQUEST['sphelthservice'];
    $enough_light= $_REQUEST['cmbenoughlight'];
    $seek_center_st= $_REQUEST['cmbseekcenterst'];
    $help_centre= $_REQUEST['seekcenter'];
    
    $make_date=date('Y-m-d H:i:s');
    $st='1';
    $qry="insert into survey_form(servey_id,form_id,demographic,servey_date,serveyor_id,loctaion_id,name_of_head,gender_of_head,age_of_head,cell_of_head,cell_others,mfin_id,m_fin_number,has_nid_pf_head,has_nid_of_spouse_head,tot_family_member,tot_infant,tot_children,tot_boy,tot_girl,tot_women,tot_man,tot_sr_women,tot_sr_man,has_disable_member,present_address,permenent_address,tot_member_wt_profession,main_proffession,tot_mon_profession,secoend_proffession,monthly_income,has_scholl_child,do_go_school,not_going_cause,has_family_latrin,do_share_latrin,latrin_problem,has_latrin_water,source_of_drinking_water,source_of_non_drinking_water,has_water_bill,water_bill,has_water_logging,how_long_water_logged,does_damage_water_log,has_wast_mgt,did_simulation,simulation_details,did_NGO_simulate,did_govt_served,not_served_reason,received_services,has_helth_service,helth_services,has_special_helth_services,special_helth_services,make_date,servey_st,nid_pf_head,nid_of_spouse_head,slmnm,blkno,wrdno,ctycorp,vill,per_union,upzila,district,enough_light,seek_center_st,help_centre )
values( ".$servey_id.",".$form_id.",".$demographic.",'".$servey_date."','".$serveyor_id."','".$loctaion_id."','".$name_of_head."','".$gender_of_head."','".$age_of_head."','".$cell_of_head."','".$cell_others."','".$mfin_id."','".$m_fin_number."','".$has_nid_pf_head."','".$has_nid_of_spouse_head."','".$tot_family_member."','".$tot_infant."','".$tot_children."','".$tot_boy."','".$tot_girl."','".$tot_women."','".$tot_man."','".$tot_sr_women."','".$tot_sr_man."','".$has_disable_member."','".$present_address."','".$permenent_address."','".$tot_member_wt_profession."','".$main_proffession."','".$tot_mon_profession."','".$secoend_proffession."','".$monthly_income."','".$has_scholl_child."','".$do_go_school."','".$not_going_cause."','".$has_family_latrin."','".$do_share_latrin."','".$latrin_problem."','".$has_latrin_water."','".$source_of_drinking_water."','".$source_of_non_drinking_water."','".$has_water_bill."','".$water_bill."','".$has_water_logging."','".$how_long_water_logged."','".$does_damage_water_log."','".$has_wast_mgt."','".$did_simulation."','".$simulation_details."','".$did_NGO_simulate."','".$did_govt_served."','".$not_served_reason."','".$received_services."','".$has_helth_service."','".$helth_services."','".$has_special_helth_services."','".$special_helth_services."','".$make_date."','".$st."','".$nid_pf_head."','".$nid_of_spouse_head."','".$slmnm."','".$blkno."','".$wrdno."','".$ctycorp."','".$vill."','".$per_union."','".$upzila."','".$district."','".$enough_light."','".$seek_center_st."','".$help_centre."')";
 $err="New record created successfully";
 //echo $qry; die;
}
if ( isset( $_POST['update'] ) ) {
    $uid= $_REQUEST['pid'];
    $servey_id=1;
    $form_id=1;
    $demographic=1;
    $servey_date= $_REQUEST['servey_dt'];
    $serveyor_id= $_REQUEST['cmbsrvnm'];
    $loctaion_id= $_REQUEST['cmbLoc'];
    $name_of_head= $_REQUEST['headNm'];
    $gender_of_head= $_REQUEST['sex'];
    $age_of_head= $_REQUEST['ageHead'];
    $cell_of_head= $_REQUEST['mobhead'];
    $cell_others= $_REQUEST['mobOther'];
    $mfin_id= $_REQUEST['cmbmfs'];
    $m_fin_number= $_REQUEST['mfsno'];
    $has_nid_pf_head= $_REQUEST['cmbnid'];
    $nid_pf_head=$_REQUEST['nid'];
    $has_nid_of_spouse_head= $_REQUEST['cmbnids'];
    $nid_of_spouse_head=$_REQUEST['nidsp'];
    $tot_family_member= $_REQUEST['membernum']; 
    $tot_infant= $_REQUEST['infantnum'];
    $tot_children= $_REQUEST['childnum'];
    $tot_boy= $_REQUEST['bchildnum'];
    $tot_girl= $_REQUEST['gchildnum'];
    $tot_women= $_REQUEST['ywomennum'];
    $tot_man= $_REQUEST['ymennum'];
    $tot_sr_women= $_REQUEST['sWomennum'];
    $tot_sr_man= $_REQUEST['smennum'];
    $has_disable_member= $_REQUEST['cmbdisable'];
    $slmnm= $_REQUEST['slmnm'];
    $blkno= $_REQUEST['blkno'];
    $wrdno= $_REQUEST['wrdno'];
    $ctycorp= $_REQUEST['ctycorp'];
    $present_address=$slmnm.$blkno.$wrdno.$ctycorp ;
    
    $vill= $_REQUEST['vill'];
    $per_union= $_REQUEST['per_union'];
    $upzila= $_REQUEST['upzila'];
    $district= $_REQUEST['district'];
    $permenent_address=$vill.$per_union.$upzila.$district;
    $tot_member_wt_profession= $_REQUEST['profnum'];
    $main_proffession= $_REQUEST['mprof'];
    $tot_mon_profession= $_REQUEST['profmon'];
    $secoend_proffession= $_REQUEST['sprof'];
    $monthly_income= $_REQUEST['mincome'];
    $has_scholl_child= $_REQUEST['cmbschoolchild'];
    $do_go_school= $_REQUEST['cmbschoolGoingchild'];
    $not_going_cause= $_REQUEST['cmbschoolnotGoingCause'];
    $has_family_latrin= $_REQUEST['cmblatrine'];
    $do_share_latrin= $_REQUEST['cmblatrineShare'];
    $latrin_problem= $_REQUEST['latrinprob'];
    $has_latrin_water= $_REQUEST['cmblatrineWater'];
    $source_of_drinking_water= $_REQUEST['cmbdrinkWater'];
    $source_of_non_drinking_water= $_REQUEST['cmbnodrinkWater'];
    $has_water_bill= $_REQUEST['cmbWaterbill'];
    $water_bill= $_REQUEST['waterbill'];
    $has_water_logging= $_REQUEST['cmbWaterlog'];
    $how_long_water_logged= $_REQUEST['cmbWaterlogtm'];
    $does_damage_water_log= $_REQUEST['cmbWaterlogdam'];
    $has_wast_mgt= $_REQUEST['cmbWaterlogmgt'];
    $did_simulation= $_REQUEST['cmbgovtsimulat'];
    $simulation_details= $_REQUEST['simulatTime'];
    $did_NGO_simulate= $_REQUEST['cmbngosimulat'];
    $did_govt_served= $_REQUEST['cmbgovtserv'];
    $not_served_reason= $_REQUEST['govtnoservice'];
    $received_services= $_REQUEST['govtservice'];
    $has_helth_service= $_REQUEST['cmbhelthserv'];
    $helth_services= $_REQUEST['helthservice'];
    $has_special_helth_services= $_REQUEST['cmbsphelthserv'];
    $special_helth_services= $_REQUEST['sphelthservice'];
    $enough_light= $_REQUEST['cmbenoughlight'];
    $seek_center_st= $_REQUEST['cmbseekcenterst'];
    $help_centre= $_REQUEST['seekcenter'];
    $make_date=date('Y-m-d H:i:s');
    $st='1';
   // echo $uid;die;
    $qry="update  survey_form set servey_id=".$servey_id.",form_id=".$form_id.",servey_date='".$servey_date."',serveyor_id='".$serveyor_id."',loctaion_id='".$loctaion_id."',name_of_head='".$name_of_head."',gender_of_head='".$gender_of_head."',age_of_head='".$age_of_head."',cell_of_head='".$cell_of_head."',cell_others='".$cell_others."',mfin_id='".$mfin_id."',m_fin_number='".$m_fin_number."',has_nid_pf_head='".$has_nid_pf_head."',has_nid_of_spouse_head='".$has_nid_of_spouse_head."',tot_family_member='".$tot_family_member."',tot_infant='".$tot_infant."',tot_children='".$tot_children."',tot_boy='".$tot_boy."',tot_girl='".$tot_girl."',tot_women='".$tot_women."',tot_man='".$tot_man."',tot_sr_women='".$tot_sr_women."',tot_sr_man='".$tot_sr_man."',has_disable_member='".$has_disable_member."',present_address='".$present_address."',permenent_address='".$permenent_address."',tot_member_wt_profession='".$tot_member_wt_profession."',main_proffession='".$main_proffession."',tot_mon_profession='".$tot_mon_profession."',secoend_proffession='".$secoend_proffession."',monthly_income='".$monthly_income."',has_scholl_child='".$has_scholl_child."',do_go_school='".$do_go_school."',not_going_cause='".$not_going_cause."',has_family_latrin='".$has_family_latrin."',do_share_latrin='".$do_share_latrin."',latrin_problem='".$latrin_problem."',has_latrin_water='".$has_latrin_water."',source_of_drinking_water='".$source_of_drinking_water."',source_of_non_drinking_water='".$source_of_non_drinking_water."',has_water_bill='".$has_water_bill."',water_bill='".$water_bill."',has_water_logging='".$has_water_logging."',how_long_water_logged='".$how_long_water_logged."',does_damage_water_log='".$does_damage_water_log."',has_wast_mgt='".$has_wast_mgt."',did_simulation='".$did_simulation."',simulation_details='".$simulation_details."',did_NGO_simulate='".$did_NGO_simulate."',did_govt_served='".$did_govt_served."',not_served_reason='".$not_served_reason."',received_services='".$received_services."',has_helth_service='".$has_helth_service."',helth_services='".$helth_services."',has_special_helth_services='".$has_special_helth_services."',special_helth_services='".$special_helth_services."',update_dt='".$make_date."',nid_pf_head='".$nid_pf_head."',nid_of_spouse_head='".$nid_of_spouse_head."',slmnm='".$slmnm."',blkno='".$blkno."',wrdno='".$wrdno."',ctycorp='".$ctycorp."',vill='".$vill."',per_union='".$per_union."',upzila='".$upzila."',district='".$district."',enough_light='".$enough_light."',seek_center_st='".$seek_center_st."',help_centre ='".$help_centre."'  where id=".$uid."";
    $err="Record update successfully";
   // echo $qry; die;
}

//$qry="insert into survey_form(servey_id,form_id,demographic,servey_date,serveyor_id,loctaion_id,name_of_head,gender_of_head,age_of_head,cell_of_head,cell_others,mfin_id,m_fin_number,has_nid_pf_head,has_nid_of_spouse_head,tot_family_member,tot_infant,tot_children,tot_boy,tot_girl,tot_women,tot_man,tot_sr_women,tot_sr_man,has_disable_member,present_address,permenent_address,tot_member_wt_profession,main_proffession,tot_mon_profession,secoend_proffession,monthly_income,has_scholl_child,do_go_school,not_going_cause,has_family_latrin,do_share_latrin,latrin_problem,has_latrin_water,source_of_drinking_water,source_of_non_drinking_water,has_water_bill,water_bill,has_water_logging,how_long_water_logged,does_damage_water_log,has_wast_mgt,did_simulation,simulation_details,did_NGO_simulate,did_govt_served,not_served_reason,received_services,has_helth_service,helth_services,has_special_helth_services,special_helth_services,make_date,servey_st)
//values( ".$servey_id.",".$form_id.",".$demographic.",'".$servey_date."',".$serveyor_id.",".$loctaion_id.",'".$name_of_head."','".$gender_of_head."',".$age_of_head.",'".$cell_of_head."','".$cell_others."',".$mfin_id.",".$m_fin_number.",".$has_nid_pf_head.",".$has_nid_of_spouse_head.",".$tot_family_member.",".$tot_infant.",".$tot_children.",".$tot_boy.",".$tot_girl.",".$tot_women.",".$tot_man.",".$tot_sr_women.",".$tot_sr_man.",".$has_disable_member.",'".$present_address."','".$permenent_address."',".$tot_member_wt_profession.",'".$main_proffession."',".$tot_mon_profession.",'".$secoend_proffession."',".$monthly_income.",".$has_scholl_child.",".$do_go_school.",".$not_going_cause.",".$has_family_latrin.",".$do_share_latrin.",".$latrin_problem.",".$has_latrin_water.",".$source_of_drinking_water.",".$source_of_non_drinking_water.",".$has_water_bill.",".$water_bill.",".$has_water_logging.",".$how_long_water_logged.",".$does_damage_water_log.",".$has_wast_mgt.",".$did_simulation.",'".$simulation_details."',".$did_NGO_simulate.",".$did_govt_served.",'".$not_served_reason."','".$received_services."',".$has_helth_service.",'".$helth_services."',".$has_special_helth_services.",'".$special_helth_services."','".$make_date."','".$st."')";
//echo $qry;die;
if ($conn->connect_error) {
   echo "Connection failed: " . $conn->connect_error;
}

if ($conn->query($qry) === TRUE) {
   //echo $qry; die;
    header("Location: http://bithut.biz/kini-bechi/servey.php?res=1&msg=".$err."&id=''");
    //echo "New record created successfully";
} else {
     $err="Error: " . $qry . "<br>" . $conn->error;
     header("Location: http://bithut.biz/kini-bechi/servey.php?res=2&msg=".$err."&id=''");
     //echo "Error: " . $qry . "<br>" . $conn->error;
}

// header("Location: http://bithut.biz/actionBd/dummy/dashboard.php");
   
//$conn->query($qry);
$conn->close();
?>