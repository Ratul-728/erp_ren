<?php

require_once "common/conn.php";

session_start();

$usr = $_SESSION["user"];

if ($usr == '') {
    header("Location: " . $hostpath . "/hr.php");
} else {

    $res = $_GET['res'];

    $msg = $_GET['msg'];

    $id = $_GET['id'];

    if ($res == 4) {

        $qry = " select `id`, `code`, `name`, `type`, `mu`, `color`, `size`, `pattern`, `rate`, `cost`, `catagory`, `dimension`, `wight`, `currency`, `image`, `description` FROM `item` where id= " . $id;

        //echo $qry; die;

        if ($conn->connect_error) {

            echo "Connection failed: " . $conn->connect_error;

        } else {

            $result = $conn->query($qry);

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    $iid  = $row["id"];
                    $code = $row["code"];

                    $name        = $row["name"];
                    $productType = $row["type"];

                    $mu      = $row["mu"];
                    $color   = $row["color"];
                    $size    = $row["size"];
                    $pattern = $row["pattern"];
                    $rate    = $row["rate"];
                    $cost    = $row["cost"];
                    $ItemCat = $row["catagory"];

                    $dimension = $row["dimension"];
                    $weight    = $row["wight"];
                    $currency  = $row["currency"];
                    $prodPhoto = $row["image"];
                    $details   = $row["description"];

                }

            }

        }

        $mode = 2; //update mode

        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    } else {

        $iid  = '';
        $code = '';

        $name        = '';
        $productType = '1';

        $mu      = '1';
        $color   = '1';
        $size    = '';
        $rate    = '0';
        $cost    = '0';
        $ItemCat = '1';

        $dimension = '';
        $weight    = '0';
        $currency  = '1';
        $prodPhoto = '';
        $details   = '';
        $pattern   = '';

        $mode = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

     */

    $currSection = 'item';

    $currPage = basename($_SERVER['PHP_SELF']);

    ?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">

<?php include_once 'common_header.php'; ?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css">


<style>

.ds-divselect-wrapper{
        margin-top: 100px;
        height: 300px;
        width: 20%;

}
.ds-input{

     position: relative;

}
.ds-list{
    border: thin solid lightgray;

   /* position: absolute;*/
    background: white;
}
.ds-list li{
    list-style:none;
    padding: 5px 0;
    border-bottom: thin solid lightgray;
    margin-left: -30px;
    cursor: pointer;
    padding-left: 5px;;
}
.ds-list li:hover{
    background: #e7e9eb;
}
.ds-add-list{
   /* position: absolute;*/
    height: 200px;
    padding: 10px;
    background: white;
    border: thin solid lightgray;
    padding: 10px;


}
.ds-add-list h3{
    margin: 0;
    padding: 10px 0;
}
.ds-add-list hr{
    margin: 0;
    padding: 5px 0;
}
.ds-add-list label{
   font-size: 16px;
    padding: 5px 0;
}
.ds-add-list button{
    padding: 5px 20px;
    background: white;
    border: thin solid lightgray;
    border-radius: 30px;
    float: right;
}
.ds-add-list button:hover{
    background: #00abe3;
    color: white;
}
.add-more-col{
    text-align: left;
}

button.more-info {
    border: 0;
    border-radius: 0;
    font-size: 12px;
    background: transparent !important;
    padding: 0 3px;
    text-align: left;
    margin-top: 5px;
    float: left

}


</style>

<body class="form">

<?php include_once 'common_top_body.php'; ?>



<div id="wrapper">

  <!-- Sidebar -->

    <div id="sidebar-wrapper" class="mCustomScrollbar">

        <div class="section">

  	        <i class="fa fa-group  icon"></i>

            <span>Item  Details</span>

        </div>

        <?php include_once 'menu.php'; ?>

	    <div style="height:54px;">

	    </div>

    </div>

   <!-- END #sidebar-wrapper -->

   <!-- Page Content -->


    <form method="post" action="common/addselect.php" id="form1" enctype="multipart/form-data">

        <div class="ds-divselect-wrapper cat-name">
                <div class="ds-input">
                    <input type="hidden" name="dest" value="">
                    <input type="hidden" name="org_id" id = "org_id" >
                     <input type="text" name="org_name" autocomplete="off"  class="input-box form-control" >
                </div>
                <div class="list-wrapper">
                    <div class="ds-list">

                        <ul class="input-ul" id="inpUl">
                            <li class="addnew">+ Add new</li>


                            <?php $qryitm = "SELECT `id`, `name`  FROM `organization` order by name";
    $resultitm                                = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
        $tid = $rowitm["id"];
        $nm  = $rowitm["name"]; ?>
                                        <li class="pp1" value = "<?=$tid ?>"><?=$nm ?></li>
                        <?php }} ?>
                        </ul>
                    </div>
                    <div class="ds-add-list">
                        <h3>Add new Item</h3>
                        <hr>
                        <label for="">Name</label> <br>
                        <input type="text" name="" autocomplete="off" class="Name addinpBox form-control" id="">
                        <br>
                        <div class="row">
                            <div class="col-lg-6 add-more-col">
                                <button type="button" class="more-info">+add more info</button>

                            </div>
                            <div class="col-lg-6">
                                 <button type = "button" class="primary ds-add-list-btn ">Save</button>
                            </div>
                        </div>

                    </div>
                </div>
        </div>

        <button type = "submit"> Submit</button>

    </form>


   <!-- end row-->

                            </div>

                        </div>



                            <!-- /#end of panel -->



                        </form>

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- /#page-content-wrapper -->

