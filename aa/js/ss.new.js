$(function() {
	if(tema_done) {
		$("<span></span>").prependTo($("#set_tema"));
		$("#vyber_tema div.current").prepend($("<span></span>"));
	}
	if(skin_done) {
		$("<span></span>").prependTo($("#set_prvky"));
		$("#vyber_skin div.current").prepend($("<span></span>"));
	}
});

$(function() {
	$("#col_left2").after('<div id="popnn" class="PopWin"></div>');
});

$(function() {
	$("#PopVyhra").on("click",".switch", function() {
		// schovam
		if($(this).hasClass("show")) {
			$(this).removeClass("show");
			$("#win_repeat_box").fadeOut();
		}
		// zobrazim
		else {
			$(this).addClass("show");
			$("#win_repeat_box").fadeIn();
		}
	});
});

$(function() {
	$("#rules_reset").click(function() {
		if(confirm(confirm_pravidla_resetovat)) {
			var data = "type=getDefaultRules&aplikace_id=" + $(this).attr("rel") + "&session_id=" + getSession();
	//		alert(data);
	//		var loading = $(this).closest(".aplikace").find(".loading-img");
	//		loading.show();

	//		ajaxloading($(this).closest(".aplikace, .appdashboard")); // u aplikace se zobrazi dole
			ajaxloading($(this).parent());
			$.ajax({
				type: "GET",
				url: url_redir + 'php/actions.php',
				data: data,
				dataType: 'json',
				cache: false,
				success: function(response) {
					console.log(response);
					if(response.session == "expired") {
						window.location.href = url_redir;
					}
					if(response.html) {
						$("#pravidla").html(response.html);
					}
					killajaxloading();

	//				loading.hide();
				}
			})
		}
	})
})

function uploaded_img_draggable() 
{
		$( "#uploaded_img div" ).draggable({
//			refreshPositions: true
//			appendTo: "body",
//			stack: "#col_right"
			zIndex: 6,
			helper: 'clone',
		    appendTo: 'div#all_cont',
			start: function( event, ui ) {
				ui.helper.addClass("red");
//				if(ui.draggable.length)
//					ui.draggable.addclass("blue");
//				alert("dd");

			}

//		    containment: 'div#all_cont'
		});
}

$(function() {
//		$("#all_cont div.new_obj").draggable();

		$("#all_cont div.new_obj").draggable({
//			refreshPositions: true
//			appendTo: "body",
//			stack: "#col_right"
			zIndex: 6,
			helper: 'clone',
		    appendTo: 'div#all_cont',
			start: function( event, ui ) {
				ui.helper.addClass("red");
			}
		});
		
		$("#all_cont").on("mouseenter","div.new_obj", function() {
			if(!$(this).find(".delete_new_obj").length)
				$(this).prepend("<div class='delete_new_obj' title='remove'></div>");
		});
		$("#all_cont").on("mouseleave","div.new_obj", function() {
			$(this).find(".delete_new_obj").remove();
		});
		$("#all_cont").on("click",".delete_new_obj", function() {
			$(this).parent().remove();
			refreshNahrano();
		});
		$( "#all_cont" ).droppable({
			tolerance: "fit", // fit, intersect, pointer, touch
/*		
			activate: function( event, ui ) {
				alert("start");
			},
*/			
			drop: function( event, ui ) {
				var position = ui.position;
				var offset = ui.offset;
/*				console.log("position: left: " + position.left + ", top: " + position.top );
				console.log("offset: left: " + offset.left + ", top: " + offset.top );
//				ui.draggable("destroy");
				console.log(ui.helper);
				console.log(ui.draggable);
				console.log(ui.helper.context);
				console.log(ui.draggable.context);
				console.log(ui.draggable.html());
				console.log(ui.draggable.context.outerHTML);
				console.log(ui.draggable.context.innerHTML);
*/				
				var $new_obj = $(ui.helper.context);
				ui.helper.remove();
				$new_obj.appendTo( "#all_cont" );
				$new_obj.css({ position: 'absolute', "z-index": 6 });
//				alert(ui.draggable.context);
//				alert(new_obj.css("z-index"));
				$new_obj.css({ left: position.left, top: position.top });
				$new_obj.addClass("new_obj");
				refreshNahrano();
				var d = "";
				var elems = $( "#all_cont .new_obj" );
				count = elems.length;
				var datas = "";
				elems.each(function( index, text ) {
					position = $(this).position();
					datas += "left[" + index + "]" + "=" + position.left + "&";
					datas += "top[" + index + "]" + "=" + position.top + "&";
					datas += "html[" + index + "]" + "=" + encodeURIComponent($(this).html()) + "&";
					if (!--count) {
						saveOwnObjects(datas);
					}
				});
			}
		});

});

$(function() {
	$("body").on("click","#nn",function() {
		set_widget_overlay("overlay");
		$("#popnn").empty();
		var data = "type=resetpicEdit";
		$.ajax({
			type: "GET",
			url: url_redir + 'php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.session == "expired") {
					window.location.href = url_redir;
				}
				if(response.html) {
					$("#popnn").html(response.html, function() {
						$('#popnn').show().center();
						$('#thebox').picEdit({
							maxWidth: 400,
							maxHeight: 400,
							formSubmitted: function(response) {
//								alert('Form submitted!');
								$('#popnn').hide();
								remove_widget_overlay("overlay");
								refreshNahrano();
							}
						});
					});
				}
				killajaxloading();

//				loading.hide();
			}
		})

	});
});

function refreshNahrano() {
	var data = "type=refreshNahrano";
	$.ajax({
		type: "GET",
		url: url_redir + 'php/actions.php',
		data: data,
		dataType: 'json',
		cache: false,
		success: function(response) {
			console.log(response);
			if(response.session == "expired") {
				window.location.href = url_redir;
			}
			if(response.html) {
				$("#uploaded_img").html(response.html, function() {
				});
			}
//			killajaxloading();
		}
	})
}

function saveOwnObjects(datas) {
	var data = datas + "type=saveOwnObjects";
//	console.log(datas);
	$.ajax({
		type: "POST",
		url: url_redir + 'php/actions.php',
		data: data,
		dataType: 'json',
		cache: false,
		success: function(response) {
			console.log(response);
			if(response.session == "expired") {
				window.location.href = url_redir;
			}
			if(response.dbaff > 0) {
				console.log("saved");
			}
		}
	})
}
