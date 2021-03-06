/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
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

	config.filebrowserBrowseUrl = '/nksoft/plugins/editor/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = '/nksoft/plugins/editor/ckfinder/ckfinder.html?type=Images';
	config.filebrowserFlashBrowseUrl = '/nksoft/plugins/editor/ckfinder/ckfinder.html?type=Flash';
	config.filebrowserUploadUrl = '/nksoft/plugins/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = '/nksoft/plugins/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = '/nksoft/plugins/editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};

