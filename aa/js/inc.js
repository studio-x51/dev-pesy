/**
* zamezeni error hlasky  "console is not defined" pri vypnutem firebug
*/

if(typeof(console) === "undefined" || typeof(console.log) === "undefined")
    var console = { log: function() { } };
/*
if (!("console" in window) || !("firebug" in console))
{
  var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml",
	  "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];

  window.console = {};
  for (var i = 0; i < names.length; ++i)
	window.console[names[i]] = function() {}
}
*/

var session_id;
var timer;

function Login(scope, session_id, redir_admin)
{
//	alert("login" + session_id);
	FB.login(function(response) {
		if (response.authResponse) 
		{
			console.log('User logged');
//			token=response.authResponse.accessToken;
			access_token =   FB.getAuthResponse()['accessToken'];
			console.log('Access Token = '+ access_token);

			getUserInfo("login",session_id, redir_admin); // zalozim uivatele
				
//			isFan(access_token); 
		}
		else 
		{
			console.log('User cancelled login or did not fully authorize.');
		}
	 },{scope: "manage_pages, email, public_profile, user_friends" });
}

/* fce musi getSession() byt zde, fce getSession() u aplikaci je primo v js pro aplikace js/global_app.js */
function getSession() {
	return $("body").attr("rel");
}

var contact_email;
function getUserInfo(from, session_id, redir_admin) {
	FB.api('/me', function(response) {
		if (response && !response.error_code) {
			console.log(response);
			contact_email = response.email;
			var data = "type=login&redir_admin=" + redir_admin + "&user="+ response.id + "&firstname="+ response.first_name + "&email="+ response.email + "&gender="+ response.gender + "&lastname="+ response.last_name + "&try_app=" + getUrlVars()["try_app"] + "&session_id=" + getSession();
//			alert("getUserInfo:" + data);
			$.ajax({
				type:'GET',
				url: url_redir + 'php/actions.php',
				data: data,
				dataType: 'json',
				success: function(response2)
				{
//					alert("after getUserInfo" + response2);
					console.log("getUserInfo response:");
					console.log(response2);
					$("body").attr("rel", response2.session_id);
					$("#btn_login").hide();
//					$("#main").attr("rel",session_id);
					if($.trim(response2.email_contant) == "used") {
						alert("email contact used");
					}
					
					// stahni pdf 26 napadu
					if($.trim(response2.type) == "stahni") {
						$("#email_contact").val(contact_email);
						$("#set_email").fadeIn().center();
						set_widget_overlay("overlay");
						return;
					}


					// on demand academy
					if($.trim(response2.type) == "on_demand_academy") {
//						alert("on_demand_academy");
						$("#f_session_id").val(response2.session_id);
						$("body").attr("rel",response2.session_id);
						$("#f_fb_id").val(response2.fb_id);
						$("#setPayment").fadeIn();
//						$("#setPayment").css("display","block");
						
						var autodoplneni = false;
						$.each(response2.odberatel, function( index, value ) {
//							console.log("zadano:" + $.trim($(".form_odberatel :input[name="+ index + "]").val()));
							if($(".form_odberatel :input[name="+ index + "]").length && $.trim($(".form_odberatel :input[name="+ index + "]").val()) == "") {
								autodoplneni = 1;
//								console.log("doplnuju:" + index + "|" + value);
								$(".form_odberatel input[name="+ index + "]").val(value);
							}
						});


						// vyjimka pro checkbox platce_dph
						if(autodoplneni && response2.odberatel.platce_dph == "ano") {
							$("#platce_dph").prop( "checked",true );
						}

						// vyjimka pro stat vezmu z db, pokud jsem nic nedoplnil
						if(autodoplneni && response2.odberatel.stat_iso) {
							$(".form_odberatel :input[name=stat_iso]").val(response2.odberatel.stat_iso);
						}

						console.log("autodoplneni=" + autodoplneni);
						if(response2.premium_user) {
							alert(err_form_sorry_you_are_premium);
							killajaxloading();
							return;
						}


						$("#PopPlatba form:first" ).trigger( "submit" );
						return;

						if(autodoplneni != 1) {
							$("#PopPlatba form:first" ).trigger( "submit" );
							return;
						}
						else {
							killajaxloading();
							return;
						}

						// TODO: zde vrazim do formu fb_id a session_id!
						// a odeslu reg form a vyvolam platebni branu!
						// 1. odeslani registracniho formulare (nejaka validace?)
						// asi v success prijde krok 2. odeslani registracniho formu! => createPayment("premium");
					}
					if($.trim(response2.redirect) == "redirect") {
						// on_demand je pripravene
						if($.trim(response2.url_new) == "premium" || $.trim(response2.url_new) == "on_demand") 
							url_redir = url_redir + $.trim(response2.url_new);
						else if(response2.user == "new")
							url_redir = url_redir + "signupcomplete";
						else if(response2.user == "old")
							url_redir = url_redir + "dashboard";
						url_redir =  url_redir + "?session_id=" + response2.session_id;
						url_redir = url_redir + (response2.try_app && response2.try_app != "undefined" ? "?try_app=" + response2.try_app : "");
//						alert("redir:" + url_redir);
						window.location.href = url_redir;
					}
				}
			});
/*
			var str="<b>Name</b> : "+response.name+"<br>";
				str +="<b>Link: </b>"+response.link+"<br>";
				str +="<b>id: </b>"+response.id+"<br>";
				str +="<b>Email:</b> "+response.email+"<br>";
				str +="<b>GEnder:</b> "+response.gender+"<br>";
				str +="<input type='button' value='Get Photo' onclick='getPhoto();'/>";
				str +="<input type='button' value='Logout' onclick='Logout();'/>";
				document.getElementById("status").innerHTML=str;
*/					  
		}
	});
}

/**
* share dialog - share as like
*/
function fbShareAsLike() {
	// nebo s obrazkem
	FB.ui({
		method: 'share_open_graph',
		action_type: 'og.likes',
		action_properties: JSON.stringify({
//		object:'http://x51.cz',
		object: url_share,
	})
	}, function(response){
		console.log(response);
	});
}



function sklonuj(word, how_much) {
	switch(word) {
		case "kód":
			if(how_much == 1) return how_much + " kód";
			if(how_much >= 5) return how_much + " kódů";
			return how_much + "kódy";
			break;
		case "cena":
			if(how_much == 1) return how_much + " cena";
			if(how_much >= 5) return how_much + " cen";
			return how_much + " ceny";
			break;
		case "minut":
			if(how_much == 1) return how_much + " minutu";
			if(how_much >= 5) return how_much + " minut";
			return how_much + " minuty";
			break;
		case "clovek":
			if(how_much == 1) return how_much + " člověk";
			if(how_much >= 5) return how_much + " lidí";
			return how_much + " lidé";
			break;

		default: return "neni nastaveno ve fci sklonuj";
	}
}

function casuj(word, how_much) {
	switch(word) {
		case "Chtít":
			if(how_much == 1 || how_much >= 5) return "Chce";
			if(how_much < 5) return "Chtějí";
			break;
		default: return "neni nastaveno ve fci sklonuj";
	}
}

function FacebookInviteFriends2_4()
{
/* make the API call */
  FB.api(
	  "/apprequests",
	  function (response) {
			console.log(response);
	  if (response && !response.error) {
			console.log(response);
	  /* handle the result */
	  }
  });
}

var tabs_added = [];
function addFBTab(appURL) {
	FB.ui({
		method: 'pagetab',
		redirect_uri: appURL
	}, function(response){
		console.log(jQuery.parseJSON(response));
		console.log(response.tabs_added.length);
		// vypis objectu
/*		
		var acc = [];
		$.each(response.tabs_added, function(index, value) {
			acc.push(index + ': ' + value);
			});
		alert(JSON.stringify(acc));
*/
		$.each(response.tabs_added, function( index, value ) {
			if(value == true) {
				saveFBTab(index);
			}
//			alert( index + ": " + value );
		});
//		alert(JSON.stringify(tabs_added));
	})
}


function logout_reload(try_app) {
	url_redir = url_redir + (try_app ? "logout?try_app=" + try_app  : "logout");
//	alert("redir:" + url_redir);
	window.location.href= url_redir;
}	

function reload_page() {
	location.reload(); 
}	

function reload_set_tema_skin() {
	window.location.href = "setapp";
}


