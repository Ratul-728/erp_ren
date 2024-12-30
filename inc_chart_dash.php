<script>

//====start HR Dashboard

// CHART DONUT (HR1 Employee Status)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

    var data = [
<?php
$i        = 0;
$qrychrt1 = "SELECT b.Title,count(b.title) c FROM hraction a left join JobType b on a.jobtype=b.ID
	where  (a.postingdepartment=" . $cmbdept . " or " . $cmbdept . " =0) and (a.designation=" . $cmbdesg . " or " . $cmbdesg . " =0)
	group by b.Title";
$resultchrt1 = $conn->query($qrychrt1);
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$lebel = $rowschrt1["Title"];
        $data                             = $rowschrt1["c"];
        $i++;

//$color = substr(md5(rand()), 0, 6);$n=25;$o=75; ?>

        { "color" : "<?=$colors[$i] ?>",
        "data" : <?php echo $data; ?>,
        "label" : "<?php echo $lebel; ?>"
      },
<?php }} ?>

    ];

    var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 30, // This makes the donut shape
							   label: {
									show: true,
									radius: 0.8,
									formatter: function (label, series) {
										return '<div class="flot-pie-label">' +
											label + ' : ' +
										Math.round(series.percent)+'%';
										//Math.round(series.data[0][1]);

									  //  '%</div>';
									},
									background: {
										opacity: 0,
										color: '#fff'
									}

								}
                        }
                    },
     legend: {
        show: false
    },
                };

    var chart = $('.chart-hr1');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);

// CHART BAR STACKED (HR2 date wise attendence)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

  var data = [{
      "label": "Present",
      "color": "#0082e4",
      "data": [
<?php
$td = date("d/m/Y");
$fd = date('d/m/Y', strtotime('-15 day'));

$qrychrtatt1 = "select  b.trdt,b.sttus,count(b.sttus) cnt
FROM(
select DATE_FORMAT(u.dt,'%d') trdt
,(case when entrytm is null then (case when u.lv is null then (case when u.holiday is null then 'Absent' else u.holiday end)  else u.lv end)  else 'Present' end ) sttus
from
(
select d.dt,h.id,h.hrName
 ,(select min(intime) from attendance where hrid=h.id and date=d.dt) entrytm
,(SELECT lt.title FROM  `leave` l, leaveType lt where l.leavetype=lt.id and  hrid=h.id
and d.dt BETWEEN l.startday and l.endday) lv
,(SELECT ht.title FROM  Holiday h,holidayType ht where h.holidaytype=ht.id and h.`date`=d.dt) holiday
from loggday d,hr h ,employee e left join hraction ha on ha.hrid=e.id
where
d.dt BETWEEN between STR_TO_DATE('" . $fd . "','%d/%m/%Y') and STR_TO_DATE('" . $td . "','%d/%m/%Y')
and h.emp_id=e.employeecode
 and (ha.postingdepartment=" . $cmbdept . " or " . $cmbdept . " =0) and (ha.designation=" . $cmbdesg . " or " . $cmbdesg . " =0)
) u
)b where b.sttus='Present'
group by b.trdt,b.sttus
order by b.trdt";
$resultchrtatt1 = $conn->query($qrychrtatt1);
if ($resultchrtatt1->num_rows > 0) {
    while ($rowschrtatt1 = $resultchrtatt1->fetch_assoc()) {
        $dt = $rowschrtatt1["trdt"];
        $ct = $rowschrtatt1["cnt"];
        ?>
        ["<?php echo $dt; ?>", <?php echo $ct; ?>],
<?php }} ?>
      ]
    }, {
      "label": "Absent",
      "color": "#00abe3",
      "data": [
<?php
$qrychrtatt2 = "select  b.trdt,b.sttus,count(b.sttus) cnt
FROM(
select DATE_FORMAT(u.dt,'%d') trdt
,(case when entrytm is null then (case when u.lv is null then (case when u.holiday is null then 'Absent' else u.holiday end)  else u.lv end)  else 'Present' end ) sttus
from
(
select d.dt,h.id,h.hrName
 ,(select min(intime) from attendance where hrid=h.id and date=d.dt) entrytm
,(SELECT lt.title FROM  `leave` l, leaveType lt where l.leavetype=lt.id and  hrid=h.id
and d.dt BETWEEN l.startday and l.endday) lv
,(SELECT ht.title FROM  Holiday h,holidayType ht where h.holidaytype=ht.id and h.`date`=d.dt) holiday
from loggday d,hr h ,employee e left join hraction ha on ha.hrid=e.id
where
d.dt BETWEEN STR_TO_DATE('" . $fd . "','%d/%m/%Y') and STR_TO_DATE('" . $td . "','%d/%m/%Y')
and h.emp_id=e.employeecode
and (ha.postingdepartment=" . $cmbdept . " or " . $cmbdept . " =0) and (ha.designation=" . $cmbdesg . " or " . $cmbdesg . " =0)
) u
)b where b.sttus='Absent'
group by b.trdt,b.sttus
order by b.trdt";
$resultchrtatt2 = $conn->query($qrychrtatt2);
if ($resultchrtatt2->num_rows > 0) {
    while ($rowschrtatt2 = $resultchrtatt2->fetch_assoc()) {
        $dt = $rowschrtatt2["trdt"];
        $ct = $rowschrtatt2["cnt"];
        ?>
        ["<?php echo $dt; ?>", <?php echo $ct; ?>],
<?php }} ?>
      ]
    }, {
      "label": "Leave",
      "color": "#7d848a",
      "data": [
        <?php
$qrychrtatt3 = "select  b.trdt,b.sttus,count(b.sttus) cnt
FROM(
select DATE_FORMAT(u.dt,'%d') trdt
,(case when entrytm is null then (case when u.lv is null then (case when u.holiday is null then 'Absent' else u.holiday end)  else 'Leave' end)  else 'Present' end ) sttus
from
(
select d.dt,h.id,h.hrName
 ,(select min(intime) from attendance where hrid=h.id and date=d.dt) entrytm
,(SELECT lt.title FROM  `leave` l, leaveType lt where l.leavetype=lt.id and  hrid=h.id
and d.dt BETWEEN l.startday and l.endday) lv
,(SELECT ht.title FROM  Holiday h,holidayType ht where h.holidaytype=ht.id and h.`date`=d.dt) holiday
from loggday d,hr h ,employee e left join hraction ha on ha.hrid=e.id
where
d.dt BETWEEN STR_TO_DATE('" . $fd . "','%d/%m/%Y') and STR_TO_DATE('" . $td . "','%d/%m/%Y')
and h.emp_id=e.employeecode
and (ha.postingdepartment=" . $cmbdept . " or " . $cmbdept . " =0) and (ha.designation=" . $cmbdesg . " or " . $cmbdesg . " =0)
) u
)b where b.sttus='Leave'
group by b.trdt,b.sttus
order by b.trdt";
$resultchrtatt3 = $conn->query($qrychrtatt3);
if ($resultchrtatt3->num_rows > 0) {
    while ($rowschrtatt3 = $resultchrtatt3->fetch_assoc()) {
        $dt = $rowschrtatt3["trdt"];
        $ct = $rowschrtatt3["cnt"];
        ?>
        ["<?php echo $dt; ?>", <?php echo $ct; ?>],
<?php }} ?>

      ]
    }];

    var options = {
				lines: {
					show: false
				},
                    series: {
                        stack: 1,
					bars: {
						order: 1,
						show: true,
						barWidth: 0.6,
						barHeight:0.5,
						lineWidth: 0.5,
						fill: 1,
						align: 'center',

					},
                    },
					legend:{
						show:false,
					},
                    grid: {
                        borderColor: '#eee',
                        borderWidth: 0,
                        hoverable: true,
                        backgroundColor: '#fff'

                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function (label, x, y) { return label + ' : ' + y; }
                    },
                    xaxis: {
                        tickColor: '#fcfcfc',
                        mode: 'categories',
						tickLength:0,
						position:'bottom',


                    },
                    yaxis: {
                        //position: 'right' or 'left'
                        tickColor: '#eee',
						axisLabel: "Date",

                    },
                    shadowSize: 0
                };




    var chart = $('.chart-hr2');
    if(chart.length)
      $.plot(chart, data, options);

