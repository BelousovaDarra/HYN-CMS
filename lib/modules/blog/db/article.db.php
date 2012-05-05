<?PHP

if(!defined("HYN")) { exit; }

class blogArticle_ extends ModuleRecord {
	protected static function _db_table()
	{
		return "m_blog_article";
	}
	protected static function _db_columns()
	{
		return array(
			"id"			=> "integer",
			"title"			=> "string",
			"content"		=> "string",
			"tags"			=> "string",
			"by"			=> "string",
			"created"		=> "date",
		);
	}
}

ModuleRecord::register( "blogArticle" );