/**
* set contant email email_contact - na index.php zobrazim form na pridani kontaktniho emailu (pokud ho jiz uzivatel nema zadan)!
*/
$(function() {
	if($("#set_email:not(.noshow)").length) {
		set_widget_overlay("overlay");
		$("#set_email").fadeIn();
		$("#set_email").center();
		$("#set_email form").submit(function(e) {
			ajaxloading($(this).parent());
//			alert($(this).serialize());
			$.ajax({
				type: "GET",
				url:'./php/actions.php',
				data: $(this).serialize(),
				dataType: 'json',
				cache: false,
				success: function(response) {
					console.log(response);
					killajaxloading();
					if(response.dbaff < 0)
						alert("E-mail is not saved");
					else if(response.dbaff >= 0) {
						killajaxloading();
						$("#set_email").fadeIn(function() {
							$(this).remove();
							remove_widget_overlay("overlay");
						});
					}

				}
			});
			e.preventDefault();
			return false;
		});
	}
});

$(function() {
	var winheight = Math.max($( window ).height(), window.innerHeight);
//	alert(winheight);
	$("#col_left2").height(winheight - 87);
});

var resizeId;
$(window).resize(function() {
	clearTimeout(resizeId);
	resizeId = setTimeout(doneResizing, 500);
});
		 
		  
function doneResizing(){
	//whatever we want to do
	$("#col_left2").height($( window ).height() - 87);
}

(function($){
	$(window).load(function(){
		$("#col_left2").mCustomScrollbar({
			theme:"minimal"
		});
	});
})(jQuery);


// nastaveni tematu x skinu
$(function() {
	$("#vyber_tema").on("click","img", function(){
		setTema($(this).attr("id"));
//		alert($(this).attr("id"));
		$("#vyber_tema img").removeClass("current");
		$(this).addClass("current");
	});
	$("#vyber_skin").on("click","img", function(){
		setSkin($(this).attr("id"));
//		alert($(this).attr("id"));
		$("#vyber_skin img").removeClass("current");
		$(this).addClass("current");
	});
});




/**
* fce nastaveni tematu
*/
function setTema(tema) {
	console.log("fce setTema" + tema);
	var data = "tema="+ tema + "&type=setTema&session_id=" + getSession();
//	alert(data);
	$.ajax({
		type:'GET',
		url:'./php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log(response);
			if($.trim(response.redirect) == "redirect") 
				reload_set_tema_skin();
//				reload_page();
		}
	});
}

/**
* fce nastaveni skinu
*/
function setSkin(skin) {
	console.log("fce setSkin" + skin);
	var data = "skin="+ skin + "&type=setSkin&session_id=" + getSession();
//	alert(data);
	$.ajax({
		type:'GET',
		url:'./php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log(response);
			if($.trim(response.redirect) == "redirect")  
				reload_set_tema_skin();
//				reload_page();
		}
	});
}

/*
function getSession() {
	return $("body").attr("rel");
}
*/
// vyber img ze skinu
$(function() {
	$("#uprav_app").on("click","img.img_change", function(e){
		var slick_change_item = $("#slick_change_item");
		slick_change_item.removeClass("hide");
//		var offset = $(this).offset();
		var pos_y= e.pageY;
		var pos_x = e.pageX;
		var offset = $("#col_right").offset();
//		alert(offset.left);
		var max_x = offset.left + 200;
		var max_y = "1750";

//		alert(e.pageX+ ' , ' + e.pageY);
//		alert("left: " + offset.left + ", top: " + offset.top );
//		alert($(this).attr("id"));
		slick_change_item.attr("class","").addClass($(this).attr("id"));

//		pozice dle prvku		
//		zobrazSkinPic($(this).attr("rel"), offset.top, offset.left);
//		pozice dle krysy		
		zobrazSkinPic($(this).attr("rel"), $(this).attr("id"), Math.max(Math.min(pos_y, max_y),295), Math.min(pos_x, max_x));
	});
	$("#slick_change_item").on("click","div.item", function(){
		var pic_old_id = $(this).find("img").attr("rel");
		var pic_new = $(this).find("img");
//		alert(pic_new.attr("src") +"|"+ $(this).attr("id") +"|"+ pic_new.attr("rel") +"|"+ pic_new.attr("rel2"));
//		alert($("#" + pic_old_id).attr("id"));
//		alert($(this).attr("id"));
//		alert(pic_new.attr("rel2") + " | " + $(this).ahelpttr("rel"));
		setPrvekSkin(pic_new.attr("rel2"), $(this).attr("rel"));
		$("#" + pic_old_id).attr("src",pic_new.attr("src"));
//		$("#" + pic_old_id).attr("rel2",$(this).attr("rel"));
	});

})	

/**
* fce na vyber img ze skinu
*/
function zobrazSkinPic(pic, id_img,  pos_top, pos_left) {
	console.log("fce zobrazSkinPic: " + pic);

	var slick_change_item = $("#slick_change_item");
	pos_left = pos_left - 30;

	slick_change_item.empty("");
//	slick_change_item.show("");
//	slick_change_item.fadeIn("");
	slick_change_item.hide();
	slick_change_item.addClass("loading");
	slick_change_item.fadeIn("");
	
	slick_change_item.offset({ top: pos_top-120,  left: pos_left + 25});
//	slick_change_item.hide("");

	var data = "pic="+ pic + "&type=zobrazSkinPic&session_id=" + getSession();
	$.ajax({
		type:'GET',
		url:'./php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log(response);
			if($.trim(response.redirect) == "redirect")  {
				reload_page();
			}
			else {
				slick_change_item.html(response.list, function() {
					slick_change_item.slick({
						infinite: true,
						centerMode: true,
						slidesToShow: 1,
	//					slidesToScroll: 1,
						variableWidth: true
					});
//				slick_change_item.slick('slickPrev');
					slick_change_item.slick('slickGoTo',response.current_index);
				});
	//			slick_change_item.unslick();

				// omezeni maximalni vysky slick-listu
	//			$(".slick-list").css("height","60px");
				// pro srovnani pustim a vratim!!!
	//			slick_change_item.slick('slickPrev');
	//ista1.slickPrev();
				slick_change_item.removeClass("loading");
	//			slick_change_item.show("");
				var height_slick_change_item = $("#slick_change_item").height();
				var width_slick_change_item = $("#slick_change_item").width();
				// vyjimka pozic pro sipky
	/*			
				if(slick_change_item.hasClass("left"))
					pos_left = pos_left - 100;
				if(slick_change_item.hasClass("right"))
					pos_left = pos_left - 350;
	*/			
				slick_change_item.offset({ top: pos_top - height_slick_change_item - 30,  left: pos_left + 25});
//				alert(width_slick_change_item + " | " + height_slick_change_item);
				slick_change_item.after("<div id=\"help_slick\"></div>");
				var help = $("#help_slick");
				help.addClass("show");
				help.offset({ top: pos_top - height_slick_change_item - 30,  left: pos_left + 25 + width_slick_change_item + 70});
				help.removeClass("show");
				help.fadeIn(1000);
	//			slick_change_item.height(height_slick_change_item);
	//			slick_change_item.height(160);
	//			if($.trim(response.redirect) == "redirect") 
	//				window.location.href="./setapp";
			}
		}
	});
}




$(document).mouseup(function (e)
	{
	var container = $("#slick_change_item, #help_slick");

	if (!container.is(e.target) // if the target of the click isn't the container...
	  && container.has(e.target).length === 0) // ... nor a descendant of the container
	{
		slick_change_item_hide();
		container.addClass("hide");
		$(".help_text").fadeOut(1000);
	}
});


/**
* fce nastaveni prvku skinu
*/
function setPrvekSkin(prvek, skin) {
	console.log("fce setPrvekSkin" + prvek);
	var data = "prvek="+ prvek + "&skin="+ skin + "&type=setPrvekSkin&session_id=" + getSession();
//	alert(data);
	$.ajax({
		type:'GET',
		url:'./php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log("setPrvekSkin responce");
			console.log(response);

			if(prvek == "left.png")
				$("#right").attr("src", response.dir_skin + skin + "/right.png");
			if(prvek == "right.png")
				$("#left").attr("src", response.dir_skin + skin + "/left.png");
			slick_change_item_hide();
/*			
			if($.trim(response.redirect) == "redirect")  {
				alert("redir");
				window.location.href="./setapp";
			}
*/				
		}
	});
}

