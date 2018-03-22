$(document).ready(function(){
	load_data("TODAY");
});

$(document).on("click" , ".btn-click" , function(){
	var btn_click = $(this).data("type");
	load_data(btn_click);
});

$(document).on("change" , "#select_locations" , function(){
	load_data("LOCATION");
});

function load_data(btn_click){
	var selected_outlet = $("#select_locations").val();
	var today = $('#scheduler-name').data("date");
	$.ajax({
		url : url ,
		method : "POST" ,
		data : {outlet_id : selected_outlet , today : today , btn_click : btn_click},
		success : function(response){
			var json = jQuery.parseJSON(response);
			scheduler_builder(json);
			load_total_hours();
		}
	});
}

function table_builder(v){
	var schedule_list = v.schedule_list;
	var tr = $("<tr>");

	var td = $("<td>" , {class : "border-left border-bottom" , "data-staff" : v.staff_id});
	var div = $("<div>").append($("<img>" , {src : site_url+v.image_path+"/60/60/"+v.image_name , style : "width:30px;"}) );
	var div2 = $("<div>").append('<span>'+v.first_name+' '+v.last_name+'</span><small title="Preferred Hours / Scheduled Hours / Max Hours"><i class="fa fa-clock-o" aria-hidden="true"></i> <span>0 / 0 / '+v.max_hours+'</span></small>');

	td.append(div);
	td.append(div2);

	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom" , "data-dateid" : "#td_monday"});

	if(schedule_list["Mon"]){
		$.each(schedule_list["Mon"] , function(a , b){
			td.append(build_shift(b));
		});
	}
	
	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom" , "data-dateid" : "#td_tuesday"});

	if(schedule_list["Tue"]){
		$.each(schedule_list["Tue"] , function(a , b){
			td.append(build_shift(b));
		});
	}

	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom" , "data-dateid" : "#td_wednesday"});

	if(schedule_list["Wed"]){
		$.each(schedule_list["Wed"] , function(a , b){
			td.append(build_shift(b));
			
		});
	}

	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom" , "data-dateid" : "#td_thursday"});

	if(schedule_list["Thu"]){
		$.each(schedule_list["Thu"] , function(a , b){
			td.append(build_shift(b));
		});
	}

	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom" , "data-dateid" : "#td_friday"});

	if(schedule_list["Fri"]){
		$.each(schedule_list["Fri"] , function(a , b){
			td.append(build_shift(b));
		});
	}

	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom border-right" , "data-dateid" : "#td_saturday"});

	if(schedule_list["Sat"]){
		$.each(schedule_list["Sat"] , function(a , b){
			td.append(build_shift(b));
		});
	}

	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom border-right" , "data-dateid" : "#td_sunday"});

	if(schedule_list["Sun"]){
		$.each(schedule_list["Sun"] , function(a , b){
			td.append(build_shift(b));
		});
	}

	tr.append(td);

	$(".scheduler-table tbody").append(tr);
}

function scheduler_builder(json){
	$('#scheduler-name').data("date" , json.scheduler_name_date).text(json.scheduler_name);
	$(".table-header2").find('#td_sunday').data("date" , json.loop_date["Sun"].date).text(json.loop_date["Sun"].value);
	$(".table-header2").find('#td_monday').data("date" , json.loop_date["Mon"].date).text(json.loop_date["Mon"].value);
	$(".table-header2").find('#td_tuesday').data("date" , json.loop_date["Tue"].date).text(json.loop_date["Tue"].value);
	$(".table-header2").find('#td_wednesday').data("date" , json.loop_date["Wed"].date).text(json.loop_date["Wed"].value);
	$(".table-header2").find('#td_thursday').data("date" , json.loop_date["Thu"].date).text(json.loop_date["Thu"].value);
	$(".table-header2").find('#td_friday').data("date" , json.loop_date["Fri"].date).text(json.loop_date["Fri"].value);
	$(".table-header2").find('#td_saturday').data("date" , json.loop_date["Sat"].date).text(json.loop_date["Sat"].value);

	var staff_list = json.staff_list.result;
	var btn = $(".btn-publish");

	if(json.staff_list.unpublished){
		btn.data("submit" , true).removeClass("btn-default").addClass("btn-success").html('Publish & Notify <br><small>Entire Schedule</small>');
	}else{
		btn.data("submit" , false).removeClass("btn-success").addClass("btn-default").html('Everything Published <br><small>No Changes</small>');
	}
	
	$(".scheduler-table tbody").html("");

	var tr = $("<tr>" , {class : "td_open_shift" , style : "background-color:#e0ffd3;"});
	var td = $("<td>" , {class : "border-left border-bottom text-center"});
	td.append('<span>OpenShifts</span>');

	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom" , "data-dateid" : "#td_monday"});
	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom" , "data-dateid" : "#td_tuesday"});
	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom" , "data-dateid" : "#td_wednesday"});
	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom" , "data-dateid" : "#td_thursday"});
	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom" , "data-dateid" : "#td_friday"});
	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom border-right" , "data-dateid" : "#td_saturday"});
	tr.append(td);

	var td = $("<td>" , {class : "border-left border-bottom border-right" , "data-dateid" : "#td_sunday"});
	tr.append(td);

	$(".scheduler-table tbody").append(tr);

	$.each(staff_list , function(k , v){
		table_builder(v);
	});
}