/*
	var chartv2 = $('.chart-bar-stackedv2');
    if(chartv2.length)
      $.plot(chartv2, datav2, options);
*/
  });

})(window, document, window.jQuery);

// CHART DONUT (HR3 Leave Status)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

    var data = [
<?php
$i        = 0;
$qrychrt1 = "SELECT t.title ,count(t.title) c from `leave` l left join leaveType t on l.`leavetype`=t.id
left join hr on l.hrid=hr.id
left join employee e on hr.emp_id=e.employeecode
left join hraction ha on ha.hrid=e.id
where ('2021-07-08' BETWEEN startday and endday)
and (ha.postingdepartment=" . $cmbdept . " or " . $cmbdept . " =0) and (ha.designation=" . $cmbdesg . " or " . $cmbdesg . " =0)
group by t.title";
$resultchrt1 = $conn->query($qrychrt1);
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$lebel = $rowschrt1["title"];
        $data                             = $rowschrt1["c"];
        $i++;

//$color = substr(md5(rand()), 0, 6);$n=25;$o=75; ?>

        { "color" : "<?=$colors[$i] ?>",
        "data" : <?php echo $data; ?>,
        "label" : "<?php echo $lebel; ?>"
      },
<?php }} ?>

    ];

    var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 30, // This makes the donut shape
							   label: {
									show: true,
									radius: 0.8,
									formatter: function (label, series) {
										return '<div class="flot-pie-label">' +
											label + ' : ' +
										Math.round(series.percent)+'%';
										//Math.round(series.data[0][1]);

									  //  '%</div>';
									},
									background: {
										opacity: 0,
										color: '#fff'
									}

								}
                        }
                    },
     legend: {
        show: false
    },
                };

    var chart = $('.chart-hr3');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);

// HORIZONTAL BARCHART (HR4 Department wise employee)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

var data = [
<?php
$i        = 0;
$qrychrt1 = "SELECT b.name,count(b.name) c FROM hraction a left join department b on a.postingdepartment=b.id
	where  (a.postingdepartment=" . $cmbdept . " or " . $cmbdept . " =0) and (a.designation=" . $cmbdesg . " or " . $cmbdesg . " =0)
	group by b.name";
$resultchrt1 = $conn->query($qrychrt1);
$nrows       = $resultchrt1->num_rows;
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$rev = $rowschrt1["c"];
        $pnm                            = $rowschrt1["name"];
        $i++;
        ?>


    {
    label: '<?php echo $pnm; ?>(<?php echo $rev; ?> )',
		color:"<?=$colors[$i] ?>",
    data: [
        [<?php echo $rev; ?>,<?php echo $i; ?>],

    ]
},
<?php }} ?>
   ];