function slick_change_item_hide() {
	$("#slick_change_item").addClass("hide");
	$("#help_slick").remove();
}

/**
* prepinac menu / tema, prvky, nahrano / tema, skiny
*/
$(function() {
	$("#col_left1").on("click","div.set_what:not(.TemaSingle)", function(){
		// pokud cliknu na sama sebe - vracim
		if($(this).hasClass("current")) return;
		if($(this).attr("id") == "set_tema") {
			$("#col_left1 div.set_what, #col_left2 div.set_what").removeClass("current");
//			alert("jaja tema");
//			$("#col_left2 div").removeClass("current");
			$(this).addClass("current");
			$("#col_left2 .vyber:not(#vyber_tema)").fadeOut(100,function() {
				$("#col_left2 #vyber_tema").fadeIn();
			});
		}

		if($(this).attr("id") == "set_prvky") {
			$("#col_left1 div.set_what, #col_left2 div.set_what").removeClass("current");
//			$("#col_left2 div").removeClass("current");
//			alert("jaja prvk");
			$(this).addClass("current");
			$("#col_left2 .vyber:not(#vyber_skin)").fadeOut(100,function() {
				$("#col_left2 #vyber_skin").fadeIn();
			});
		}			

		if($(this).attr("id") == "set_nahrano") {
			if($("body").hasClass("skin-text-photo_setting") && !$("body").hasClass("no-own-photo")) {
				$("#col_left1 div.set_what, #col_left2 div.set_what").removeClass("current");
	//			$("#col_left2 div").removeClass("current");
	//			alert("jaja nahr");
				$(this).addClass("current");
				$("#col_left2 .vyber:not(#vyber_nahrano)").fadeOut(100,function() {
					$("#col_left2 #vyber_nahrano").fadeIn();
				});
			}
		}			


	});
});



function ShowMyName() {
	console.log();
	FB.api("/me",
		function (response) {
			console.log(response);
			alert('Name is ' + response.name);
		});
}

var p_accessToken = "";

/**
* fce na zmenu nazvu FB TABU
*/
function changeFBTabName(page_id, app_id, tab_name) {
//		alert("changeFBTabName");
	
//	access_token =   FB.getAuthResponse()['accessToken'];

	FB.api('/me/accounts', function(response){ 
		  var p_accessToken = response.data[0].access_token; 
		  var p_name = response.data[0].name; 
		  console.log('The pagename is:'
			+ p_name + 'Page access token is' 
			+ p_accessToken);
//		alert("/" + page_id +"/tabs/app_" + app_id + "?custom_name=" + tab_name);
// https://graph.facebook.com/v2.2/259239627467123/tabs/app_1376210906024288?position=3&custom_name=Newtabname&access_token=CAATjqBIwiWABAMLZBYaNsLUFM0r6hrC3yHv72viSw0qxu1faA2SggZCRQYqVAeLTWlQ0CRruCZBrYv1omQuVpUE7YUVVixVo19ZCe7XxoMv0gi9mfYNA3Ic7P2Lg1twMpy5jXEGpam0lMYc4vGl9FsN83Wd1JU
		  FB.api(
//			  "/" + page_id +"/tabs/app_" + app_id + "?position=3&custom_name=test6",
			  "/" + page_id +"/tabs/app_" + app_id + "?custom_name=" + tab_name,
	  //		"/259239627467123/tabs/app_1376210906024288",
	  //		"DELETE",
			  "POST",
			  function (response) {
				  console.log(response);
				  if (response && !response.error) {
				  /* handle the result */
				  }
			  },{access_token:  p_accessToken}
		  );
	});
}


/**
* Updating Objects, re-scrape  POST /?id={object-instance-id or object-url}&scrape=true
* app=1 : 776322872459590
*/
function rescrapeObject(app_id) {
//		alert("changeFBTabName");
	
//	access_token =   FB.getAuthResponse()['accessToken'];

	FB.api('/me/accounts', function(response){ 
		  var p_accessToken = response.data[0].access_token; 
		  var p_name = response.data[0].name; 
		  console.log('The pagename is:'
			+ p_name + 'Page access token is' 
			+ p_accessToken);
//		alert("/" + page_id +"/tabs/app_" + app_id + "?custom_name=" + tab_name);
// https://graph.facebook.com/v2.2/259239627467123/tabs/app_1376210906024288?position=3&custom_name=Newtabname&access_token=CAATjqBIwiWABAMLZBYaNsLUFM0r6hrC3yHv72viSw0qxu1faA2SggZCRQYqVAeLTWlQ0CRruCZBrYv1omQuVpUE7YUVVixVo19ZCe7XxoMv0gi9mfYNA3Ic7P2Lg1twMpy5jXEGpam0lMYc4vGl9FsN83Wd1JU
		  FB.api(
			  "/?id=" + app_id + "&scrape=true",
	  //		"/259239627467123/tabs/app_1376210906024288",
	  //		"DELETE",
			  "POST",
			  function (response) {
				  console.log(response);
				  if (response && !response.error) {
				  /* handle the result */
				  }
//			  },{access_token:  p_accessToken}
			  }
		  );
	});
}


// create a reference to the old `.html()` function
var htmlOriginal = $.fn.html;

// redefine the `.html()` function to accept a callback
$.fn.html = function(html,callback){
  // run the old `.html()` function with the first parameter
  var ret = htmlOriginal.apply(this, arguments);
  // run the callback (if it is defined)
  if(typeof callback == "function"){
	callback();
  }
  // make sure chaining is not broken
  return ret;
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}


// dalsi jako form validator a dalsi ... /web/elearning.tvorba.com/modules/elearning/inc.js 
function setAJAX (url, data, type, fce, close_div, param1, data_type) {
//	alert("run deleteajax");
//	alert(data);
	$.ajax({
		type: type ? type : "GET",
		url: url,
		data: data,
		dataType: data_type ? data_type : 'text', // 'json'
		cache: false,
		success: function(response){
			if(fce) eval(fce + "('" + param1 + "', '" + response + "')");
			console.log(response);
			$(close_div).hide();	
//			result.empty().append(html);
//			stop_preloader();
		}
	});
}

/**
* help u vyberu prvku skinu
*/
var timeoutShowKrokyNextInfo = 5;
$(function() {
	// info text next page
	$("#krok_next").before('<div id="kroky-next-info">' + help_button_next + '<div id="kroky-next-info-sipka"></div></div>');
	setTimeout(function(){
		showKrokyNextInfo();
	}, 500)
	$("#col_right").on("mouseover","#krok_next", function() {
		clearTimeout(timeoutShowKrokyNextInfo);
		showKrokyNextInfo();
	})
	$("#col_right").on("mouseout","#krok_next", function() {
		$("#kroky-next-info").removeClass("show");
	})
})

/**
* help hlavni
*/
var timeout_help_hlavni_text = 0;
$(function() {
	$("#col_right .uprav_app").prepend('<div id="help_hlavni"></div>');
	setTimeout(function(){
		$("#help_hlavni").fadeIn(1000);
	}, 500);
	// hlavni help (krome iframe addtab)
	$("body:not(.pagetab_setting, .pagetab_pay)").on("click","#help_hlavni", function() {
		show_head_help();	
	});
	// hlavni help z iframe addtab!
	$("body.pagetab_setting").on("click","#help_hlavni", function() {
		$("#frameapp").fadeIn();
		$("#help_aplikace_ready").fadeIn();
		set_blur();
		set_widget_overlay("overlay_help");
	});
	// hlavni help z iframe addtab!
	$("body.pagetab_pay").on("click","#help_hlavni", function() {
//		set_blur();
//		set_widget_overlay("overlay_help");
		var data = "type=platba&session_id=" + getSession();
		showPopPLatba(data, $("#loading"));
	});
	// automatic paying
	if($("body.pagetab_pay").length) {
		var data = "type=platba&session_id=" + getSession();
		showPopPLatba(data, $("#loading"));
	}

/*	
	$("#col_right").on("click","#help_hlavni", function() {
		$(this).after('<div id="help_hlavni_text" class="help_text">' + help_hlavni_text + '</div>');
		$("#help_hlavni_text").fadeIn(1000, function() {
			var isHovered = $(this).is(":hover");
			if(!isHovered || !timeout_help_hlavni_text)
				timeout_help_hlavni_text = setTimeout(function(){
					$("#help_hlavni_text").fadeOut(1000);
				}, 15000)
		});
	})
	$("#col_right").on("mouseout","#help_hlavni_text", function() {
		timeout_help_hlavni_text = setTimeout(function(){
			$("#help_hlavni_text").fadeOut(1000);
		}, 2000)
	});
	$("#col_right").on("mouseover","#help_hlavni_text", function() {
		console.log("clear timeout_help_hlavni_text");
		clearTimeout(timeout_help_hlavni_text);
	});
*/	
})