function build_shift(b){
	var a = $("<a>" , {href: "javascript:void(0);" , "data-total_hours" : b.total_hours , "data-shift_id" : b.date_id  , "data-published" : b.published , class: "tdShift shift-list-a btn btn-block " , style : "background-color : "+b.block_color , text : b.start_time+" - "+b.end_time});
	var span = $("<span>").html(b.group_name);
	a.append(span);
	var div = $("<div>" , { class : "shift_container " , style : "display:block;width:100%;margin:0px;padding:0px;border-radius:10px;"});
	div.append(a);
	div.append($("<div>" , {class : b.published}));
	return div;
}

function load_total_hours(){
	var table = $(".scheduler-table");
	var tr = table.find("tbody").find("tr:not(.td_open_shift)");

	var monday    = 0;
	var tuesday   = 0;
	var wednesday = 0;
	var thursday  = 0;
	var friday    = 0;
	var saturday  = 0;
	var sunday    = 0;

	$.each(tr , function(k , v){

	    var a = $(v).find("td:eq(1)").find("a.tdShift").data("total_hours");

	    if(a != undefined){
	    	monday    += parseFloat(a);
	    }

	    var a = $(v).find("td:eq(2)").find("a.tdShift").data("total_hours");

	    if(a != undefined){
	    	tuesday    += parseFloat(a);
	    }

	    var a = $(v).find("td:eq(3)").find("a.tdShift").data("total_hours");

	    if(a != undefined){
	    	wednesday    += parseFloat(a);
	    }

	    var a = $(v).find("td:eq(4)").find("a.tdShift").data("total_hours");

	    if(a != undefined){
	    	thursday    += parseFloat(a);
	    }

	    var a = $(v).find("td:eq(5)").find("a.tdShift").data("total_hours");

	    if(a != undefined){
	    	friday    += parseFloat(a);
	    }

	    var a = $(v).find("td:eq(6)").find("a.tdShift").data("total_hours");

	    if(a != undefined){
	    	saturday    += parseFloat(a);
	    }

	    var a = $(v).find("td:eq(7)").find("a.tdShift").data("total_hours");

	    if(a != undefined){
	    	sunday    += parseFloat(a);
	    }

	});

	$('#td_total_2').html(monday+" Hrs");
	$('#td_total_3').html(tuesday+" Hrs");
	$('#td_total_4').html(wednesday+" Hrs");
	$('#td_total_5').html(thursday+" Hrs");
	$('#td_total_6').html(friday+" Hrs");
	$('#td_total_7').html(saturday+" Hrs");
	$('#td_total_8').html(sunday+" Hrs");

	var total = monday + tuesday + wednesday + thursday + friday + saturday + sunday;

	$("#td_total_1").html(total+" Hrs");
}


$(document).on("click" , ".scheduler-table tbody > tr:not(.td_open_shift) > td:not(:first-child)" , function(e){
	var dateid = $(this).data("dateid");
	var datename = $(dateid).data("date");
	var staff_id = $(this).parent().find("td").first().data("staff");
	var selected_outlet = $("#select_locations").val();
	var a = $(this);

	if(e.target !== e.currentTarget){
		return false;
	}

	$.ajax({
		url : get_preferred_shift ,
		data : {staff_id : staff_id , outlet_id : selected_outlet , date : datename},
		method : "POST",
		success : function(response){

			var modal = $('#assign_shift_modal').modal("show");
			var json = jQuery.parseJSON(response);

			modal.data("click" , a);
			modal.data("staffid" , staff_id);
			
			modal.find(".modal-body > div").html(" ");

			var staff_name = json.staff.first_name+" "+json.staff.last_name;
			var datename = json.date;

			modal.data("datename" , datename);

			modal.find('.modal-title').html("Assign Shift to "+staff_name+" on "+datename);

			$.each(json.shift , function(k , v){

				var section = $("<section>");
				section.append($("<label>" , {text : k}));

				var div = $("<div>" , {class : "row"});

				$.each(v , function(a , b){
	
					var a = $("<a>" , {href: "javascript:void(0);" , "data-blockid" : b.shift_blocks_id, class: "shift-list-a btn btn-block" , style : "background-color : "+b.block_color , text : b.start_time+" - "+b.end_time});
					var span = $("<span>").html(b.group_name);
					a.append(span);

					var div1 = $("<div>" , {class : "col-lg-4"});
					div1.append(a);
					div.append(div1);
				});

				section.append(div);
				modal.find(".modal-body > div").append(section);
			});
			
		}
	});

});

