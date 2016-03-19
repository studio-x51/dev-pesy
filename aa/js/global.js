if(!(window.console && console.log)) {
	console = {
		log: function(){},
		debug: function(){},
		info: function(){},
		warn: function(){},
		error: function(){}
	};
}


// function to check for an empty object
function isEmpty(obj) {
  for(var prop in obj) {
	if(obj.hasOwnProperty(prop))
	  return false;
  }
  return true;
}

function stopBubble(e)
{
	if (e.stopPropagation) e.stopPropagation();
	else e.cancelBubble = true;
}
function stopDefault(e)
{
	if (e.preventDefault) e.preventDefault();
	else e.returnValue = false;
}
/* opens window and cancels the event if successfull */
function openAWin(path,width,height,ev,namewin,scrollbar, menubar, widgets) 
{
	if (!ev) ev = window.event;
	if (!ev) { alert("No event in openAWin"); return false; }
	if (!openWin(path,width,height,namewin,scrollbar, menubar, widgets)) return null;
	stopDefault(ev); stopBubble(ev);
	return false;
}

function openWin(path,width,height,namewin,scrollbar, menubar, widgets)
{
	if(window.anewWin)
		window.anewWin.close();
	if(!namewin) namewin = 'nove_okno';
//    if(!width || !height) anewWin = window.open(path,namewin,'menubar=yes,toolbar=yes,location=yes,directories=yes,fullscreen=no,titlebar=yes,hotkeys=yes,status=yes,scrollbars=yes,resizable=yes');
	if(namewin == '_blank')
		anewWin = window.open(path,"_blank");
	else if(widgets == "none") 
		anewWin = window.open(path,namewin,"width="+width+",height="+height+",toolbar=0,directories=0,location=0,menubar=0,status=0,scrollbars=" + (scrollbar ? scrollbar : 0) + ",resizable=yes");
	else if(scrollbar && menubar)  
		anewWin = window.open(path,namewin,"width="+width+",height="+height+",menubar=yes,toolbar=yes,location=yes,directories=yes,fullscreen=no,titlebar=yes,hotkeys=yes,status=yes,scrollbars=yes,resizable=yes");
	else if(scrollbar)  
		anewWin = window.open(path,namewin,"width="+width+",height="+height+",toolbar=0,directories=0,menubar=0,status=0,scrollbars=1,resizable=yes");
	// bez scrollbaru a menu baru
	else 
		anewWin = window.open(path,namewin,"width="+width+",height="+height+",toolbar=0,directories=0,location=0,menubar=0,status=0,scrollbars=0,resizable=yes");
	anewWin.focus();
	return anewWin;
}

$(function() {
	var switch_app_on_off;
	$("#dashboard .switch-app-on-off").click(function(e) {
		switch_app_on_off = $(this);
		var data = "type=switch_app_on_off&aplikace_id=" + $(this).attr("rel") + "&session_id=" + getSession();
//		alert(data);
//		var loading = $(this).closest(".aplikace").find(".loading-img");
//		loading.show();

//		ajaxloading($(this).closest(".aplikace, .appdashboard")); // u aplikace se zobrazi dole
		ajaxloading($(this).parent());
		$.ajax({
			type: "GET",
			url: './php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.session == "expired") {
					window.location.href = url_redir;
				}
				if(response.spusteno == 1) {
					switch_app_on_off.addClass("on");
					// pouze v SSP administraci
					if (window['swich_title_spusteno'] != undefined)
						switch_app_on_off.attr("title",swich_title_spusteno);
				}
				if(response.spusteno == 0) {
					switch_app_on_off.removeClass("on");
					// pouze v SSP administraci
					if (window['swich_title_stopnuto'] != undefined)
						switch_app_on_off.attr("title",swich_title_stopnuto);
				}
				// dashboard u aplikace
				$("#stav span").html(response.stav);
				// dashboard administrace
				$("#app_id_" + switch_app_on_off.attr("rel") + " .stav .val").html(response.stav);
				$("#app_id_" + switch_app_on_off.attr("rel") + " .licence .val").html(response.licence);
				$("#app_id_" + switch_app_on_off.attr("rel") + " .termin .val").html(response.termin);
				$("#app_id_" + switch_app_on_off.attr("rel") + " .stav .platba").html(response.platba);
				killajaxloading();

//				loading.hide();
			}
		});
	});
});