/**
* help slick slider - vyber prvku ze skinu
*/
var timeout_help_slick_text = 0;
$(function() {
	$("#col_right").on("click","#help_slick", function(e) {
		var pos_y= e.pageY;
		var pos_x = e.pageX;
//		alert(pos_y + " | " + pos_x);
		$(this).after('<div id="help_slick_text" class="help_text">' + help_slick_text + '</div>');
		$("#help_slick_text").show().offset({ top: pos_y - 40,  left: pos_x - 70}).hide();
		clearTimeout(timeout_help_slick_text);
		$("#help_slick_text").fadeIn(1000, function() {
			var isHovered = $(this).is(":hover");
			if(!isHovered || !timeout_help_slick_text)
				timeout_help_slick_text = setTimeout(function(){
					$("#help_slick_text").fadeOut(1000);
				}, 4000)
		});
	});
	$("#col_right").on("mouseout","#help_slick_text", function() {
		timeout_help_slick_text = setTimeout(function(){
			$("#help_slick_text").fadeOut(1000);
		}, 2000)
	});
	$("#col_right").on("mouseover","#help_slick_text", function() {
		console.log("clear timeout_help_slick_text");
		clearTimeout(timeout_help_slick_text);
	});
});

/**
* help - select tema / skin
*/
$(function() {
	var i = 1;
//	$( "body:not(.home)" ).prepend( $( '<div id="help_set_tema_text" class="help_text">' + help_help_set_tema_text + '</div>' ) );
//	$( "body:not(.home)" ).prepend( $( '<div id="help_set_skin_text" class="help_text">' + help_help_set_skin_text + '</div>' ) );
	setTimeout(function() {
		$( "#col_left2 h2" ).append( $( '<span class="help_set_tema_skin"></span>' ) );
		var help = $(".help_set_tema_skin");
		var helpshow = setInterval(function(){
	//		window.location = "./step2";
			if(help.hasClass("show")) {
				help.removeClass("show");
			}
			else {
				help.addClass("show");
			}
			i++;
			if(i == 6)
				clearInterval(helpshow);
		}, 300);
	}, 3000);
});

/**
* help slick slider - vyber prvku ze skinu
*/
var timeout_help_set_tema_skin_text = 0;
$(function() {
	$("#col_left2").on("click",".help_set_tema_skin", function(e) {
		show_tema_skin_help();	
	/*
		var pos_y= e.pageY;
		var pos_x = e.pageX;
//		alert(pos_y + " | " + pos_x);
//		alert($(this).closest("div.vyber").attr("id").split('_')[1]);
		var cont_text = $("#help_set_" + $(this).closest("div.vyber").attr("id").split('_')[1] + "_text");
		cont_text.show().offset({ top: pos_y - 40,  left: pos_x + 15}).hide();
		clearTimeout(timeout_help_set_tema_skin_text);
		cont_text.fadeIn(1000, function() {
			var isHovered = $(this).is(":hover");
			if(!isHovered || !timeout_help_set_tema_skin_text)
				timeout_help_set_tema_skin_text = setTimeout(function(){
					cont_text.fadeOut(1000);
				}, 4000)
		});
	*/
	});
	$(document).on("mouseout",".help_set_tema_skin_text", function() {
		var cont_text = $(this);
		timeout_help_set_tema_skin_text = setTimeout(function(){
			cont_text.fadeOut(1000);
		}, 2000)
	});
	$(document).on("mouseover",".help_set_tema_skin_text", function() {
		console.log("clear timeout_help_set_tema_skin_text");
		clearTimeout(timeout_help_set_tema_skin_text);
	});
});

/**
* info text next page
*/
function showKrokyNextInfo() {
	$("#kroky-next-info").addClass("show");
	timeoutShowKrokyNextInfo = setTimeout(function(){
//		window.location = "./step2";
		$("#kroky-next-info").removeClass("show");
	}, 5000);
}

/**
* UI datepicker
*/
var msecsInADay = 86400000;
$(function() {
	// nastaveni platby po reloadu dle posledni platby - po nedokonceni platby - CANCEL, TIMEOUT!
	// naco tady???
//	setPayment();
});




$(function() {
	$("body").on("click", '#PopPlatba input[name=typ_platby], #PopPlatba  input[name=delka_trvani]', function() {
		setPayment();
	});
	$("body").on("click", "#setPayment", function() {
		var druh_platby_detail = "";
		var druh_platby = $(this).attr("rel");
		if(druh_platby == "premium_academy") {
			druh_platby_detail = "premium_academy";
			druh_platby = "premium";
		}
		// platba premium !
		if($(".premium_platba #email").length) {
			if(email_validator($(".premium_platba #email")))
				return false;
			// ulozim email
			else {
				var data = "type=saveContactEmail&druh_platby_detail=" + druh_platby_detail + "&contact_email=" + $("#email").val();
//				alert(data);
				$.ajax({
					type: "GET",
					url:'./php/actions.php',
					data: data,
					dataType: 'json',
					cache: false,
					success: function(response) {
						console.log(response);
						killajaxloading();
						if(response.dbaff < 0)
							alert("E-mail is not saved");
						else if(response.dbaff >= 0) {
//							alert(druh_platby);
							createPayment(druh_platby, druh_platby_detail);
						}

					}
				});
			}
		}
		// platba standard za aplikaci !
		else {
//			alert("standard platba");
			createPayment($(this).attr("rel"));
		}
		return false;
	});
	$("body").on("click", "#godashboard", function() {
		window.location.href = url_redir;
		return false;
	});


	$("body").on("click", "#cont_slev_kupon label", function() {
		$("#cont_slev_kupon #inputs").fadeToggle();
	});
	$("body").on("click", "#cont_slev_kupon button", function() {
		checkSlevKod();
		return false;
	});
});

/*
function priceByDate()
{
	
	var from = $("#from").datepicker('getDate');
	var to = $("#to").datepicker('getDate');
	var month_diff = datediff(from, to, "months");
//	alert(month_diff);
	return price_app[aplikace_typ_id]['MONTH'] * month_diff;
}		
*/

/**
*	vola vytvoreni platby a vyvolani nove platebni brany
*	I:	typ platba {standardni zaplaceni aplikace - default, premium }
*/
function createPayment(druh_platby, druh_platby_detail)
{
	var druh_platby_detail = druh_platby_detail ? druh_platby_detail : "";
	$('#loading-img').show(); //show loading img
	var url = url_redir + "php/gopay.php";
	var data = "";
	// orezu query strings z url
	var return_url = window.location.href.split('?')[0];
	if(druh_platby == "premium")
//		data = "druh_platby=premium&aplikace_id=" + $("#aplikace_id").val() + "&amount=" + $("#amount").val() + "&amount_together=" + $("#amount_together").val() + "&type=gateWayPaypal&delka_trvani=12&typ_platby=MONTH&session_id=" + getSession() + "&return_url=" + return_url + encodeURIComponent("?action=gopay");
		data = "druh_platby=premium&druh_platby_detail=" + druh_platby_detail + "&aplikace_id=" + $("#aplikace_id").val() + "&amount=" + $("#amount").val() + "&amount_together=" + $("#amount_together").val() + "&type=gateWayPaypal&session_id=" + ($("#f_session_id").val() ? $("#f_session_id").val() : getSession()) + "&typ_platby=" + $("input[name=typ_platby]").val() + "&return_url=" + return_url + encodeURIComponent("?action=gopay");
	else
		data = "druh_platby=standard&slev_kupon=" + $("#slev_kupon").val() + "&aplikace_id=" + $("#aplikace_id").val() + "&amount=" + $("#amount").val() + "&amount_together=" + $("#amount_together").val() + "&type=gateWayPaypal&delka_trvani=" + $("input[name=delka_trvani]:checked", "#PopPlatba").val() + "&typ_platby=" + $("input[name=typ_platby]:checked", "#PopPlatba").val() + "&session_id=" + getSession() + "&return_url=" + return_url + encodeURIComponent("?action=gopay");
//	alert("createPayment:" + data);
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		dataType: 'json',
		cache: false,
		success: function(response) {
			console.log(response);
			if(response.session == "expired") {
//				Login(scope, getSession(), "premium");
				window.location.href = url_redir;
				return;
			}
			else if(response.slev_kod == "100%") {
				window.location.href = url_redir + "?paid=success";
//				alert("zaplaceno");
			}
			else if(response.gw_url == null) {
				alert("Aktualne probiha udrzba systemu GoPay.cz. Behem nekolika minut bude system opet dostupny. Dekujeme za pochopeni");
				return;
			}
			else if(response.gw_url) {
				console.log(response.gw_url + " a jedeme Gopay branu!");
				set_payment_gw_url(response);
			}
			else {
				alert(response.state);
			}
		}
	});
}

