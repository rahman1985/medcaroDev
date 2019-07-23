jQuery(document).ready(function($) 
{	
	  $("#appointment_date").change(function()
	  {
			   $('.removeselect').css("background","#FFFFFF");			    
			   $('.removeselect').removeClass("select"); 
			   $('.removeselect .time').css('visibility', 'hidden');			   
			   $('.removeselect').removeClass("checked"); 
			   $('.removeselect .time').prop('checked', false); 
			 
			var apointment_date  = $('#appointment_date').val() ;
			var doctor_id =$('#doctor').val();
			var patient_id =$('#patient').val();
			var date1 = $('#appointment_date').datepicker('getDate');
			var day = date1.getDay();	
				if (day == 1)
				{
					var dayofweek="monday";
				}
				if (day == 2){
					var dayofweek="tuesday";
				}
				if (day == 3)
				{
					var dayofweek="wednesday";
				}
				if (day == 4){
					var dayofweek="thursday";
				}
				if (day == 5)
				{
					var dayofweek="friday";
				}
				if (day == 6){
					var dayofweek="saturday";
				}
				if (day == 7){
					var dayofweek="sunday";
				}
	 		  var curr_data = {
	 					 action: 'Mj_hmgt_onchage_gate_apointment_time',
	 					 apointment_date: apointment_date,			
						 doctor_id: doctor_id,	
	 					 patient_id: patient_id,	
						 dayofweek: dayofweek,					 
	 					 dataType: 'json'
	 					 };
						 
	 				$.post(hmgt.ajax, curr_data, function(response) {
					
					var json_obj = $.parseJSON(response);
										
					 var new_val ="";
					 $.each( json_obj, function( i, val ) {
						
					  new_val = val.replace(":","_");
					 
				      $('.selected_'+new_val).css("background","#4CAF50");
				      $('.selected_'+new_val).addClass("select"); 
				      $('.select .time').css("visibility","visible");
					
					 }); 					
	 			 return true;				 
	 			 });
		    }); 
	
		$('.appointment_date').change(function(){
			
			  $('.removeselect').css("background","#FFFFFF");			    
			  $('.removeselect').removeClass("select_apointment"); 
			  $('.removeselect .time').css('visibility', 'hidden');
			 
			 var apointment_date  = $('#appointment_date').val();
			 var edit_apointment_date  = $('#hide_date_value').val();
			 var edit_apointment_time  = $('#hide_time_value').val();
			 var doctor_id =$('#doctor').val() ;
			 var patient_id =$('#patient').val() ;
			
			 var curr_data = {
	 					action: 'MJ_hmgt_onchage_gate_apointment',
	 					apointment_date: apointment_date,			
	 					doctor_id: doctor_id,						
	 					edit_apointment_date: edit_apointment_date,						
	 					edit_apointment_time: edit_apointment_time,						
	 					patient_id: patient_id,						
	 					dataType: 'json'
	 					};
				    $.post(hmgt.ajax, curr_data, function(response) {
						
						
					var json_obj = jQuery.parseJSON(response);	
					
					 var new_val ="";
					 $.each(json_obj['book_appointment_time'], function( i, val ) {
					
					 new_val = val.replace(":","_");
										 
				     $('.selected_'+new_val).css("background","#008CBA");
				     $('.selected_'+new_val).addClass("select_apointment"); 
				     $('.select_apointment .time').css("visibility","hidden");
					});
					$.each(json_obj['edit_appointment_time'], function( i, val ) {
					
						time = val.replace(":","_");
						
					   $('.selected_'+time).css("background","#4CAF50");
				       $('.selected_'+time).addClass("edited_select"); 
				       $('.edited_select .time').css("visibility","visible");					
					   $('.selected_'+time).addClass("checked"); 				
				       $('.checked .time').prop('checked', true); 
					});
	 			return true;
	 			});
		 });  
		 
	//Category Add and Remove
	$("body").on("click", "#addremove", function(event)
	{
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var model  = $(this).attr('model') ;
		
	   var curr_data = {
	 					action: 'MJ_hmgt_add_remove_category',
	 					model : model,
	 					dataType: 'json'
	 					};	 				
	 					$.post(hmgt.ajax, curr_data, function(response) 
						{ 						
	 						$('.popup-bg').show().css({'height' : docHeight});
							$('.category_list').html(response);	
							$('.medicine_data').html(response);	
							return true; 					
	 					});	
	
  });
  
   $("body").on("click", ".close-btn-cat", function()
   {	
	 $( ".category_list" ).empty();
	 $('.popup-bg').hide(); // hide the overlay	
  });  
   $("body").on("click", ".close-btn-cat1", function()
   {	
	 $( ".medicine_data" ).empty();
	 $('.medicine_details').hide(); // hide the overlay
  }); 
  $("body").on("click", ".btn-delete-cat", function()
  {		
		var cat_id  = $(this).attr('id') ;	
		 var model  = $(this).attr('model') ;
		
		if(confirm(language_translate.delete_record_alert))
		{
			var curr_data = {
					action: 'MJ_hmgt_remove_category',
					model : model,
					cat_id:cat_id,			
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response)
					{						
						jQuery('#cat-'+cat_id).hide();
						//if(model=='specialization'){
							jQuery("#"+model).find('option[value='+cat_id+']').remove();
							jQuery(".more_invoice_charges").find('option[value='+cat_id+']').remove();
						//}
						//else{
							//$("#category_data").find('option[value='+cat_id+']').remove();
						//}
						jQuery('.symptoms_list').multiselect('rebuild');
						jQuery('.report_type').multiselect('rebuild');
						return true;				
					});			
		}
	});
  
  $("body").on("click", "#btn-add-cat", function()
  {		
		var medicine_cat_name  = $('#medicine_name').val() ;
		var report_cost  = $('#report_cost').val() ;
		var diagnosis_description  = $('#diagno_description').val() ;
		var diagnosis_tax  = $('#diagnosis_tax').val() ;
		
		var operation_cost  = $('#operation_cost').val() ;
		var operation_description  = $('#operation_description').val() ;
		var operation_tax  = $('#operation_tax').val() ;
		
		var charge_amount  = $('#charge_amount').val() ;
		var charge_description  = $('#charge_description').val() ;
		var charge_tax  = $('#charge_tax').val() ;
		
		var model  = $(this).attr('model');	
		
		var valid = jQuery('#medicinecat_form').validationEngine('validate');
		
		if (valid == true) 
		{		
		/* if(medicine_cat_name != "")
		{ */
			var curr_data = {
					action: 'MJ_hmgt_add_category',
					model : model,
					medicine_cat_name: medicine_cat_name,			
					report_cost: report_cost,			
					diagnosis_description: diagnosis_description,			
					diagnosis_tax: diagnosis_tax,			
					operation_cost: operation_cost,			
					operation_description: operation_description,			
					operation_tax: operation_tax,			
					charge_amount: charge_amount,			
					charge_description: charge_description,			
					charge_tax: charge_tax,			
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {		
						
						 var json_obj = $.parseJSON(response);//parse JSON	
							
						$('.table').append(json_obj[0]);
						$('#medicine_name').val("");
						$('#report_cost').val("");
						$('#diagno_description').val("");		
						$('#operation_cost').val("");
						$('#operation_description').val("");
						$('#charge_amount').val("");
						$('#charge_description').val("");
						
						jQuery('#diagnosis_tax').multiselect('deselectAll', false);
						jQuery("#diagnosis_tax").multiselect('refresh');
						
						jQuery('#operation_tax').multiselect('deselectAll', false);
						jQuery("#operation_tax").multiselect('refresh');
						
						jQuery('#charge_tax').multiselect('deselectAll', false);
						jQuery("#charge_tax").multiselect('refresh');
						
						//if(model=="specialization"){
							jQuery("#"+model).append(json_obj[1]);	
							jQuery(".operation_bed_type").append(json_obj[1]);					
							jQuery(".more_invoice_charges").append(json_obj[1]);					
														
							jQuery('.symptoms_list').multiselect('rebuild');
							jQuery('.report_type').multiselect('rebuild');							
							    
						/* }
						else{
							$("#category_data").append(json_obj[1]);
						} */					
						
						return false;					
					});	
		}
		/* }
		else
		{
			alert("Please enter Category Name.");
		} */
	});
 
  //End category Add Remove 
//start load subject for managemarks
$("#bed_type_id").change(function(){
	$('#bednumber').html('');	
	var bed_type_id = $("#bed_type_id").val();	
	var optionval = $(this);
	var curr_data = {
		action: 'MJ_hmgt_get_bednumber',
		bed_type_id: bed_type_id,			
		dataType: 'json'
	};				
	$.post(hmgt.ajax, curr_data, function(response) {				
		$('#bednumber').append(response);
	});
					
});

//getting bed location
$("#bednumber").change(function(){	
	var bed_no = $(this).val();	
	var curr_data = {
		action: 'MJ_hmgt_get_bed_location',
		bed_no: bed_no,			
		dataType: 'json'
	};				
	$.post(hmgt.ajax, curr_data, function(response) {				
		//alert(response);
		$('#bedlocation').html("");
		$('#bedlocation').html(response);
	});
					
});
  //----------view patient status from list----------------------
  
  $("body").on("click", ".show-popup", function(event){
	
	
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var idtest  = $(this).attr('idtest');
		//alert(idtest);
		//return false;
	   var curr_data = {
	 					action: 'MJ_hmgt_patient_status_view',
	 					idtest: idtest,
	 					dataType: 'json'
	 					};	 	
							//alert('hello');					
	 					$.post(hmgt.ajax, curr_data, function(response) { 	
	 						//alert(response);	 
	 					$('.popup-bg').show().css({'height' : docHeight});							
						$('.patient_data').html(response);	
						return true; 					
	 					});	
	
  });
  //----------show charges inpopup---------------
  $("body").on("click", ".show-charges-popup", function(event){
	
	
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var idtest  = $(this).attr('idtest');
		//alert(idtest);
		//return false;
	   var curr_data = {
	 					action: 'MJ_hmgt_patient_charges_view',
	 					idtest: idtest,
	 					dataType: 'json'
	 					};	 	
							//alert('hello');					
	 					$.post(hmgt.ajax, curr_data, function(response) { 	
	 						//alert(response);	 
	 					$('.popup-bg').show().css({'height' : docHeight});							
						$('.patient_data').html(response);	
						return true; 					
	 					});	
	
  });
  
  //-----------Add nurse notes-------------------
  $("body").on("click", "#btn-add-note", function(){		
		var note_by=$(this).attr('note_by');
		var doctor_note  = $('#doctor_note_text').val();
		var nurse_note  = $('#nurse_note_text').val();
		var patient_id  = $('#patient_id').val();
		if(doctor_note != "" || nurse_note !="")
		{
			var curr_data = {
					action: 'MJ_hmgt_add_nurse_notes',
					note_by: note_by,
					doctor_note: doctor_note,
					nurse_note: nurse_note,
					patient_id:patient_id,
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {	
						var json_obj = $.parseJSON(response);//parse JSON						
						$('.nurse_notes').append(json_obj[0]);
						$('#doctor_note_text').val("");
						$('#nurse_note_text').val("");
						
						return false;					
					});	
		
		}
		else
		{
			//alert("Please enter a note.");
			alert(language_translate.add_note_alert);
		}
	});
  
  $("body").on("click", "#btn-add-doctor-note", function(){		
		var note_by=$(this).attr('note_by');
		var doctor_note  = $('#doctor_note_text').val();
		
		var patient_id  = $('#patient_id').val();
		if(doctor_note != "")
		{
			var curr_data = {
					action: 'MJ_hmgt_add_doctor_notes',
					note_by: note_by,
					doctor_note: doctor_note,
					
					patient_id:patient_id,
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {
				
						var json_obj = $.parseJSON(response);//parse JSON						
						$('.doctor_notes').append(json_obj[0]);
						$('#doctor_note_text').val("");
						
						
						return false;					
					});	
		
		}
		else
		{
			//alert("Please enter a note.");
			alert(language_translate.add_note_alert);
		}
	});
	//----------view Invoice popup--------------------
	 $("body").on("click", ".show-invoice-popup", function(event){
		
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	  var idtest  = $(this).attr('idtest');
	  var invoice_type  = $(this).attr('invoice_type');
	  
	   var curr_data = {
	 					action: 'MJ_hmgt_patient_invoice_view',
	 					idtest: idtest,
	 					invoice_type: invoice_type,
	 					dataType: 'json'
	 					};	 	
							//alert('hello');					
	 					$.post(hmgt.ajax, curr_data, function(response) { 	
	 						//alert(response);	 
	 					$('.popup-bg').show().css({'height' : docHeight});							
						$('.invoice_data').html(response);	
						return true; 					
	 					});	
	
  });
	//----------remove nurse note---------------
	$("body").on("click", ".btn-delete-note", function(){		
		var note_id  = $(this).attr('noteid') ;	
		if(confirm(language_translate.delete_record_alert))
		{
			var curr_data = {
					action: 'MJ_hmgt_remove_nurse_note',
					note_id:note_id,			
					dataType: 'json'
					};
					
					
					$.post(hmgt.ajax, curr_data, function(response) {						
						
						$('#note-'+note_id).hide();
						$('#notex-'+note_id).hide();
						
						
						
						return true;				
					});			
		}
	});
	
	
	  $("body").on("click", ".view-profile", function(event){
		 
		  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		  var docHeight = $(document).height(); //grab the height of the page
		  var scrollTop = $(window).scrollTop();
		 var user_id  = $(this).attr('idtest') ;
		 //alert(user_id);
		// return false;
		   var curr_data = {
		 					action: 'MJ_hmgt_user_profile',
		 					user_id : user_id,
		 					dataType: 'json'
		 					};	 				
		 					$.post(hmgt.ajax, curr_data, function(response) { 						
		 						$('.popup-bg').show().css({'height' : docHeight});
								//$('.profile_data').html(response);	
								$('.category_list').html(response);	
								return true; 					
		 					});	
		
	  });
	  
	  //---------view events----------
	   $("body").on("click", ".view-notice", function(event)
	   {
		   //alert("hello");
	  var evnet_id = $(this).attr('id');
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	   //alert(evnet_id);
	   var curr_data = {
	 					action: 'MJ_hmgt_view_event',
	 					evnet_id: evnet_id,			
	 					dataType: 'json'
	 					};
	 					//alert('hello');
	 					$.post(hmgt.ajax, curr_data, function(response) {
	 						
	 						//alert('hello');
	 						$('.popup-bg').show().css({'height' : docHeight});
							$('.notice_content').html(response);	
	 						return true;
	 						
	 					
	 					
	 					});	
	 		});
	   
	   $("body").on("click", ".view-prescription", function(event){
		   //alert("hello");
	  var prescription_id = $(this).attr('id');
	  var prescription_type = $(this).attr('prescription_type');

	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = $(document).height(); //grab the height of the page
	  var scrollTop = $(window).scrollTop();
	   //alert(evnet_id);
	   var curr_data = {
	 					action: 'MJ_hmgt_view_priscription',
	 					prescription_id: prescription_id,			
	 					prescription_type: prescription_type,			
	 					dataType: 'json'
	 					};
	 					//alert('hello');
	 					$.post(hmgt.ajax, curr_data, function(response) {
	 						
	 						//alert('hello');
	 						$('.popup-bg').show().css({'height' : docHeight});
							$('.prescription_content').html(response);	
	 						return true;
	 						
	 					
	 					
	 					});	
	 		});
			
			
			//---------------convert patient into inpatient-------
		$("#patient_id").change(function(){
			$('.convert_patient').html('');	
		
		
		
		var optionval = $(this);
			var curr_data = {
					action: 'MJ_hmgt_load_convert_patient',
					patient_id: $("#patient_id").val(),			
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {
					
					$('.convert_patient').append(response);
					});
						
						
		
	});

		//SMS Message
		 $("input[name=select_serveice]:radio").change(function(){
			
			 var curr_data = {
						action: 'MJ_hmgt_sms_service_setting',
						select_serveice: $(this).val(),			
						dataType: 'json'
						};					
						
						$.post(hmgt.ajax, curr_data, function(response) {	
							
							
						$('#sms_setting_block').html(response);
						});
		 });
		 $("#chk_sms_sent").change(function(){
				
			 if($(this).is(":checked"))
			{
				 //alert("chekked");
				 $('#hmsg_message_sent').addClass('hmsg_message_block');
				 
			}
			 else
			{
				 $('#hmsg_message_sent').addClass('hmsg_message_none');
				 $('#hmsg_message_sent').removeClass('hmsg_message_block');
			}
		 });
		 
		 
		$("body").on("click","#profile_change",function() {
			
			//event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			 var docHeight = $(document).height(); //grab the height of the page
			var scrollTop = $(window).scrollTop();
		   //alert(evnet_id);
			 var curr_data = {
						action: 'MJ_hmgt_change_profile_photo',
						dataType: 'json'
						};					
						
						$.post(hmgt.ajax, curr_data, function(response) {	
						$('.popup-bg').show().css({'height' : docHeight});
							$('.profile_picture').html(response);	
						});
		});
		
		
		
		
		
		
		
		
		$("body").on("change","#bednumber",function() {		
         $("#save_allow").attr('disabled',true);		
			var bednumber = $( "#bednumber" ).val();
			//var allotment_date = $( "#allotment_date" ).val();			
			//alert(bednumber);
			var curr_data = {
				action: 'MJ_hmgt_get_appliyed_bad',				
				bednumber:bednumber,				
				dataType: 'json'
			};	
				

			$.post(hmgt.ajax, curr_data, function(response) {
				//alert(response);
				// $('.custom_popup').show().css({'height' : docHeight});
				$('.datas').html(response);	
			});
						
						
						
			return false;
			
		});
	


$("body").on("click",".show-inovice",function()
{
	var patient_id =$( "#patient_id" ).val();
	
	var docHeight = $(document).height(); //grab the height of the page
	var scrollTop = $(window).scrollTop();
	
    var curr_data = {
		action: 'MJ_hmgt_get_patient_invoice',
			patient_id: patient_id,
			dataType: 'json'
		};					
				
		$.post(hmgt.ajax, curr_data, function(response) {
		
			$('.popup-bg').show().css({'height' : docHeight});
			$('.patient_invoice').html('');
			$('.patient_invoice').html(response);
			
		});
	});
//load invoice title category
$("body").on("change",'.load_category',function() 
{	
	var counter = $(this).attr("dataid");
	var types = $("#type_"+counter).val();
	
	$('#amount_'+ counter).val('');	
	$('#tax_amount_'+ counter).val('');	
	$('#discount_amount_'+ counter).val('');	
	$('#total_amount_'+ counter).val('');	
	
	$('#amount_'+ counter).attr('readonly', false);
	$('#tax_amount_'+ counter).attr('readonly', false);
	$('#total_amount_'+ counter).attr('readonly', false);
	
	var curr_data = {
		action: 'MJ_hmgt_load_invoice_cat',				
		counter:counter,				
		types:types,				
		dataType: 'json'
	};
	$.post(hmgt.ajax, curr_data, function(response)
	{
		$('#title_'+ counter).html(response);	
	});
	return false;
});
//invoice charge entry autofill amount
$("body").on("change",'.charge_amount_autofill',function() 
{	
	var counter = $(this).attr("dataid");
	var types = $("#type_"+counter).val();
	var id = $(this).val();
	
 	var curr_data = {
		action: 'MJ_hmgt_invoice_entry_charge_autofill',				
		counter:counter,				
		types:types,				
		id:id,				
		dataType: 'json'
	};
	$.post(hmgt.ajax, curr_data, function(response)
	{
		var json_obj = $.parseJSON(response);//parse JSON	
		$('#amount_'+ counter).val(json_obj[0]);	
		$('#discount_amount_'+ counter).val(json_obj[1]);	
		$('#tax_amount_'+ counter).val(json_obj[2]);	
		$('#total_amount_'+ counter).val(json_obj[3]);	
		
		if(json_obj[4] == 1)
		{	
			$('#amount_'+ counter).attr('readonly', true);
			$('#tax_amount_'+ counter).attr('readonly', true);
			$('#total_amount_'+ counter).attr('readonly', true);
		}	
		else
		{
			$('#amount_'+ counter).attr('readonly', false);
			$('#tax_amount_'+ counter).attr('readonly', false);
			$('#total_amount_'+ counter).attr('readonly', false);
		}
	}); 
	return false;
});
//load patient prescription..
$("body").on("change",'#patient_id',function() {
	var patient_id = $("#patient_id").val();
	 var curr_data = {
		action: 'MJ_hmgt_load_patient_prescription',				
		patient_id:patient_id,
		dataType: 'json'
	};
	$.post(hmgt.ajax, curr_data, function(response) {
		//alert(response);
		$("#prescription").html(response);
		
		return false; 
});
});
$("body").on("change",'#prescription',function() 
{
	var prescription_id = $("#prescription").val();
	 var curr_data = {
		action: 'MJ_hmgt_load_prescription_id_madicine',				
		prescription_id:prescription_id,
		dataType: 'json'
	};
	$.post(hmgt.ajax, curr_data, function(response) {	
		$("#madicinedata").html('');	
		$("#madicinedata").html(response);
		var count_text=0;
		count_text=$(".med_price").length;
		
		sum_total=0;
	
		for(i=1;i<=count_text;i++){
			a = $("#price_"+i).val();
			sum_total=sum_total+Number(a);
		}	
		
			$("#dispatch_medicine_price").val(sum_total);
		});		
	return false; 
});
//dispatch medicine calculation
$("body").on("keyup",'.days',function()
{	
	var qty = $(this).val();	
	var dataid = $(this).attr("dataid");
	var counter = $(this).attr("counter");

	 var patient_id = $("#patient_id").val();
	 var madicine_quantity = $(".madicine_quantity_"+counter).val();
	 var discount_value = $("#discount_value_"+counter).val();
	 var med_discount_in = $("#med_discount_in_"+counter).val();
	
	var sum = 0;
	$(".medicineqty_"+dataid).each(function(){
	
		if($(this).val()!="")
		{
			sum += parseInt($(this).val()); 
		}			
	});

	if(madicine_quantity>=sum)
	{
		 var curr_data = {
			action: 'MJ_hmgt_load_madicine_price_by_qty',				
			dataid:dataid,
			qty:qty,
			discount_value:discount_value,
			med_discount_in:med_discount_in,
			dataType: 'json'
		};
		$.post(hmgt.ajax, curr_data, function(response) 
		{
			var json_obj = $.parseJSON(response);
			
			 $("#price_"+counter).val(json_obj[0]);	
			 $("#discount_"+counter).val(json_obj[1]);	
			 $("#tax_"+counter).val(json_obj[2]);	
				
			var count_text=0;
			count_text=$(".med_price").length;
			
			sum_total=0;
		
			for(i=1;i<=count_text;i++)
			{
				a = $("#price_"+i).val();
				sum_total=sum_total+Number(a);
			}	
			
			$("#dispatch_medicine_price").val(sum_total.toFixed(2)); 
						
			var count_tax=0;
			count_tax=$(".tax_amount").length;
			
			sum_total_tax=0;
		
			for(i=1;i<=count_tax;i++)
			{
				a = $("#tax_"+i).val();
				sum_total_tax=sum_total_tax+Number(a);
			}	
		
			$("#med_tax").val(sum_total_tax.toFixed(2)); 
			
			var count_discount=0;
			count_discount=$(".med_discount").length;
			
			sum_total_discount=0;
		
			for(i=1;i<=count_tax;i++)
			{
				a = $("#discount_"+i).val();
				sum_total_discount=sum_total_discount+Number(a);
			}	
		
			$("#discount").val(sum_total_discount.toFixed(2)); 
					
			var medprice = $("#dispatch_medicine_price").val();
			var med_tax = $("#med_tax").val();
			var discount = $("#discount").val();
			var sub_total =Number(medprice)+ Number(med_tax)-Number(discount);
			$("#sub_total").val(sub_total.toFixed(2));
				
		});
	}
	else
	{
		alert(language_translate.medicine_stock_out_alert);
	
		$("#qty_"+counter).val(''); 
		
		$("#price_"+counter).val(''); 
		$("#discount_"+counter).val(''); 
		$("#tax_"+counter).val('');		
		
		$("#med_tax").val(''); 
		$("#dispatch_medicine_price").val(''); 
		$("#discount").val(''); 		
		$("#sub_total").val('');		
		
		var count_text=0;
		count_text=$(".med_price").length;
		
		sum_total=0;
	
		for(i=1;i<=count_text;i++)
		{
			a = $("#price_"+i).val();
			sum_total=sum_total+Number(a);
		}	
	
		$("#dispatch_medicine_price").val(sum_total.toFixed(2)); 
		
		var count_tax=0;
		count_tax=$(".tax_amount").length;
		
		sum_total_tax=0;
	
		for(i=1;i<=count_tax;i++)
		{
			a = $("#tax_"+i).val();
			sum_total_tax=sum_total_tax+Number(a);
		}	
	
		$("#med_tax").val(sum_total_tax.toFixed(2)); 
		
		var count_discount=0;
		count_discount=$(".med_discount").length;
		
		sum_total_discount=0;
	
		for(i=1;i<=count_discount;i++)
		{
			a = $("#discount_"+i).val();
			sum_total_discount=sum_total_discount+Number(a);
		}	
	
		$("#discount").val(sum_total_discount.toFixed(2)); 
		
		var medprice = $("#dispatch_medicine_price").val();
		var med_tax = $("#med_tax").val();
		var discount = $("#discount").val();
		var sub_total =Number(medprice)+ Number(med_tax)-Number(discount);
		$("#sub_total").val(sub_total.toFixed(2));
		
	}	
	return false; 
});
//dispatch medicine discount change
$("body").on("keyup",'.med_discount_value',function()
{	
	var discount_value = $(this).val();
	var dataid = $(this).attr("dataid");
	var counter = $(this).attr("counter");

	var med_discount_in = $("#med_discount_in_"+counter).val();
	
	var qty = $("#qty_"+counter).val();
	
	var patient_id = $("#patient_id").val();
	
		 var curr_data = {
			action: 'MJ_hmgt_load_madicine_price_by_qty',				
			dataid:dataid,
			qty:qty,
			discount_value:discount_value,
			med_discount_in:med_discount_in,
			dataType: 'json'
		};
		$.post(hmgt.ajax, curr_data, function(response) 
		{
			var json_obj = $.parseJSON(response);
		
			 $("#discount_"+counter).val(json_obj[1]);	
			 $("#tax_"+counter).val(json_obj[2]);	
									
			var count_tax=0;
			count_tax=$(".tax_amount").length;
			
			sum_total_tax=0;
		
			for(i=1;i<=count_tax;i++)
			{
				a = $("#tax_"+i).val();
				sum_total_tax=sum_total_tax+Number(a);
			}	
		
			$("#med_tax").val(sum_total_tax.toFixed(2)); 
			
			var count_discount=0;
			count_discount=$(".med_discount").length;
			
			sum_total_discount=0;
		
			for(i=1;i<=count_tax;i++)
			{
				a = $("#discount_"+i).val();
				sum_total_discount=sum_total_discount+Number(a);
			}	
		
			$("#discount").val(sum_total_discount.toFixed(2)); 
					
			var medprice = $("#dispatch_medicine_price").val();
			var med_tax = $("#med_tax").val();
			var discount = $("#discount").val();
			var sub_total =Number(medprice)+ Number(med_tax)-Number(discount);
			$("#sub_total").val(sub_total.toFixed(2));
				
		});	
	return false; 
});
$("body").on("click",'#add_new_medicine_entry',function() {	
		
	 var curr_data = {
		action: 'MJ_hmgt_load_madicine_html',		
		dataType: 'json'
	};
	
	$.post(hmgt.ajax, curr_data, function(response) {
		//console.log(response);
		
		$("#invoice_medicine_entry").append(response);
		
		return false;
	});
});

	/* ------- Assign Instument -----*/
  
  $("#instrument_id").change(function(){
		$('#select_instrument_block').html('');	
		var selection = $(this).val();
			var curr_data = {
					action: 'MJ_hmgt_instrument_assign_period',
					instrument_id: selection,				
					dataType: 'json'
					};
					$.post(hmgt.ajax, curr_data, function(response) {
						
						$('#select_instrument_block').html('');
						$('#select_instrument_block').append(response);	
					});
						
					
	});	
	  $(".symptoms_list").change(function(){
		var symptoms=$('#symptomstextarea').val();	
		var selection = $(this).val();		
		
			var curr_data = {
					action: 'Mj_hmgt_select_symptoms',
					symptoms_id: selection,				
					dataType: 'json'
					};
					$.post(hmgt.ajax, curr_data, function(response) {
						console.log(response);
						
						if(symptoms!=" ")
						{
							$('#symptomstextarea').append('\n '+response);
						}
						else
						{
							$('#symptomstextarea').append(response);
						}						
					});									
	});
	$('body').on('focus',".medicine_manufactured_date", function()
	{			
	   	
        jQuery('.medicine_manufactured_date').datepicker({
			endDate: '+0d',
			autoclose: true
		});
	}); 
	$('body').on('focus',".medicine_expiry_date", function()
	{			
	   	var date = new Date();
        date.setDate(date.getDate()-0);
		//$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
        jQuery('.medicine_expiry_date').datepicker({
	    startDate: date,
        autoclose: true
		});
	}); 
	jQuery("body").on("click",'.delete_medicine_div',function() 
	{		
		if(confirm(language_translate.delete_record_alert))
		{
			$(this).closest('.medicine_div').remove();
		}	
		return false;
	});
	
	$("body").on("keyup",'.medicine_name',function()
	{	
		var that = this;
		var medicine_name = $(this).val();		
		var no = 0;		

		$('.medicine_name').each(function()
		{
			if($(this).val()== medicine_name)
			{
				no=no+1;
			}		
		});
		if(no>1)				
		{					
			//alert('Please Enter Unique Medicine Name');					
			alert(language_translate.unique_medicine_alert);					
			$(that).val('');
			return false; 	
		}
		var curr_data = {
				action: 'hmgt_check_medicine_name_duplicate',				
				medicine_name:medicine_name,			
				dataType: 'json'
			};
			$.post(hmgt.ajax, curr_data, function(response) 
			{				
				if(response>0)				
				{					
					//alert('Please Enter Unique Medicine Name');	
					alert(language_translate.unique_medicine_alert);						
					$(that).val('');
					return false; 	
				}
				
			});	 
		
	}); 
	$("body").on("keyup",'.edit_medicine_name',function()
	{	
		var that = this;
		var medicine_name = $(this).val();		
		var medicine_id = $(".medicine_id").val(); 	
		
		var curr_data = {
				action: 'MJ_hmgt_check_edit_medicine_name_duplicate',				
				medicine_name:medicine_name,			
				medicine_id:medicine_id,			
				dataType: 'json'
			};
			$.post(hmgt.ajax, curr_data, function(response) 
			{				
				if(response>0)				
				{					
					//alert('Please Enter Unique Medicine Name');
					alert(language_translate.unique_medicine_alert);					
					$(that).val('');
					return false; 	
				}
				
			});	 
		
	}); 
	$("body").on("keyup",'.med_uniqueid',function()
	{	
		var that = this;
		var med_uniqueid = $(this).val();		
		var no = 0;		

		$('.med_uniqueid').each(function()
		{
			if($(this).val()== med_uniqueid)
			{
				no=no+1;
			}		
		});
		if(no>1)				
		{					
			//alert('Please Enter Unique Medicine ID');	
			alert(language_translate.unique_medicine_id__alert);								
			$(that).val('');
			return false; 	
		}
		var curr_data = {
				action: 'MJ_hmgt_check_medicine_id_duplicate',				
				med_uniqueid:med_uniqueid,			
				dataType: 'json'
			};
			$.post(hmgt.ajax, curr_data, function(response) 
			{				
				if(response>0)				
				{					
					//alert('Please Enter Unique Medicine Id');
					alert(language_translate.unique_medicine_id__alert);													
					$(that).val('');
					return false; 	
				}
				
			});	 
		
	});
	$("body").on("keyup",'.edit_med_uniqueid',function()
	{	
		var that = this;
		var med_uniqueid = $(this).val();		
		var medicine_id = $(".medicine_id").val(); 	
		
		var curr_data = {
				action: 'MJ_hmgt_check_edit_medicine_id_duplicate',				
				med_uniqueid:med_uniqueid,			
				medicine_id:medicine_id,			
				dataType: 'json'
			};
			$.post(hmgt.ajax, curr_data, function(response) 
			{				
				if(response>0)				
				{					
					//alert('Please Enter Unique Medicine Id');					
					alert(language_translate.unique_medicine_id__alert);								
					$(that).val('');
					return false; 	
				}				
			});	 
		
	}); 
	$("body").on("click",".importdata",function() 
	{
			
			//event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			 var docHeight = $(document).height(); //grab the height of the page
			var scrollTop = $(window).scrollTop();
		   //alert(evnet_id);
			 var curr_data = {
						action: 'MJ_hmgt_import_data',
						dataType: 'json'
						};					
						
						$.post(hmgt.ajax, curr_data, function(response) {	
						$('.popup-bg').show().css({'height' : docHeight});
							//$('.import_data').html(response);	
							$('.category_list').html(response);	
							$('.patient_data').html(response);	
						});
		});	
	$("input[name=prescription_type]:radio").change(function()
	{
				
		var selected_type = $(this).val();
		if(selected_type == 'report')
		{
			$('#tretment_div').css("display", "none");
			$('#prescription_report_div').css("display", "block");
			$('input[name=patient_convert]:checkbox').prop('checked', false);
			$('.convert_patient').css("display", "none");			
		}
		else
		{
			$('#tretment_div').css("display", "block");
			$('#prescription_report_div').css("display", "none");
			$('input[name=patient_convert]:checkbox').prop('checked', false);
			$('.convert_patient').css("display", "block");	
		}
		
		return false; 				
			
	});		
	//add more diagnosis fronted
	$("body").on("click",".add_more_report_fronted",function() 
	{			
			 var curr_data = {
						action: 'MJ_hmgt_add_more_dignosis',
						dataType: 'json'
						};					
						
						$.post(hmgt.ajax, curr_data, function(response) 
						{
							$(".diagnosissnosis_div").append(response);
							return false; 	
						});
	});
	//phone code validation
	$('.onlynumber_and_plussign').on('keyup', function()
	{
		var phoneno = /^[0-9\+]+$/;
		if(($(this).val().match(phoneno)))
		{
			
		}
		else
		{
			alert(language_translate.phocode_alert);			
			$(this).val('');
			return false;
		} 		
	});	
	// Operation_status function
	$(".operation_status").change(function()
	{
		var operation_status = $(this).val();
		
		if(operation_status == 'Completed')
		{
			$('.out_come_status').css("display","block");		
		}
		else
		{
			$('.out_come_status').css("display","none");			
		}	
	});	
    //edit category function
	$("body").on("click", ".btn-edit-cat", function()
	{	
		var cat_id  = $(this).attr('id') ;	
		var model  = $(this).attr('model') ;
		var curr_data =
		{
		action: 'MJ_hmgt_edit_diagnosisreport_name',
		model : model,
		cat_id:cat_id,			
		dataType: 'json'
		};
		$.post(hmgt.ajax, curr_data, function(response) 
		{	
			$(".table tr#cat-"+cat_id).html(response);						
			return true;				
		});			
			
	});
	//Update_cancel Category function
	$("body").on("click", ".btn-cat-update-cancel", function(){	

		var cat_id  = $(this).attr('id') ;	
		var model  = $(this).attr('model') ;
		var report_name = $("#report_name").val();
		
		var curr_data = {
						action: 'MJ_hmgt_update_cancel_diagnosisreport_name',
					model : model,
					cat_id:cat_id,			
					report_name:report_name,			
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {	
					
						$(".table tr#cat-"+cat_id).html(response);
						return true;				
		});			
			
	});
	//Update_save diagnosis report name function

	$("body").on("click", ".btn-cat-update", function()
	{		
			var cat_id  = $(this).attr('id') ;	
			var model  = $(this).attr('model') ;
			var report_name = $("#report_name").val();
			var report_amount = $("#report_amount").val();
			var diagnosis_tax = $("#diagnosis_tax").val();
			var report_des = $("#report_des").val();
			
			var operation_name = $("#operation_name").val();
			var operation_amount = $("#operation_amount").val();
			var operation_tax = $("#operation_tax").val();
			var operation_des = $("#operation_des").val();
			
			var charge_amount = $("#charge_amount").val();
			var charge_tax = $("#charge_tax").val();
			var charge_description = $("#charge_description").val();
			
			if(confirm(language_translate.edit_record_alert))
			{
				var valid = jQuery('#report_typ_form').validationEngine('validate');
			
				if (valid == true) 
				{
					var curr_data = {
								action: 'MJ_hmgt_update_diagnosisreport_name',
								model : model,
								cat_id:cat_id,			
								report_name:report_name,			
								report_amount:report_amount,			
								diagnosis_tax:diagnosis_tax,			
								report_des:report_des,			
								operation_name:operation_name,			
								operation_amount:operation_amount,			
								operation_tax:operation_tax,			
								operation_des:operation_des,			
								charge_amount:charge_amount,			
								charge_tax:charge_tax,			
								charge_description:charge_description,			
								dataType: 'json'
								};
								
								$.post(hmgt.ajax, curr_data, function(response) 
								{	
									var json_obj = $.parseJSON(response);//parse JSON	
									
									jQuery(".table tr#cat-"+cat_id).html(json_obj[0]);
									
									jQuery("#"+model).find('option[value='+cat_id+']').remove();
									
									jQuery("#"+model).append(json_obj[1]);
									
									jQuery(".more_invoice_charges").find('option[value='+cat_id+']').remove();
									
									jQuery(".more_invoice_charges").append(json_obj[1]);	
									
									jQuery('.report_type').multiselect('rebuild');		
									
									return true;				
								});	
				}					
			}
	});
	
	//---------count_dagnosisreport amount ----------//
	jQuery('.report_type').on('change',function() 
	{
		
      var report_type_id = $(this).val();
	 
	  var curr_data = {
					action: 'MJ_hmgt_count_dagnosisreport_amount',
					report_type_id : report_type_id,
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {	
					
					var json_obj = $.parseJSON(response);//parse JSON	
					
					 $(".cost").val(json_obj[0]);
				     $(".report_tax").val(json_obj[1]);
				     $(".report_cost").val(json_obj[2]);
				     return true;				
		});	
     });
   //add more medicine tax multiselect
	jQuery("body").on("click", "#add_new_entry", function(event)
	{
		jQuery('.tax_charge').multiselect({
			nonSelectedText :language_translate.select_multiselect_tax,
			includeSelectAllOption: true,
			selectAllText : language_translate.select_all_multiselect
		 });
	});	
	// invoice Charge Popup More Entry add
	var counter = 0;
	$("body").on("click", ".add_more_charge_entry", function()
	{	
		var window_width =$( window ).width();
		
		counter++;		
		var curr_data = {
					action: 'MJ_hmgt_hmgt_add_more_charge_entry',					
					counter: counter,					
					window_width: window_width,					
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response)
					{	
						$("#entriys").append(response);
						return true;				
					});					
	});    
	$('body').on('focus',".invoice_date", function()
	{			
		var date = new Date();
		date.setDate(date.getDate()-0);
		//$.fn.datepicker.defaults.format =" <?php  echo MJ_hmgt_dateformat_PHP_to_jQueryUI(MJ_hmgt_date_formate()); ?>";
		$('.invoice_date').datepicker({
		endDate: '+0d',
		autoclose: true
		});		
	}); 
	//delete invoice charge entry
	jQuery("body").on("click",'.remove_transaction',function() 
	{	
		if(confirm(language_translate.delete_record_alert))
		{
			$(this).closest('div.row').remove();
		}	
		return false;
	});
	//opertation charge calculate	
	jQuery("#operation").change(function()
	{		
		var operation_id = $(this).val(); 
	 
	  var curr_data = {
					action: 'MJ_hmgt_operation_charge_calculation',
					operation_id : operation_id,
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) {	
				
					var json_obj = $.parseJSON(response);//parse JSON	
					
					 $("#ot_charge").val(json_obj[0]);
				     $("#ot_tax").val(json_obj[1]);
				     $("#operation_charge").val(json_obj[2]);
				     return true;				
		});	
     });
	 
	//---------Add Tabular formate uploads report ----------//
	jQuery('.dignosis_upload').on('change',function() 
	{
		
      var report_type_id = $(this).val();
      var action_name = $('#action_name').val();
      var diagnosisid = $('#diagnosisid').val();
	  
		var curr_data = {
					action: 'MJ_hmgt_uploads_dagnosisreport_table_formate',
					report_type_id : report_type_id,
					action_name : action_name,
					diagnosisid : diagnosisid,
					dataType: 'json'
					};
					
					$.post(hmgt.ajax, curr_data, function(response) 
					{	
						//alert(response);return false;			
					  $(".add_document_div_main_class").html('');							
					  $(".add_document_div_main_class").html(response);				 
				     return true;				
					});	 
     });
	 //patient address auto fill up in ambulance request	
	jQuery(".patient_address").change(function()
	{		
		var patient_id = $('#patient_id').val(); 
	 
		  var curr_data = {
						action: 'MJ_hmgt_patient_address',
						patient_id : patient_id,
						dataType: 'json'
						};
						
						$.post(hmgt.ajax, curr_data, function(response) 
						{														
						 $("#address").val(response);
				     return true;				
				});	
     });
	 // prescription report then patient Convert into Inpatient div clean
	 jQuery(".patient_address").change(function()
	{		
		var patient_id = $('#patient_id').val(); 
	 
		  var curr_data = {
						action: 'hmgt_patient_address',
						patient_id : patient_id,
						dataType: 'json'
						};
						
						$.post(hmgt.ajax, curr_data, function(response) 
						{														
						 $("#address").val(response);
				     return true;				
				});	
     });
	 //dashboard Event And task display model
	  $("body").on("click", ".show_task_event", function(event)
	  {		
		  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
		  var docHeight = $(document).height(); //grab the height of the page
		  var scrollTop = $(window).scrollTop();
		  var id  = $(this).attr('id') ;
		  var model  = $(this).attr('model') ;
		   	
		   var curr_data = {
							action: 'MJ_hmgt_show_event_notice',
							id : id,
							model : model,
							dataType: 'json'
							};	
											
							$.post(hmgt.ajax, curr_data, function(response)
							{ 	
								$('.popup-bg').show().css({'height' : docHeight});
								$('.task_event_list').html(response);	
													
								return true; 					
							});		 
	  });
	  // dashboard view details popup hide
	 $("body").on("click", ".event_close-btn", function()
	{		
		$('.popup-bg').hide(); // hide the overlay
	}); 
	// charge popup discount on change
	//invoice old charge entry autofill amount after discount
	$("body").on("change",'.transaction_discount',function() 
	{	
		var counter = $(this).attr("dataid1");
		
		var amount= $('.amount_'+counter).val();
		var discount_amount= $(this).val();
		var old_discount_amount= $('.old_discount_amount_'+counter).val();
		var old_tax_amount= $('.old_tax_amount_'+counter).val();
		
		var curr_data = {
			action: 'MJ_hmgt_discount_onchange_invoice_entry_charge_autofill',				
			amount:amount,				
			discount_amount:discount_amount,				
			old_discount_amount:old_discount_amount,				
			old_tax_amount:old_tax_amount,				
			dataType: 'json'
		};
		$.post(hmgt.ajax, curr_data, function(response)
		{			
			var json_obj = $.parseJSON(response);//parse JSON	
		
			$('.amount_'+ counter).val(json_obj[0]);	
			$('.discount_amount_'+ counter).val(json_obj[1]);	
			$('.tax_amount_'+ counter).val(json_obj[2]);	
			$('.total_amount_'+ counter).val(json_obj[3]);	 
		}); 
		return false;
	});
	//invoice category charge entry autofill amount after discount
	$("body").on("change",'.transaction_discount_new_entry',function() 
	{	
		var counter = $(this).attr("dataid1");
		
		var type= $('#type_'+counter).val();		
		var discount_amount= $(this).val();
				
		var curr_data = {
			action: 'MJ_hmgt_discount_onchange_new_invoice_entry_charge_autofill',				
			type:type,	
			discount_amount:discount_amount,						
			dataType: 'json'
		};
		$.post(hmgt.ajax, curr_data, function(response)
		{			
			var json_obj = $.parseJSON(response);//parse JSON	
			
			if(json_obj[3] == 1)
			{					
				$('#discount_amount_'+ counter).val(json_obj[0]);	
				$('#tax_amount_'+ counter).val(json_obj[1]);	
				$('#total_amount_'+ counter).val(json_obj[2]);	
			}	
		}); 
		return false;
	});
	// assigned instrument form submit date and time validation display
	$("body").on("click", ".assigned_instrument_validation", function()
	{			
		var start_time= $('#start_time').val();	
		var end_time= $('#end_time').val();	
	
		var valid = jQuery('#assign_instrument_form').validationEngine('validate');
		
		if (valid == true) 
		{			
			if(start_time!="" && end_time !="")		
			{
				if(start_time > end_time)	
				{
					alert(language_translate.time_validation_alert);					
					return false;
				}	
			}		
		}		
	});
});  