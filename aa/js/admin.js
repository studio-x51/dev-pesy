$(function() {
	$("#admin .adminDashboard").click(function(e) {
		e.preventDefault();
		var data = "type=adminDashboard&aplikace_id=" + $(this).attr("rel") + "&session_id=" + getSession();
//		alert(data);
		ajaxloading($("#dashboard"));
		$.ajax({
			type: "GET",
			url: '../php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.session == "expired") {
					window.location.href = url_redir;
				}
				$("#dashboard .aplikace").html(response.html);
				$('html, body').animate({
			        scrollTop: $("#dashboard").offset().top - 60
			    }, 500);
				killajaxloading();

//				loading.hide();
			}
		});
	});
});

$(function() {
	$("#admin .change_status_owner").change(function(e) {
		e.preventDefault();
		var data = "type=changeStatusOwner&fb_id=" + $(this).attr("rel") + "&status=" + $(this).val() + "&session_id=" + getSession();
		var tr_cont = $(this).closest("tr");
//		alert(data);
		$.ajax({
			type: "GET",
			url: '../php/actions.php',
			data: data,
			dataType: 'json',
			cache: false,
			success: function(response) {
				console.log(response);
				if(response.session == "expired") {
					window.location.href = url_redir;
				}
//				$("#dashboard .aplikace").html(response.html);
				if(response.dbaff == 1) {
//					alert(tr_cont.attr("class"));
					tr_cont.removeClass("status_end status_active status_pending status_refund");
					tr_cont.addClass("status_" + response.status);
				}

//				loading.hide();
			}
		});
//		alert("baf");
	});
});
