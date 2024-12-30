<?php
require "common/conn.php";

session_start();
$usr=$_SESSION["user"];
$res= $_GET['res'];
$msg= $_GET['msg'];
$aid = $_GET["id"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'hc';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/hc.php?res=0&msg='Insert Data'&mod=4");
    }
    if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'CONTACT CODE')
                ->setCellValue('C1', 'NAME')
                ->setCellValue('D1', 'CONTACT TYPE')
    			->setCellValue('E1', 'ORGANIZATION')
    			 ->setCellValue('F1', 'DOB')
                ->setCellValue('G1', 'DESIGNATION')
                 ->setCellValue('H1', 'DEPARTMENT')
                ->setCellValue('I1', 'PHONE')
                ->setCellValue('J1', 'EMAIL')
                ->setCellValue('K1', 'WEBSITE')
                ->setCellValue('L1', 'SOURCE')
                ->setCellValue('M1', 'SOURCE NAME')
    			->setCellValue('N1', 'DETAILS')
    			 ->setCellValue('O1', 'AREA')
                ->setCellValue('P1', 'STREET')
                 ->setCellValue('Q1', 'DISTRICT')
                ->setCellValue('R1', 'ZIP')
                ->setCellValue('S1', 'COUNTRY')
                ->setCellValue('T1', 'NAME')
                ->setCellValue('U1', 'CREATE DATE')
    			->setCellValue('V1', 'BALANCE'); 
    			
        $firststyle='A2';
        $qry="SELECT a.`contactcode`, a.`name`,b.`name` contacttype, a.`organization`, a.`dob`, c.`name` `designation`,d.`name` `department`, a.`phone`, a.`email`, a.`website`, h.`name` `source`, a.`sourcename`
        , a.`details`, a.`area`, a.`street`,e.`name` `district`,g.`name` `state`, a.`zip`,f.`name` `country`, a.`opendt`, a.`currbal` FROM `contact` a ,`contacttype` b,`designation` c,`department` d,`district` e,`country` f,`state` g,`source` h WHERE a.`contacttype`=b.`id` and a.`designation`=c.`id` and a.`department`=d.`id` and a.`district`=e.`id` and a.`country`=f.id and a.`state`=g.`id` and a.`source`=h.`id` and a.`status`=1 and a.`contacttype` in (1,2) order by a.`name`"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;
                $col10='J'.$urut; $col11='K'.$urut;$col12='L'.$urut;$col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;$col17='Q'.$urut;$col18='R'.$urut;
                $col19='S'.$urut; $col20='T'.$urut;$col21='U'.$urut;$col22='V'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['contactcode'])
    						->setCellValue($col3, $row['name'])
    					    ->setCellValue($col4, $row['contacttype'])
    					     ->setCellValue($col5, $row['organization'])
    					     ->setCellValue($col6, $row['dob'])
    					     ->setCellValue($col7, $row['designation'])
    						->setCellValue($col8, $row['department'])
    					    ->setCellValue($col9, $row['phone'])
    					     ->setCellValue($col10, $row['email'])
    					     ->setCellValue($col11, $row['website'])
    					    ->setCellValue($col12, $row['source'])
    					    ->setCellValue($col13, $row['sourcename'])
    					    ->setCellValue($col14, $row['details'])
    					    ->setCellValue($col15, $row['area'])
    					     ->setCellValue($col16, $row['street'])
    					     ->setCellValue($col17, $row['district'])
    					    ->setCellValue($col18, $row['state'])
    					    ->setCellValue($col19, $row['zip'])
    					    ->setCellValue($col20, $row['country'])
    					    ->setCellValue($col21, $row['opendt'])
    					    ->setCellValue($col22, $row['currbal']);	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Contact');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'contact_'.$today.'.xls'; 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);
        
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$fileNm);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileNm));
        ob_clean();
        flush();
        readfile($fileNm);
        exit;
    }
    
    
    ?>
    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php
     include_once('common_header.php');
    ?>
    <style>
    iframe {
    width: 1300px;
    height: 1100px;
    margin: 45px;
    margin-top: 50px;
}
</style>
    
    <body>
        
    <?php
     include_once('common_top_body.php');
    ?>
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>All Employee</span>
      </div>
      
    <?php
        include_once('menu.php');
    ?>
      
      	<div style="height:54px;">
    	</div>
      </div>
    
      <!-- END #sidebar-wrapper --> 
      
      <!-- Page Content -->
      
    </div>
    <iframe src="http://bithut.biz/BitFlow/employee_hr.php?res=<?= $res ?>&msg='Update Data'&id=<?= $aid ?>&mod=4" width: 100%; title=""></iframe>

    <!-- /#page-content-wrapper -->
    
    <?php
        include_once('common_footer.php');
    ?>
    <?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
    
     <!-- Datatable JS -->       
		<script src="js/plugins/datagrid/datatables.min.js"></script>
        
        <!-- Script -->
        <script>
        $(document).ready(function(){
            var table = $('#listTable').DataTable({
                /*'processing': true,
                'serverSide': true,
                'serverMethod': 'post', */
                'processing': true,
				'fixedHeader': true,
                'serverSide': true,
                'serverMethod': 'post',
				'pageLength': 10,
				'scrollX': true,
				'bScrollInfinite': true,
				'bScrollCollapse': true,
				/*scrollY: 550,*/
				//'select':true,
				'deferRender': true,
				'scroller': true,
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=hc'
                },
                'columns': [
                    { data: 'photo' },
                    { data: 'employeecode' },
                    { data: 'name' },
                    { data: 'dob' },
					{ data: 'office_contact' },
					{ data: 'office_email' },
                    { data: 'nid' },
					{ data: 'bloodgroup' },
					{ data: 'edit', "orderable": false },
					{ data: 'del', "orderable": false },
                ]
            });
        });
        
        $('#listTable tbody').on('click', 'tr', function () {
                //var d = table.row( this ).data();
                //console.log( table.row( this ).data() );
               // alert(d["nid"]);
                //var  loc = "issue-details.php?isit=".concat(d["tikcketno"])
                //window.location=loc;
                alert("Boom");
            });
            
            
        $('#listTable tbody').click( function () {
            //var aData = oTable.fnGetData( this );
            alert("Boom"); // assuming the id is in the first column
        } );
        
        $('#listTable tbody').on('click',function(){
            //var aPos = pTable.fnGetPosition(this);
            //var aData = pTable.fnGetData(aPos[0]);
            alert("Boom2");
        });
		
		

		
        </script>  
    
    </body></html>
  <?php }?>    