var interval_set_widget_overlay_size;
var count_interval_set_widget_overlay_size = 1;
function set_widget_overlay(id, prepend_div)
{
	if(!$("#"+id).length)
		$(prepend_div ? prepend_div : "body").prepend('<div id="' + id + '"></div>');
	$(function() {
		set_blur();
	});
	set_widget_overlay_height(id);
	var interval_set_widget_overlay_size = setInterval(function() {
		count_interval_set_widget_overlay_size++;
		set_widget_overlay_height(id);
//		console.log(count_interval_set_widget_overlay_size);
		if(count_interval_set_widget_overlay_size == 3)
			clearInterval(interval_set_widget_overlay_size);
	}, 2000);
}

function set_widget_overlay_height(id)
{
	var okno_height = Math.max($(document).height(), $(window).height(), $("#col_right").height());
	var okno_width = Math.max($(document).width(), $(window).width(), $("#col_right").width());
//	alert($("body").height() + "|" + $(document).height() + " | " + $(window).height());
    $("#" + id).css("height",okno_height);
    $("#" + id).css("width",okno_width);
}	
function remove_widget_overlay(id)
{
	$("#" + id).remove();
	remove_blur();
}

/**
* odstrani blur pri hlavnim helpu
*/
function remove_blur()
{
	$("#top_lista").removeClass("blur");
	$("#col_left1").removeClass("blur");
	$("#col_left2").removeClass("blur");
	$("#col_right").removeClass("blur");
	$("#dashboard").removeClass("blur");
	$("#cont_all_app").removeClass("blur");
}

/**
* prida blur pri hlavnim helpu
*/
function set_blur()
{
	$("#top_lista").addClass("blur");
	$("#col_left1").addClass("blur");
	$("#col_left2").addClass("blur");
	$("#col_right").addClass("blur");
	$("#dashboard").addClass("blur");
	$("#cont_all_app").addClass("blur");
}

var resizeId;
$(window).resize(function() {
    clearTimeout(resizeId);
    resizeId = setTimeout(doneResizing, 500);
});

function doneResizing() {
	set_widget_overlay_height("overlay_help");
	set_widget_overlay_height("overlay");
}




jQuery.fn.center = function ()
{
//  this.css("position","absolute");
  this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
		$(window).scrollTop()) + "px");
  this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
		$(window).scrollLeft()) + "px");
  return this;
}

jQuery.fn.center_in_obj = function ()
{
	var windowWidth = window.innerWidth;
	var windowHeight = window.innerHeight;

//	alert('viewport width is: '+ windowWidth + ' and viewport height is:' + windowHeight);
//  this.css("position","absolute");
	console.log($(this).parent().attr("id") + " | class=" + $(this).parent().attr("class"));
	console.log($(this).parent().outerHeight() + " | " + $(this).parent().outerWidth());
	console.log($(this).parent().outerHeight() + " | " + $(window).scrollTop());
	this.css("top", Math.min($(this).parent().outerHeight() / 2 - $(this).outerHeight() /2,  $(window).scrollTop() + windowHeight / 2 - $(this).outerHeight() /2) + "px");
	this.css("left", Math.min($(this).parent().outerWidth() / 2 - $(this).outerWidth() / 2,  $(window).scrollLeft() + windowWidth / 2 - $(this).outerWidth() /2) + "px");
	return this;
}




/**
* zjisteni zda jsem v iframe!
*/ 
function getParentUrl() {
  var isInIframe = (parent !== window),
	  parentUrl = null;

  if (isInIframe) {
	parentUrl = document.referrer;
  }
//	if(parentUrl.indexOf("facebook"))
  return parentUrl;
}


/* iframe addtabs - help aplikace pripravena - pridat na FB */
$(function() {
//	$("#main_addtab").center();
	if($("#main").attr("id") == "main") {
	}
	$("#help_aplikace_ready .close").click(function() {
		window.parent.postMessage("unblur", "*");
	});
});


/* /iframe addtabs - help aplikace pripravena - pridat na FB */

/**
* vytvori unikatni preloader v celem parent posicovanem (reelative, abolute) objektu vycentrovany na stred
*/	
function ajaxloading(obj) {
	if(!obj.length) 
		return;
	obj.prepend('<div id="ajax_loader"></div>');
	$("#ajax_loader").html('<figure_loader>' + 
'  <div class="dot four"></div>' + 
'  <div class="dot three"></div>' +
'  <div class="dot two"></div>' +
'  <div class="dot one"></div>' +
'</figure_loader>');
/*	$("#ajax_loader").css("top",obj.outerHeight()/2 - 32);
	$("#ajax_loader").css("left",obj.outerWidth()/2 - 32); */
	$("#ajax_loader").center_in_obj();
}
/**
* smazne preloader vyvolany ajaxloading
*/
function killajaxloading() {
	setTimeout(function(){
		$("#ajax_loader").remove();
	}, 100)
}