$(document).on("click" , ".modal .shift-list-a" , function(){
	var modal = $(this).closest(".modal");
	var clone = $(this).clone();
	var td = modal.data("click");
	var staff_id = modal.data("staffid");
	var datename = modal.data("datename");
	var shift_id = $(this).data("blockid");
	var selected_outlet = $("#select_locations").val();
	
	$.ajax({
		url : save_shift ,
		method : "POST" ,
		data : {staff_id : staff_id , date_name : datename , shift_id : shift_id , outlet_id : selected_outlet },
		success : function(response){
			var json = jQuery.parseJSON(response);

			if(json.status){
				clone.data("shift_id" , json.id);
				clone.addClass("tdShift");
				clone.data("total_hours" , json.total_hours);
				clone.data("published" , "unpublished");

				var div = $("<div>" , { class : "shift_container " , style : "display:block;width:100%;margin:0px;padding:0px;border-radius:10px;"});
				div.append(clone);
				div.append($("<div>" , {class : "unpublished"}));

				td.append(div);
				modal.modal("hide");

				var btn = $(".btn-publish");
				btn.data("submit" , true).removeClass("btn-default").addClass("btn-success").html('Publish & Notify <br><small>Entire Schedule</small>');
				load_total_hours();
			}

		}
	});

	
});
$(document).on("click" , ".tdShift , .shift_container .unpublished" , function(e){

	if($(this).hasClass("unpublished")){
		var $me = $(this).parent().find("a");
	}else{
		var $me = $(this);
	}

	var id = $me.data("shift_id");
	var published = $me.data("published");
	var dateid = $(this).closest("td").data("dateid");
	var datename = $(dateid).data("date");
	var staff_id = $(this).closest("tr").find("td").first().data("staff");
	var name = $(this).closest("tr").find("td").first().find("div").last().find("span").first().text();
	var selected_outlet = $("#select_locations").val();
	$.ajax({
		url : get_shift_information_by_id_url ,
		method : "POST" ,
		data : { date_id : id , published : published , staff_id : staff_id , date : datename},
		success : function(response){
			var modal = $('#edit_shift_modal').modal("show");
			datename = datename.toLowerCase();
			modal.find('.modal-title').html("Edit Shift of "+name+" on "+datename.charAt(0).toUpperCase() + datename.slice(1));
			var json = jQuery.parseJSON(response);

			modal.find("input[name='outlet_id']").val(selected_outlet);
			modal.find("input[name='staff_id']").val(json.position_id);
			modal.find("input[name='unpaid_break']").val(json.unpaid_break);
			modal.find("input[name='pre_time_start']").val(json.start_time);
			modal.find("input[name='pre_time_end']").val(json.end_time);
			modal.find("input[name='group_color']").val(json.block_color);
			modal.find("input[name='date_id']").val(id);
			modal.find("input[name='published']").val(published);
			modal.data("click" , $me);

		}
	});
});

$(document).on('hidden.bs.modal' , '#assign_custom_shift_modal' , function () {
	if(!$(this).data("noshow")){
		var modal = $("#assign_shift_modal").modal("show");
	}
	
});

$(document).on("click" , ".btn-custom-shift" , function(){
	var last_modal = $(this).closest(".modal").modal("hide");
	
	var modal = $("#assign_custom_shift_modal").modal("show");
	modal.find('.modal-title').html(last_modal.find('.modal-title').html());
	modal.data("noshow" , false);
});