var options = {
    series: {
        stack: 1,
        bars: {
            order: 1,
            show: 2,
            barWidth: 0.8,
			barHeight:0.5,
            fill:1,
			lineWidth: 0.5,
            align: 'center',
            horizontal: true
        },
    },
    grid: {
        hoverable: false,
        borderWidth: 0
    },
    tooltip: true,
    tooltipOpts: {
        cssClass: "flotTip",
        content: "%s: %y",
        defaultTheme: false
    },
    legend: {
        show: false
    },
    yaxis: {

        position:top,

          max:<?php echo $nrows + 1; ?>, // set according to your needs, maybe dynamic depending on chart width
          min: 0,
        ticks: [

<?php
$i        = 0;
$qrychrt1 = "SELECT b.name,count(b.name) c FROM hraction a left join department b on a.postingdepartment=b.id
	where  (a.postingdepartment=" . $cmbdept . " or " . $cmbdept . " =0) and (a.designation=" . $cmbdesg . " or " . $cmbdesg . " =0)
	group by b.name";
$resultchrt1 = $conn->query($qrychrt1);
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$rev = $rowschrt1["c"];
        $pnm                            = $rowschrt1["name"];
        $i++;
        ?>
            [<?php echo $i; ?>, '<?php echo $pnm; ?>(<?php echo $rev; ?>)'],
<?php }} ?>
        ]
    },
	 xaxis: {

		 tickLength:0,

	 },
};

    var chart = $('.chart-hr4');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);

// CHART PIE (HR5 Gender wise Employee)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

    var data = [
 <?php
$clrkey   = 0;
$qrychrt1 = "SELECT (case when e.gender='M' then 'Male' when 'F' then 'Female' else 'Others' end) gndr,count(e.gender) c FROM employee e
left join hraction ha on ha.hrid=e.id
where
 (ha.postingdepartment=" . $cmbdept . " or " . $cmbdept . " =0) and (ha.designation=" . $cmbdesg . " or " . $cmbdesg . " =0)
group by e.gender
";
$resultchrt1 = $conn->query($qrychrt1);
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$lbl = $rowschrt1["gndr"];
        $data                           = $rowschrt1["c"];
        ?>

        {
      "label": "<?php echo $lbl; ?>(<?php echo $data; ?>)",
      "color": "<?php echo $colors[$clrkey]; ?>",
      "data": <?php echo $data; ?>,

    },
    <?php
$clrkey++;

    }} ?>];

    var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 0,
                            label: {
                                show: true,
                                radius: 0.6,
                                formatter: function (label, series) {
                                    return '<div class="flot-pie-label">' +
                                    label + ' : ' +
                                    Math.round(series.percent)+'%';
									//Math.round(series.data[0][1])

                                  //  '%</div>';
                                },
                                background: {
                                    opacity: 0,
                                    color: '#222'
                                }
                            },
                        }
                    },
                        legend: {
                        show: false
                    },
                };

    var chart = $('.chart-hr5');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);

// CHART DONUT (HR6 Designation wise Employee)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

    var data = [
<?php
$i        = 0;
$qrychrt1 = "SELECT b.name,count(b.name) c FROM hraction a left join designation b on a.designation=b.id
	where  (a.postingdepartment=" . $cmbdept . " or " . $cmbdept . " =0) and (a.designation=" . $cmbdesg . " or " . $cmbdesg . " =0)
	group by b.name";
$resultchrt1 = $conn->query($qrychrt1);
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$lebel = $rowschrt1["name"];
        $data                             = $rowschrt1["c"];
        $i++;

//$color = substr(md5(rand()), 0, 6);$n=25;$o=75; ?>

        { "color" : "<?=$colors[$i] ?>",
        "data" : <?php echo $data; ?>,
        "label" : "<?php echo $lebel; ?>"
      },
<?php }} ?>

    ];

    var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 30, // This makes the donut shape
							   label: {
									show: true,
									radius: 0.9,
									formatter: function (label, series) {
										return '<div class="flot-pie-label">' +
											label + ' : ' +
										Math.round(series.percent)+'%';
										//Math.round(series.data[0][1]);

									  //  '%</div>';
									},
									background: {
										opacity: 0,
										color: '#fff'
									}

								}
                        }
                    },
     legend: {
        show: false
    },
                };

    var chart = $('.chart-hr6');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);

//===end hr dashs

//===Billing

