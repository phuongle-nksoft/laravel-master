/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.allowedContent = true;
    config.basicEntities = false;
    config.entities = false;
    config.entities_greek = false;
    config.entities_latin = false;
    config.htmlEncodeOutput = false;
    config.entities_processNumerical = false;

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	config.filebrowserBrowseUrl = '/backend/editor/ckfinder/ckfinder.html';
   	config.filebrowserImageBrowseUrl = '/backend/editor/ckfinder/ckfinder.html?type=Images';
   	config.filebrowserFlashBrowseUrl = '/backend/editor/ckfinder/ckfinder.html?type=Flash';
   	config.filebrowserUploadUrl = '/backend/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
   	config.filebrowserImageUploadUrl = '/backend/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
   	config.filebrowserFlashUploadUrl = '/backend/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};

