<?PHP

# should be configurable themes?
$cpdir				= HYN_PATH_TPL . "cp.brain.lite" . DS;

DOM::set_css( $cpdir . "css" . DS . "reset.css" );

$cssfiles			= scandir( $cpdir . "css" );
if(_v($cssfiles,"array")) {foreach($cssfiles as $css ) {
	if( $css == "reset.css" ) { continue; }
	DOM::set_css( $cpdir . "css" . DS . $css );	
}}

		
DOM::set_js( $cpdir . "js" . DS . "spinner/jquery.mousewheel.js" );
DOM::set_js( $cpdir . "js" . DS . "spinner/ui.spinner.js" );

DOM::set_js( $cpdir . "js" . DS . "fileManager/elfinder.min.js" );

DOM::set_js( $cpdir . "js" . DS . "wysiwyg/jquery.wysiwyg.js" );
DOM::set_js( $cpdir . "js" . DS . "wysiwyg/wysiwyg.image.js" );
DOM::set_js( $cpdir . "js" . DS . "wysiwyg/wysiwyg.link.js" );
DOM::set_js( $cpdir . "js" . DS . "wysiwyg/wysiwyg.table.js" );

DOM::set_js( $cpdir . "js" . DS . "flot/jquery.flot.js" );
DOM::set_js( $cpdir . "js" . DS . "flot/jquery.flot.pie.js" );
DOM::set_js( $cpdir . "js" . DS . "flot/excanvas.min.js" );
DOM::set_js( $cpdir . "js" . DS . "flot/jquery.flot.resize.js" );

DOM::set_js( $cpdir . "js" . DS . "dataTables/jquery.dataTables.js" );
DOM::set_js( $cpdir . "js" . DS . "dataTables/colResizable.min.js" );

DOM::set_js( $cpdir . "js" . DS . "forms/forms.js" );
DOM::set_js( $cpdir . "js" . DS . "forms/autogrowtextarea.js" );
DOM::set_js( $cpdir . "js" . DS . "forms/autotab.js" );
DOM::set_js( $cpdir . "js" . DS . "forms/jquery.validationEngine-en.js" );
DOM::set_js( $cpdir . "js" . DS . "forms/jquery.validationEngine.js" );
DOM::set_js( $cpdir . "js" . DS . "forms/jquery.dualListBox.js" );
DOM::set_js( $cpdir . "js" . DS . "forms/jquery.filestyle.js" );

DOM::set_js( $cpdir . "js" . DS . "colorPicker/colorpicker.js" );

DOM::set_js( $cpdir . "js" . DS . "uploader/plupload.js" );
DOM::set_js( $cpdir . "js" . DS . "uploader/plupload.html5.js" );
DOM::set_js( $cpdir . "js" . DS . "uploader/plupload.html4.js" );
DOM::set_js( $cpdir . "js" . DS . "uploader/jquery.plupload.queue.js" );

DOM::set_js( $cpdir . "js" . DS . "ui/progress.js" );
DOM::set_js( $cpdir . "js" . DS . "ui/jquery.jgrowl.js" );
DOM::set_js( $cpdir . "js" . DS . "ui/jquery.tipsy.js" );
DOM::set_js( $cpdir . "js" . DS . "ui/jquery.alerts.js" );

DOM::set_js( $cpdir . "js" . DS . "wizards/jquery.form.wizard.js" );
DOM::set_js( $cpdir . "js" . DS . "wizards/jquery.validate.js" );
DOM::set_js( $cpdir . "js" . DS . "wizards/jquery.smartWizard.min.js" );

DOM::set_js( $cpdir . "js" . DS . "jBreadCrumb.1.1.js" );
DOM::set_js( $cpdir . "js" . DS . "cal.min.js" );
DOM::set_js( $cpdir . "js" . DS . "jquery.collapsible.min.js" );
DOM::set_js( $cpdir . "js" . DS . "jquery.ToTop.js" );
DOM::set_js( $cpdir . "js" . DS . "jquery.listnav.js" );
DOM::set_js( $cpdir . "js" . DS . "jquery.sourcerer.js" );
DOM::set_js( $cpdir . "js" . DS . "jquery.timeentry.min.js" );
DOM::set_js( $cpdir . "js" . DS . "jquery.prettyPhoto.js" );

DOM::set_js( $cpdir . "js" . DS . "custom.js" );