// Optimizely Custom Event Goals

var facebookCallbackInterval = setInterval(function() {

	if ($(".fb-like iframe[src*='facebook.com']").length > 0) {

		// Triggers the 'fb_like' Custom Event Goal when 'Like' is clicked 
		FB.Event.subscribe('edge.create', function(url, html_element) {
				window['optimizely'] = window['optimizely'] || [];
				window.optimizely.push(["trackEvent", "fb_like"]);
		});

		// Triggers the 'fb_unLike' Custom Event Goal when the 'Like' is removed
		FB.Event.subscribe('edge.remove', function(url, html_element) {
				window['optimizely'] = window['optimizely'] || [];
				window.optimizely.push(["trackEvent", "fb_unLike"]);
		});

		// Clears the Interval
		clearInterval(facebookCallbackInterval);
	} 

}, 1000);

// Optional placeholder for Optimizely Editor - this won't be added to your live site

/* _optimizely_evaluate=editor_only */
var likeEditorPlaceholder = setInterval(function() {
	if ($(".fb-like").length > 0) {
		$(".fb-like").addClass("likeEditorPlaceholder");
		$(".likeEditorPlaceholder").css({"display":"inline-block","background-color":"none","width":"0","height":"0","border-top":"7px solid #384CA6","border-left":"7px solid #384CA6","border-right":"7px solid transparent","border-bottom":"7px solid transparent"});
	clearInterval(likeEditorPlaceholder);
	}
}, 50);
/* _optimizely_evaluate=end_editor_only */