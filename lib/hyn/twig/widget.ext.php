<?PHP

if( !defined("HYN")) { exit; }

class HYN_Widget_TokenParser extends Twig_TokenParser {
	public function parse( Twig_Token $token ) {
		$lineno		= $token -> getLine();
		$widget		= $this -> parser -> getExpressionParser() -> parseExpression();
		
		$this -> parser -> getStream() -> expect( Twig_Token::BLOCK_END_TYPE );
		
		return new HYN_Widget_Node( $widget , $lineno , $this -> getTag() );
	}
	public function getTag() {
		return "widget";
	}
}

class HYN_Widget_Node extends Twig_Node {
	public function __construct( Twig_Node_Expression $widget , $lineno , $tag=NULL ) {

	}
	public function compile( Twig_Compiler $compiler ) {
		$compiler
				-> addDebugInfo( $this )
				-> write( '' );
	}
}