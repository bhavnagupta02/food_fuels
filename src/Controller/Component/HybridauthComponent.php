<?php
/** 
*  Project : Saner 
*  Author : Xicom Technologies 
*  Creation Date : 26-06-2015 
*  Description : This is Common component which is used for common functions.
*/
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Core\Configure;

require_once(ROOT. DS   . 'vendor' . DS  . 'hybridauth' . DS . 'Hybrid' . DS . 'Auth.php');
require_once(ROOT. DS   . 'vendor' . DS  . 'hybridauth' . DS . 'Hybrid' . DS . 'Endpoint.php');

use \Hybrid_Auth;
use \Hybrid_Endpoint;

class HybridauthComponent extends Component {

    public $hybridauth = null;
    public $adapter = null;
    public $user_profile = null;
    public $error = "no error so far";
    public $provider = null;
    public $debug_mode = false;
    public $debug_file = "";

    protected function init(){
        $config = array(
            "base_url"   => Configure::read('Hybridauth.base_url'),
            "providers"  => Configure::read('Hybridauth.providers'),
            "debug_mode" => Configure::read('Hybridauth.debug_mode'),
            "debug_file" => Configure::read('Hybridauth.debug_file'),
        );
       
        $this->hybridauth = new Hybrid_Auth( $config );
    }
	
	/**
     * process the 
     * 
     * @return string
     */
    public function processEndpoint(){
        if( !$this->hybridauth ) $this->init ();
        Hybrid_Endpoint::process();
    }
    
    /**
     * get serialized array of acctual Hybridauth from provider...
     * 
     * @return string
     */
    public function getSessionData(){
        if( !$this->hybridauth ) $this->init ();
        return $this->hybridauth->getSessionData();
    }
    
    /**
     * 
     * @param string $hybridauth_session_data pass a serialized array stored previously
     */
    public function restoreSessionData( $hybridauth_session_data ){
        if( !$this->hybridauth ) $this->init ();
        $hybridauth->restoreSessionData( $hybridauth_session_data );
    }
    
    /**
     * logs you out
     */
    public function logout(){
        if( !$this->hybridauth ) $this->init ();
        $providers = $this->hybridauth->getConnectedProviders();
        
        if( !empty( $providers ) ){
            foreach( $providers as $provider ){
                $adapter = $this->hybridauth->getAdapter($provider);
                $adapter->logout();
            }
        }
    }
    
    /**
     * connects to a provider
     * 
     * 
     * @param string $provider pass Google, Facebook etc...
     * @return boolean wether you have been logged in or not
     */
    public function connect($provider) {
        
        if( !$this->hybridauth ) $this->init ();

        $this->provider = $provider;
        $provider       = $this->provider;
        try {
            // try to authenticate the selected $provider

            $this->adapter = $this->hybridauth->authenticate($this->provider);
            
            // grab the user profile
            $this->user_profile = $this->normalizeSocialProfile($this->provider);
            
            return true;
            
        } catch (Exception $e) {
            // Display the recived error
            switch ($e->getCode()) {
                case 0 : $this->error = "Unspecified error.";
                    break;
                case 1 : $this->error = "Hybriauth configuration error.";
                    break;
                case 2 : $this->error = "Provider [".$provider."] not properly configured.";
                    break;
                case 3 : $this->error =  "[" .$provider. "] is an unknown or disabled provider.";
                    break;
                case 4 : $this->error = "Missing provider application credentials for Provider [".$provider."].";
                    break;
                case 5 : $this->error = "Authentification failed. The user has canceled the authentication or the provider [" .$provider. "] refused the connection.";
                    break;
                case 6 : $this->error = "User profile request failed. Most likely the user is not connected to the provider [" .$provider. "] and he/she should try to authenticate again.";
                    $this->adapter->logout();
                    break;
                case 7 : $this->error = "User not connected to the provider [" .$provider. "].";
                    $this->adapter->logout();
                    break;
            }

            // well, basically your should not display this to the end user, just give him a hint and move on..
            if( $this->debug_mode ){
                $this->error .= "<br /><br /><b>Original error message:</b> " . $e->getMessage();
                $this->error .= "<hr /><pre>Trace:<br />" . $e->getTraceAsString() . "</pre>"; 
            }
            

            return false;
        }
    }
	
