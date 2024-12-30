<script>
/* combo form populators */

//This event is required for each select;
//Department
$("select[name=cmbdpt]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmbdpt]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  });


//Designation
$("select[name=cmbdsg]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmbdsg]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  });
  
  
  
//Contact Type
$("select[name=cmbcontype]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmbcontype]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  });
  
//District
$("select[name=district]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=district]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  });
  
//country
$("select[name=country]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=country]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  });      


//state
$("select[name=state]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=state]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  });    
  
  
//Industry Type
$("select[name=cmbindtype]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmbindtype]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  }); 
  
//Area
$("select[name=area]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=area]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  }); 
  
//Operation Status
$("select[name=cmbopttype]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmbopttype]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('title'));
	}
  });

//Organization
$("select[name=cmborg]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmborg]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:500px; height:250px;');
		$(".cmb-modal .modal-title").html('Add '+title);;
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('title'));
	}
  });
  
//Trans Type
$("select[name=cmbtype]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmbtype]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  });
  
//Deal Status
$("select[name=cmbstat]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmbstat]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  });  
  
//Deal Lost Reason
$("select[name=cmblost]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmblost]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  });         
   
//Item Category
$("select[id=itemcat]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[id=itemcat]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname=itemcat&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  }); 
  
  
  //Currency
$("select[name=cmbcur]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmbcur]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:160px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb_multiple.php?cmbname='+$(this).attr('name')+'&field[name]=Name&field[shnm]=Code&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  }); 
  
  
 //Size	NOTE: &idisvalue=true IN URL
$("select[name=size]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=size]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:160px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb_multiple.php?cmbname='+$(this).attr('name')+'&field[name]=Name&field[code]=Code&title='+title+'&idisvalue=true');
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  }); 
  
  //Color
$("select[name=cmbcolor]").append('<option value="addcmb" class="load-modal" data-toggle="modal" data-target="#myModal">Add New</option>');
$("select[name=cmbcolor]").change(function() {
	
	var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:160px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb_multiple.php?cmbname='+$(this).attr('name')+'&field[name]=Name&field[code]=Code&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  }); 
  
   
</script>