function FacebookInviteFriends2_4()
{
  /* make the API call */
  FB.api(
	  "me/apprequests",
	  function (response) {
	  console.log(response);
	  if (response && !response.error) {
	  console.log(response);
	  /* handle the result */
	  }
	  });
}

/**
* od JS SDK 2.4 nefunguje!
*/
function FacebookInviteFriends()
{
	FB.ui({
	method: 'apprequests',
	message: 'Your Message diaolog'
	}, function(response) {
		console.log(response);
	}); 
}

/** 
*  share dialog - share classic
*/
function fbSendShare(url) {
  FB.ui({
	method: 'share',
	href: url
	},
	function(response) {
		console.log(response);
		if (response && !response.error_code) {
			// TODO: vyhodit hlasku o sdileni a pokracovat ve videu!
		} else {
			alert('Error while posting.');
		}
	}
);
}


/*---------------------------------------*/
/*      administrace vyher               */
/*---------------------------------------*/

/**
* vyvolani pop dialogu vyhry!
*/


/**
* Inicializace Slick slideru + ostatnich  po ajax reloadu slick slideru - ceny administrace
*/
var slide_index = 0;
$(function() {
	SetVyhryInit();
});

/**
* Nastaveni pravdepodobnosti 
*/
$(function() {
	$("#PopVyhra").on("click",".pravdepodobnost", function() {
		$(".pravdepodobnost").removeClass("set");
		$(this).addClass("set");
		$("#pravdepodobnost").val($(this).attr("rel"));
	});
});

/**
* fce load / reload pop okna editace vyher
*/
function reloadPopVyhra(vyhra_id, prvek) {
	console.log("fce reloadPopVyhra");
	var offset = prvek.offset();
	var data = "type=reloadPopVyhra&vyhra_id=" + vyhra_id + "&session_id=" + getSession();
//	alert(offset.top + "|" + offset.left + "|" + prvek.width());
	$.ajax({
		type:'GET',
		url: url_redir + './php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log(response);
			if($.trim(response.redirect) == "redirect")  {
				logout_reload();
			}
			else if(response.stav_spusteno == "stop") {
				alert(alert_soutez_spustena);
			}
			else if (response.vyherci == 0 || (response.vyherci > 0 && confirm(confirm_change_price))) {
				$("#PopVyhra").html(response.html);
				$("#PopVyhra").removeClass("loading");
				$("#PopVyhra").show();
				var top_pos = offset.top - $("#PopVyhra").height() - 20;
				if(top_pos < 30)
					top_pos = offset.top + $("#slider_vyhry").height() + 10;
//				$("#PopVyhra").offset({ top: top_pos,  left: offset.left }); // - prvek.width() / 2});
				$("#PopVyhra").offset({ top: top_pos,  left: offset.left - $("#PopVyhra").width() / 2 + prvek.width() / 2}); // - prvek.width() / 2});
			//	alert($("#PopVyhra").html() + $("#PopVyhra").height() + " | " + $("#PopVyhra").width());
//				$("#PopVyhra_sipka").offset({ top: offset.top - $("#PopVyhra").height() - 20 + 3 + $("#PopVyhra").height()});
				$("#PopVyhra").hide();
				$("#PopVyhra").fadeIn();
			}
		}
	});
}


/**
* fce reload slider editace vyher
*/
function reloadSliderVyhry() {
	console.log("fce reloadSliderVyhry");
	var data = "type=reloadSliderVyhry&session_id=" + getSession();
//	alert(data);
	$.ajax({
		type:'GET',
		url: url_redir + './php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{

			console.log("response reloadSliderVyhry: " + response);
			if($.trim(response.redirect) == "redirect")  {
//				alert("redir");
				logout_reload();
			}
			else {
				$("#slider_vyhry").slick("unslick");

				$("#slider_vyhry").html(response.html);
					
				/* 
				a vse musim reinincializovat!!!
				TODO: dat do fce !!!
				*/
				SetVyhryInit();
						/* 
				/ a vse musim reinincializovat!!!
				/ TODO: dat do fce !!!
				*/ 
			}
		}
	});
}