// CHART Line Chart (month wise Sale Revenue)
(function(window, document, $, undefined){

  $(function(){

    var data = [{
        "label": "Revenue",
        "color": "#5ab1ef",
        "data": [
            <?php 
           
$fd = date('d/m/Y', strtotime('-15 day'));           
$fdt = date('d/m/Y', strtotime('-15 day',$td1));            
$qrychrtatt2="select   date_format(orderdate,'%D%b,%y') mnt
,sum(invoiceamount) invamt
from soitem
where ( date( `orderdate`) between STR_TO_DATE('" . $fd . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y'))
group by date_format(orderdate,'%D%b,%y')
order by date_format(orderdate,'%y%m%d')";

$resultchrtatt2= $conn->query($qrychrtatt2);
if ($resultchrtatt2->num_rows > 0){
while($rowschrtatt2 = $resultchrtatt2->fetch_assoc()){
$mnth=$rowschrtatt2["mnt"];$invamt=$rowschrtatt2["invamt"];
?>
  ["<?php echo  $mnth;?>", <?php echo  $invamt;?>],
<?php }}?>       
      ]
    }];

    var options = {
                    series: {
                        lines: {
                            show: true,
                            fill: 0.01
                        },
                        points: {
                            show: true,
                            radius: 4
                        }
                    },
                    grid: {
                        borderColor: '#eee',
                        borderWidth: 1,
                        hoverable: true,
                        backgroundColor: '#fcfcfc'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function (label, x, y) { return x + ' : ' + y; }
                    },
                    xaxis: {
                        tickColor: '#eee',
                        mode: 'categories'
                    },
                    yaxis: {
                        // position: 'right' or 'left'
                        tickColor: '#eee'
                    },
                    shadowSize: 0
                };

    var chart = $('.chart-pos0');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);



// HORIZONTAL BARCHART (BILL1 Invoice vs Collection)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

var data = [
    {
    label: 'Invoice',
		color:"<?=$colors[0] ?>",
    data: [
        [<?php echo $inv; ?>,1],

    ]
},
 {
    label: 'Collection',
		color:"<?=$colors[5] ?>",
    data: [
        [<?php echo $paid; ?>,2],

    ]
}
   ];

var options = {
    series: {
        stack: 1,
        bars: {
            order: 1,
            show: 2,
            barWidth: 0.8,
			barHeight:0.5,
            fill:1,
			lineWidth: 0.5,
            align: 'center',
            horizontal: true
        },
    },
    grid: {
        hoverable: false,
        borderWidth: 0
    },
    tooltip: true,
    tooltipOpts: {
        cssClass: "flotTip",
        content: "%s: %y",
        defaultTheme: false
    },
    legend: {
        show: false
    },
    yaxis: {

        position:top,

          max: 3, // set according to your needs, maybe dynamic depending on chart width
          min: 0,
        ticks: [
            [1, 'Invoice (<?php echo number_format($inv,2); ?>)'],[2, 'Collection (<?php echo number_format($paid,2); ?>)']
        ]
    },
	 xaxis: {
        show: false,
		 tickLength:0,

	 },
};

    var chart = $('.chart-bill1');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);

// CHART BAR STACKED (BILL2 Month wise Invoice and collection)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

  var data = [ {
      "label": "Invoice",
      "color": "#00abe3",

      "data": [
<?php
$qrychrtatt2 = "select   concat(substr(MONTHNAME(STR_TO_DATE(invoicemonth,'%m')),1,3),substr(invyr,3,2)) mnth,sum(invoiceamt) invamt,sum(paidamount) pamt  from invoice
where ( date( `invoicedt`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y'))
group by invoicemonth,invyr
order by invyr,invoicemonth";
$resultchrtatt2 = $conn->query($qrychrtatt2);
if ($resultchrtatt2->num_rows > 0) {
    while ($rowschrtatt2 = $resultchrtatt2->fetch_assoc()) {
        $mnth   = $rowschrtatt2["mnth"];
        $invamt = $rowschrtatt2["invamt"];
        ?>
        ["<?php echo $mnth; ?>", <?php echo $invamt; ?>],
<?php }} ?>
      ]
    }, {
      "label": "Collection",
      "color": "#7d848a",
      "data": [
        <?php
$qrychrtatt3 = "select   concat(substr(MONTHNAME(STR_TO_DATE(invoicemonth,'%m')),1,3),substr(invyr,3,2)) mnth,sum(invoiceamt) invamt,sum(paidamount) pamt  from invoice
where ( date( `invoicedt`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y'))
group by invoicemonth,invyr
order by invyr,invoicemonth";
$resultchrtatt3 = $conn->query($qrychrtatt3);
if ($resultchrtatt3->num_rows > 0) {
    while ($rowschrtatt3 = $resultchrtatt3->fetch_assoc()) {
        $mnth = $rowschrtatt3["mnth"];
        $pamt = $rowschrtatt3["pamt"];
        ?>
        ["<?php echo $mnth; ?>", <?php echo $pamt; ?>],
<?php }} ?>

      ]
    }];

    var options = {
				lines: {
					show: false
				},
                    series: {
                        stack: 1,
					bars: {
						order: 1,
						show: true,
						barWidth: 0.6,
						barHeight:0.5,
						lineWidth: 0.5,
						fill: 1,
						align: 'center',

					},
                    },
					legend:{
						show:false,
					},
                    grid: {
                        borderColor: '#eee',
                        borderWidth: 0,
                        hoverable: true,
                        backgroundColor: '#fff'

                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function (label, x, y) { return label + ' : ' + y; }
                    },
                    xaxis: {
                        tickColor: '#fcfcfc',
                        mode: 'categories',
						tickLength:0,
						position:'bottom',


                    },
                    yaxis: {
                        //position: 'right' or 'left'
                        tickColor: '#eee',
						axisLabel: "Date",
                        
                    },
                    shadowSize: 0
                };




    var chart = $('.chart-bill2');
    if(chart.length)
      $.plot(chart, data, options);

/*
	var chartv2 = $('.chart-bar-stackedv2');
    if(chartv2.length)
      $.plot(chartv2, datav2, options);
*/
  });

})(window, document, window.jQuery);

// CHART BAR STACKED (BILL3 month wise Sale Revenue)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

  var data = [ {
      "label": "Revenue",
      "color": "#00abe3",
      "data": [
<?php
$qrychrtatt2 = "select   date_format(orderdate,'%b%y') mnt
,sum(invoiceamount) invamt
from soitem
where ( date( `orderdate`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y'))
group by date_format(orderdate,'%b%y')
order by date_format(orderdate,'%y%m')";
$resultchrtatt2 = $conn->query($qrychrtatt2);
if ($resultchrtatt2->num_rows > 0) {
    while ($rowschrtatt2 = $resultchrtatt2->fetch_assoc()) {
        $mnth   = $rowschrtatt2["mnt"];
        $invamt = $rowschrtatt2["invamt"];
        ?>
        ["<?php echo $mnth; ?>", <?php echo $invamt; ?>],
<?php }} ?>
      ]
    }];

    var options = {
				lines: {
					show: false
				},
                    series: {
                        stack: 1,
					bars: {
						order: 1,
						show: true,
						barWidth: 0.6,
						barHeight:0.5,
						lineWidth: 0.5,
						fill: 1,
						align: 'center',

					},
                    },
					legend:{
						show:false,
					},
                    grid: {
                        borderColor: '#eee',
                        borderWidth: 0,
                        hoverable: true,
                        backgroundColor: '#fff'

                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function (label, x, y) { return label + ' : ' + y; }
                    },
                    xaxis: {
                        tickColor: '#fcfcfc',
                        mode: 'categories',
						tickLength:0,
						position:'bottom',


                    },
                    yaxis: {
                        //position: 'right' or 'left'
                        tickColor: '#eee',
						axisLabel: "Date",

                    },
                    shadowSize: 0
                };




    var chart = $('.chart-bill3');
    if(chart.length)
      $.plot(chart, data, options);

/*
	var chartv2 = $('.chart-bar-stackedv2');
    if(chartv2.length)
      $.plot(chartv2, datav2, options);
*/
  });

})(window, document, window.jQuery);