/**
*	overeni slevoveho kodu
*/
function checkSlevKod()
{
	$("#cont_slev_kupon #inputs").hide();
	$("#sleva_za_kupon").val(0);
	$("#sleva_kupon").val("");
	$("#check_code_result").remove();
	$('#loading-img').show(); //show loading img
	var url = url_redir + "php/actions.php";
	// orezu query strings z url
	var data = "type=checkSlevKod&aplikace_id=" + $("#aplikace_id").val() + "&slev_kupon=" + $("#slev_kupon").val() + "&session_id=" + getSession();
	$.ajax({
		type: "GET",
		url: url,
		data: data,
		dataType: 'json',
		cache: false,
		success: function(response) {
//			console.log(response);
			if(response.state) {
				$("#cont_slev_kupon").append('<div id="check_code_result">' + response.state + '</div>');
				$("#discount_slev_kupon span").text("");
				$("#set_payment_method_cont #discount_slev_kupon").hide();
			}
			else if(response.sleva) {
				$("#cont_slev_kupon").append('<div id="check_code_result">' + response.txt1 + ' "' + response.kod + '". ' + response.txt2 + ' ' + response.sleva + '%</div>');
				$("#sleva_za_kupon").val(response.sleva);
				$("#sleva_kupon").val(response.kod);
				$("#discount_slev_kupon span").text(response.sleva + " %");
				$("#set_payment_method_cont #discount_slev_kupon").show();
			}
			setPayment();
			$('#loading-img').hide(); //show loading img
		}
	});
}


/**
* converts date object to iso yyyy-mm-dd
*/
function isoDate(date)
{
	return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate()
}

function setPayment()
{
	$("#setPayment").hide(); // hide loading img
	var delka_trvani = $("input[name=delka_trvani]:checked", "#PopPlatba").val();
	if(delka_trvani) {
		$("#PopPlatba #loading-img").show(); // hide loading img
		if(delka_trvani > 1) {
			$("#recurrency").fadeIn();
		}
		else
			$("#recurrency").hide();
		// nastavime spravne castky 
		set_payment_method(delka_trvani);

		$("#setPayment").show(); //
		$("#loading-img").hide(); // hide loading img
	}
}

function mysql_month_diff(from, to)
{
	var data = "type=mysql_month_diff&from=" + isoDate(from) + "&to=" + isoDate(to);
	$.ajax({
		type:'GET',
		url:'./../php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log(response);
			return response;
		}
	});
}


/**
* fce nastavi url platebni brany!
* a rovnou u vyvola / submit form
*/
function set_payment_gw_url(response)
{
	$("#gateWayPaypal form").attr("action",response.gw_url);
	$("#loading-img").hide(); // hide loading img
	$("#setPayment").hide(); // hide button setPayment
	$( "#pay_submit" ).trigger( "click" ); // okamzite vyvolani platebni brany - inline!!!
//	$("#gateWayPaypal form").submit(); // okamzite vyvolani platebni brany - pouze extrene
}	

/**
* nastavime spravne castky dle zadanych datumu a zobrazime
*/
function set_payment_method(month_diff)
{
//	alert(month_diff);
	var aplikace_typ_id = $("#aplikace_typ_id").val();
	var price_by_date = price_app[aplikace_typ_id]['MONTH'] * month_diff;
	var price_total_by_date = price_by_date; 
	if($("#sleva_za_kupon").val() > 0)
		price_total_by_date = price_total_by_date - price_total_by_date * $("#sleva_za_kupon").val() / 100;
	$("#set_payment_method_cont #discount").hide();
	$("#set_payment_method_cont #price_monthly").hide();
	// 1) nastaveni standardni castky platby
	if(month_diff == 1) {
		$("#set_payment_method_cont #amount").val(price_total_by_date);
	}
	// 2) platba najednou na 3, 6, 12 mesicu
	else if(month_diff > 1 && $("input[name=typ_platby]:checked", "#PopPlatba").val() == "ALL") {
//		alert(price_app[aplikace_typ_id]['YEAR_DISCOUNT']);
		price_total_by_date = price_app[aplikace_typ_id]['MONTH'] * month_diff * (1 - price_app[aplikace_typ_id][month_diff + 'M_DISCOUNT']);
		if($("#sleva_za_kupon").val() > 0)
			price_total_by_date = price_total_by_date - price_total_by_date * $("#sleva_za_kupon").val() / 100;
		// nastaveni standardni castky platby
		$("#set_payment_method_cont #amount").val(price_total_by_date);
		$("#set_payment_method_cont #discount span").html(price_app[aplikace_typ_id][month_diff + 'M_DISCOUNT'] * 100 + " %");
		$("#set_payment_method_cont #discount").show();
	}
	// 3) platba mesicni (3, 6, 12 mesicu)
	else if(month_diff > 1 && $("input[name=typ_platby]:checked", "#PopPlatba").val() == "MONTH") {
		var price_monthly = price_total_by_date/month_diff;
		// nastaveni standardni castky platby
		$("#set_payment_method_cont #amount").val(price_monthly);
		var price_monthly_display = month_diff + " x " + price_monthly;
		$("#set_payment_method_cont #price_monthly span").html(price_monthly_display);
		$("#set_payment_method_cont #price_monthly").show();
	}
	// nastaveni celkove castky za aplikaci!
	$("#set_payment_method_cont #amount_together").val(price_total_by_date);
	
	
	$("#set_payment_method_cont").hide();
	$("#set_payment_method_cont #price span").html(priceFormat(price_by_date));
	$("#set_payment_method_cont #price_together span").html(priceFormat(price_total_by_date));
	$("#set_payment_method_cont").fadeIn();
	set_widget_overlay_height("overlay_help");
}

function priceFormat(price)
{
	return number_format(price, 0, "", " ");
}

function isValidDate(controlName, format){
  var isValid = true;
  var testdate = false;

  try{
	//  alert(jQuery.datepicker.parseDate(format, jQuery('#' + controlName).val(), null));
	testdate = jQuery.datepicker.parseDate(format, jQuery('#' + controlName).val(), null);
	console.log(testdate);
	console.log(testdate.toISOString());
  }
  catch(error){
	isValid = false;
  }

  return testdate;
}

function datediff(fromDate,toDate,interval) { 
  /*
   * DateFormat month/day/year hh:mm:ss
   * ex.
   * datediff('01/01/2011 12:00:00','01/01/2011 13:30:00','seconds');
   */
  var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7; 
  fromDate = new Date(fromDate); 
  toDate = new Date(toDate); 
  var timediff = toDate - fromDate; 
  if (isNaN(timediff)) return NaN; 
  switch (interval) { 
	case "years": return toDate.getFullYear() - fromDate.getFullYear(); 
	case "months": 
		return Math.floor(timediff/31536000000) * 12 + Math.ceil((timediff % 31536000000)/2628000000);
//		return ((timediff % 31536000000)/2628000000);
//	return  monthDiff(fromDate, toDate);
/*		( toDate.getFullYear() * 12 + toDate.getMonth() ) 
		- 
		( fromDate.getFullYear() * 12 + fromDate.getMonth() ) 
		); 
*/		
	case "weeks"  : return Math.floor(timediff / week); 
	case "days"   : return Math.floor(timediff / day);  
	case "hours"  : return Math.floor(timediff / hour);  
	case "minutes": return Math.floor(timediff / minute); 
	case "seconds": return Math.floor(timediff / second); 
	default: return undefined; 
  } 
}