/**
* fce load / reload okna  pravidla
*/
/*
function reloadShowRules() {

	var data = "type=reloadShowRules&session_id=" + getSession();
	$("#pravidla").addClass("loading");
//	alert(data);
	$.ajax({
		type:'GET',
		url: url_redir + './php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log("response reloadShowRules: " + response);
			if($.trim(response.redirect) == "redirect") 
				logout_reload();
			else {
				$("#pravidla").removeClass("loading");
				$("#pravidla").html(response.html);
			}
		}
	});
}
*/

/**
* fce load / reload pop okna editace pravidla
*/
/*
function reloadShowPopRules() {
	$("#PopPravidla").show();
	$("#PopPravidla").empty();
	$("#PopPravidla").addClass("loading");

	var data = "type=reloadShowPopRules&session_id=" + getSession();
//	alert(data);
	$.ajax({
		type:'GET',
		url: url_redir + './php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log("response reloadShowPopRules: " + response);
			if($.trim(response.redirect) == "redirect") 
				logout_reload();
			else {
				$("#PopPravidla").removeClass("loading");
				$("#PopPravidla").html(response.html);
				$("#PopPravidla").hide();
				$("#PopPravidla").fadeIn();
			}
		}
	});
}
*/

/**
* Inicializace Slick slideru + po ajax reloadu slick slideru - ceny administrace
*/
function SetVyhryInit() {
	$("#slider_vyhry").slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		variableWidth: true,
	});
	$("#slider_vyhry").slick('slickGoTo',slide_index); // nastavi se dalsi vyhra pro editaci

/*
	$('.js-add-slide').on('click', function() {
		  $('#slider_vyhry').slick('slickAdd','<div class="photo_content js-add-slide"><img src="img/nova_cena.png" alt=""></div>');
	 });
*/	  
}

/**
* uprava vyher obecne
*/
$(function() {
	$("#col_right").on("click","#prevBtn, #nextBtn", function() {
		$("#PopVyhra").hide();
	});
	// jelikoz upravuji pouze na jedne strance $(".set_vyhry #slider_vyhry")...
	$(".set_vyhry #slider_vyhry").on("click",".photo_content img", function(){
		var offset = $(this).offset();
		$("#PopVyhra").addClass("loading");
//		alert($(this).attr("rel") + " | " + $(this).parent().attr("data-slick-index"));
		slide_index = $(this).parent().attr("data-slick-index");	
		$("#PopVyhra").empty();
		reloadPopVyhra($(this).attr("rel"), $(this));
//		alert("left: " + offset.left + ", top: " + offset.top );
	});


	$("body").on("click","#col_right #btn_uprav_fb_og, #dashboard .edit_og", function(){
		var offset = $(this).offset();
		var dashboard = "";
		if($(this).hasClass("edit_og"))
			dashboard = "dashboard";
		reloadPopFbOg($(this).attr("rel"), offset, dashboard);
	});
});

/**
* fce load / reload pop okna editace Baneru
*/
function reloadPopBaner(baner_id) {
	console.log("fce reloadPopBaner");
	var data = "type=reloadPopBaner&baner_id=" + baner_id + "&session_id=" + getSession();
//	alert(data);
	$.ajax({
		type:'GET',
		url: url_redir + './php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log("response reloadPopBaner: " + response);
			if($.trim(response.redirect) == "redirect") 
				logout_reload();
			else {
				$("#PopBaner").html(response.html);
				$("#PopBaner").removeClass("loading");
			}
		}
	});
}

/**
* fce load / reload pop okna editace FB OG
*/
function reloadPopFbOg(aplikace_id, offset, dashboard) {
	$("#PopFbOgDashBoard").remove();
	$("#PopFbOg").empty();
	ajaxloading($("#PopFbOg"));
	ajaxloading($("#PopFbOgDashBoard"));
	$("#PopFbOg").fadeIn();
	$("#PopFbOg").offset({ top: offset.top - $("#PopFbOg").height() - 40,  left: offset.left - 230});
	$("#PopVyhra_sipka").offset({ top: offset.top - $("#PopFbOg").height() - 20 + 3 + $("#PopFbOg").height(),  left: offset.left - 110 - 20 + $("#PopFbOg").width() / 2 });
	console.log("fce reloadPopFbOg");
	var type = "reloadPopFbOg";
	if(dashboard)
		type = "reloadPopFbOgDashboard";
	var data = "type=" + type + "&aplikace_id=" + aplikace_id + "&session_id=" + getSession();
//	alert(data);
	$.ajax({
		type:'GET',
		url: url_redir + './php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log("response reloadPopFbOg: " + response);
			if($.trim(response.redirect) == "redirect") 
				logout_reload();
			else {
				if(dashboard) {
					var obj = $("#app_id_" + aplikace_id).children(".link_short_share");
//					alert(offset.top + "|" +offset.left);
					var myNewElement = $( "<div id='PopFbOgDashBoard' class='PopWin'></div>" );
					var offset_obj = obj.position();
					obj.after(myNewElement);
					myNewElement.html(response.html);
					myNewElement.offset({ top: offset_obj.top + 81,  left: offset_obj.left - 10 });
//					alert(offset.top);
					myNewElement.fadeIn();
					killajaxloading();
				}
				else {
					$("#PopFbOg").html(response.html);
					killajaxloading();
				}
			}
		}
	});
}