// HORIZONTAL BARCHART (Bill4 Type wise Expense)
// -----------------------------------

(function(window, document, $, undefined){

  $(function(){

var data = [
<?php
$i           = 0;
$qrychrt1    = "select ifnull(t.name,'Miscelinious') name,sum(e.amount)/1000 inv from expense e left join transtype t on e.transtype=t.id
	where ( date( `trdt`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y'))
GROUP BY t.name";
$resultchrt1 = $conn->query($qrychrt1);
$nrows       = $resultchrt1->num_rows;
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$rev = $rowschrt1["inv"];
        $pnm                            = $rowschrt1["name"];
        $i++;
        ?>


    {
    label: '<?php echo $pnm; ?>(<?php echo number_format($rev,2); ?> )',
		color:"<?=$colors[$i] ?>",
    data: [
        [<?php echo $rev; ?>,<?php echo $i; ?>],

    ]
},
<?php }} ?>
   ];

var options = {
    series: {
        stack: 1,
        bars: {
            order: 1,
            show: 2,
            barWidth: 0.8,
			barHeight:0.5,
            fill:1,
			lineWidth: 0.5,
            align: 'center',
            horizontal: true
        },
    },
    grid: {
        hoverable: false,
        borderWidth: 0
    },
    tooltip: true,
    tooltipOpts: {
        cssClass: "flotTip",
        content: "%s: %y",
        defaultTheme: false
    },
    legend: {
        show: false
    },
    yaxis: {

        position:top,

          max:<?php echo $nrows + 1; ?>, // set according to your needs, maybe dynamic depending on chart width
          min: 0,
        ticks: [

<?php
$i           = 0;
$qrychrt1    = "select ifnull(t.name,'Miscelinious') name,sum(e.amount)/1000 inv from expense e left join transtype t on e.transtype=t.id
	where ( date( `trdt`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y'))
GROUP BY t.name";
$resultchrt1 = $conn->query($qrychrt1);
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$rev = $rowschrt1["inv"];
        $pnm                            = $rowschrt1["name"];
        $i++;
        ?>
            [<?php echo $i; ?>, '<?php echo $pnm; ?>(<?php echo number_format($rev,2); ?>)'],
<?php }} ?>
        ]
    },
	 xaxis: {

		 tickLength:0,

	 },
};

    var chart = $('.chart-bill4');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);

// CHART BAR STACKED (BILL5 month wise net profit)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

  var data = [ {
      "label": "Profit",
      "color": "#00abe3",
      "data": [
<?php
$qrychrtatt2 = "select mnth,(sum(inc)-sum(exp)) prft from
(
select   concat(invyr,invoicemonth) ord, concat(substr(MONTHNAME(STR_TO_DATE(invoicemonth,'%m')),1,3),substr(invyr,3,2)) mnth,sum(paidamount) inc,0 exp  from invoice
 where ( date( `invoicedt`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y'))
 group by invoicemonth,invyr
union all
select  date_format(trdt,'%y%m') ord,date_format(trdt,'%b%y') mnth,0 inc,sum(amount) exp from expense
where ( date( `trdt`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y'))
group by date_format(trdt,'%b%y')
) u
group by mnth
order by ord";
$resultchrtatt2 = $conn->query($qrychrtatt2);
if ($resultchrtatt2->num_rows > 0) {
    while ($rowschrtatt2 = $resultchrtatt2->fetch_assoc()) {
        $mnth   = $rowschrtatt2["mnth"];
        $invamt = $rowschrtatt2["prft"];
        ?>
        ["<?php echo $mnth; ?>", <?php echo $invamt; ?>],
<?php }} ?>
      ]
    }];

    var options = {
				lines: {
					show: false
				},
                    series: {
                        stack: 1,
					bars: {
						order: 1,
						show: true,
						barWidth: 0.6,
						barHeight:0.5,
						lineWidth: 0.5,
						fill: 1,
						align: 'center',

					},
                    },
					legend:{
						show:false,
					},
                    grid: {
                        borderColor: '#eee',
                        borderWidth: 0,
                        hoverable: true,
                        backgroundColor: '#fff'

                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function (label, x, y) { return label + ' : ' + y; }
                    },
                    xaxis: {
                        tickColor: '#fcfcfc',
                        mode: 'categories',
						tickLength:0,
						position:'bottom',


                    },
                    yaxis: {
                        //position: 'right' or 'left'
                        tickColor: '#eee',
						axisLabel: "Date",

                    },
                    shadowSize: 0
                };




    var chart = $('.chart-bill5');
    if(chart.length)
      $.plot(chart, data, options);

/*
	var chartv2 = $('.chart-bar-stackedv2');
    if(chartv2.length)
      $.plot(chartv2, datav2, options);
*/
  });

})(window, document, window.jQuery);

// HORIZONTAL BARCHART (Bill6 Cutomer wise Revenue)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

var data = [
<?php
$i           = 0;
$qrychrt1    = "select u.name,u.inv from (select o.name,sum(i.invoiceamt)/1000 inv from invoice i  left join organization o on i.organization=o.id  where  ( date( `invoicedt`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y')) group by o.name order by inv desc limit 10) u order by u.inv asc ";
$resultchrt1 = $conn->query($qrychrt1);
$nrows       = $resultchrt1->num_rows;
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$rev = $rowschrt1["inv"];
        $pnm                            = $rowschrt1["name"];
        $i++;
        ?>


    {
    label: '<?php echo $pnm; ?>(<?php echo number_format($rev,2); ?> )',
		color:"<?=$colors[$i] ?>",
    data: [
        [<?php echo $rev; ?>,<?php echo $i; ?>],

    ]
},
<?php }} ?>
   ];

var options = {
    series: {
        stack: 1,
        bars: {
            order: 1,
            show: 2,
            barWidth: 0.8,
			barHeight:0.5,
            fill:1,
			lineWidth: 0.5,
            align: 'center',
            horizontal: true
        },
    },
    grid: {
        hoverable: false,
        borderWidth: 0
    },
    tooltip: true,
    tooltipOpts: {
        cssClass: "flotTip",
        content: "%s: %y",
        defaultTheme: false
    },
    legend: {
        show: false
    },
    yaxis: {

        position:top,

          max:<?php echo $nrows + 1; ?>, // set according to your needs, maybe dynamic depending on chart width
          min: 0,
        ticks: [

<?php
$i           = 0;
$qrychrt1    = "select u.name,u.inv from (select o.name,sum(i.invoiceamt)/1000 inv from invoice i  left join organization o on i.organization=o.id  where  ( date( `invoicedt`) between STR_TO_DATE('" . $fd1 . "','%d/%m/%Y') and STR_TO_DATE('" . $td1 . "','%d/%m/%Y')) group by o.name order by inv desc limit 10) u order by u.inv asc";
$resultchrt1 = $conn->query($qrychrt1);
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$rev = $rowschrt1["inv"];
        $pnm                            = $rowschrt1["name"];
        $i++;
        ?>
            [<?php echo $i; ?>, '<?php echo $pnm; ?>(<?php echo number_format($rev,2); ?>)'],
<?php }} ?>
        ]
    },
	 xaxis: {

		 tickLength:0,

	 },
};

    var chart = $('.chart-bill6');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);