function monthDiff(start, end) {
//  var tempDate = new Date(start);
  var monthCount = 0;
  while((start.getMonth()+''+start.getFullYear()) != (end.getMonth()+''+end.getFullYear())) {
	monthCount++;
	start.setMonth(start.getMonth()+1);
  }
  return monthCount+1;
}

var loading;
$(function() {
/*
	var switch_app_on_off;
	$("#dashboard .switch-app-on-off").click(function(e) {
		switch_app_on_off = $(this);
		var data = "type=switch_app_on_off&aplikace_id=" + $(this).attr("rel") + "&session_id=" + getSession();
		alert(data);
		var loading = $(this).closest(".aplikace").find(".loading-img");
		loading.show();
		$.ajax({
			type: "GET",
			url:'./php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.spusteno == 1) 
					switch_app_on_off.addClass("on");
				if(response.spusteno == 0)
					switch_app_on_off.removeClass("on");
				$("#app_id_" + switch_app_on_off.attr("rel") + " .stav .val").html(response.stav);
				$("#app_id_" + switch_app_on_off.attr("rel") + " .licence .val").html(response.licence);
				$("#app_id_" + switch_app_on_off.attr("rel") + " .termin .val").html(response.termin);
				loading.hide();
			}
		});
	});
*/
	$(document).on("click","#dashboard .platba", function(e) {
		$("#PopPlatba").remove();
		var loading = $(this).closest(".aplikace").find(".loading-img");
		loading.show();
		var data = "type=platba&aplikace_id=" + $(this).attr("rel")  + "&session_id=" + getSession();
//		alert(data);
		showPopPLatba(data, loading);

	});
});

function showPopPLatba(data, loading)
{
		$.ajax({
			type: "GET",
			url: url_redir + 'php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response){
				console.log(response);

				if(response.session == "expired") {
					window.location.href = url_redir;
					return;
				}
				loading.hide();
//				alert(response.html);
				$("body").prepend(response.html);
				// zvetsim okno, kvuli naslednemu vycentrovani
				$("#PopPlatba").height(550);
				$("#PopPlatba").center();
				$("#PopPlatba").height('auto');
				set_widget_overlay("overlay_help");
	//			result.empty().append(html);
	//			stop_preloader();
			}
		});
}



$(function() {
	$(document).on("click",".close", function() {
		$(this).parent("div").fadeOut();
		if(getUrlVars()["paid"] == "success" && $(this).parent().attr("id") != "PopGratulace") {
			$("#PopPlatba").removeClass("schovat").fadeIn();
			// vyhodim jeste gratulaci z on_demand academy!
			if(window.location.href.indexOf('on_demand') == -1)
				showGratulace();
			killajaxloading();
		}
		else {
			remove_widget_overlay("overlay");
			remove_widget_overlay("overlay_help");
			killajaxloading();
		}
	});

	$(".hlavni_help .close, .hlavni_help button").click(function() {
		$(".hlavni_help").fadeOut();
		remove_widget_overlay("overlay_help");
	});
});

var close_div_after_delete_app;
$(function() {
	$("#dashboard .delete").click(function() {
		if(confirm(are_you_sure_delete_app)) {
			close_div_after_delete_app = $(this).parent("div");
			ajaxloading($(this).parent());
			$.ajax({
				type: "GET",
				url: "php/actions.php",
				data: "type=deleteapp&aplikace_id=" + $(this).attr("rel"),
				dataType: 'json',
				cache: false,
				success: function(response){
					console.log(response);
					afterDeleteApp(close_div_after_delete_app, response)
				}
			});


	    }
	    return false;
	});
});

function afterDeleteApp(close_div, response) {
	if(response.transaction == "commit" && response.aff >=0) {
//		alert("delete ok");
		close_div.fadeOut(function() {
			$(this).remove();
			// osvezeni css, porovnani
			$.each($('#dashboard .aplikace'), function( index, value ) {
				console.log(index + " | " + index%2 + " | " + " | "  + value);
				$(this).removeClass("left right");
				if(index%2 == 0)
					$(this).addClass("left");
				else
					$(this).addClass("right");
			});
		});


	}
	else {
		alert("delete failed");
	}
	killajaxloading();
}

function show_head_help() {
	$("#hlavni_help").center();
	set_widget_overlay("overlay_help");
	$("#hlavni_help").fadeIn();
};

function show_tema_skin_help() {
	$("#tema_skin_help").center();
	set_widget_overlay("overlay_help");
	$("#tema_skin_help").fadeIn();
};


function iframeLoaded() {
	var iframe = $('#frameapp').contents();;
	alert(iframe.find("body").attr("rel"));
	document.getElementById('frameapp').contentWindow.$('#loginfb').trigger('click');
//	iframe.find("#loginfb").trigger('click');

    alert("Iframe loaded!");
}
/*
$(document).ready(function() {
	$('#frameapp').ready(function() {
	window.setTimeout(function(){
		var iframe = $('#frameapp').contents();;
	//	var kocka = $(document).contents().find('iframe').contents().find('#loginfb');
		alert(iframe.find("body").attr("rel"));
	}, 1000);
	});
});
*/

// Here "addEventListener" is for standards-compliant web browsers and "attachEvent" is for IE Browsers.
var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];

$(document).ajaxComplete(function(){
	try{
		FB.XFBML.parse();
	}catch(ex){}
}); 


$(document).ready(function() {
	// Now...
	// if 
	//    "attachEvent", then we need to select "onmessage" as the event. 
	// if 
	//    "addEventListener", then we need to select "message" as the event

	var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

	// Listen to message from child IFrame window
	eventer(messageEvent, function (e) {
//		console.log("messageEvent::::",e.data);
		if(e.data == "unblur") {
			remove_blur();
			remove_widget_overlay("overlay_help");
			$("#frameapp").fadeOut();
		}
		if(e.data == "blur") {
			remove_blur();
			remove_widget_overlay("overlay_help");
			set_blur();
			set_widget_overlay("overlay_help");
			$("#frameapp").fadeIn();
		}
		if(e.data == "next_step") {
			// presmeruji na dalsi krok (platba)
			if($("#krok_next").length) {
				window.location.href = $("#krok_next").attr("href");
			}
			// pokud dalsi krok neni (FREE APP) -> dashboard
			else
				window.location.href = url_redir;
		}
		// aplikace pridana na FB tab
		if(e.data == "addtab_done") {
			// presmeruji na dalsi krok (platba)
			if($("#krok_next").length && $("#krok_next").hasClass("placena")) {
				$("#krok_next").attr("href",$("#krok_next").attr("href") + "?addtab=ok");
			}
			// pokud dalsi krok neni (FREE APP) -> dashboard
//			else
//				window.location.href = url_redir;
		}



		// Do whatever you want to do with the data got from IFrame in Parent form.
		}, false);
});


/**
* user board
*/
var user_board_show;
$(function() {
	$("#user_board #name").click(function() {
		if($(this).hasClass("shown")) {
			$(this).removeClass("shown");
			$("#cont_sprava").slideUp( "slow", function() {
			});
		}
		else {
			$(this).addClass("shown");
			$("#cont_sprava").slideDown( "slow", function() {
			});
		}
		return false;
	});
	$("#user_board").mouseleave(function() {
			user_board_show = setTimeout(function() {
				$("#user_board #name").removeClass("shown");
				$("#cont_sprava").slideUp( "slow", function() {
				});
			}, 3000);
	});			
	$("#user_board").mouseenter(function() {
		clearTimeout(user_board_show);
	});
});