function hidePopErr() {
	$("#output").show();  
	$('.overlay').show(); //hide submit button
	setTimeout(function(){
		$("#output").hide();
//		$('.overlay').hide(); //hide submit button
	}, 5000);
}

function hidePopErr_og() {
	$("#output_og").show();  
	$('.overlay').show(); //hide submit button
	setTimeout(function(){
		$("#output_og").hide();
//		$('.overlay').hide(); //hide submit button
	}, 5000);
}



$(function() {
	$("#PopFbOg").on("mouseover", "textarea", function() {
		$(this).addClass("bordered");
	});
	$("#PopFbOg").on("mouseout", "textarea", function() {
		$(this).removeClass("bordered");
	});
});


/*
$(function() {
	// odeslani a ulozeni pravidel - rules
	$("#col_right .uprav_app").on("submit", "#PopPravidla form",function() {
		var data = $(this).formSerialize(); 
//		console.log(data);
		// RESET TESTU, DOTAZNIKU
		$("#PopPravidla textarea").html($("#tinymce").html());
		setAJAX(url_redir + "./php/actions.php", data, "POST", "reloadShowRules", "#PopPravidla", "");
		return false;
	});

	$("#col_right .uprav_app").on("click","#pravidla", function() {
//		reloadShowPopRules();
	});
})
*/

// doplni nazev souboru k label orezany o "path" pri vyberu souboru 
$(function() {
	$("#col_right .uprav_app").on("change", "#imageInput", function() {
		var fileName = this.value;
		if (fileName.indexOf("\\") >= 0)
			fileName = fileName.substr(strrpos(fileName, "\\")+1);
		else if (fileName.indexOf("\/") >= 0)
			fileName = fileName.substr(strrpos(fileName, "\/")+1);
		$(this).parent().next("label").text(fileName);
	});
});

/**
* button na smaznuti vyhry 
*/
$(function() {
	$("#slider_vyhry").on("click", ".photo_content .del", function() {
		$("#PopVyhra").hide();
		if(confirm(confirm_delete_price)) {
//			alert("smazu no!" + $(this).attr("rel"));
//			delete_price($(this).attr("rel"));
			var data = "type=delete_price&vyhra_id=" + $(this).attr("rel") + "&session_id=" + getSession();
		//	alert(offset.top + "|" + offset.left + "|" + prvek.width());
			$.ajax({
				type:'GET',
				url: url_redir + './php/actions.php',
				data: data,
				dataType: 'json',
				success: function(response)
				{
					console.log(response);
					if(response.session == "expired") {
						window.location.href = url_redir;
					}
					else if(response.stav_spusteno == "stop") {
						alert(alert_soutez_spustena);
					}
					else {
						reloadSliderVyhry();
					}
				}
			});

		}
		else
			return false;
	})
});

/**
* fce na smaznuti 1 vyhry po click on del
*/
function delete_price(vyhra_id)
{


	setAJAX(url_redir + "./php/actions.php", "type=delete_price&vyhra_id=" + vyhra_id + "&session_id=" + getSession(), "GET", "reloadSliderVyhry");
//	reloadSliderVyhry();
}