// CHART SPLINE
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

    var data = [{
      "label": "Uniques",
      "color": "#768294",
      "data": [
        ["Mar", 70],
        ["Apr", 85],
        ["May", 59],
        ["Jun", 93],
        ["Jul", 66],
        ["Aug", 86],
        ["Sep", 60]
      ]
    }, {
      "label": "Recurrent",
      "color": "#1f92fe",
      "data": [
        ["Mar", 21],
        ["Apr", 12],
        ["May", 27],
        ["Jun", 24],
        ["Jul", 16],
        ["Aug", 39],
        ["Sep", 15]
      ]
    }];

    var datav2 = [{
      "label": "Hours",
      "color": "#23b7e5",
      "data": [
        ["Jan", 70],
        ["Feb", 20],
        ["Mar", 70],
        ["Apr", 85],
        ["May", 59],
        ["Jun", 93],
        ["Jul", 66],
        ["Aug", 86],
        ["Sep", 60],
        ["Oct", 60],
        ["Nov", 12],
        ["Dec", 50]
      ]
    }, {
      "label": "Commits",
      "color": "#7266ba",
      "data": [
        ["Jan", 20],
        ["Feb", 70],
        ["Mar", 30],
        ["Apr", 50],
        ["May", 85],
        ["Jun", 43],
        ["Jul", 96],
        ["Aug", 36],
        ["Sep", 80],
        ["Oct", 10],
        ["Nov", 72],
        ["Dec", 31]
      ]
    }];

    var datav3 = [{
      "label": "Home",
      "color": "#1ba3cd",
      "data": [
        ["1", 38],
        ["2", 40],
        ["3", 42],
        ["4", 48],
        ["5", 50],
        ["6", 70],
        ["7", 145],
        ["8", 70],
        ["9", 59],
        ["10", 48],
        ["11", 38],
        ["12", 29],
        ["13", 30],
        ["14", 22],
        ["15", 28]
      ]
    }, {
      "label": "Overall",
      "color": "#3a3f51",
      "data": [
        ["1", 16],
        ["2", 18],
        ["3", 17],
        ["4", 16],
        ["5", 30],
        ["6", 110],
        ["7", 19],
        ["8", 18],
        ["9", 110],
        ["10", 19],
        ["11", 16],
        ["12", 10],
        ["13", 20],
        ["14", 10],
        ["15", 20]
      ]
    }];

    var options = {
      series: {
          lines: {
              show: false
          },
          points: {
              show: true,
              radius: 4
          },
          splines: {
              show: true,
              tension: 0.4,
              lineWidth: 1,
              fill: 0.5
          }
      },
      grid: {
          borderColor: '#eee',
          borderWidth: 1,
          hoverable: true,
          backgroundColor: '#fcfcfc'
      },
      tooltip: true,
      tooltipOpts: {
          content: function (label, x, y) { return x + ' : ' + y; }
      },
      xaxis: {
          tickColor: '#fcfcfc',
          mode: 'categories'
      },
      yaxis: {
          min: 0,
          max: 150, // optional: use it for a clear represetation
          tickColor: '#eee',
          //position: 'right' or 'left',
          tickFormatter: function (v) {
              return v/* + ' visitors'*/;
          }
      },
      shadowSize: 0
    };

    var chart = $('.chart-spline');
    if(chart.length)
      $.plot(chart, data, options);

    var chartv2 = $('.chart-splinev2');
    if(chartv2.length)
      $.plot(chartv2, datav2, options);

    var chartv3 = $('.chart-splinev3');
    if(chartv3.length)
      $.plot(chartv3, datav3, options);

  });

})(window, document, window.jQuery);

