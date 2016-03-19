/**
* spolecne fce pro SS aplikace
*/

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

/**
* nacte session_id fce je take v js/inc.js pro administraci SS
*/
function getSession() {
	return $("body").attr("rel");
}


//var reload = "";
function Login(scope, session_id, reload, addtab)
{
	console.log('Login fce, session_id=' + session_id);
//	alert("login" + session_id);
	FB.login(function(response) {
		if (response.authResponse) 
		{
			console.log('User logged, reload=' + reload);
//			alert("log");
//			token=response.authResponse.accessToken;
			access_token =   FB.getAuthResponse()['accessToken'];
//			console.log('Access Token = '+ access_token);

			getUserInfo("login", session_id, reload, addtab); // zalozim uivatele
				
//			isFan(access_token); 
		}
		else 
		{
			console.log('User cancelled login or did not fully authorize.');
		}
	 },{scope: scope });
}

var fbid;
function getUserInfo(from, session_id, reload, addtab) {
//	alert("r - " + reload);
	FB.api('/me?fields=email,name,first_name, last_name, gender', function(response) {
		if (response && !response.error_code) {
			console.log("getUserInfoStart:");
			console.log(response);
			fbid = response.id;
			var data = "user="+ response.id + "&firstname="+ response.first_name + "&email="+ response.email + "&gender="+ response.gender + "&lastname=" + (response.last_name ? response.last_name : response.name) + "&type=login&addtab=" + addtab + "&session_id=" + getSession();
//			var data = "user=undefined&firstname="+ response.first_name + "&email="+ response.email + "&gender="+ response.gender + "&lastname=" + (response.last_name ? response.last_name : response.name) + "&type=login&addtab=" + addtab + "&session_id=" + getSession();
//			alert(data);
			$.ajax({
				type:'GET',
				url:'./php/actions.php',
				data: data,
				dataType: 'json',
				success: function(response)
				{
	//				alert("after getUserInfo" + response);
					console.log("getUserInfoEnd");
					console.log(response);
					// schovam button login
//					$("#ciselnik").attr("rel",session_id);
//					if($.trim(response) == "redirect") 
//						window.location.href="./";
//					console.log(getUrlVars());
					var qs = "";
					getUrlVars().forEach(function(key, entry) {
//					    console.log(key + ":" + entry + ":"+  getUrlVars()[key]);
						// preskocim key=http://... (u app trezor!!!)
						if(key.substring(0, 5) == "https") return true;
						if(key == "session_id") {
							qs += key + "=" + response.session_id + "&";
						}
						else {
							qs += key + "=" + getUrlVars()[key] + "&";
						}
					});
//					console.log("qs:" + qs);
//					console.log(window.location.href.split("?")[0]);
					if(addtab) {
						addFBTab();
					}
					else if(reload || response.redirect == "redirect") { 
						// nastavim stejne url, abych zachoval Query String parametry
						var url = window.location.href;   
						var clear_url = window.location.href.split("?")[0];
//						alert(url + "||" + clear_url);
//						console.log("test redirect: reload=" + reload + " | rr=" + response.redirect);
//						console.log(url.indexOf('fc=1'));
						if(url.indexOf('fc=1') == - 1 || getUrlVars()["session_id"] != response.session_id) {
							/*
							if (url.indexOf('?') > -1){
								url += '&fc=1&session_id=' + response.session_id';
							} else {
								url += '?fc=1&session_id=' + response.session_id
							}
							
							window.location.href = url;
							*/
							if(url.indexOf('fc=1') == - 1) {
								qs += "fc=1&";
							}
							if(!getUrlVars()["session_id"])
								qs += 'session_id=' + response.session_id + "&";
//							alert(clear_url + "?" + qs.substr(0,qs.length-1));
							window.location.href = clear_url + "?" + qs.substr(0,qs.length-1);
						}
						else if(aplikace_typ_id == 7) {
	//						alert(aplikace_typ_id);
							console.log("testVyhra 1");
							testVyhra();
						}
						$("#btn_login").hide();
					}
					else if(aplikace_typ_id == 7) {
//						alert(aplikace_typ_id);
						console.log("testVyhra 1");
						testVyhra();
						$("#btn_login").hide();
					}
					else {
						$("#btn_login").hide();
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
* ulozi do FB tabu
*/
var tabs_added = [];
function addFBTab() {
	FB.ui({
		method: 'pagetab'
//		redirect_uri: appURL
	}, function(response){
		console.log("fce addFBTab:");
		console.log(response);
		console.log("/fce addFBTab");
		if(response) {
//			console.log(response.tabs_added.length);
//			console.log(response.tabs_added);
			// vypis objectu
	/*		
			var acc = [];
			$.each(response.tabs_added, function(index, value) {
				acc.push(index + ': ' + value);
				});
			alert(JSON.stringify(acc));
	*/
			
			$.each(response.tabs_added, function( index, value ) {
				// uspesne ulozeno!
				if(value == true) {
					getPageName2(index, "saveFBTab"); // stahne nazev stranky a ulozi do db {saveFBTab(page_id, page_name)}
//					getPageName(index, "saveFBTab"); // stahne nazev stranky a ulozi do db {saveFBTab(page_id, page_name)}
					// posle zpravu do parent (SS), kde se nastavi u dalsiho kroku k url QS "?attab=ok" 
//					window.parent.postMessage("addtab_done", "*");
//					saveFBTab(index);
//					saveFBTab(index, getPageName(index));
					return false;
				}
	//			alert( index + ": " + value );
			});
	//		alert(JSON.stringify(tabs_added));
		}
	})
}

/**
* Ulozi info stranky po vybrani FB stranky pro aplikaci
*/
function saveFBTab(page_id, page_name, page_picture, page_url) {
	var url = "php/actions.php"; // the script where you handle the form input.
	var data = "type=saveFBTab&uid=" + $("#main_addtab").attr("rel") + "&page_id=" + page_id + "&page_name=" + page_name + "&page_url=" + page_url + "&page_picture=" + page_picture + "&session_id=" + $("body").attr("rel");
//		alert(data);
		$.ajax({
			type: "GET",
			url: url,
			data: data, // serializes the form's elements. (type=send_adress as hidden in form id=send_adress!!!)
			success: function(response)
			{
				console.log(response);
				if($.trim(response) == "OK") {
					$("#cont_aplikace_ready").fadeOut();
					$("#cont_aplikace_added").fadeIn();
					setTimeout(function(){
						// namisto automat redirect je tlacitko "Prejit k platbe!"
						window.parent.postMessage("next_step", "*");
					}, 11000);
				}
				else {
				}
			}
		});
}


/**
*	tlacitko "Prejit k platbe!" 
*/
$(function() {
	$("#gotonext").click(function() {
		window.parent.postMessage("next_step", "*");
	});
});	

$(function() {
	$("#feedback").on("click",".smiley", function(){
		$("#feedback form").slideDown();
		$("#feedback #spokojenost").val($(this).attr("rel"));
	});
	$(document).on("submit", "#f_feedback",function() {
		var data = $(this).serialize(); 
		console.log(data);
		setAJAX("php/actions.php", data, "GET", "afterfeedback", "#feedback_cont", "");
		return false;
	});
});

function afterfeedback()
{
	$("#thanks").fadeIn();
}

// dalsi jako form validator a dalsi ... /web/elearning.tvorba.com/modules/elearning/inc.js 
function setAJAX (url, data, type, fce, close_div, param1) {
//	start_preloader();
//	alert("run deleteajax");
//	alert(data);
	$.ajax({
		type: type ? type : "GET",
		url: url,
		data: data,
//		dataType: 'json',
		cache: false,
		success: function(response){
			if(fce) eval(fce + "('" + param1 + "', '" + $.trim(response) + "')");
			console.log(response);
			$(close_div).fadeOut();	
//			result.empty().append(html);
//			stop_preloader();
		}
	});
}


$(function(){
	$('#ss_sign').mouseenter(function() {
	    $("#ss_sign a").fadeIn(200).css("display","block");
	}).mouseleave(function() {
	    $("#ss_sign a").fadeOut(200);
	});
});


$(function(){
	if(getParentUrl()) {
		$("body").css("overflow","hidden");
	}
});	

$(function(){
	$("#dashboard .switch-snow-on-off").click(function() {
			var data = "type=setSnow&aplikace_id=" + $(this).attr("rel")+ "&session_id=" + getSession();
//			var data = "user=undefined&firstname="+ response.first_name + "&email="+ response.email + "&gender="+ response.gender + "&lastname=" + (response.last_name ? response.last_name : response.name) + "&type=login&addtab=" + addtab + "&session_id=" + getSession();
//			alert(data);
			$.ajax({
				type:'GET',
				url: './php/actions.php',
				data: data,
				dataType: 'json',
				success: function(response)
				{
					console.log(response);
					if(response.dbaff) {
						window.location.reload();
					}
				}
			});
	});
});	
