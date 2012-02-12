<?PHP
if( !defined("HYN")) { exit; }


class Twig_Hyn_Loader implements Twig_LoaderInterface {
    public function getSource($name)
    {
	if( $f	= filefind( $name )) {
		return file_get_contents($f);
	}
	$r	= routing::get_instance();
	if( isset( $r -> called ) && $f	= filefind( $name , $r -> called )) {
		return file_get_contents( $f );
	}
	throw new Twig_Error_Loader(sprintf('Template "%s" could not be found.', $name));
    }

    public function getCacheKey($name)
    {
      return $name;
    }

    public function isFresh($name, $time)
    {
      return false;
    }
}