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


class CommonComponent extends Component
	{
		/*
		 * Purpose :- this function will create paypal method data 
		 * 
		 * Inputs : $data - Paypal and order details   
		 * 
		 * Outputs : Data Array which is to be passed in paypal curl
		 * 
		 * Returns : It will return array of data
		*/
    	public function pay_me($paramArray=array(),$urlHit=null)
    	{
    		$nvpString = "USER=".PAYPAL_USER;  # User ID of the PayPal caller account
    		$nvpString .= "&PWD=".PAYPAL_PWD;	 # Password of the caller account
    		$nvpString .= "&SIGNATURE=".PAYPAL_SIGNATURE; # Signature of the caller account
    		
    		if(empty($urlHit))
    			$urlHit	=	PAYPAL_NVP_URL;

    		$headers = array(
		        "X-PAYPAL-SECURITY-USERID: ".PAYPAL_USER,
		        "X-PAYPAL-SECURITY-PASSWORD: ".PAYPAL_PWD,
		        "X-PAYPAL-SECURITY-SIGNATURE: ".PAYPAL_SIGNATURE,
		        "X-PAYPAL-REQUEST-DATA-FORMAT: NVP",
		    );
    		
    		if(!empty($paramArray))
    		{
    			foreach ($paramArray as $key => $value)
    			{
    				$nvpString .= "&".$key."=".$value;
    			}

    		}
    		$curlRespo = $this->hit_me($nvpString,$headers,$urlHit);

			return $curlRespo;
	    }

	    /*
		 * Purpose :- this function will run curl for paypal payment 
		 * 
		 * Inputs : $data - Paypal data   
		 * 
		 * Returns : It will return array of response
		*/
    	public function hit_me($paramString,$hData,$urlHit)
    	{	
    		$ch = curl_init();  
 			curl_setopt($ch,CURLOPT_URL,$urlHit);
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($ch,CURLOPT_HTTPHEADER, $hData); 
		    curl_setopt($ch, CURLOPT_POST, count($paramString));
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $paramString);    
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		    $output= urldecode(curl_exec($ch));
		    parse_str($output, $args);
		    curl_close($ch);
		    
		    return $args;
	    }

		public function ismobile()
	    {
		    $is_mobile = '0';

		    if(preg_match('/(android|up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		        $is_mobile=1;
		    }

		    if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		        $is_mobile=1;
		    }

		    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
		    $mobile_agents = array('w3c ','acs-','alav','alca','amoi','andr','audi','avan','benq','bird','blac','blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno','ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-','maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-','newt','noki','oper','palm','pana','pant','phil','play','port','prox','qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar','sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-','tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp','wapr','webc','winw','winw','xda','xda-');

		    if(in_array($mobile_ua,$mobile_agents)) {
		        $is_mobile=1;
		    }

		    if (isset($_SERVER['ALL_HTTP'])) {
		        if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0) {
		            $is_mobile=1;
		        }
		    }

		    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0) {
		        $is_mobile=0;
		    }

		    return $is_mobile;
		}

		public function encrypt($id)
		{
		    $id = base_convert($id, 10, 36); // Save some space
		    $data = mcrypt_encrypt(MCRYPT_BLOWFISH, Configure::read('Security.cipherSeed'), $id, 'ecb');
		    $data = bin2hex($data);

		    return $data;
		}

		public function decrypt($encrypted_id)
		{
		    $data = pack('H*', $encrypted_id); // Translate back to binary
		    $data = mcrypt_decrypt(MCRYPT_BLOWFISH, Configure::read('Security.cipherSeed'), $data, 'ecb');
		    $data = base_convert($data, 36, 10);

		    return $data;
		}

		public function generateRandomString($length = 10) {
		    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    return $randomString;
		}

		public function _send_email($to, $token, $token_value, $template, $subjectParams=null )
	    {
	        if(!filter_var($to, FILTER_VALIDATE_EMAIL))
	        {
	            return false;
	        }
	        
	        $emailTemplate = TableRegistry::get('EmailTemplates');
	        $template = $emailTemplate->findBySlug($template)->first();
	        if(!empty($template))
              $template  =   $template->toArray();
					else
	            return false;
	        

	        $subject = str_replace($token, $token_value ,$template['subject']);
	      
	        $msg = $template['content'];
	        
	        $msg = str_replace($token, $token_value, $msg);
	        
	        $email = new Email('default');     
	        
	        return $email->to($to)
	                ->from($template['from_email'])
	                ->subject($subject)
	                ->emailFormat('html')
	                ->send($msg);
	    }

    }
?>