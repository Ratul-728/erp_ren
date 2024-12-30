<script>
/* combo form populators */

//This event is required for each select;
//Department

 $( window ).on( "load", function() {
     var title = $(this).find("option:first").text();title = title.split(" ");title = title[1];
	if($(this).find("option:selected").val() == 'addcmb'){
		$(".cmb-modal iframe").attr('style','width:100%; height:110px;');
		$(".cmb-modal .modal-title").html('Add '+title);
		$(".cmb-modal iframe").attr('src','cmb_forms/form_cmb.php?cmbname='+$(this).attr('name')+'&title='+title);
		$(".cmb-modal").modal();
		//alert($(this).attr('name'));
	}
  });
  
  </script>