/**
* banery
*/
$(function() {
	$("#col_right").on("click", ".baner_admin", function(e){
		var offset = $(this).offset();
		var pos_y= e.pageY;
		var pos_x = e.pageX;
		var offset = $("#col_right").offset();
//		alert(offset.left);
		var max_x = offset.left + $("#col_right").width() - $("#PopBaner").width() - 5;

		$("#PopBaner").addClass("loading");
//		alert($(this).attr("rel"));
		$("#PopBaner").empty();
		reloadPopBaner($(this).attr("rel"));
		$("#PopBaner").show();
//		$("#PopBaner").offset({ top: offset.top - $("#PopBaner").height() + 100,  left: offset.left + 140});
		$("#PopBaner").offset({ top: pos_y - $("#PopBaner").height() - 45,  left: Math.min(max_x, pos_x - $("#PopBaner").width() / 2)});
//		alert($("#PopBaner").height() + " | " + $("#PopBaner").width());
//		$("#PopVyhra_sipka").offset({ top: offset.top - $("#PopBaner").height() - 20 + 3 + $("#PopBaner").height(),  left: offset.left - 110 - 20 + $("#PopBaner").width() / 2 });
		$("#PopBaner").hide();
		$("#PopBaner").fadeIn();
//		alert("left: " + offset.left + ", top: " + offset.top );
	});
});

$(function() {
	$("#hlavni_help .noshow, #tema_skin_help .noshow").click(function(){
		head_help_off();
	});
});

/**
* fce 
*/
function head_help_off() {
	console.log("fce head_help_off");
	remove_widget_overlay("overlay");
	remove_widget_overlay("overlay_help");
	$("#hlavni_help, #tema_skin_help").fadeOut();

	var data = "type=head_help_off&session_id=" + getSession();
//	alert(data);
	$.ajax({
		type:'GET',
		url: url_redir + './php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log("response head_help_off: " + response);
		}
	});
}



/**
* Validace formu dle atributu rel
*/
function validateForm(form) {
	var err = "";
	$(form + " input[type=text], " + form + " input[type=file], " + form + " input[type=checkbox], " + form + " textarea").each(function() {
		console.log($(this).attr("name") + " = " + $(this).attr("type")  + " = " + $(this).prop('checked'));
		if($(this).attr("type") == "checkbox") {
			if($(this).prop('checked') != true && $(this).attr("rel") == "y") 
				err += "\t" + $(this).attr("placeholder")+ "\n";
		}
		else if(!(this.value) && $(this).attr("rel") == "y") {
			err += "\t" + $(this).attr("placeholder")+ "\n";
			$(this).addClass("red");
		}
	});


	if(err) err = err_form_title + " \n" + err
	return err;
}




function capture(href, parent_div) {
//	alert(parent_div.attr("id"));
//	return false;
	if($("#save_screenshot").length) {
		parent_div.find(".overlay").remove();
		$("#zamek, #overlay_app").remove();
		parent_div.html2canvas({
			onrendered: function (canvas) {
				//Set hidden field's value to image data (base-64 string)
				$('#img_val').val(canvas.toDataURL("image/png"));
				//Submit the form manually
				save_screenshot(href);
	//			document.getElementById("save_screenshot").submit();
			}
		});
	}
}

/**
* fce ulozi screenshot
*/
function save_screenshot(href) {
	console.log("fce save_screenshot");
	var data = $("#save_screenshot").serialize();
//	alert(offset.top + "|" + offset.left + "|" + prvek.width());
	$.ajax({
		type:'POST',
		url: url_redir + './php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log("fce save_screenshot href=" + href + " response:");
			console.log(response);
			window.location.href = href;
		}
	});
}

function getPageName(page_id, fce) {
	var picture;
	FB.api(page_id + '?fields=id,name,link,picture,app_links,access_token', function(response2) {
		console.log('/'+ page_id + '?fields=id,name,link,picture,app_links,access_token');
		console.log(response2);
		if (response2 && !response2.error) {
			console.log("getPageInfo:");
			console.log(response2.name);
			console.log(response2.link);
			console.log(response2.picture.data.url);
			picture = response2.picture ? response2.picture.data.url : "";
//			return response2.name;
			if(fce == "saveFBTab")
				saveFBTab(page_id, response2.name, encodeURIComponent(response2.picture.data.url), response2.link);
			else 
				saveFBPage(page_id, response2.name, encodeURIComponent(response2.picture.data.url), response2.link);
//			return response2.name;
		}
	});
}
// fce saveFBTab nebo saveFBPage
function getPageName2(page_id, fce) {
	var url = "php/actions.php"; // the script where you handle the form input.
	var data = "type=" + fce + "&uid=" + $("#main_addtab").attr("rel") + "&page_id=" + page_id + "&session_id=" + $("body").attr("rel");
	$.ajax({
		type: "GET",
		url: url,
		data: data, // serializes the form's elements. (type=send_adress as hidden in form id=send_adress!!!)
		success: function(response)
		{
			console.log(response);
			window.parent.postMessage("addtab_done", "*");

				$("#cont_aplikace_ready").fadeOut();
				$("#cont_aplikace_added").fadeIn();
				setTimeout(function(){
					// namisto automat redirect je tlacitko "Prejit k platbe!"
					window.parent.postMessage("next_step", "*");
				}, 11000);
		}
	});
}




