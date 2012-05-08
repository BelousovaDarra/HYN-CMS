<?PHP
if(!defined("HYN")) { exit; }

class loginCP extends moduleCP {
	function name() {
		return _("login");
	}
	function settings() {
		return array(
			"registration"	=> array(
				"default"	=> false,
				"type"		=> "boolean",
				"saveas"	=> "int",
				"validates"	=> "boolean",
				"name"		=> _("registration"),
				"description"
							=> _("when enabled allows visitors to sign up on your website")
			),
			"login"			=> array(
				"default"	=> false,
				"type"		=> "boolean",
				"saveas"	=> "int",
				"validates"	=> "boolean",
				"name"		=> _("log in"),
				"description"
							=> _("when enabled allows visitors to log in with existing username/password")
			),
		);
	}
	function description() {
		return _("this module allows for logging in and registering on your website");
	}
}