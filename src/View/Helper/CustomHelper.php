<?php
/**
 * Common helper for application
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * Name		: CommonHelper class
 * Author 	: Praveen Pandey
 * Created 	: 19 Nov, 2014 
 */

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\TimeHelper;
use Cake\I18n\Time;

class CustomHelper extends Helper
{
	public function getClientSideDate($date=null, $time=null){
		$checkVal = '';
		if(!empty($date))
			$checkVal = Date('Y-m-d',strtotime($date)).' ';

		if(!empty($time))
			$checkVal .= Date('H:i:s',strtotime($time)).' ';
		
		//$date = new DateTime($checkVal);
							
		//if(!empty($client_time_zone))
		//	$date->setTimezone(new DateTimeZone($client_time_zone));

		return $date->format('Y/m/d H:i:s');
	}

	/**
	 * Method	: getProfileImage
	 * Author	: Bharat Borana
	 * Created	: 08 Jna, 2015
	 * Purpose	: used to get profile image
	 */
	public function getProfileImage($imageName=null,$prefix=null)
	{
		if(isset($imageName) && !empty($imageName) && file_exists(USER_IMAGE_URL.$prefix.$imageName))
        {
            $freshName = BASE_URL.USER_IMAGE_URL.$prefix.$imageName; 
        }
        else
        {
            $freshName = 'choose-file.png';  
        }
		return $freshName;
	}

    /**
     * Method   : getDishImage
     * Author   : Bharat Borana
     * Created  : 27 Jna, 2016
     * Purpose  : used to get profile image
     */
    public function getDishImage($imageName=null,$prefix=null)
    { 
        if(isset($imageName) && !empty($imageName) && file_exists(DISH_IMAGE_URL.$prefix.$imageName))
        {
            $freshName = BASE_URL.DISH_IMAGE_URL.$prefix.$imageName; 
        }
        else
        {
            $freshName = 'img-15.png';  
        }
        return $freshName;
    }

    /**
     * Method   : getMyPics
     * Author   : Bharat Borana
     * Created  : 27 Jna, 2016
     * Purpose  : used to get profile image
     */
    public function getMyPics($imageName=null,$prefix=null)
    { 
        if(isset($imageName) && !empty($imageName) && file_exists(MYPIC_IMAGE_URL.$prefix.$imageName))
        {
            $freshName = BASE_URL.MYPIC_IMAGE_URL.$prefix.$imageName; 
        }
        else
        {
            $freshName = 'img-15.png';  
        }
        return $freshName;
    }

    /**
     * Method   : getMyVidoes
     * Author   : Bharat Borana
     * Created  : 27 Jan, 2016
     * Purpose  : used to get video
     */
    public function getMyVideos($imageName=null,$prefix=null)
    { 
        $freshName = "";
        if(isset($imageName) && !empty($imageName) && file_exists(MYVIDOES_URL.$prefix.$imageName))
        {
            $freshName = BASE_URL.MYVIDOES_URL.$prefix.$imageName; 
        }
        
        return $freshName;
    }

	public function getTimeAgo($time){
		if(isset($time) && !empty($time)){
			$time = time() - $time; // to get the time since that moment

		    $tokens = array (
		        31536000 => 'year',
		        2592000 => 'month',
		        604800 => 'week',
		        86400 => 'day',
		        3600 => 'hour',
		        60 => 'minute',
		        1 => 'second',
            );

		    foreach ($tokens as $unit => $text) {
		        if ($time < $unit) continue;
		        $numberOfUnits = floor($time / $unit);
		        $returnVal = $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
		      
                if(empty($returnVal))
                    $returnVal = 'just a second ago';

                return $returnVal;
            }
		}
	}

	 var $cacheDir = 'imagecache'; // relative to IMAGES_URL path 
    
    public function url($path=null,$return=false) {
         $fullpath = BASE_URL.'images/'.$path; 
         return $fullpath;
    }

    public function resize($path, $dst_w, $dst_h, $htmlAttributes = array(), $return = false) { 
         
        $types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type 
         
        $fullpath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.IMAGES_URL; 
        
        if(strpos($path,'images/')>0)
            $url = $path;
        else
            $url = 'images/'.$path; 
        

        list($w, $h, $type) = getimagesize($url);
        if($w==0 || $h==0)
        {
            $url = 'images/img1.png'; 
            list($w, $h, $type) = getimagesize($url);
        }

        $r = $w / $h;
        $dst_r = $dst_w / $dst_h;
        
        if ($r > $dst_r) {
            $src_w = $h * $dst_r;
            $src_h = $h;
            $src_x = ($w - $src_w) / 2;
            $src_y = 0;
        } else {
            $src_w = $w;
            $src_h = $w / $dst_r;
            $src_x = 0;
            $src_y = ($h - $src_h) / 2;
        }

        $relfile = $this->cacheDir.'/'.$dst_w.'x'.$dst_h.'_'.basename($path); // relative file 
        
        $cachefile = WWW_ROOT.DS.'img'.DS.$relfile;
         
        if (file_exists($cachefile)) {
            if (@filemtime($cachefile) >= @filemtime($url)) {
                $cached = true;
            } else {
                $cached = false;
            }
        } else { 
            $cached = false; 
        } 
         
        if (!$cached) { 
            $image = call_user_func('imagecreatefrom'.$types[$type], $url); 
            if (function_exists("imagecreatetruecolor")) {
                $temp = imagecreatetruecolor($dst_w, $dst_h); 
                imagecopyresampled($temp, $image, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h); 
            } else { 
                $temp = imagecreate ($dst_w, $dst_h); 
                imagecopyresized($temp, $image, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h); 
            } 
            call_user_func("image".$types[$type], $temp, $cachefile); 
            imagedestroy($image); 
            imagedestroy($temp); 
        }
        return $this->Html->image('../img/'.$relfile);
    }

}