// CHART AREA
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

    var data = [{
      "label": "Uniques",
      "color": "#aad874",
      "data": [
        ["Mar", 50],
        ["Apr", 84],
        ["May", 52],
        ["Jun", 88],
        ["Jul", 69],
        ["Aug", 92],
        ["Sep", 58]
      ]
    }, {
      "label": "Recurrent",
      "color": "#7dc7df",
      "data": [
        ["Mar", 13],
        ["Apr", 44],
        ["May", 44],
        ["Jun", 27],
        ["Jul", 38],
        ["Aug", 11],
        ["Sep", 39]
      ]
    }];

    var options = {
                    series: {
                        lines: {
                            show: true,
                            fill: 0.8
                        },
                        points: {
                            show: true,
                            radius: 4
                        }
                    },
                    grid: {
                        borderColor: '#eee',
                        borderWidth: 1,
                        hoverable: true,
                        backgroundColor: '#fcfcfc'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function (label, x, y) { return x + ' : ' + y; }
                    },
                    xaxis: {
                        tickColor: '#fcfcfc',
                        mode: 'categories'
                    },
                    yaxis: {
                        min: 0,
                        tickColor: '#eee',
                        // position: 'right' or 'left'
                        tickFormatter: function (v) {
                            return v + ' visitors';
                        }
                    },
                    shadowSize: 0
                };

    var chart = $('.chart-area');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);

// CHART BAR
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

			var data = [
				{
				  label: "Raihan01",
				  data: [['1',5]],
					labels: {
						position: "outside"
					}
				},
				{
				  label: "Samiul01",
				  data: [['2',3]],
					labels: {
						position: "outside"
					}
				},
				{
				  label: "Kazi01",
				  data: [['3',6]],
					labels: {
						position: "outside"
					}
				},
				{
				  label: "Akash01",
				  data: [['4',3]],
					labels: {
						position: "outside"
					}
				},

				{
				  label: "Ratul01",
				  data: [['5',4]],
					labels: {
						position: "outside"
					}
				},
				{
				  label: "Sabir01",
				  data: [['6',5]],
					labels: {
						position: "outside"
					}
				},

				{
				  label: "Sahir01",
				  data: [['7',2]],
					labels: {
						position: "outside"
					}
				},

			];

    var options = {
                    series: {
                        bars: {
                            align: 'center',
                            lineWidth: 0,
                            show: true,
                            barWidth: 0.5,
                            fill: 0.9
                        }
                    },
                    grid: {
						//show:false,
                        borderColor: '#eee',
                        borderWidth: 1,
                        hoverable: true,
                        backgroundColor: '#fcfcfc'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function (label, x, y) { return x + ' : ' + y; }
                    },
                    xaxis: {
						tickColor: '#fcfcfc',
                        mode: 'categories'

                    },
                    yaxis: {
                        // position: 'right' or 'left'

                        tickColor: '#eee'
                    },
                    shadowSize: 0
                };

    var chart = $('.chart-barhr1');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);


