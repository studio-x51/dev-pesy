String.prototype.filename=function(extension){
  var s= this.replace(/\\/g, '/');
  s= s.substring(s.lastIndexOf('/')+ 1);
  return s;
//  return extension? s.replace(/[?#].+$/, ''): s.split('.')[0];
}


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

/* vlastni objekty - obrazky */
$(function() {
		$("#all_cont div.new_obj img").resizable({
			stop: function( event, ui ) {
				prepareSaveOwnObjects(false);
			}
		});


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
		
		$("#all_cont, #uploaded_img").on("mouseenter","div.new_obj, div.up_img", function() {
			if(!$(this).find(".delete_new_obj").length)
				$(this).prepend("<div class='delete_new_obj' title='remove'></div>");
		});
		$("#all_cont, #uploaded_img").on("mouseleave","div.new_obj, div.up_img", function() {
			$(this).find(".delete_new_obj").remove();
		});
		$("#all_cont, #uploaded_img").on("click",".delete_new_obj", function() {
			var del_obj = $(this).parent();
			if(del_obj.hasClass("up_img")) {
				if(confirm(picedit_confirm_delete_from_disk)) {
					del_obj.remove();
					prepareSaveOwnObjects(del_obj.find("img").attr("src").filename());
				}
			}
			else {
//				refreshNahrano();
				del_obj.remove();
				prepareSaveOwnObjects(false);
			}
		});
		$( "#all_cont" ).droppable({
			tolerance: "intersect", // fit, intersect, pointer, touch
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
				console.log($(ui.helper.context).find("img").html());
				$new_obj.appendTo( "#all_cont" );
				$new_obj.css({ position: 'absolute', "z-index": 6 });
//				alert(ui.draggable.context);
//				alert(new_obj.css("z-index"));
				$new_obj.css({ left: position.left, top: position.top });
				$new_obj.addClass("new_obj");
				$new_obj.removeClass("up_img");
				
				$new_obj.find("img").resizable ({
					stop: function( event, ui ) {
						console.log("ui.size:");
						console.log(ui.size.height + " | " + ui.size.width);
						console.log("ui.originalElement:");
						console.log(ui.originalElement.context);
						prepareSaveOwnObjects(false);
					}
				});
				
//				refreshNahrano();
				
				prepareSaveOwnObjects(false);
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
							maxWidth: 810,
							maxHeight: 900,
							formSubmitted: function(response) {
//								alert('Form submitted!');
								$('#popnn').hide();
								remove_widget_overlay("overlay");
								refreshNahrano();
							}
						});
						$('#popnn').center();
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

function saveOwnObjects(datas, del_img) {
	var data = datas + "type=saveOwnObjects&del_img=" + del_img;
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
				refreshNahrano();
			}
		}
	})
}

function prepareSaveOwnObjects(del_img) {
	var elems = $( "#all_cont .new_obj" );
	if(!del_img)
		var del_img = "";
//	alert(del_img);
	count = elems.length;
	var datas = "";
	var imgname;
	var imgobj;
	if(count > 0) {
		elems.each(function( index, text ) {
			imgobj = $(this).find("img");
			imgname = imgobj.attr("src").filename();
			// pokud je mazany obrazek stejny s tim, co mame vyhodim a je to!
			if(del_img == imgname) {
				// smaznu prvek
//				alert("smazu");
				$(this).remove();
			}
			else {
				position = $(this).position();
				datas += "left[" + index + "]" + "=" + position.left + "&";
				datas += "top[" + index + "]" + "=" + position.top + "&";
				datas += "html[" + index + "]" + "=" + encodeURIComponent(imgobj[0].outerHTML) + "&";
				datas += "img[" + index + "]" + "=" + encodeURIComponent(imgname) + "&";
	/*			
				console.log("prepareSaveOwnObjects:");
				console.log(imgname);
				console.log(imgobj[0].outerHTML);
	*/			
			}
			if (!--count) {
	//			alert(datas);
				saveOwnObjects(datas, del_img);
			}
		});
	}
	else
		saveOwnObjects(datas, del_img);
}
/* /vlastni objekty - obrazky */