/**
* nabidka all apps
*/
$(function() {
	$(document).on("click","#cont_test_app_info #close_nahled", function() {
		remove_widget_overlay("overlay");
		$("#cont_test_app").fadeOut(function() {
			$(this).remove();
		});
	});
	$("#all_app .app").mouseenter(function() {
		$(this).find(".appinfo").slideDown(400, function() {
		});
	});
	$("#all_app .app, cont_test_app").mouseleave(function() {
		$(this).find(".appinfo").slideUp(400, function() {
		});
	});
	$(document).on("click","#all_app .app:not(.app_nahled), #cont_test_app button", function() {
		if($(this).attr("rel") > 0) {
			window.location = $(this).attr("rel")+"/setapp?aplikace_id_set=new&aplikace_typ_id="+$(this).attr("rel");
			return;
		}
	});
	$("#all_app .app_nahled").click(function() {
		nahled_app($(this).attr("rel"));
	});
	$("#all_app #other_app").click(function() {
		var c = $("#cont_all_app");
		if(c.hasClass("shown")) {
			c.removeClass("shown");
			$("#all_app #other_app .hide").fadeOut("fast",function() {
				$("#all_app #other_app .show").fadeIn();
			});				
		}
		else {
			c.addClass("shown");
			$("#all_app #other_app .show").fadeOut("fast",function() {
				$("#all_app #other_app .hide").fadeIn();
			});				
		}
	});
	/* hlasovani */
	$("#all_app .app").on("click",".vote",function(ev) {
		stopDefault(ev);
//		alert("vote" + $(this).parent().attr("id").split('_')[1]);
		var app_number = $(this).parent().attr("id").split('_')[1];
		ajaxloading($(this).closest("#all_app .app"));
		setAJAX ("php/actions", "type=vote&app_vote=" + app_number + "&session_id=" + getSession(), "GET", "after_vote", "", app_number);
		return false;
	});
	$("#all_app .app").on("click","#voted",function(ev) {
		stopDefault(ev);
		return false;
	});

});

function after_vote(app_number, num_vote) {
	$("#voted").remove();
	$("#app_" + app_number + " .vote").before("<div id='voted'><span>Díky za pomoc!</span> " + casuj("Chtít", num_vote) + " to už " + sklonuj("clovek", num_vote) + "</div>");
	killajaxloading();
//	alert("after_vote" + app_number + ", volilo:" + num_vote);
}

$(function() {
//	if (Modernizr.csstransforms) {
		$.each($('#all_app .name p'), function( index, value ) {
//			console.log($(this).closest("div.app").attr("id"));
			$(this).css({
				'transform': 'translateY(1%)',
				'position' : 'relative',
		//			'left' : '50%',
				'top' : '50%',
		//			'margin-left' : -$('#all_app .name p').outerWidth()/2,
				'margin-top' : -$(this).outerHeight()/2
			});

		});
//	}		
});
	
$(function() {
	if($("#pravidla").length) {
		var str = $("#pravidla").html();
//		alert(str);
//		$("#pravidla").html(str.replace(/(xx+)/gi,'<span class="hl">$1</span>')); 
//		hilight_rules("#pravidla");
//		alert(a);
	}
});

function hilight_rules(id) {
		alert(id);
	  var cont = $(id);
//	  cont.html(cont.html().replace(/<span class="hl">([^<]+)<\/span>/gi,'$1'));
	  cont.html(cont.html().replace(/(xx+)/gi,'<span class="hl">$1</span>'));
}

function resizeIframe(obj) {
	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

/**
* formular kontaktni adresy 
*/
$(function() {
	// odeslani adresy vyherce
	$(document).on("submit", "#f_adress_set",function() {
		var queryString = $(this).formSerialize(); 
		var data = { 'required[]' : [], 'adress[]' : []};
//		var data1 = { 'adress[]' : []};
		$("#f_adress_set input[type=checkbox]").each(function() {
			if (this.checked)
				data['required[]'].push($(this).val());
			else
				data['required[]'].push("off");
		});
		data["type"] = "adress_set";
		$("#f_adress_set input.set_adress").each(function() {
			console.log($(this).val());
			data['adress[]'].push($(this).val());
		});
//		console.log(data);
		// RESET TESTU, DOTAZNIKU
//		setAJAX("php/actions.php", queryString, "GET", "", "#PopAdress");
//		$('#loading-img').show(); //show loading img
		ajaxloading($("#PopAdress"));
		setAJAX("php/actions.php", data, "GET", "reloadShowPopAdress", "#PopAdress", "");
		return false;
	});
	$("#PopAdress").on("click","#addadress", function() {
		var new_id = $("div.set_adress").length - 1;
		var new_id = uniqId();
		$("#PopAdress div.set_adress_new").before("<div class='set_adress set_adress_added'>" + $("div.set_adress_new").html() + "</div>");
		$div_new = $("#PopAdress div.set_adress_added");
//		$div_new.find("#adress_new").attr("id","adress_" + new_id);
		$div_new.find("#req_new").attr("id","req_" + new_id);
		$div_new.find("label").attr("for","req_" + new_id);
//		$div_new.find("#delreq_new").attr("id","delreq_" + new_id);
		$div_new.removeClass("set_adress_new, set_adress_added");
		$div_new.hide();
		$div_new.fadeIn();
//		$("div.set_adress_new").html();
	});
	$("#PopAdress").on("click","span.delreq", function() {
		$(this).parent("div").fadeOut(300, function() { $(this).remove(); });
	});
	$("#adress").click(function(e) {
//		alert($(e.target).attr("id"));
		// vynecham div#setting_adress_title z editovatelnym textem
		if ($(e.target).closest("#setting_adress_title").length) {
			return;
		}
/*		console.log(e.target);
		console.log(e.target.id);
		console.log(e.target.tagName);
*/		
		$("#PopAdress").empty();
		$("#PopAdress").addClass("loading");
		$("#PopAdress").show();
		reloadPopAdress();
		$("#PopAdress").hide();
		$("#PopAdress").fadeIn();
	});
	
});

function uniqId() {
  return Math.round(new Date().getTime() + (Math.random() * 100));
}

/**
* fce load / reload pop okna editace kontakt. adresy
*/
function reloadPopAdress() {
	console.log("fce ");
	var data = "type=reloadPopAdress&session_id=" + getSession();
//	alert(data);
	$.ajax({
		type:'GET',
		url:'./php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log("response reloadPopAdress: " + response);

			if($.trim(response.redirect) == "redirect") 
				logout_reload();
			else {
				$("#PopAdress").html(response.html);
				$("#PopAdress").removeClass("loading");
			}
		}
	});
}



/**
* fce load / reload pop okna zobrazeni kontakt. adresy
*/
function reloadShowPopAdress() {
	console.log("fce ");
	var data = "type=reloadShowPopAdress&session_id=" + getSession();
//	alert(data);
	$.ajax({
		type:'GET',
		url:'./php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log("response reloadShowPopAdress: " + response);
			if($.trim(response.redirect) == "redirect") 
				logout_reload();
			else {
//				alert(response.html);
				$("#adress #form_adress").html(response.html);
				killajaxloading();
//				$("#adress").removeClass("loading");
			}
		}
	});
}


function nahled_app(aplikace_typ_id) {
	var data = "type=nahled_app&aplikace_typ_id=" + aplikace_typ_id + "&session_id=" + getSession();
	$.ajax({
		type:'GET',
		url: url_redir + 'php/actions.php',
		data: data,
		dataType: 'json',
		success: function(response)
		{
			console.log(response);
//			alert("jj");
			if(response.nahled == "no_nahled") {
				console.log("neni nahled!");
			}
			else if(response.session == "expired") {
//				Login(scope, getSession(), "dashboard");
			}
			else {
				$("body").prepend(response.nahled);
				set_widget_overlay("overlay");
			}
		}
	});
}

// Read a page's GET URL variables and return them as an associative array.
function getUrlVars()
{
  var vars = [], hash;
  var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
  for(var i = 0; i < hashes.length; i++)
  {
	hash = hashes[i].split('=');
	vars.push(hash[0]);
	vars[hash[0]] = hash[1];
  }
  return vars;
}

// zavolani 
$(function() {
	if(getUrlVars()["paid"] == "success") {
		$("#PopPlatba").hide();
		nastaveni_fakturace("session_fb_id","paid_success");
	}
});


// zavolani nahledove testovaci aplikace
if(getUrlVars()["try_app"]) {
	nahled_app(getUrlVars()["try_app"]);
}

$(function() {
	if($("body").hasClass("hura")) {
		set_widget_overlay("overlay");
		$("#PopPlatba.hura:not(.schovat)").show();
		$("#PopPlatba.hura").center();
	}
});

