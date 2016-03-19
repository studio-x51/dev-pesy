/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
	config.toolbar = 'Full';
//	config.toolbar = 'Basic';
	config.extraPlugins = 'ckeditor-gwf-plugin';

	myFonts = ['Roboto', 'Lato', 'Oswald', 'Slabo 27px', 'Roboto Condensed', 'Lora', 'Open Sans Condensed:300', 'Poiret One', 'Lobster', 'Play', 'Abril Fatface', 'Kaushan Script', 'Russo One', 'Marck Script', 'Great Vibes', 'Audiowide', 'Limelight', 'Sacramento', 'Petit Formal Script', 'Eagle Lake', 'Amita'];
	config.font_names = 'Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS, cursive;Courier New/Courier New, Courier, monospace;Georgia/Georgia, serif;Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;Verdana/Verdana, Geneva, sans-serif';

	for(var i = 0; i<myFonts.length; i++){
		config.font_names = config.font_names+';'+myFonts[i];
		myFonts[i] = 'http://fonts.googleapis.com/css?family='+myFonts[i].replace(' ','+');
	}
	
//	config.contentsCss = '@import url("http://fonts.googleapis.com/css?family=Aclonica|Allan|Allerta");';

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
//		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];
	config.toolbar_Full = [
		{ name: 'basicstyles', items : [ 'Bold','Italic' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'styles', items : [ 'Format','Font','FontSize' ] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'links', items : [ 'Link', 'Unlink'] },
//		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
		'/',
//		{ name: 'paragraph', items : [ 'NumberedList','BulletedList' ] },
//		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
			'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
		{ name: 'tools', items : [ 'Maximize','-','About' ] },
	];
	config.toolbar_Basic =
	[
//		['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']
//		['Bold', 'Italic', '-', 'Link', 'Unlink']
		{ name: 'basicstyles', items : [ 'Bold','Italic'] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'links', items : [ 'Link', 'Unlink'] },
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3';

	config.allowedContent = true; // zanecha vsechny tagy, jinak je maze (napr "span" atd ...)

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

};
