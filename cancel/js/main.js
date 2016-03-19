$(function(){

	var validation = {
		required : function(val) { return !!val; },
		email 	 : function(val) { return val.match( /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/ ); },
	}

	$('form').submit(function(){

		var inputs 	= $(this).find(':input').not(':submit'),
			valid 	= true;

		inputs.removeClass('error');
		inputs.next().hide();

		inputs.each(function(){

			var input 	= $(this),
				value 	= input.val(),
				classes = input.prop('class').split(' ');

			for (i in classes){
				if( validation.hasOwnProperty(classes[i]) && !validation[classes[i]]( value )){
					input.addClass('error');
					input.next().show();
					valid = false;
				}
			}

		});

		return valid;

	});

});