$(function() {
	$("#dashboard .title span").click(function() {
		$(this).hide();
		$(".txt_" + $(this).attr("rel")).fadeIn();
	});
	$("#dashboard .title button").click(function() {
//		alert($(".txt_" + $(this).attr("rel")).val());
		var aplikace_id = $(this).attr("rel");
		var data = "type=save_app_title&aplikace_id=" + $(this).attr("rel") + "&title=" + encodeURIComponent($("#txt_" + aplikace_id).val()) + "&session_id=" + getSession();
//		alert(data);
		ajaxloading($(this).parent().parent());
		$.ajax({
			type: "GET",
			url:'./php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.dbaff >= 0) {
					killajaxloading();
					$("#title_" + aplikace_id).text($("#txt_" + aplikace_id).val());
					$(".txt_" + aplikace_id).hide();
					$(".title_" + aplikace_id).fadeIn();
				}
				else
					alert("neulozeno");
			}
		});


	});
});


// validace emailu, pozor trim, takze i v php pri dalsim pouziti!
function email_validator($email, noalert) {
//	var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
//	var re = /[a-z]+@[a-z]+\.[a-z]+/igm;
	var re = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,63})?$/igm;
//	alert("|" + $.trim($email.val()) + "|");
	if ($email.val() == '' || !re.test($.trim($email.val())))
	{
		$email.addClass("red");
		if(!noalert) 
			alert(err_form_zadejte_platny_email);
		return err_form_zadejte_platny_email;
	}
	return false;
}

$(function() {
	$("body").on("click","#set_fakturace .close, #set_lang .close",function() {
//		alert($(this).parent().attr("id"))
		$(this).parent().remove();
	});
	$("body").on("click","#set_fakturace button",function(event) {
		event.preventDefault();
		ajaxloading($("#set_fakturace"));
		var data = $("#set_fakturace form").serialize();
//		alert(data);
		$.ajax({
			type: "GET",
			url: url_redir + 'php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.dbaff >= 0) {
					killajaxloading();
					$("#set_fakturace").fadeOut().remove();
					if(getUrlVars()["paid"] == "success") {
						$("#PopPlatba").removeClass("schovat").fadeIn();
						// sem dat popokno s anim gifem!
						// vyhodim jeste gratulaci z on_demand academy!
						if(window.location.href.indexOf('on_demand') == -1)
							showGratulace();
					}
					else
						remove_widget_overlay("overlay");
				}
				else
					alert("???");
			}
		});

	});
	$("#cont_sprava #nastaveni").click(function() {
		$("#cont_sub_nastaveni").slideToggle();
		return false;
	});
	$("#cont_sprava #nastaveni_lang").click(function() {
		set_widget_overlay("overlay");
		var data = "type=showSetLanguage&session_id=" + getSession();
		ajaxloading($("body"));
		$.ajax({
			type: "GET",
			url: url_redir + 'php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.html) {
					killajaxloading();
					$("body").prepend(response.html);
					$("#set_lang").fadeIn().center();
//					remove_widget_overlay("overlay");
				}
				else
					alert("???");
			}
		});
		return false;
	});

	$("#cont_sprava #nastaveni_fakturace, #admin .edit_fakturace").click(function() {
		nastaveni_fakturace($(this).attr("rel"));
		return false;
	});
	$("#cont_sprava #prehled_fakturace").click(function() {
		prehled_fakturace($(this).attr("rel"));
		return false;
	});



	$("body").on("click","#set_lang a",function() {
		setLanguage($(this).attr("id"));
		return false;
	});
});

// popokno s anim gifem gratulace!
function showGratulace()
{
		var data = "type=showGratulace&session_id=" + getSession();
//		alert(data);
		$.ajax({
			type: "GET",
			url: url_redir + 'php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.session == "expired") {
	//				Login(scope, getSession(), "premium");
					window.location.href = url_redir;
				}
				else if(response.typ == "premium") {
					killajaxloading();
				}
				else if(response.html) {
					$("body").prepend(response.html);
					$("#PopGratulace").show().center();
				}
				else {
					killajaxloading();
					remove_widget_overlay("overlay");
//					alert("???");
				}
			}
		});
}

/**
* otevre edit okno s prehledem faktur v pdf 
*/
function prehled_fakturace(fb_id)
{
		set_widget_overlay("overlay");
		var data = "type=showFaktury&fb_id=" + fb_id + "&session_id=" + getSession();
//		alert(data);
		ajaxloading($("body"));
		$.ajax({
			type: "GET",
			url: url_redir + 'php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.session == "expired") {
	//				Login(scope, getSession(), "premium");
					window.location.href = url_redir;
				}
				else if(response.html) {
					killajaxloading();
					$("body").prepend(response.html);
					$("#show_faktury").fadeIn().center();
//					remove_widget_overlay("overlay");
				}
				else {
					killajaxloading();
					remove_widget_overlay("overlay");
//					alert("???");
				}
			}
		});
}

/**
* otevre edit okno na zadani fakturacnich udaju
*/
function nastaveni_fakturace(fb_id, action)
{
		set_widget_overlay("overlay");
		var data = "type=showSetFakturace&action=" + action  + "&fb_id=" + fb_id + "&session_id=" + getSession();
//		alert(data);
		ajaxloading($("body"));
		$.ajax({
			type: "GET",
			url: url_redir + 'php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.session == "expired") {
	//				Login(scope, getSession(), "premium");
					window.location.href = url_redir;
				}
				else if(response.html) {
					killajaxloading();
					$("body").prepend(response.html);
					$("#set_fakturace").fadeIn().center();
//					remove_widget_overlay("overlay");
				}
				else {
					killajaxloading();
					remove_widget_overlay("overlay");
//					alert("???");
				}
			}
		});
}

function setLanguage(lang) {
	var data = "type=setLanguage&lang=" + lang;
	ajaxloading($("#set_lang"));
	$.ajax({
		type: "GET",
		url: url_redir + '/php/actions.php',
		data: data,
		dataType: 'json',
		cache: false,
		success: function(response) {
			console.log(response);
			if(response.dbaff >= 0) {
				killajaxloading();
				$("#set_lang").fadeOut();
				remove_widget_overlay("overlay");
				// pokud je oprtavdu zmena jazyka
				if(response.dbaff == 1) {
					reload_page();	
				}
			}
			else
				alert("neulozeno");
		}
	});
}

/* academy upis fce */

function change_amount_unie_dph() {
	if($("#form_by_what_premium").length) {
		if($("#form_by_what_premium #name_iso").val() == "SK" && $("#platce_dph").prop("checked")) 
			$("#amount").val(Math.round($("#amount").attr("rel") / 1.2 * 100) / 100);
		else
			$("#amount").val($("#amount").attr("rel"));
	}
}

$(function() {
	// 1. Check formu, pri vyplnenem adresari (napriklad: pokud uz je v databazi nebo zrusil platbu)
	change_amount_unie_dph();

	// 2. Login + checform
	$("#form_by_what_premium #bt_login").click(function(event) {
//		ajaxloading($(this).parent());
		ajaxloading($("#PopPlatba"));

		Login(scope, getSession(), 'on_demand_academy');
		event.preventDefault();
	});

	// doplneni formu, zrusim red border
	$("body").on("change","#form_by_what_premium input", function(event) {
		console.log("f change");
//		console.log(event);
		$(event.target).removeClass("red");
	});
	// zmena statu - kontrola zda SK zmenim cenu bez DPH (cena/1.2)
	$("body").on("change","#form_by_what_premium #name_iso, #form_by_what_premium #platce_dph", function(event) {
		change_amount_unie_dph();
	});

	// submit platebniho formulare - ulozim odberatele a vyvolam platebni branu
	$("body").on("submit","#form_by_what_premium", function(event) {
//		alert("#PopPlatba form submit?" + $(this).serialize());

		var email_err = "";
		// validace formulare
		var err = validateForm("#form_by_what_premium");
//			alert($(this).serialize());
		// validace emailu - formulare
		if(email_err = email_validator($("#f_email"), "noalert"))
			err = err + "\t" + email_err;
		if(err) {
			alert(err);
			killajaxloading();
			return false;
		}

		$("#bt_login").fadeOut();

		$.ajax({
			type: "GET",
			url: url_redir + 'php/actions.php',
			data: $(this).serialize(),
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				killajaxloading();
				if(response.dbaff < 0)
					alert("odberatel not saved");
				else if(response.dbaff >= 0) {
//					createPayment("premium", "premium_academy");
					createPayment("premium", $("#form_by_what_premium input[name='what']").val());
//					killajaxloading();
//					remove_widget_overlay("overlay");
				}

			}
		});
		event.preventDefault();
		return false;
	});
});



/* /academy upis fce */