<?php include_once 'common_footer.php'; ?>

    <script>


        $(document).ready(function(){

             $('.ds-list').attr('style','display:none');
             $('.ds-add-list').attr('style','display:none');

             //Input Click

            $('.input-box').click(function(){
                 //console.log("d1");
                 $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:block');
                // $(this).find('.ds-add-list').attr('style','display:none');
            });

            //Option's value shows on input box

            $('.input-ul li').click(function(){
               // console.log(this);

                if(!$(this).hasClass("addnew")){

                    let litxt= $(this).text();
                    let lival= $(this).val();

                    $("#org_id").val(lival);
					$(this).closest('.ds-divselect-wrapper').find('.input-box').val(litxt);
					$(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value',litxt);

                    // $(this).closest('.ds-add-list').attr('style','display:none');
                    $(this).closest('.ds-list').attr('style','display:none');
                }

            });

            // New input box display

            $('.input-ul .addnew').click(function(){
                $(this).closest('.list-wrapper').find('.ds-add-list').attr('style','display:block');
                $(this).closest('.ds-list').attr('style','display:none');
            });

            // New-Input box's value display on old-input box

            $('.ds-add-list-btn').click(function(){
                let x= $(this).closest('.ds-add-list').find('.addinpBox').val();
                //console.log(x);
                $(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value', x);
				$(this).closest('.ds-divselect-wrapper').find('.input-box').val(x);
                $(this).closest('.ds-add-list').attr('style','display:none');
                //$(this).closest('.ds-add-list').find('.addinpBox').val('');
                console.log($(this).closest('.ds-add-list').find('.addinpBox').val(""));
                // alert(x);
                // }
                action(x);
                function action(x){
                    $.ajax({
                        url:"phpajax/divSelectOrg.php",
                        method:"POST",
                        data:{newItem: x},
                        dataType: 'JSON',
                        success:function(res)
                            {
                                $("#org_id").val(res.id);
                                $('.display-msg').html(res.name);
                                messageAlertLong(res,'alert-success');

                            }
                    });
	             }


            });




            $(document).mouseup(function (e) {
                if ($(e.target).closest(".ds-list").length
                            === 0) {
                    $(".ds-list").hide();

                } if($(e.target).closest(".ds-add-list").length
                            === 0) {
                    $(".ds-add-list").hide();
                }
            });

            //Search functionality and add value to new input box
            /*
             $('.input-box').on("keyup", function() {
             //alert("h");
            var value = $(this).val().toLowerCase();
            $(this).closest(".input-ul li ").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            $('.addnew').text("+Add Item" + " (" + value + ")");
            $('.addnew').click(function(){
                alert("S");
                 $('.ds-add-list').attr('style','display:block');
                 $('.addinpBox').val(value);
                  $('.ds-list').attr('style','display:none');
            });
            });

            */
            //   $(document).ready(function(){
            //keyup comment


            $('.input-box').on("keyup", function() {
			    //alert($(this).val());
			    var searchKey = $(this).val().toLowerCase();
                $(this).closest('.ds-divselect-wrapper').find(".input-ul li ").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchKey)>-1);
                });
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('style', 'display:block');
                $(this).closest('.ds-divselect-wrapper').find('.addnew').attr('value', searchKey);
                $(this).closest('.ds-divselect-wrapper').find('.addnew').text("+Add Item" + " (" + searchKey + ")");
			    $(this).closest('.ds-divselect-wrapper').find('.input-ul li').click(function(){
                    // console.log(this)
                    if(!$(this).hasClass("addnew")){
                        let x= $(this).text();
                        //console.log($(this).closest('.ds-divselect-wrapper').attr('value', x));
                        $(this).closest('.ds-divselect-wrapper').val(x);
                        // $(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value', x);
                        //console.log($(this).closest('.ds-divselect-wrapper').find('.input-box').attr('value', x));
                        // $(this).closest('.ds-add-list').attr('style','display:none');
                        $(this).closest('.ds-list').attr('style','display:none');
                    }
                })

                $(this).closest('.ds-divselect-wrapper').find('.addnew').click(function(){

                    $(this).closest('.ds-divselect-wrapper').find('.ds-add-list').attr('style','display:block');
                    $(this).closest('.ds-divselect-wrapper').find('.addinpBox').val(searchKey);
                    $(this).closest('.ds-divselect-wrapper').find('.ds-list').attr('style','display:none');
                });

			});


        })


    </script>

