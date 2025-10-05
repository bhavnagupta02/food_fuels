<?php
/** 
*  Project : FoodFuels
*  Creation Date : 26-06-2015 
*  Description : This is Common component which is used for common functions.
*/
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Network\Email\Email;
use Cake\ORM\TableRegistry;


class UploaderComponent extends Component
{
	public $name = 'Uploader';

/**
 * property that will be used to store uploaded file name(path).
  * @var string
 */
	public $newfile_path;

/**
 * property to manage error in uploading.
 * @var boolean
 */
	public $error = false;

/**
 * property that will be store error message.
 * @var string
 */
	public $errorMessage;
/**
 * property to allow the file uplaoding extension.
 * @var array
 */
	private $allow_extensions = array('jpg', 'jpeg', 'png', 'gif');
/**
 * property image source dir path.
 * @var string
 */
	private $source_dir;

/**
 * property image destination dir path.
 * @var string
 */
	private $destination_dir;
/**
 * property thumbnails directory path.
 * @var string
 */
	private $thumb_dir;

/**
 * property that will be used to allow to create thumbnails.
 * @var array
 */
	private $thumb = array(
				array(
					'width'=>39,
					'height'=>39,
					'suffix' => 'S_'
				),
				array(
					'width'=>71,
					'height'=>71,
					'suffix' => 'M_'
				),
				array(
					'width'=>155,
					'height'=>155,
					'suffix' => 'L_'
				)
	);

/**
 * upload the image
 * @param array $files, post uplaoded file
 * @param string $destination_dir, directory path where images will be upload
 * @param $thumb boolean, create thumb default true
 * @param array $params, that contains create thumb attributes
 * @param $save_filename (string), file
 * return uploaded file name
 */
 public function upload($file, $destination_dir, $user_thumb = false, $save_filename = '', $params = array(), $size_dimensions = array('width'=>50, 'height'=>50), $resize_image = false)
 {
	if ( !empty($file) )
	{
		$ext = explode('.', $file['name']) ;
		$ext = end($ext);
		$ext = strtolower($ext);
		if($file['error'] == 0){
			if (in_array($ext, $this->allow_extensions ))
			{
				//Image width validation
				$file_info = getimagesize($file['tmp_name']);
				if ($file_info['0'] < $size_dimensions['width'] || $file_info['1'] < $size_dimensions['height'])
				{
					$this->error = true;
					$this->errorMessage =  __('Image width and height should not be less than '.$size_dimensions['width'].' * '.$size_dimensions['height']);
					return false;
				}
				else if ($resize_image)
				{
					if ($file_info['0'] > $resize_image['width'] || $file_info['1'] > $resize_image['width'])
					{
						$resize_image['suffix'] = '';
						$user_thumb = array($resize_image);
					}
				}
				if ( isset($params['size']))
				{
					$image_size_in_bytes = $file['size'];
					$image_size_in_kb = round($image_size_in_bytes/1024);

					if($image_size_in_kb > $params['size'])
					{
						$this->error = true;
						$this->errorMessage =  __('Image size should be less than '.round($params['size']/1024).' MB');
						return false;
					}
				}

				if ( empty($this->filename) )
				{
					if (! empty($save_filename) )
					{
						$file_name = $save_filename . '.'.$ext;
					}
					else
					{
						$file_name = uniqid() . '.' . $ext;
					}
				}
				else
				{
					$file_name = $this->filename;
				}

				$upload_file = $destination_dir . $file_name;

				if ( is_array($file) )
				{
					$source_file_path = $file['tmp_name'];
				}
				else
				{
					$source_file_path = $file;
				}

				if(file_exists($source_file_path) && is_writable($source_file_path)) {
					if ( move_uploaded_file( $source_file_path, $upload_file) )
					{
						//Create thumbnails from uploaded image

						if ( $user_thumb )
						{
							foreach ( $user_thumb as $thumb_img)
							{
								$src_file = $upload_file;
								$newfile_path = $destination_dir. $thumb_img['suffix'] . $file_name;
								$new_w =  $thumb_img['width'];
								$new_h =  $thumb_img['height'];
								$this->createthumb($src_file, $newfile_path, $new_w,$new_h);
							}
						}
						return $this->filename = $file_name;
					}
					else
					{
						$this->error = true;
						$this->errorMessage =  __('Some error occur while uploading file.');
					}
				}
				else
				{
					$this->error = true;
					$this->errorMessage =  __('Upload directory is not writable, or does not exist.');
				}
			}
			else
			{
				$this->error = true;
				$this->errorMessage =  __('Please check the format of your photo and try again. We support these photo formats: JPG, GIF and PNG.');
			}	
		}
		else{
			$this->error = true;
			$this->errorMessage =  __('Server did not allowed this image file. Please reduce image size and try again.');
		}
		
	}
	else
	{
		$this->error = true;
		$this->errorMessage = __('File not uploaded');
	}
 }
/**
 * Crop the image
 * @params array $filedata source and destination files
 * @params array $file_dimension crop image dimensions
 * @params $is_thumb boolean, create thumb default true
 * @params array $params thumbnail info
 * return (string) filename;
 */
	public function crop( $filedata, $file_dimension, $is_thumb = true, $params = array())
	{
		$targ_w =  $file_dimension['width'];
		$targ_h =  $file_dimension['height'];
		$quality = 95;

		$src = $filedata['source_path'];
		$newfile_path = $filedata['dest_dir'] . $filedata['file_name'];

		$system = explode(".",$src);
		$extension = end($system);

		if (preg_match("/jpg|jpeg/i",$extension))
		{
			$src_img = @imagecreatefromjpeg($src);
		}
		if (preg_match("/png/i",$extension))
		{
			$src_img = @imagecreatefrompng($src);
		}
		if (preg_match("/gif/i",$extension))
		{
			$src_img = @imagecreatefromgif($src);
		}
		if (preg_match("/bmp/i",$extension))
		{
			$src_img = @imagecreatefromwbmp($src);
		}
		
		if (!empty($src_img) && isset($file_dimension['rotate']) && is_numeric($file_dimension['rotate']) && $file_dimension['rotate'] != 0) {
          // PHP's degrees is opposite to CSS's degrees

		  $degrees = $file_dimension['rotate'];
          $new_img = imagerotate( $src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127) );
          imagedestroy($src_img);
          $src_img = $new_img;
          $deg = abs($degrees) % 180;
          $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
          $src_img_w = $targ_w * cos($arc) + $targ_h * sin($arc);
          $src_img_h = $targ_w * sin($arc) + $targ_w * cos($arc);
          // Fix rotated image miss 1px issue when degrees < 0
          $src_img_w -= 1;
          $src_img_h -= 1;
        }