/**
* Updatne nazev FB stranky dle page_id
*/
function saveFBPage(page_id, page_name, page_picture, page_url) {
		var url = url_redir + "./php/actions.php"; // the script where you handle the form input.
//		alert("saveFBPage");
		var data = "type=saveFBPage&page_id=" + page_id + "&page_name=" + page_name + "&page_url=" + page_url + "&page_picture=" + page_picture + "&session_id=" + $("body").attr("rel");
//		alert(data);
		$.ajax({
			type: "GET",
			url: url,
			data: data, // serializes the form's elements. (type=send_adress as hidden in form id=send_adress!!!)
			success: function(response)
			{
				console.log(response);
			}
		});
}

function dump(obj) {
  var out = '';
  for (var i in obj) {
	out += i + ": " + obj[i] + "\n";
  }

  alert(out);

  // or, if you wanted to avoid alerts...

  var pre = document.createElement('pre');
  pre.innerHTML = out;
  document.body.appendChild(pre)
}


/* editace textu ckeditor */
function start_ck_editor() {
		// We need to turn off the automatic editor creation first.
//		CKEDITOR.disableAutoInline = false;
		CKEDITOR.disableAutoInline = true;

		$("div[contenteditable='true']" ).each(function( index ) {

			var content_id = $(this).attr('id');
			var table_data = $(this).attr('rel');

			CKEDITOR.inline( content_id, {
				on: {
					blur: function( e) {
//						saveChangeText( e, content_id );
					},
					change: function( e) {
						saveChangeTextEndAndStartTimer(e, content_id, table_data)
						uprav_underline_by_span_color();

//						saveChangeTextEndAndStartTimer(e );
					}
				},
				customConfig: '../../js/ckeditor/config.js',
				toolbar: (content_id == "button_more" ? 'Basic' : 'Full')
			} );

		});
}		

/* editace textu ckeditor */
function start_ck_editor_elem(elem_id) {
		// We need to turn off the automatic editor creation first.
//		CKEDITOR.disableAutoInline = false;
		var elem = $("#" + elem_id);
		$("#" + elem_id + " div[contenteditable='true']").each(function( index ) {
			var content_id = $(this).attr('id');
			var table_data = $(this).attr('rel');

			CKEDITOR.inline( content_id, {
				on: {
					blur: function( e) {
//						saveChangeText( e, content_id );
					},
					change: function( e) {
						saveChangeTextEndAndStartTimer(e, content_id, table_data)
//						saveChangeTextEndAndStartTimer(e );
					}
				},
				customConfig: '../../js/ckeditor/config.js',
				toolbar: (content_id == "button_more" ? 'Basic' : 'Full')
			} );

		});
}		


	var savechangetext;
	function saveChangeTextEndAndStartTimer(e, content_id, table_data) {
		window.clearTimeout(savechangetext);
		//var millisecBeforeRedirect = 10000; 
		savechangetext = window.setTimeout(function(){ saveChangeText(e, content_id, table_data);},500); 
	}

	function saveChangeText(e, content_id, table_data) {
		var data = e.editor.getData();
		// test na zmenu textu
		if(oldData[content_id] == data)
			return;
//		alert(content_id);
		console.log( data );
		oldData[content_id] = data;
		var request = jQuery.ajax({
			url: "php/actions.php",
			type: "POST",
			data: {
				type: "setTextHtml",
				content : data,
				content_id : content_id,
				table_data : table_data,
			},
			dataType: "html"
		});
	}


/* /editace textu ckeditor */

/* sample: scrollToAnchor("#hlasovat") */
function scrollToAnchor(aid){
	var aTag = $(aid);
	$('html,body').animate({scrollTop: aTag.offset().top},'slow');
//	console.log($(aTag).position().top);
}

/**
* uni ajax-image-upload by js
*/
function PopErr_ajax_univ_image_upload(output, txt) {
	output.html(txt);
	output.show();
	killajaxloading();
	setTimeout(function(){
		output.hide();
//		$('.overlay').hide(); //hide submit button
	}, 5000);
}

var form_elem = "";
function afterSuccess_by_js()
{
	killajaxloading();
	if(form_elem.find(".file-wrapper .button").html().length > 3)
		form_elem.find(".file-wrapper").addClass("photo");
	form_elem.find(".file-wrapper .imageInput").val("");
}

