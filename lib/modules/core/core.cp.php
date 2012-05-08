<?PHP
if(!defined("HYN")) { exit; }

class coreCP extends moduleCP {
	function name() {
		return _("core");
	}
	function settings() {
		return;
	}
	function description() {
		return _("core website module");
	}
}