     /**
     * Share Something
     */
    public function shareme($provider){
        if(!$this->hybridauth)
        $this->init ();
        
        $this->provider = $provider;

        try {
            
            $facebook = $this->hybridauth->authenticate($provider);

            $facebook->api()->api("/me/feed", "post", array(
            "message" => "Hi there",
            "picture" => "http://www.mywebsite.com/path/to/an/image.jpg",
            "link" => "http://www.mywebsite.com/path/to/a/page/",
            "name" => "My page name",
            "caption" => "And caption"
            ));
            
            return true;
        }
        catch (Exception $e) {
           // Display the recived error
            switch ($e->getCode()) {
                case 0 : $this->error = "Unspecified error.";
                    break;
                case 1 : $this->error = "Hybriauth configuration error.";
                    break;
                case 2 : $this->error = "Provider [".$provider."] not properly configured.";
                    break;
                case 3 : $this->error =  "[" .$provider. "] is an unknown or disabled provider.";
                    break;
                case 4 : $this->error = "Missing provider application credentials for Provider [".$provider."].";
                    break;
                case 5 : $this->error = "Authentification failed. The user has canceled the authentication or the provider [" .$provider. "] refused the connection.";
                    break;
                case 6 : $this->error = "User profile request failed. Most likely the user is not connected to the provider [" .$provider. "] and he/she should try to authenticate again.";
                    $this->adapter->logout();
                    break;
                case 7 : $this->error = "User not connected to the provider [" .$provider. "].";
                    $this->adapter->logout();
                    break;
            }

            // well, basically your should not display this to the end user, just give him a hint and move on..
            if( $this->debug_mode ){
                $this->error .= "<br /><br /><b>Original error message:</b> " . $e->getMessage();
                $this->error .= "<hr /><pre>Trace:<br />" . $e->getTraceAsString() . "</pre>"; 
            }
            
            echo  $this->error;
           exit;
        }
    }
   

	/**
     * creates a social profile array based on the hybridauth profile object
     * 
     * 
     * @param string $provider the provider given from hybridauth
     * @return boolean wether you have been logged in or not
     */
	protected function normalizeSocialProfile($provider){
		// convert our object to an array
		$incomingProfile = (Array)$this->adapter->getUserProfile();
        // populate our social profile
		if($provider=='Facebook')
		  $socialProfile['Users']['facebook_id']        =   $incomingProfile['identifier'];
		else if($provider=='Google')
		  $socialProfile['Users']['google_id']    =   $incomingProfile['identifier'];
        else if($provider=='Twitter')
          $socialProfile['Users']['twitter_id']   =   $incomingProfile['identifier'];

        if(isset($incomingProfile['email']) && !empty($incomingProfile['email']))
		  $socialProfile['Users']['email']    =   $incomingProfile['email'];
		else
          $socialProfile['Users']['email']    =   $incomingProfile['identifier'].'@'.$provider.'.com';
        
        $socialProfile['Users']['username']   =   $incomingProfile['displayName'];
		
        $displayName = explode(' ', $incomingProfile['displayName']);
       
        if(!empty($incomingProfile['firstName']))
            $socialProfile['Users']['first_name'] =  $incomingProfile['firstName'];
		else{
            if(isset($displayName[0]))
                $socialProfile['Users']['first_name'] =  $displayName[0];
            else
                $socialProfile['Users']['first_name'] =  '';
        }

        if(!empty($incomingProfile['lastName']))
            $socialProfile['Users']['last_name']  =  $incomingProfile['lastName'];
        else{
            if(isset($displayName[1]))
                $socialProfile['Users']['last_name'] =  $displayName[1];
            else
                $socialProfile['Users']['last_name'] =  '';
        }

        $socialProfile['Users']['link']       =   $incomingProfile['profileURL'];
		$socialProfile['Users']['image']      =   $incomingProfile['photoURL'];
		$socialProfile['Users']['created']    =   date('Y-m-d h:i:s');
		$socialProfile['Users']['modified']   =   date('Y-m-d h:i:s');
		$socialProfile['Users']['group_id']   =   CLIENTGROUPID;
		$socialProfile['Users']['phone']      =   (!empty($incomingProfile['phone'])) ? $incomingProfile['phone'] : '';

		return $socialProfile;
    }

}
