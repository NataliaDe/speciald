/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {

//
//config.plugins =
//		'about,' +
//		'a11yhelp,' +
//		'basicstyles,' +
//
//		'colorbutton,' +
//
//		'contextmenu,' +
//
//		'elementspath,' +
//		'enterkey,' +
//		'entities,' +
//		'filebrowser,' +
//
//		'floatingspace,' +
//
//		'format,' +
//
//		'horizontalrule,' +
//		'htmlwriter,' +
//		'image,' +
//
//
//
//		'link,' +
//		'list,' +
//
//		'magicline,' +
//		'maximize,' +
//
//
//
//
//
//		'pastetext,' +
//
//
//		'removeformat,' +
//		'resize,' +
//
//
//		'showborders,' +
//
//		'sourcearea,' +
//		'specialchar,' +
//		'stylescombo,' +
//		'tab,' +
//		'table,' +
//		'tableselection,' +
//		'tabletools,' +
//
//		'toolbar,' +
//		'undo,' +
//		'uploadimage,' +
//		'wysiwygarea';


	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
//		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
//                { name: 'document', groups: [  'document', 'doctools' ] },
//		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
//		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
//		{ name: 'forms', groups: [ 'forms' ] },
		'/',
//		{ name: 'links', groups: [ 'links' ] },
//		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
//		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
//		{ name: 'insert', groups: [ 'insert','mode' ] },
//		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] },
	];

	config.filebrowserUploadUrl = '/teacher/dashboard/loadApi/ckeditorImage';




	config.removeButtons = 'Source,Save,Templates,NewPage,Preview,Print,Cut,Undo,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,HiddenField,Replace,Redo,Copy,Paste,PasteText,PasteFromWord,Underline,Subscript,Superscript,CopyFormatting,CreateDiv,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,Language,BidiRtl,BidiLtr,Anchor,Flash,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,Format,Font,FontSize,Maximize,ShowBlocks,About';

                      config.extraPlugins='colorbutton';

                      config.removePlugins = 'elementspath';

                      //config.colorButton_colors = 'ff3b3b, CF5D4E,454545,FFF,DDD,CCEAEE,66AB16';

};