</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js
"></script>
<script>
    //alert("s");
$(document).ready(function(){

   //alert("j");
  $(".more-info").click(function(){
       //alert("j1");
       //return false;
   BootstrapDialog.show({

							title: 'Add New Organization',
							//message: '<div id="printableArea">'+data+'</div>',
							message: $('<div></div>').load('addselect_modal_org_tab.php'),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: false, // <-- Default value is false
							draggable: true, // <-- Default value is false
							buttons: [{
								//icon: 'glyphicon glyphicon-print',
								cssClass: 'btn-primary',
								id: 'btn-1',
								label: 'Save',
								action: function(dialog) {
									
									var $button = this;
									$button.hide();
									
									dialog.setClosable(false);
									
								    var orgtype = $('#org-type').serializeArray();

								    if(orgtype[0].value == 1){
								        var ajxdata = $('#form-org').serializeArray();
								    }else{
								        var ajxdata = $('#form-indi').serializeArray();
								    }

                                    $.ajax({
                                          type: "POST",
                                          url: 'phpajax/divSelectOrg.php',
                                          data: {data: ajxdata, type: orgtype[0].value},
                                          type: 'POST',
								          dataType:"json",
                                          success: function(res){
											  
											  //dialog.setMessage("Success");
											  
											  
                                              $("#org_id").val(res.id);
                                              //dialogItself.close(); need help Raihan Bhaia
                                              $('.input-box').attr('value',res.name);
											  
											  	 dialog.close();
//                                            function closeModal (dialogItself) {
//									            dialogItself.close();
//												
//								            }
											  

                                            //alert("ok");

                                            //close -- res.name
                                          }
                                        });


								/*var $button = this;
								//$button.hide();
                                //dialogItself.close();
								//$button.spin();
								dialog.setClosable(false);



								var obj = [];

								var cdata = {};


								 cdata.name = $("#new-cat-field").val();



								//check user data;
								  if(!$("#new-cat-field").val()){alert('Please enter category name'); $button.show(); return false;}


								 obj.push(cdata);

								var dataString = JSON.stringify(obj);



								/*alert(dataString);

								$.ajax({
								   url: 'phpajax/cmb_add_category.php',
								   data: {posData: dataString},
								   type: 'POST',
								   dataType:"json",
								   success: function(res) {

									   if(res != 0){
    									    // dialog.setMessage(res.query);
    									   //$("#new-cat-field").val(res.name);
    									   $("#old-prod-cart-field").val(res.name);
    									   $("#catID").val(res.id);
    									   $("#catID").attr('data-name',res.name);
    									   //document.title = res.name;
    									  // dialogItself.close();
										  dialog.setMessage(res.msg);
										  setTimeout(function(){
											  	dialog.close();
											  },2000);

									   }else{
									       alert("Something went wrong!!!");
									   }

								   }
								});  */




								},
							}, {
								label: 'Close',
								action: function(dialogItself) {
									dialogItself.close();
								}
							}]
						});

  });
});




</script>

</html>

<?php } ?>