$(document).on("click" , ".btn-create-shift" , function(){
	var modal = $(this).closest(".modal");
	var form = modal.find("form");
	
	var staff_id = $("#assign_shift_modal").data("staffid");
	var datename = $("#assign_shift_modal").data("datename");
	var selected_outlet = $("#select_locations").val();

	modal.find('input[name="staff_id"]').val(staff_id);
	modal.find('input[name="date_name"]').val(datename);
	modal.find('input[name="outlet_id"]').val(selected_outlet);

	var data = form.serialize();

	modal.data("noshow" , true);

	$.ajax({
		url : form.attr("action") ,
		data : data ,
		method : "POST" ,
		success : function(response){
			
			var json = jQuery.parseJSON(response);
			var group_name = modal.find('select[name="position"] option[value="'+json.data.position+'"]').text();
			
			var a = $("<a>" , { href: "javascript:void(0);" , "data-total_hours" : json.total_hours , "data-shift_id" : json.id , "data-published" : json.published , class: "tdShift shift-list-a btn btn-block " , style : "background-color : "+json.data.group_color , text : json.data.pre_time_start+" - "+json.data.pre_time_end});
			var span = $("<span>").html(group_name);
			a.append(span);

			var div = $("<div>" , { class : "shift_container " , style : "display:block;width:100%;margin:0px;padding:0px;border-radius:10px;"});
			div.append(a);
			div.append($("<div>" , {class : json.published}));
			
			var td = $("#assign_shift_modal").data("click");

			td.append(div);

			modal.modal("hide");

			load_total_hours();
		}
	});
});

$(document).on("click" , ".btn-update-shift" , function(){
	var form = $(this).closest(".modal").find("form");
	var data = form.serialize();
	var url = form.attr("action");
	var modal = $(this).closest(".modal");

	$.ajax({
		url : url ,
		data : data ,
		method : "POST" ,
		success : function(response){

			var json = jQuery.parseJSON(response);
			var group_name = modal.find('select[name="position"] option[value="'+json.data.position+'"]').text();
			
			var a = $("<a>" , { href: "javascript:void(0);" , "data-total_hours" : json.total_hours , "data-shift_id" : json.id , "data-published" : json.published , class: "tdShift shift-list-a btn btn-block " , style : "background-color : "+json.data.group_color , text : json.data.pre_time_start+" - "+json.data.pre_time_end});
			var span = $("<span>").html(group_name);
			a.append(span);

			var div = $("<div>" , { class : "shift_container " , style : "display:block;width:100%;margin:0px;padding:0px;border-radius:10px;"});
			div.append(a);
			div.append($("<div>" , {class : json.published}));
			
			var a_click = modal.data("click");
			var td = a_click.closest("td");
			a_click.closest(".shift_container ").remove();

			td.append(div);

			modal.modal("hide");

			load_total_hours();
		}
	});
});

$(document).on('hidden.bs.modal' , '#confirm_remove_shift_modal' , function () {
	if(!$(this).data("noshow")){
		$("#edit_shift_modal").modal("show");
	}
});

$(document).on("click" , ".btn-remove-shift" , function(){
	var modal = $(this).closest(".modal").modal("hide");
	var a_click = modal.data("click");

	var dateid = a_click.closest("td").data("dateid");
	var datename = $(dateid).data("date");
	var staff_id = a_click.closest("tr").find("td").first().data("staff");
	var name = a_click.closest("tr").find("td").first().find("div").last().find("span").first().text();

	var modal = $("#confirm_remove_shift_modal").modal("show");
	datename = datename.toLowerCase();
	modal.find(".modal-title").html("Confirm Removal of Shift for "+name+" on "+datename.charAt(0).toUpperCase() + datename.slice(1));
	modal.data("noshow" , false);
});

$(document).on("click" , ".btn-proceed" , function(){
	var modal = $('#edit_shift_modal');
	var url = $(this).data("url");
	var a_click = modal.data("click");
	var id = a_click.data("shift_id");
	var published = a_click.data("published");
	var current_modal = $(this).closest(".modal");
	$.ajax({
		url : url , 
		data : {date_id : id , published : published},
		method : "POST" ,
		success : function(response){
			var json = jQuery.parseJSON(response);
			var btn = $(".btn-publish");

			if(json.published){
				btn.data("submit" , false).removeClass("btn-success").addClass("btn-default").html('Everything Published <br><small>No Changes</small>');
			}else{
				btn.data("submit" , true).removeClass("btn-default").addClass("btn-success").html('Publish & Notify <br><small>Entire Schedule</small>');
			}

			a_click.closest(".shift_container").remove();
			current_modal.data("noshow" , true).modal("hide");

			load_total_hours();
		}
	});
});

$(document).on("click" , ".btn-publish" , function(){
	var submit = $(this).data("submit");
	var selected_outlet = $("#select_locations").val();
	if(submit){
		$.ajax({
			url : publish_shift ,
			method : "POST" ,
			data : {outlet_id : selected_outlet} ,
			success : function(response){
				var json = jQuery.parseJSON(response);
				if(json.status){
					$(".btn-publish").data("submit" , false).removeClass("btn-success").addClass("btn-default").html('Everything Published <br><small>No Changes</small>');
					var div = $(".scheduler-table").find("tbody > tr > td > div.shift_container > div");
					div.removeClass("unpublished").addClass("published");

					$.notify("Successfully Published the shift" , { className:  "success" , position : "top center"});
				}
			}
		});
	}
});