		$dst_img = @ImageCreateTrueColor( $targ_w, $targ_h );

		@imagecopyresampled($dst_img, $src_img, 0,0, $file_dimension['x'], $file_dimension['y'], $targ_w,$targ_h, $file_dimension['width'], $file_dimension['height']);

		//header('Content-type: image/jpeg');
		//imagejpeg($dst_img,null,$quality);

		$dst_img = @imagecreatetruecolor($targ_w,$targ_h);

		if(preg_match("/png/i",$extension))
		{
			// Turn off transparency blending (temporarily)
			@imagealphablending($dst_img, false);
			// Create a new transparent color for image
			$color = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
			// Completely fill the background of the new image with allocated color.
			@imagefill($dst_img, 0, 0, $color);
			// Restore transparency blending
			@imagesavealpha($dst_img, true);
		}
		else
		{

			$trnprt_indx = @imagecolortransparent($src_img);

		// If we have a specific transparent color
			if ($trnprt_indx >= 0)
			{
			// Get the original image's transparent color's RGB values
				$trnprt_color    = @imagecolorsforindex($src_img, $trnprt_indx);
				// Allocate the same color in the new image resource
				$trnprt_indx    = @imagecolorallocate($dst_img, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				// Completely fill the background of the new image with allocated color.
				@imagefill($dst_img, 0, 0, $trnprt_indx);
				// Set the background color for new image to transparent
				@imagecolortransparent($dst_img, $trnprt_indx);
			}
		}
		@imagecopyresampled($dst_img, $src_img, 0,0, $file_dimension['x'], $file_dimension['y'], $targ_w,$targ_h, $file_dimension['width'], $file_dimension['height']);

		if (preg_match("/png/i", $extension))
		{
			@imagepng($dst_img, $newfile_path);
		}
		else if(preg_match("/gif/i",$extension))
		{
			@imagegif($dst_img, $newfile_path);
		}
		else
		{
			@imagejpeg($dst_img, $newfile_path, $quality); // Quality setting 95
		}
		@imagedestroy($dst_img);
		@imagedestroy($src_img);

		// Remove source file
		if ( (isset($params) && ($params['remove'] == true)) && file_exists( $filedata['source_path'] ) )
		{
			unlink($filedata['source_path']);
		}

		//Create thumbnails
		if ($is_thumb)
		{
			if (isset($params['thumb']))
			{
				$this->thumb = $params['thumb'];
			}
			foreach ( $this->thumb as $thumb_img)
			{
				$src_file = $filedata['dest_dir'] .  $filedata['file_name'];
				$newfile_path = $filedata['dest_dir'] . $thumb_img['suffix'] . $filedata['file_name'];
				$new_w =  $thumb_img['width'];
				$new_h =  $thumb_img['height'];
				$this->createthumb($src_file, $newfile_path, $new_w, $new_h);
			}
		}
		 $this->filename = $filedata['file_name'];
		return $this->filename;
	}
/**
 * createthumb : use to create the thumbnails
 * @params string $name source file path
 * @params strind $newfile_path creating file path
 * @params int $new_w width in pixcel
 * @params int $new_h height of the image in pixcel
 * return
 */

