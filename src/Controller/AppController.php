<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since    0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Network\Email\Email;
use Cake\Utility\Hash;
use Cake\I18n\I18n;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Datasource\ConnectionManager;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];

    public $collectiveValidationErrors = [];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'Users',
                'action' => 'home',
                'plugin' => false
            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'home',
            ],
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email']
                ],
            ],
            'authError' => __('Please login to visit that page'),
        ]);

        $lang=$this->request->session()->read('Config.language');
        
        if(empty($lang))
            $lang = "th";

        $this->loadModel('Cmspages');
        $cmsText = $this->Cmspages->findBySlug('home-page')->toArray();
        $this->set('cmsText', $cmsText);
        
        I18n::locale($lang);
        Time::$defaultLocale = 'en';
    }

    public function beforeRender(Event $event)
    { 
        $client_time_zone = $this->request->session()->read('client_timezone');
        if(empty($client_time_zone))
            $client_time_zone = '';
                  
        $this->loadModel('Users');
        $memberOfmonth = $this->Users->getLeaderBoardData(0,1);
        $startDate = $this->Auth->user('paid_date');
        $days = 0;


        /*$coach_assign = ConnectionManager::get('default');
                
        $coach_users = $coach_assign->execute("SELECT * FROM users where trainer_id ='".$this->Auth->user('id')."'")->fetchAll('assoc');
        //print_r($coach_users);die;

         $userDetails = $this->Users->get($this->Auth->user('id'),['contain' => ['Trainers' => ['fields' => ['Trainers.id','Trainers.first_name','Trainers.last_name','Trainers.email','Trainers.image','Trainers.short_description']]]]);
       //print_r($userDetails);*/

        if(!empty($startDate)){
            $this->loadModel('UserSubscriptions');
            $enddate = $this->UserSubscriptions->find()->where(['user_id' => $this->Auth->user('id')])->order(['UserSubscriptions.id' => 'DESC'])->first();
            
            $datefrom = strtotime(date('Y-m-d'), 0);
            $dateto = strtotime($enddate->end_date, 0);
            $difference = $dateto - $datefrom;
            $datediff = floor($difference / (604800/7));

            $subscriptionStatus = 'expired';
	    if(!empty($enddate)) {
		if(strtotime($enddate->end_date) > strtotime(date('Y-m-d'))) {
			$subscriptionStatus = 'renew';
		}
	    }
        }
        
        $this->set('remainingDays',$datediff);
        $lang = I18n::locale();   
        //print_r($userDetails);                                                 
        $this->set(compact('client_time_zone','memberOfmonth','subscriptionStatus','coach_users','userDetails'));

    }

    public function beforeFilter(Event $event)
    {   
        $this->response->header('Access-Control-Allow-Origin','*');
        $this->response->header('Access-Control-Allow-Methods','*');
        $this->response->header('Access-Control-Allow-Headers','X-Requested-With');
        $this->response->header('Access-Control-Allow-Headers','Content-Type, x-xsrf-token');
        $this->response->header('Access-Control-Max-Age','172800');
        $this->_aclCheck();
    }

    public function _sendMail($to, $subject = '', $body = '') {
        $email = new Email('default');
        $email->to($to);
        $email->emailFormat('both');
        $email->from(array('admin@foodfuels.com' => 'Foodfuels'));
        $email->subject($subject);
        $email->send($body);
    }

    public function _findAll($dataObj) {
        $data = array();
        foreach($dataObj as $dataObjUno) {
            $data[] = $dataObjUno->toArray();
        }
        return $data;
    }

    /**
     * check if current logged in user is owner for a record of a model
     */
    public function _isOwnedBy($model, $id, $slug = null, $foreign_key = null) {
        $this->loadModel($model);
        if(!$foreign_key) {
            $foreign_key = 'user_id';
        }
        if(!$id && $slug) {
            // $this->$model->find('')
            $res = $this->$model->find('all', ['conditions' => ['slug' => $slug, $foreign_key => $this->Auth->user('id')],
                'fields' => ['id']])->first();
        } else {
            $res = $this->$model->find('all', ['conditions' => ['id' => $id, $foreign_key => $this->Auth->user('id')],
                'fields' => ['id']])->first();
        }
        return !empty($res);
    }

    public function _aclCheck() {
        //currently writing hard coded acl t distinguish between admins and normal users
        if($this->request->prefix == 'backoffice') {
            //if user is logged in
            if($this->Auth->user('id') && $this->request->action != 'login') {
                if($this->Auth->user('group_id') == ADMINGROUPID) {
                    //is admin
                    //return $this->redirect(['prefix' => 'backoffice','controller' => 'Users', 'action' => 'dashboard']);
                } else {
                    $this->Flash->error(__('You do not have access to visit that page'));
                    return $this->redirect(['prefix' => 'backoffice', 'controller' => 'Users', 'action' => 'login']);
                }
            }
        } else {
            //if its not the admin site
            if($this->Auth->user('id') && $this->request->action != 'login') {
                $isPaid = $this->Auth->user('is_paid');
                
                if($this->Auth->user('group_id') == CLIENTGROUPID) {
                    if(!$isPaid && ($this->request->action != 'edit_profile' && $this->request->action != 'payment_select' && $this->request->action != 'pay_me')){
                        return $this->redirect(['controller' => 'users', 'action' => 'pay_me']);
                    }
                    //is normal user
                    //return $this->redirect(['controller' => 'users', 'action' => 'home']);
                }
                elseif($this->Auth->user('group_id') == USERGROUPID) {
                    //is normal user
                    //return $this->redirect(['controller' => 'trainers', 'action' => 'home']);
                } else {
                    $this->Flash->error(__('Admin does not have access to content pages'));
                    if($this->Auth->user('group_id') != ADMINGROUPID) {
                        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                    }else {
                        return $this->redirect(['prefix' => 'backoffice','controller' => 'Users', 'action' => 'dashboard']);
                    }
                }
                return true;
            }
        }
    }

    public function _getValidationMessages($errors, $returnString = 1) {
        foreach($errors as $error) {
            foreach($error as $errorText) {
                if(!is_array($errorText)) {
                    $this->collectiveValidationErrors[] = $errorText;
                } else {
                    $this->_getValidationMessages($error);
                }
            }
        }
        return implode(', ', $this->collectiveValidationErrors) . '. ';
    }

    public function selectTarget(){
        $isPaid = $this->Auth->user('is_paid');
        $profileStatus = $this->Auth->user('profile_status');
        $groupId = $this->Auth->user('group_id');
        if($groupId == CLIENTGROUPID){
            if($profileStatus == 0)
                $redirect = Router::url(['controller' => 'users', 'action' => 'edit_profile'], true);
            else if($profileStatus == 1)
            {
                $this->loadModel('UserSubscriptions');
                
                $alreadyExists = $this->UserSubscriptions->find()->where(['user_id' => $this->Auth->user('id')])->first();
                
                if($isPaid == 0 && empty($alreadyExists))
                    $redirect = Router::url(['controller' => 'users', 'action' => 'payment_select'], true);
                elseif($isPaid == 0 && !empty($alreadyExists))
                    $redirect = Router::url(['controller' => 'users', 'action' => 'pay_me',$alreadyExists->subscription_id], true);
                elseif($isPaid == 1 && $profileStatus == 1)
                    $redirect = Router::url(['controller' => 'users', 'action' => 'coach_assign'], true);
                else
                    $redirect = Router::url(['controller' => 'users', 'action' => 'home'], true);
            }
            else if($profileStatus == 2){
                $redirect = Router::url(['controller' => 'users', 'action' => 'coach_assign'], true);
            }
            else if($profileStatus == 3){
                $redirect = Router::url(['controller' => 'users', 'action' => 'home'], true);
            }
        }
        else{
            if($profileStatus == 0)
                $redirect = Router::url(['controller' => 'trainers', 'action' => 'edit_profile'], true);
            else
                $redirect = Router::url(['controller' => 'trainers', 'action' => 'home'], true);
        }

        return $redirect;
    }

    function _base64_to_jpeg($base64_string, $output_file) {
        
        $ifp = fopen($output_file, "wb"); 
        $data = explode(',', $base64_string);
        fwrite($ifp, base64_decode($data[1])); 
        fclose($ifp); 
        return $output_file; 
    }

    /**
	 * No view file
	 * function to check user's membership expired or not
	 */
	public function checkMembershipExpired() {
		$this->loadModel('UserSubscriptions');
		$subscriptionData = $this->UserSubscriptions->find()->where(['user_id' => $this->Auth->user('id')])->order(['UserSubscriptions.id' => 'DESC'])->first();
		if(strtotime($subscriptionData->end_date) < strtotime(date('Y-m-d'))) {
			return $this->redirect(['controller' => 'Users', 'action' => 'membership_expired']);
                        exit;
		}
	}

     /**
	 * No view file
	 * function to verify google reCaptcha
	 */
	public function _verifyReCaptcha($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
		$curlData = curl_exec($curl);
		
		curl_close($curl);
		return $curlData;
	}
}