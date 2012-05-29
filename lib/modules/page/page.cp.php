<?PHP
if(!defined("HYN")) { exit; }


class pageCP extends moduleCP {
	function name() {
		return _("page");
	}
	function description() {

		return _(sprintf("create information pages, allow editing inline with an advanced WYSIWYG editor"));
	}
	function settings() {
		return;
	}
	function blocks() {

		return array(
			array(
				"title"				=> _("list of current pages"),
				"template"			=> filefind("page.cp.block.list","page"),
				"pages"				=> pages::find_all()
			)
		);
	}
}