	function createthumb($name, $newfile_path, $new_w, $new_h)
	{

		$system = explode(".",$name);
		$extension = end($system);

		if (preg_match("/jpg|jpeg/i",$extension))
		{
			$src_img = imagecreatefromjpeg($name);
		}
		if (preg_match("/png/i",$extension))
		{
			$src_img = imagecreatefrompng($name);
		}
		if (preg_match("/gif/i",$extension))
		{
			$src_img = imagecreatefromgif($name);
		}
		if (preg_match("/bmp/i",$extension))
		{
			$src_img = imagecreatefromwbmp($name);
		}
		$old_x = imageSX($src_img);
		$old_y = imageSY($src_img);

		$vertical_shrink_ratio = $old_x > $new_w ? $new_w/ $old_x : 1;
		$horizontal_shrink_ratio = $old_y > $new_h ? $new_h / $old_y : 1;
		$shrink_ratio = min($vertical_shrink_ratio, $horizontal_shrink_ratio);
		/*if($old_x > $old_y)
		{
			$thumb_w = $new_w;
			$thumb_h = $old_y * $shrink_ratio;
		}
		else
		{
			$thumb_w = $old_x * $shrink_ratio;
			$thumb_h = $new_h;
		}*/

		$thumb_w = $old_x * $shrink_ratio;
		$thumb_h = $old_y * $shrink_ratio;
		$dst_img = imagecreatetruecolor($thumb_w,$thumb_h);

		if(preg_match("/png/i",$extension))
		{
			// Turn off transparency blending (temporarily)
			imagealphablending($dst_img, false);
			// Create a new transparent color for image
			$color = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
			// Completely fill the background of the new image with allocated color.
			imagefill($dst_img, 0, 0, $color);
			// Restore transparency blending
			imagesavealpha($dst_img, true);
		}
		else
		{

			$trnprt_indx = imagecolortransparent($src_img);

		// If we have a specific transparent color
			if ($trnprt_indx >= 0)
			{
			// Get the original image's transparent color's RGB values
			$trnprt_color    = imagecolorsforindex($src_img, $trnprt_indx);
			// Allocate the same color in the new image resource
			$trnprt_indx    = imagecolorallocate($dst_img, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			// Completely fill the background of the new image with allocated color.
			imagefill($dst_img, 0, 0, $trnprt_indx);
			// Set the background color for new image to transparent
			imagecolortransparent($dst_img, $trnprt_indx);
			}
		}
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		if (preg_match("/png/i",$extension))
		{
			imagepng($dst_img,$newfile_path);
		}
		else if(preg_match("/gif/i",$extension))
		{
			imagegif($dst_img,$newfile_path);
		}
		else
		{
			imagejpeg($dst_img,$newfile_path,95); // Quality setting 95
		}
		imagedestroy($dst_img);
		imagedestroy($src_img);
	}

	function createthumb_new($name, $newfile_path, $new_w, $new_h)
	{

		$system = explode(".",$name);
		$extension = end($system);

		if (preg_match("/jpg|jpeg/i",$extension))
		{
			$src_img = imagecreatefromjpeg($name);
		}
		if (preg_match("/png/i",$extension))
		{
			$src_img = imagecreatefrompng($name);
		}
		if (preg_match("/gif/i",$extension))
		{
			$src_img = imagecreatefromgif($name);
		}
		if (preg_match("/bmp/i",$extension))
		{
			$src_img = imagecreatefromwbmp($name);
		}
		$old_x = imageSX($src_img);
		$old_y = imageSY($src_img);

		$dst_img = imagecreatetruecolor($new_w, $new_h);

		if(preg_match("/png/i",$extension))
		{
			// Turn off transparency blending (temporarily)
			imagealphablending($dst_img, false);
			// Create a new transparent color for image
			$color = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
			// Completely fill the background of the new image with allocated color.
			imagefill($dst_img, 0, 0, $color);
			// Restore transparency blending
			imagesavealpha($dst_img, true);
		}
		else
		{

			$trnprt_indx = imagecolortransparent($src_img);

		// If we have a specific transparent color
			if ($trnprt_indx >= 0)
			{
			// Get the original image's transparent color's RGB values
			$trnprt_color    = imagecolorsforindex($src_img, $trnprt_indx);
			// Allocate the same color in the new image resource
			$trnprt_indx    = imagecolorallocate($dst_img, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			// Completely fill the background of the new image with allocated color.
			imagefill($dst_img, 0, 0, $trnprt_indx);
			// Set the background color for new image to transparent
			imagecolortransparent($dst_img, $trnprt_indx);
			}
		}
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,$old_x,$old_y);
		if (preg_match("/png/i",$extension))
		{
			imagepng($dst_img,$newfile_path);
		}
		else if(preg_match("/gif/i",$extension))
		{
			imagegif($dst_img,$newfile_path);
		}
		else
		{
			imagejpeg($dst_img,$newfile_path,95); // Quality setting 95
		}
		imagedestroy($dst_img);
		imagedestroy($src_img);
	}
/**
 * remove : remove the files
 * @params string $filename
 * @params strind $dir_path directory path form file has to be remove
 * return : null
 */
 public function remove( $filename, $dir_path)
 {
	$file_path = $dir_path .  $filename;
	if ( file_exists($file_path) )
	{
		unlink($file_path);
	}
	foreach ( $this->thumb as $thumb_img)
	{
		$file_path = $dir_path. $thumb_img['suffix'] . $filename;
		if ( file_exists($file_path) )
		{
			unlink($file_path);
		}
	}
 }
}
