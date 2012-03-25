<?PHP
if(!defined("HYN")) { exit; }

function thumb($filename, $destination, $th_width, $th_height, $forcefill=false,$type="png")
{   
   list($width, $height) = getimagesize($filename);
	$imgInfo = getimagesize($filename);
	switch ($imgInfo[2]) {
		case 1: $source = imagecreatefromgif($filename); break;
		case 2: $source = imagecreatefromjpeg($filename); break;
		case 3: $source = imagecreatefrompng($filename); break;
		#default: trigger_error('Tipo de imagen no reconocido.', E_USER_WARNING); break;
	}

   if(	($th_width > 0 && $width > $th_width) || 
		 ($th_height > 0 && $height > $th_height)) {
	  $a = $th_width/$th_height;
	  $b = $width/$height;

	  if(($a > $b)^$forcefill)
	  {
		 $src_rect_width  = $a * $height;
		 $src_rect_height = $height;
		 if(!$forcefill)
		 {
			$src_rect_width = $width;
			$th_width = $th_height/$height*$width;
		 }
	  }
	  else
	  {
		 $src_rect_height = $width/$a;
		 $src_rect_width  = $width;
		 if(!$forcefill)
		 {
			$src_rect_height = $height;
			$th_height = $th_width/$width*$height;
		 }
	  }

	  $src_rect_xoffset = ($width - $src_rect_width)/2*intval($forcefill);
	  $src_rect_yoffset = ($height - $src_rect_height)/2*intval($forcefill);

	 if(!$thumb  = imagecreatetruecolor($th_width, $th_height)) {
		echo "could not create true color";
		exit;
	 }
	 
            $sharpenMatrix = array 
            ( 
                array(-1.2, -1, -1.2), 
                array(-1, 20, -1), 
                array(-1.2, -1, -1.2) 
            ); 

            // calculate the sharpen divisor 
            $divisor = array_sum(array_map('array_sum', $sharpenMatrix));            

            $offset = 0; 
	 
	imageconvolution( $thumb , $sharpenMatrix , $divisor , $offset );
	if ( ($imgInfo[2] == IMAGETYPE_GIF) || ($imgInfo[2] == IMAGETYPE_PNG) ) {
		$trnprt_indx = imagecolortransparent($source);
		// If we have a specific transparent color
		if ($trnprt_indx >= 0) {
			// Get the original image's transparent color's RGB values
			$trnprt_color  = imagecolorsforindex($source, $trnprt_indx);
			// Allocate the same color in the new image resource
			$trnprt_indx    = imagecolorallocate($thumb, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
	
			// Completely fill the background of the new image with allocated color.
			imagefill($thumb, 0, 0, $trnprt_indx);
	
			// Set the background color for new image to transparent
			imagecolortransparent($thumb, $trnprt_indx);
		} elseif ($imgInfo[2] == IMAGETYPE_PNG) {
	
			// Turn off transparency blending (temporarily)
			imagealphablending($thumb, false);
	
			// Create a new transparent color for image
			$color = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
	
			// Completely fill the background of the new image with allocated color.
			imagefill($thumb, 0, 0, $color);
	
			// Restore transparency blending
			imagesavealpha($thumb, true);
		}
	}
	 
	 if(! imagecopyresized($thumb, $source, 0, 0, $src_rect_xoffset, $src_rect_yoffset, $th_width, $th_height, $src_rect_width, $src_rect_height)) {
		echo "could not copy resize";
		exit;
	 }
		if($type == "png") {
			if(!imagepng($thumb,$destination)) {
				$err = "1";
			}
		} elseif($type == "gif") {
			if(!imagegif($thumb,$destination)) {
				$err = "2";
			}
		} elseif($type == "jpg") {
			if(!imagejpeg($thumb,$destination)) {
				$err = "3";
			}
		}
   } else {
#		$thumb = imagecreatetruecolor($width,$height);
#		imagecopyresized($thumb, $source, 0, 0, 0, 0, $width, $height, $width, $height);
		if($type == "png") {
			if(!imagepng($source,$destination)) {
				$err = "4";
			}
		} elseif($type == "gif") {
			if(!imagegif($source,$destination)) {
				$err = "5";
			}
		} elseif($type == "jpg") {
			if(!imagejpeg($source,$destination)) {
				$err = "6";
			}
		}
   }
   if($err) {
		return "Could not create image ".$err;
   }
}