// HORIZONTAL BARCHART (collection VS revenue)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

var data = [
<?php
$qrychrt1    = "select sum( i.invoiceamt) inv,sum(p.amount) coll from invoice i left join invoicepayment p on i.invoiceno=p.invoicid";
$resultchrt1 = $conn->query($qrychrt1);
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {$rev = $rowschrt1["inv"] / 1000;
        $col                            = $rowschrt1["coll"] / 1000;}}
?>


    {
    label: 'Colection(<?php echo $col; ?> K)',
    data: [
        [<?php echo $col; ?>,1],],
        color:'<?=$colors[1] ?>',
        valueLabels: {
		show: true,
		align: "center",
	}
}, {
    label: 'Revenue(<?php echo $rev; ?> K)',
    data: [
        [<?php echo $rev; ?>, 2],

    ],  color:'<?=$colors[0] ?>',
		valueLabels: {
		show: true,
		align: "center",
	},
},

   ];

var options = {
    series: {
        stack: 1,
        bars: {
            order: 1,
            show: 2,
            barWidth: 0.8,
			barHeight:0.5,
			lineWidth: 0.5,
            fill: 1,
            align: 'center',
            horizontal: true,
        },
    },
    grid: {
        hoverable: true,
        borderWidth: 0
    },
    tooltip: false,
    tooltipOpts: {
        cssClass: "flotTip",
        content: "%s: %y",
        defaultTheme: false
    },
    legend: {
        show: false
    },
    yaxis: {
		tickLength:0,
        position:top,
          panRange: [0,50],
          max: 3, // set according to your needs, maybe dynamic depending on chart width
          min: 0,

        ticks: [
            [1, 'Colection'],
            [2, 'Revenue']
        ]
    },
	 xaxis: {

		 tickLength:0,
	 },
};

    var chart = $('.chart-bar-horz');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);





// CHART LINE
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

    var data = [{
        "label": "Complete",
        "color": "#5ab1ef",
        "data": [
            ["Jan", 500],
            ["Feb", 600],
            ["Mar", 450],
            ["Apr", 700],
            ["May", 350],
            ["Jun", 550],
            ["Jul", 200],
            ["Aug", 990],
            ["Sep", 150]
        ]
    }, {
        "label": "In Progress",
        "color": "#f5994e",
        "data": [
            ["Jan", 153],
            ["Feb", 116],
            ["Mar", 136],
            ["Apr", 119],
            ["May", 148],
            ["Jun", 133],
            ["Jul", 118],
            ["Aug", 161],
            ["Sep", 59]
        ]
    }, {
        "label": "Cancelled",
        "color": "#d87a80",
        "data": [
            ["Jan", 111],
            ["Feb", 97],
            ["Mar", 93],
            ["Apr", 110],
            ["May", 102],
            ["Jun", 93],
            ["Jul", 92],
            ["Aug", 92],
            ["Sep", 44]
        ]
    }];

    var options = {
                    series: {
                        lines: {
                            show: true,
                            fill: 0.01
                        },
                        points: {
                            show: true,
                            radius: 4
                        }
                    },
                    grid: {
                        borderColor: '#eee',
                        borderWidth: 1,
                        hoverable: true,
                        backgroundColor: '#fcfcfc'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function (label, x, y) { return x + ' : ' + y; }
                    },
                    xaxis: {
                        tickColor: '#eee',
                        mode: 'categories'
                    },
                    yaxis: {
                        // position: 'right' or 'left'
                        tickColor: '#eee'
                    },
                    shadowSize: 0
                };

    var chart = $('.chart-line');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);



// CHART PIE 2(Building Type Wise)
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

    var data = [
   <?php
$p2clrkey = 0;

$qrychrt1    = "select a.hrName org,  sum(i.invoiceamt) inv from invoice i , organization o,hr a where  i.organization=o.id and o.salesperson = a.id group by a.hrName";
$resultchrt1 = $conn->query($qrychrt1);
if ($resultchrt1->num_rows > 0) {
    while ($rowschrt1 = $resultchrt1->fetch_assoc()) {
        $org   = $rowschrt1["org"];
        $rev   = $rowschrt1["inv"] / 1000;
        $color = substr(md5(rand()), 0, 6);
        ?>
        {
      "label": "<?php echo $org; ?>(<?php echo $rev; ?> K)",
      "color": "<?php echo $colors[$p2clrkey]; ?>",
      "data": <?php echo $rev; ?>
    },
    <?php
$p2clrkey++;
    }} ?>];

    var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 40,
                            label: {
                                show: true,
                                radius: 0.5,
                                formatter: function (label, series) {
                                    return '<div class="flot-pie-label">' +
										label + ' : ' +
                                    Math.round(series.percent)+'%';
									//Math.round(series.data[0][1]);

                                  //  '%</div>';
                                },
                                background: {
                                    opacity: 0,
                                    color: '#fff'
                                },
								color: '#000',

                            }
                        }
                    },
                    legend:{
                        show:false
                    }
                };

    var chart = $('.chart-pie2');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);



</script>