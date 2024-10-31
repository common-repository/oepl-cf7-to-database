jQuery(".oepl_cf7formlist").click(function()
{  
    var pid = jQuery(this).attr("pid");
    var frm = jQuery(this).attr("frm");
    var data = {};        
    data.action = 'OEPL_cf7list_get';
    data.pid    = pid;
    data.frm    = frm;
	
    jQuery.post(ajaxurl,data,function(response)
    {
		jQuery("#OEPL_cf7_info .content").html(response);
		tb_show(data.frm,'#TB_inline?width=400&amp;height=400&amp;inlineId=OEPL_cf7_info');	
	});
});

jQuery("#doaction").click(function()
{
	var bulk_val = jQuery("#bulk-action-selector-top").val();
	if(bulk_val == 'flddelete')
	{
	  	var ck_box = jQuery('input[name="flddelete[]"]:checked').length;
	  	
		if(ck_box == 0){
	    	alert("Please check checkbox");
	      	return false;
	    }else{
	    	if(!confirm('Are you sure u want to delete this record?')){
	    		return false;
	    	}else{
	    		return true;
	    	}
	    } 
	}else{
		return false;
	}
});

jQuery("#doaction2").click(function()
{
	var bulk_val2 = jQuery("#bulk-action-selector-bottom").val();
	if(bulk_val2 == 'flddelete')
	{
	  	var ck_box2 = jQuery('input[name="flddelete[]"]:checked').length;
	  	
		if(ck_box2 == 0){
	    	alert("Please check checkbox");
	      	return false;
	    }else{
	    	if(!confirm('Are you sure u want to delete this record?')){
	    		return false;
	    	}else{
	    		return true;
	    	}
	    } 
	}else{
		return false;
	}
});

jQuery(".delete_rec").click(function()
{
	if(!confirm('Are you sure u want to delete this record?')){
		return false;
	}else{
		return true;
	}
});