function beforeSubmit_by_js(){
    //check whether browser fully supports all File API
//	if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {alert('Its Safari');}
//	alert(form_elem.attr("class"));
	if (window.File && window.FileReader && window.FileList && window.Blob || (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1))
	{
		var err_text = "";
		var output = form_elem.find(".output_ajax_upload");
		var imageInput = form_elem.find(".imageInput");
		
		if( !imageInput.val()) //check empty input filed
		{
			return;
//			PopErr_ajax_univ_image_upload(output, "Are you kidding me?");
//			return false;
		}

		if(err_text) {
			PopErr_ajax_univ_image_upload(output, err_text);
			alert(err_text);
			return false;
		}

		var fsize = imageInput[0].files[0].size; //get file size
		var ftype = imageInput[0].files[0].type; // get file type
		

		//allow only valid image file types 
		switch(ftype)
        {
            case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
                break;
            default:
//				err_text += "<?=txt("setting-err_vyberte_obrazek_og")?><br />";
				PopErr_ajax_univ_image_upload(output, "<b>"+ftype+"</b> Unsupported file type!");
				alert("<b>"+ftype+"</b> Unsupported file type!");
				return false;
        }
		
		//Allowed file size is less than 1 MB (1048576), 5MB (5242880), 10MB (10485760)
		if(fsize>10485760) 
		{
			PopErr_ajax_univ_image_upload(output, "<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
			return false;
		}
				
//		$('.overlay').show(); //hide submit button
//		$('#submit-btn').hide(); //hide submit button
//		$('#loading-img').show(); //hide submit button
		// ajaxloading zde, aby nebyl pokud vznikne chyba!
		ajaxloading(form_elem);
		output.html("");  
	}
	else
	{
		//Output error to older unsupported browsers that doesn't support HTML5 File API
		output.html("Please upgrade your browser, because your current browser lacks some new features we need!");
		return false;
	}
}

$(document).ready(function() { 
	$('.ajax_upload_by_js').submit(function() { 
		form_elem = $(this);
		$(this).ajaxSubmit({
			target:   form_elem.find(".file-wrapper .button"),   // target element(s) to be updated with server response 
		    beforeSubmit:  beforeSubmit_by_js,  // pre-submit callback 
			success:       afterSuccess_by_js,  // post-submit callback 
			resetForm: false});  			
		// always return false to prevent standard browser submit and page navigation 
		return false; 
	}); 
	$(document).on('change','.ajax_upload_by_js', function() 
	{
//		$("#ajax_upload_<?=$what_id?>").ajaxSubmit(options_<?=$what_id?>);
//		form_elem = $(this).closest("form");
		form_elem = $(this);
		form_elem.ajaxSubmit({
			target:   form_elem.find(".file-wrapper .button"),   // target element(s) to be updated with server response 
		    beforeSubmit:  beforeSubmit_by_js,  // pre-submit callback 
			success:       afterSuccess_by_js,  // post-submit callback 
			resetForm: false});
	});
}); 

/**
* / uni ajax-image-upload by js
*/

$(function() { 
	uprav_underline_by_span_color();
});

/**
* fce upravi tag a tak, aby underline byl v barve child tagu span zadany v ck editoru!
*/
function uprav_underline_by_span_color()
{
	var tst_span = $("#all_cont a span").filter('[style*=color]');
	tst_span.each(function() {
		$(this).parent().css("text-decoration","none");
//		alert($(this).parent().attr("href"));
		$(this).css("text-decoration", "underline");
//		$(this).css("color", ($(this).css("color")));
	});
}

function urldecode(str) {
	return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}

var getUrlParameter = function getUrlParameter(sParam) {
  var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	  sURLVariables = sPageURL.split('&'),
	  sParameterName,
	  i;

  for (i = 0; i < sURLVariables.length; i++) {
	sParameterName = sURLVariables[i].split('=');

	if (sParameterName[0] === sParam) {
	  return sParameterName[1] === undefined ? true : sParameterName[1];
	}
  }
};


var getUrlParameterFromQueryString = function getUrlParameter(sParam, queryString) {
  var sPageURL = decodeURIComponent(queryString),
	  sURLVariables = sPageURL.split('&'),
	  sParameterName,
	  i;

  for (i = 0; i < sURLVariables.length; i++) {
	sParameterName = sURLVariables[i].split('=');

	if (sParameterName[0] === sParam) {
	  return sParameterName[1] === undefined ? true : sParameterName[1];
	}
  }
};


