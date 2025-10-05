<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\Database\Expression\QueryExpression;
use Cake\Auth\DefaultPasswordHasher;
use Cake\I18n\I18n;  
use Cake\I18n\Time;
use Cake\Network\Email\Email;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
	public $components	= ['Hybridauth','Uploader'];
	
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->Auth->allow(['testEmail', 'register', 'login', 'logout','social_login','social_endpoint','_successfulHybridauth','_doSocialLogin', 'ajax_login', 'set_timezone']);
		$this->Auth->allow(['verify_user', 'verifyCaptcha', 'forgot_password',	'send_password', 'send_shared_emails','set_lang','enquiry','subscribe_me','skipVideo','cropnrotate','profile_upload','checkPayment']);
	}
  
	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->paginate = [
			'contain' => ['Groups']
		];
		$this->set('users', $this->paginate($this->Users));
		$this->set('_serialize', ['users']);
	}

	public function import_user(){
		$this->loadModel('JoomlaUsers');
		$allUsers = $this->JoomlaUsers->find()->all()->toArray();
		$userArray = array();

		foreach ($allUsers as $key => $value) {
			$all_in_one = explode(';', $value['all_in_one']);
			$name_array = explode(' ', $all_in_one[1]);
			$innerArray['first_name'] = $name_array[0];
			if(isset($name_array[1])){
				$innerArray['last_name'] = $name_array[1];
			}
			$innerArray['email'] = $all_in_one[0];
			$innerArray['group_id'] = CLIENTGROUPID;
			$innerArray['terms'] = 1;
			if(!$this->Users->find()->where(['email' => $all_in_one[0]])->count()){
				$this->loadComponent('Common');
				$innerArray['password'] = $this->Common->generateRandomString(8);
				$userEntity = $this->Users->newEntity($innerArray);
				if($this->Users->save($userEntity)){
					echo $all_in_one[0]." added successfully </br>";
				}
				else{
					echo "<pre>";
					print_r($userEntity->errors());
				}
			}
			
			$userArray[] = $innerArray;
		}
		echo "<pre>";
		//print_r($userArray);
		exit;
	}

	public function send_signup_email(){
		$this->loadModel('Users');
		$allUsers = $this->Users->find()->where(['email_send' => 0, 'group_id' => CLIENTGROUPID])->all();
		$userArray = array();

		foreach ($allUsers as $key => $saveUser) {
			$this->loadComponent('Common');
			$FreshPass 	=	$this->Common->generateRandomString(8);
			
			$saveUser = $this->Users->patchEntity($saveUser, array('password' => $FreshPass,'email_send' => 1, 'verification_token'=>'','is_verified' => 1,'status_id' => 1));
			
			$name = '';
			if($saveUser->first_name)
			$name = $saveUser->first_name;

			if($saveUser->last_name)
			$name = $name." ". $saveUser->last_name;

			$token      		=   ['{{name}}','{{email_address}}','{{password}}'];
			$tokenVal   		=   [	$name,$saveUser->email,$FreshPass];

			if(!filter_var($saveUser->email, FILTER_VALIDATE_EMAIL))
	        {
	            return false;
	        }
	        
	        $this->loadModel('EmailTemplates');
	        $emailTemplate = $this->EmailTemplates;
	        $template = $emailTemplate->findBySlug('welcome_email')->first();
	        if(!empty($template))
              $template  =   $template->toArray();
					else
	            return false;
	        

	        $subject = str_replace($token, $tokenVal ,$template['subject']);
	      
	        $msg = $template['content'];
	        
	        $msg = str_replace($token, $tokenVal, $msg);
	        
	        $email = new Email('default');     
	        
	        $email->to($saveUser->email)
	                ->from($template['from_email'])
	                ->subject($subject)
	                ->emailFormat('html');

	        if($email->send($msg)){
	    		$this->Users->save($saveUser);
				echo "<pre>";
				echo "Email successfully send on ".$saveUser['email'];
	        } 
	    }
		echo "<pre>";
		//print_r($userArray);
		exit;
	}
	/**
	 * Method	: Social login action for facebook and google login.
	 * Author	: Bharat Borana
	 * Created	: 15 Dec, 2014
	 */
	public function social_login($provider) {
		if( $this->Hybridauth->connect($provider)){ 
			$this->_successfulHybridauth($provider,$this->Hybridauth->user_profile);
        }else{
            // error
			$this->Flash->success($this->Hybridauth->error);
			$redirect = Router::url(array('controller' => 'pages','action'=>'home'), true);
			$this->redirect($redirect);
    	}
	}

	/**
	 * Method	: Return url for social login like facebook and google.
	 * Author	: Bharat Borana
	 * Created	: 15 Dec, 2014
	 */
	public function social_endpoint($provider=null) {
		$this->Hybridauth->processEndpoint();
	}
	
	/**
	 * Method	: After social authentication check for existing user, if exixts then directly login otherwise add user.
	 * Author	: Bharat Borana
	 * Created	: 16 Dec, 2014
	 */
	private function _successfulHybridauth($provider, $incomingProfile){

		// #1 - check if user already authenticated using this provider before
		
		$conditions = array('Users.facebook_id'=>$incomingProfile['Users']['facebook_id'],'Users.group_id' => CLIENTGROUPID);
		
		$existingProfile = $this->Users->find()->where($conditions)->first();
		
		if ($existingProfile)
		{
			// #2 - if an existing profile is available, then we set the user as connected and log them in
			$user_data = $existingProfile->toArray();
					
			if(empty($user_data['first_name']))
			$userEdit['first_name'] = $incomingProfile['Users']['first_name'];
			
			if(empty($user_data['last_name']))
			$userEdit['last_name'] = $incomingProfile['Users']['last_name'];

			if(empty($user_data['email']) || stripos($user_data['email'], "@".$provider.".com") > 0)
			$userEdit['email'] = $incomingProfile['Users']['email'];

			if(isset($userEdit) && !empty($userEdit)){
				$saveUser = $this->Users->patchEntity($this->Users->get($user_data['id']), $userEdit, ['validate' => false]);
				$saveData = $this->Users->save($saveUser,['validate'	=>	false]);
				if ($saveData) {
					$this->_doSocialLogin($saveData);
				} else {
					$this->Flash->error(__('The User could not be saved. Please try again'));
					return $this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'login']);
				}		
			}
			else{
				$this->_doSocialLogin($existingProfile);
			}
		} 
		else
		{
			// New profile.
			if ($this->Auth->user('id')) {
				// user is already logged-in , attach profile to logged in user.
				// create social profile linked to current user
				$this->request->data 				=	$incomingProfile['Users'];
				$this->request->data['pic_url'] 	= $incomingProfile['Users']['image'];
				$this->request->data['group_id'] 	= CLIENTGROUPID;
				$this->request->data['status_id'] 	= 1;
				$this->request->data['is_verified'] = 1;
				//save new user
				//get fb image and save in system
				$imageName = $this->_getFbImageAndSave($this->request->data);
				$this->request->data['image'] = $imageName;
				$saveUser = $this->Users->patchEntity($this->Users->get($this->Auth->user('id')), $this->request->data, ['validate' => false]);
				
				$saveData = $this->Users->save($saveUser,['validate'	=>	false]);
				if ($saveData) {
					$this->_doSocialLogin($saveData);
				} else {
					$this->Flash->error(__('The User could not be saved. Please try again'));
					return $this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'login']);
				}
			}
			else 
			{
				// no-one logged and no profile, must be a registration.
				$user_data = $this->Users->find()->where(['Users.email' => $incomingProfile['Users']['email']])->first();
				
				if(!empty($user_data))
				{
					$user_data = $user_data->toArray();
					
					$userEdit['is_verified'] = 1;

					if(isset($incomingProfile['Users']['facebook_id']))
					$userEdit['facebook_id'] = $incomingProfile['Users']['facebook_id'];
					
					$imageName = $this->_getFbImageAndSave($this->request->data);
					$saveUser = $this->Users->patchEntity($this->Users->get($user_data['id']), $userEdit, ['validate' => false]);
					
					$saveData = $this->Users->save($saveUser,['validate'	=>	false]);
	
					if ($saveData) {
						$this->_doSocialLogin($saveData);
					} else {
						$this->Flash->error(__('The User could not be saved. Please try again'));
						return $this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'login']);
					}
				}
				else
				{
					$this->request->data 					= $incomingProfile['Users'];
					$this->request->data['pic_url'] 		= $incomingProfile['Users']['image'];
					$this->request->data['group_id'] 		= CLIENTGROUPID;
					$this->request->data['status_id'] 		= 1;
					$this->request->data['is_verified'] 	= 1;
					$this->request->data['password'] 		= 'technologies';
					$this->request->data['newsletter'] 		= 1;
					
					//save new user
					//get fb image and save in system
					$imageName = $this->_getFbImageAndSave($this->request->data);
					if($imageName) {
						$this->request->data['profile_picture'] = $imageName;
						$this->request->data['user_images'] = array(
							array('name' => $imageName)
						);
						$saveData = $this->Users->newEntity($this->request->data, ['associated' => 'UserImages','validate' => false]);
					} else {
						$saveData = $this->Users->newEntity($this->request->data, ['validate' => false]);
					}

					$saveData = $this->Users->save($saveData, array('validate'=>false));
					if ($saveData) {
						//log in with the newly created user
						$this->_doSocialLogin($saveData,1);
					} else {
						$this->Flash->success(__('The User could not be saved. Please try again'));
						return $this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'login']);
					}

					// log in with the newly created user
				}
			}
		}
	}
	
	/**
	 * Method	: After social authentication check for existing user, if exixts then directly login.
	 * Author	: Bharat Borana
	 * Created	: 16 Dec, 2014
	 */
	private function _doSocialLogin($user, $returning = false) {
		// #1 - check if user already authenticated using this provider before
			if(!empty($user)){
				$user = $user->toArray();
			}
		
			$conditions = array();
			if(isset($user['facebook_id']))
				$conditions['OR'][] = array('Users.facebook_id'=>$user['facebook_id'],'Users.group_id' => CLIENTGROUPID);
			if(isset($user['email']))
				$conditions['OR'][] = array('Users.email'=>$user['email'],'Users.group_id' => CLIENTGROUPID);
			
			if(!empty($conditions))
			{
				$user = $this->Users->find()->where($conditions)->first();
				
				if(!empty($user))
					$user = $user->toArray();
				
				$this->Auth->setUser($user);
				if ($user && $user['group_id'] == USERGROUPID) {
					$redirect = $this->selectTarget();
		        } 
				elseif ($user && $user['group_id'] == CLIENTGROUPID) {
					$redirect = $this->selectTarget();
		        }
				$this->redirect($redirect);
			}
			else
			{
				$this->Flash->error(__('Unknown Error could not verify the user.'));
				$redirect = Router::url(array('controller' => 'users','action'=>'login'), true);
			}	
			$this->redirect($redirect);
	}

	/**
	 * View method
	 *
	 * @param string|null $id User id.
	 * @return void
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function view($id = null)
	{
		$user = $this->Users->get($id, [
			'contain' => ['Groups']
		]);
		$this->set('user', $user);
		$this->set('_serialize', ['user']);
	}

	/**
	 * Add method
	 *
	 * @return void Redirects on successful add, renders view otherwise.
	 */
	public function register()
	{
		$this->viewBuilder()->layout('ajax');
		$user = $this->Users->newEntity();
		if ($this->request->is('post')) {
			$this->request->data['group_id'] = CLIENTGROUPID;
			$this->request->data['status_id'] = 3;
			$this->loadComponent('Common');
			$randString = $this->Common->generateRandomString(25);
			$this->request->data['verification_token'] = $randString;
			
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				
				$linkTag = "http://" . $_SERVER['SERVER_NAME'] . Router::url(array('controller' => 'Users', 'action' => 'verify_user', $user->email, $user->verification_token));

				$tokenArray = ['{{name}}','{{activation_url}}'];
				$tokenValueArray = [$user->first_name." ".$user->last_name, $linkTag];
				$this->Common->_send_email($user->email,$tokenArray, $tokenValueArray,'verify-email');
				echo json_encode(array('status' => 1, 'message' => __('The user has been registered. A verification mail has been sent to your account. Please verify the mail to login')));
			} else {
				$errors = $this->_getValidationMessages($user->errors());
				echo json_encode(array('status' => 0, 'message' => __($errors . 'The user could not be saved. Please, try again')));
			}
		}
		else {
			$this->Flash->success(__('The user has been registered. A verification mail has been sent to your account. Please verify the mail to login'), [
			    'key' => 'loginbox',
			]);
			return $this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'login']);
		}
		exit;
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id User id.
	 * @return void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit($id = null)
	{
		$user = $this->Users->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success(__('The user has been saved'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again'));
			}
		}
		$groups = $this->Users->Groups->find('list', ['limit' => 200]);
		$this->set(compact('user', 'groups'));
		$this->set('_serialize', ['user']);
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id User id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$user = $this->Users->get($id);
		if ($this->Users->delete($user)) {
			$this->Flash->success(__('The user has been deleted'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again'));
		}
		return $this->redirect(['action' => 'index']);
	}

	public function login($checkStatus=0)
	{
		$this->viewBuilder()->layout(false);
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			
			if ($user && $user['group_id'] == CLIENTGROUPID) {
				if($user['status_id'] == 1) {
					$this->Auth->setUser($user);
					//check/save login cookie
					if($this->request->data['remember_me']) {
						//save cookie
						$this->_saveLoginCookie($user['email'], $user['id']);
					}
					if(empty($user['logged_in_once'])) {
						$user['logged_in_once'] = 1;
						$user['last_login_check'] = date('Y-m-d H:i:s');
						$saveUser = $this->Users->patchEntity($this->Users->get($user['id']), $user);
						$this->Users->save($saveUser);
						$redir = $this->Auth->redirectUrl();
						//$redirect = ['action' => 'email_share'];
				    	//return $this->redirect($redirect);
					} 
					else { 
		            	//return $this->redirect($redir);
					}
					
					$redirect = $this->selectTarget();
		        	if(!isset($redirect) || empty($redirect)){
		        		$redir = $this->Auth->redirectUrl();
						$redirect = Router::url($redir, true);
		        	}
		        	$response_array = array('status' => 1, 'message' => __('Logged-in successfully'), 'url' => $redirect);
		        } 
				else {
					if(!$user['is_verified']) {
						$response_array = array('status' => 0, 'message' => __('Please verify your account.'));
					} else {
						$response_array = array('status' => 0, 'message' => __('Invalid email or password, try again.'));
					}
				}
			}
			else if ($user && $user['group_id'] == USERGROUPID) {
				if($user['status_id'] == 1) {
					$this->Auth->setUser($user);
					//check/save login cookie
					if($this->request->data['remember_me']) {
						//save cookie
						$this->_saveLoginCookie($user['email'], $user['id']);
					}
					if(empty($user['logged_in_once'])) {
						$user['logged_in_once'] = 1;
						$user['last_login_check'] = date('Y-m-d H:i:s');
						$saveUser = $this->Users->patchEntity($this->Users->get($user['id']), $user);
						$this->Users->save($saveUser);
					} 
					
					$redirect = $this->selectTarget();
		        	
		        	$response_array = array('status' => 1, 'message' => __('Logged-in successfully'), 'url' => $redirect);
		        } 
				else {
					if(!$user['is_verified']) {
						$response_array = array('status' => 0, 'message' => __('Please verify your account.'));
					} else {
						$response_array = array('status' => 0, 'message' => __('Invalid email or password, try again.'));
					}
				}
			} 
			else {
				$response_array = array('status' => 0, 'message' => __('Invalid email or password, try again.'));
			}

			echo json_encode($response_array);
		} 
		else {
			$user = $this->Auth->user('id');
			if(!empty($user) && $this->Auth->user('group_id') == CLIENTGROUPID) {
				return $this->redirect($this->Auth->redirectUrl());
			} 
			else {
				//check login cookie
				if(isset($_COOKIE['_l']) && !empty($_COOKIE['_l'])) {
					$user = $this->Users->find('all', array(
						'conditions' => array('Users.cookie_token' => $_COOKIE['_l'], 'Users.status_id' => 1)
					))->first();
					if(!empty($user) && $user->group_id == CLIENTGROUPID) {
						//user exists, just log it in
						$this->Auth->setUser($user->toArray());
						$redir = $this->request->session()->read('redir');

			            if(!empty($redir))
			            {
			            	$this->request->session()->write('redir','');
			              	
			              	$redirect = Router::url($redir, true);
			            }
			            else
			            {
			            	$redirect = $this->Auth->redirectUrl();
			            	$redirect = Router::url($redirect, true);
			            }
			            echo json_encode(array('status' => 1, 'message' => __('Logged-in successfully'), 'url' => $redirect));
						//return $this->redirect($redirect);
					}
				}
			}

			$groups = $this->Users->Groups->find('list', ['limit' => 200]);
			$this->set(compact('user', 'groups'));

			return $this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'login']);
		}
		exit;
	}

	public function addweight(){

		$this->viewBuilder()->layout(false);
		$this->loadModel('UserWeights');
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$user_id = $this->Auth->user('id');
				
				//$data['weight_date'] = '2015-12-08 00:00:00';
				$data['weight_date'] = new Time($data['weight_date']);
				

				$data['user_id'] = $user_id;
				$checkData = $data;
				unset($checkData['weight']);
				///pr($checkData) ;exit;

				
				$query = $this->UserWeights->find()->where($checkData);
				$queryData = $query->toArray();
				//pr($query) ;exit;
				if( count($queryData) == 0){ // new
					$query = $this->UserWeights->newEntity();
					$this->UserWeights->patchEntity($query, $data);					
				} else{
					 
					 $query = $this->UserWeights->get($queryData[0]->id, [
						'contain' => []
					]);
					 //pr($query);
					 //exit;
					$this->UserWeights->patchEntity($query, $data);
				}
				//pr($query);exit;
				//$this->UserWeights->patchEntity($query, $data);
				if ($this->UserWeights->save($query)) {

					$this->UserWeights->findAndUpdateMyLoss($this->Auth->user('id'));
					
					$UserWeights = $this->UserWeights->find()
						->select(['weight' => 'weight', 'weight_date' => 'weight_date'])
						->where(['user_id' => $this->Auth->user('id')])
						->order([ 'weight_date' => 'DESC'])
						->limit(10)
						->toArray();

					
					$UserWeights = array_reverse($UserWeights);
					//exit;
					//pr($UserWeights);exit;
					$UserWeightsData = [];
					foreach($UserWeights as $k => $v){
						//$UserWeightsData[] = ["x"=>strtotime($v->weight_date->format('Y-m-d H:i:s')),"y"=>$v->weight];
						$UserWeightsData["x"][] = ($v->weight_date->format('d M Y'));
						$UserWeightsData["y"][] = $v->weight;
						//echo  $v->weight_date->year; echo "<br>";
					}
					//pr($UserWeightsData);exit;
					$newData =  json_encode($UserWeightsData);
					$response_array = array('status' => 1, 'message' => __('Weight added successfully'),"newData" => $newData);						 
				} else {
					$response_array = array('status' => 0, 'message' => __('Weight could not be saved.'));
				}
				echo json_encode($response_array);	
				exit;		
		}	
	}

	public function logout()
	{
		$cookie_name = "_l";
		setcookie('_l', false, time() - 3600, '/');
		unset($_COOKIE['_l']);
		return $this->redirect($this->Auth->logout());
	}

	public function home() {
		$this->set('title', 'Dashboard');

		if($this->Auth->user('group_id') == USERGROUPID) {
            return $this->redirect($this->redirect(['controller' => 'trainers', 'action' => 'home']));
        } 

        $userDetails = $this->Users->get($this->Auth->user('id'),['contain' => ['Trainers' => ['fields' => ['Trainers.id','Trainers.first_name','Trainers.last_name','Trainers.email','Trainers.image']], 'UploadImages' => ['conditions' => [ 'OR' => ['UploadImages.type = "pics" OR UploadImages.type = "videos"'] ]]]]);
		
		$this->loadModel('ShoppingLists');
		$shoppingListData = $this->ShoppingLists->getCurrentShoppingList($this->Auth->user('created'));
		
		$this->loadModel('ActivityLogs');
		$notiCount = $this->ActivityLogs->find()->where(['ActivityLogs.user_id' => $this->Auth->user('id'), 'ActivityLogs.seen' => 0])->count();
		
		$this->loadModel('Feeds');
	    $feedList = $this->Feeds->getMyFeed('','',5);
        
		$this->loadModel('ConversationReplies');
		$msgCount = 	$this->ConversationReplies
								->find()
								->where(['ConversationReplies.seen' => 0, 'ConversationReplies.user_id !=' => $this->Auth->user('id')])
								->innerJoinWith(
								    'Conversations', function ($q) {
								        return $q->where(['OR' => [ ['Conversations.sender_id' => $this->Auth->user('id')],['Conversations.receiver_id' => $this->Auth->user('id')] ] ]);
								    })
								->count();
		
		$leaderBoardData = $this->Users->getLeaderBoardData(0);
		
		$this->loadModel('UserWeights');
		$UserWeights = $this->UserWeights->find()
			->select(['weight' => 'weight', 'weight_date' => 'weight_date'])
			->where(['user_id' => $this->Auth->user('id')])
			->order([ 'weight_date' => 'DESC'])
			->limit(10);

		if($this->request->is('post')){
			$this->loadModel('UploadImages');
			
			if(isset($this->request->data['UploadImage']['name']) && !empty($this->request->data['UploadImage']['name']))
			{	
				foreach ($this->request->data['UploadImage']['name'] as $key => $value)
				{
					$ext = substr(strtolower(strrchr($value['name'], '.')), 1);
					$FileName = mt_rand().'-'.time().'.'.$ext;
					
					move_uploaded_file($value['tmp_name'],MYPIC_IMAGE_PATH.$FileName);
					
					// store the filename in the array to be saved to the db
					$uploadData['name'] = $FileName;
					$uploadData['type'] = 'pics';
					$uploadData['user_id'] = $this->Auth->user('id');
					$uploadEntity = $this->UploadImages->newEntity($uploadData);
					$this->UploadImages->save($uploadEntity);
				}
			}

			if(isset($this->request->data['UploadVideo']['name']) && !empty($this->request->data['UploadVideo']['name']))
			{	
				$ext = substr(strtolower(strrchr($this->request->data['UploadVideo']['name'], '.')), 1);
				$FileName = mt_rand().'-'.time().'.'.$ext;
				
				move_uploaded_file($this->request->data['UploadVideo']['tmp_name'],MYVIDOES_PATH.$FileName);
				
				// store the filename in the array to be saved to the db
				$uploadData['name'] = $FileName;
				$uploadData['type'] = 'videos';
				$uploadData['user_id'] = $this->Auth->user('id');
				$uploadEntity = $this->UploadImages->newEntity($uploadData);
				$this->UploadImages->save($uploadEntity);
			}
		}
		
		
		$UserWeightsData = [];
		if(!empty($UserWeights)){
			$UserWeights = $UserWeights->toArray();
			$UserWeights = array_reverse($UserWeights);
		
			foreach($UserWeights as $k => $v){
				$UserWeightsData["x"][] = ($v->weight_date->format('d M Y'));
				$UserWeightsData["y"][] = $v->weight;
			}	
		}	
		
		$this->loadModel('UserSubscriptions');
		$subscriptionData = $this->UserSubscriptions->find()->where(['user_id' => $this->Auth->user('id')])->first();
		
		$mealPlans = [];	
		
		if(!empty($subscriptionData) && $subscriptionData->start_date){
			$date1 = new \DateTime($subscriptionData->start_date);
			$date2 = new \DateTime();
			$interval = $date1->diff($date2);
			$startingDays   = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
			$currentWeek 	= floor($startingDays/7)+1;
			$weekDay 		= $startingDays%7;
			
			$date3 			= new \DateTime($subscriptionData->end_date);
			$interval 		= $date3->diff($date2);
			$remainingDays  = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
			
			if($remainingDays >= $startingDays){
				$this->loadModel('DailyMealPlans');
				$startingWeek = ceil($startingDays/7);
				
				$mealPlans 		= $this
									->DailyMealPlans
									->find()
									->select(['DailyMealPlans.week_no','DailyMealPlans.meal_date','DailyMealPlans.week_day','DailyMealPlans.id','DailyMealPlans.text_highlight', 'weak_number' => 'WEEK(meal_date,1)'])
									->where(['week_no' => $currentWeek, 'week_day' => $weekDay+1])
									->order(['DailyMealPlans.week_no' => 'ASC','DailyMealPlans.week_day' => 'ASC'])
									->contain('Meals')
									->first();
			}
		}   

		$UserWeightJson =  json_encode($UserWeightsData);
		$this->set(compact('userDetails','UserWeightJson','shoppingListData','mealPlans','leaderBoardData','notiCount','msgCount','feedList'));
	}

	public function ajax_login() {
		if ($this->request->is('post')) {
			$fbId = $this->request->data['facebook_id'] = $this->request->data['id'];
			unset($this->request->data['id']);
			$user = $this->Users->find('all', array(
				'conditions' => array('Users.facebook_id' => $fbId, 'Users.status_id' => 1)
			))->first();
			if(!empty($user)) {
				if($user['status_id'] == 1) {
					//user exists, just log it in
					$userData = $user->toArray();
					$updateUserInfo = array('fb_access_token' => $this->request->data['fb_access_token']);
					if(empty($userData['logged_in_once'])) {
						$updateUserInfo['logged_in_once'] = 1;
						$updateUserInfo['last_login_check'] = date('Y-m-d H:i:s');
						$redirect = Router::url(['action' => 'fb_share']);
					} else {
						$redirect = Router::url('/');
					}
					$saveData = $this->Users->patchEntity($user, $updateUserInfo);
					$this->Users->save($saveData);
					$this->Auth->setUser($userData);
					echo json_encode(array('return' => 1, 'message' => __('The User has been saved'), 'data' => $redirect));
					$this->_saveLoginCookie($user->email, $user->id);
				} 
				else {
					if(!$user['is_verified']) {
						echo json_encode(array('return' => 0, 'message' => __('Please verify your account')));
					}
					else{
						echo json_encode(array('return' => 0, 'message' => __('Your account has been deactivated. Please contact administrator.')));
					}
				}
			} else {
				$redirect = Router::url(['action' => 'fb_share']);
				unset($this->request->data['id']);
				$this->request->data['group_id'] = USERGROUPID;
				$this->request->data['status_id'] = 1;
				$this->request->data['is_verified'] = 1;
				$this->request->data['password'] = 'technologies';
				$this->request->data['newsletter'] = 1;
				//save new user
				//get fb image and save in system
				$imageName = $this->_getFbImageAndSave($this->request->data);
				if($imageName) {
					$this->request->data['profile_picture'] = $imageName;
					$this->request->data['user_images'] = array(
						array('name' => $imageName)
					);
					$saveData = $this->Users->newEntity($this->request->data, array('associated' => 'UserImages'));
				} else {
					$saveData = $this->Users->newEntity($this->request->data);
				}
				$saveData = $this->Users->save($saveData);
				if ($saveData) {
					//save profile picture
					$this->Auth->setUser($saveData->toArray());
					echo json_encode(array('return' => 1, 'message' => __('The User has been saved'), 'data' => $redirect));
					$this->_saveLoginCookie($saveData->email, $saveData->id);
				} else {
					echo json_encode(array('return' => 0, 'message' => __('The User could not be saved. Please try again')));
				}
			}
		}
		exit;
	}

	private function _saveLoginCookie($user_email, $user_id) {
		//save cookie
		$cookie_name = "_l";
		$cookie_value = md5($user_email . substr(md5(uniqid(mt_rand(), true)), 0, 8)	);
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
		$saveUser = $this->Users->get($user_id);
		$saveUser = $this->Users->patchEntity($saveUser, array('cookie_token' => $cookie_value));
		$this->Users->save($saveUser);
	}

	function getVerificationUrl($user_email) {
		$user = $this->Users->find('all', array(
			'conditions' => array('Users.email' => $user_email), 'recursive' => -1, 'fields' => array('email', 'verification_token')
		))->first();
		$link = "http://" . $_SERVER['SERVER_NAME'] . Router::url(array('controller' => 'Users', 'action' => 'verify_user', $user->email,
			$user->verification_token));

		$url = '';
		$url .= "<a href='$link'>Verify mail</a><br><br>";
		$url .= "If the above link doesn't work, please copy and paste the following URL into your browser's address bar and press Enter:<br>$link";
		return $url;
	}

	public function verify_user() {
		if(isset($this->request->params['pass'][0]) && isset($this->request->params['pass'][1])) {
			$email = $this->request->params['pass'][0];
			$verification_token = $this->request->params['pass'][1];
			$user = $this->Users->find()->where(['Users.email' => $email, 'Users.verification_token' => $verification_token])->first();
			if(!empty($user)) {
				$this->Users->updateAll(
					array('is_verified' => 1, 'status_id' => 1),
					array('id' => $user->id)
				);
				$this->Flash->success(__('User verified successfully. Please login here.'), [
				    'key' => 'loginbox',
				]);
			} else {
				//invalid
				$this->Flash->error(__('Invalid request.'), [
				    'key' => 'loginbox',
				]);
			}
		} else {
			$this->Flash->error(__('Invalid request'), [
			    'key' => 'loginbox',
			]);
		}
		
		$this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'login']);
	}

	public function verifyCaptcha() {
		$this->viewBuilder()->layout(false);
		$this->autoRender = false;
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array('secret' => CAPTCHASECRETKEY, 'response' => $this->request->data['response']);

		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data),
		    ),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		$result = json_decode($result, 1);
		$result = $result['success'];
		if($result) {
			echo json_encode(array('result' => 1));
		} else {
			echo json_encode(array('result' => 0));
		}
	}

	function forgot_password() {
		$this->viewBuilder()->layout(false);
		if($this->request->is(array('post', 'put')))
		{
			$saveUser = $this->Users->find()->where(['Users.email' => $this->request->data['email']])
											->first();
			
			if(empty($saveUser)) {
				echo json_encode(array('status' => 0, 'message' => __('The user does not exist')));
			} 
			else {
				$this->loadComponent('Common');
				$verification_token 	=	$this->Common->generateRandomString(25);
			
				$saveUser = $this->Users->patchEntity($saveUser, array('verification_token' => $verification_token));
				$this->Users->save($saveUser);
				$saveUser		=		$saveUser->toArray();
				
				$linkForgotPass	=	BASE_URL."users/send_password/".$this->request->data['email']."/".$verification_token;
				$token      		=   ['{{name}}','{{forget_url}}'];
				$tokenVal   		=   [	$saveUser['first_name']." ".$saveUser['last_name'],$linkForgotPass];

				$sendMail   		=   $this->Common->_send_email($this->request->data['email'],$token,$tokenVal,'forgot-password');
				echo json_encode(array('status' => 1, 'message' => __('We have sent you reset password link, please check your email.')));
			}
		}
		else{
			return $this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'forgot_password']);
		}
		exit;
	}

	function send_password() {
			$paramsData	=	$this->request->params;
			if(isset($paramsData['pass'][0]) && !empty($paramsData['pass'][0]) && isset($paramsData['pass'][1]) && !empty($paramsData['pass'][0]))
			{
				$saveUser = $this->Users->find()
															->where(['Users.email' => $paramsData['pass'][0],'Users.verification_token' => $paramsData['pass'][1]])
															->first();
				
				if(empty($saveUser)) {
					$this->Flash->error(__('The user does not exist'), [
					    'key' => 'loginbox',
					]);
					return $this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'login']);
				} 
				else {
					$this->loadComponent('Common');
					$FreshPass 	=	$this->Common->generateRandomString(8);
			
					$saveUser = $this->Users->patchEntity($saveUser, array('password' => $FreshPass,'verification_token'=>'','is_verified' => 1,'status_id' => 1));
					$this->Users->save($saveUser);
					$saveUser		=		$saveUser->toArray();
					
					$token      		=   ['{{name}}','{{password}}'];
					$tokenVal   		=   [	$saveUser['first_name']." ".$saveUser['last_name'],$FreshPass];

					$sendMail   		=   $this->Common->_send_email($saveUser['email'],$token,$tokenVal,'reset-password-link');

					$this->set('password_changed',1);
					$this->Flash->success(__('Password successfully sent to your email. Please check your email.'), [
					    'key' => 'loginbox',
					]);
					return $this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'login']);
				}		
			}
			else{
				$this->Flash->error(__('Invalid request.'), [
				    'key' => 'loginbox',
				]);
				return $this->redirect(['controller' => 'pages', 'action' => 'home', 'actionType' => 'login']);
			}
	}

	public function email_share() {
		if($this->request->is(array('post', 'put'))) {
			$emails = array_unique($this->request->data['email']);
			$saveEmailShares = array();
			foreach($emails as $eKey => $email) {
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			        // invalid address
			        unset($emails[$eKey]);
			    } else {
			    	$saveEmailShares[$eKey]['user_id'] = $this->Auth->user('id');
					$saveEmailShares[$eKey]['email'] = $email;
					$saveEmailShares[$eKey]['sent'] = 0;
			    }
			}
			if(!empty($saveEmailShares)) {
				$this->loadModel('EmailShares');
				$saveEmailShares = $this->EmailShares->newEntities($saveEmailShares);
				foreach($saveEmailShares as $saveEmailShare) {
					$rett = $this->EmailShares->save($saveEmailShare);
				}
				$log_file = LOGS . 'email_send.log';
				$address = Router::url(array('controller' => 'Users', 'action' => 'send_shared_emails'), true);
				shell_exec("curl $address >> $log_file 2>&1 &");
			}
			return $this->redirect(['action' => 'home']);
		}
	}

	public function setting() {
		if($this->request->is(array('post', 'put'))) {

			if(isset($this->request->data['notification_settings']) && !empty($this->request->data['notification_settings'])){
				$this->request->data['notification_settings'] = json_encode($this->request->data['notification_settings']);
			}
			$user = $this->Users->get($this->Auth->user('id'), [
				'contain' => ['UserImages']
			]);
			$userDatum = $user->toArray();

			$errorCount = 0; 
			if(isset($this->request->data['email']) && ($userDatum['email'] != $this->request->data['email']))
			{
				$existingProfile = $this->Users->find()->where(['Users.email' => $this->request->data['email']])->first();
				if(!empty($existingProfile)){
					$this->Flash->error(__('User already registered with this email id.'), 'error');
					$errorCount = 1;
				}	
			}

			if($errorCount==0){
				//save new user
				//get fb image and save in system

				if(isset($this->request->data['profile_picture']) && !empty($this->request->data['profile_picture'])){
							$targetFolder 		= Configure::read('UserImage.uploadDir');
							$tempFile 				= $this->request->data['profile_picture']['tmp_name'];
							$targetPath 			= WWW_ROOT . $targetFolder;
							$name 						= time() . $this->request->data['profile_picture']['name'];
							$targetFile 			= rtrim($targetPath,'/') . DS . $name;

							// Validate the file type
							$fileTypes 				= array('jpg','jpeg','png'); // File extensions
							$fileParts 				= pathinfo($name);

							if(isset($fileParts['extension']) && in_array(strtolower($fileParts['extension']), $fileTypes)) {
								move_uploaded_file($tempFile, $targetFile);
								$saveUser['profile_picture'] = $name;
							} else {
								$this->Flash->error(__('File type not supported'), 'error');
								return $this->redirect(array('action' => 'setting'));
							}
				}
				else{
					unset($this->request->data['profile_picture']);
				}

				if(!empty($this->request->data['webcam_image'])) {
				
					//webcam image
					if(!empty($this->request->data['webcam_image'])) {
						$imageData = base64_decode($this->request->data['webcam_image']);
						$uploadDir = Configure::read('UserImage.uploadDir');
						$file_name = "img_".md5(microtime(true).mt_rand(1,3000)).".jpg";
						if(!file_put_contents(WWW_ROOT.$uploadDir.DS.$file_name, $imageData))
						{
							$this->Flash->error(__('Your profile could not be updated, please try again'), 'error');
							return $this->redirect(array('action' => 'setting'));
						} else {
							$saveUser['user_images'][] = array('name' => $file_name);
							if(empty($userDatum['profile_picture'])) {
								$saveUser['profile_picture'] = $file_name;
							}
						}
					}

				}

				if(isset($saveUser) && !empty($saveUser)){
					$saveData = $this->Users->patchEntity($user, $saveUser, array('associated' => 'UserImages'));
				}
				else {
					$saveData = $this->Users->patchEntity($user, $this->request->data);
				}
				
				if($user = $this->Users->save($saveData)) {
					//UPDATE SESSION DATA, DO NOT FORGET THIS ASSHOLE!!
					$this->Auth->setUser($user->toArray());
					if(isset($saveUser['profile_picture']))
						$this->request->session()->write('Auth.User.profile_picture', $saveUser['profile_picture']);
					$this->Flash->success(__('Your profile has been updated'), 'error');
				} else {
					$this->Flash->error(__('Your profile could not be updated, please try again'), 'error');
				}
				return $this->redirect(['action' => 'setting']);
			}
		} else {
			$this->request->data = $this->Users->find('all', array(
				'conditions' => array('Users.id' => $this->Auth->user('id')),
				'contain' => 'UserImages'
			))->first()->toArray();
		}
	}

	public function edit_profile() {
		$this->set('title', 'Edit Profile');
		if($this->request->is(array('post', 'put'))) {
			$user = $this->Users->get($this->Auth->user('id'));
			$userDatum = $user->toArray();
			$this->request->data['profile_status'] = 1;
			$errorCount = 0; 
			if(isset($this->request->data['email']) && ($userDatum['email'] != $this->request->data['email']))
			{
				$existingProfile = $this->Users->find()->where(['Users.email' => $this->request->data['email']])->first();
				if(!empty($existingProfile)){
					$this->Flash->error(__('User already registered with this email id.'), 'error');
					$errorCount = 1;
				}	
			}

			if(isset($this->request->data['username']) && ($userDatum['username'] != $this->request->data['username']))
			{
				$existingProfile = $this->Users->find()->where(['Users.username' => $this->request->data['username']])->first();
				if(!empty($existingProfile)){
					$this->Flash->error(__('User already registered with this username.'), 'error');
					$errorCount = 1;
				}	
			}

			if($errorCount==0){

				$saveData = $this->Users->patchEntity($user, $this->request->data);
				
				if($user = $this->Users->save($saveData)) {
					//UPDATE SESSION DATA, DO NOT FORGET THIS ASSHOLE!!
					$this->Auth->setUser($user->toArray());
					if(isset($saveUser['image']))
						$this->request->session()->write('Auth.User.image', $saveUser['image']);
						$this->Flash->success(__('Your profile has been updated'), 'success');
						$redirect = $this->selectTarget();
						$this->redirect($redirect);
		       	} else {
					$this->Flash->error(__('Your profile could not be updated, please try again'), 'error');
				}
			}
			
		} else {
			$this->request->data = $this->Users->find('all', array(
				'conditions' => array('Users.id' => $this->Auth->user('id')),
				'contain' => 'UploadImages'
			))->first()->toArray();
		}
	}

	public function my_profile() {
		$this->set('title', 'My Profile');
		if($this->request->is(array('post', 'put'))) {
			$user = $this->Users->get($this->Auth->user('id'));
			$userDatum = $user->toArray();
			$errorCount = 0; 

			if(isset($this->request->data['username']) && ($userDatum['username'] != $this->request->data['username']))
			{
				$existingProfile = $this->Users->find()->where(['Users.username' => $this->request->data['username']])->first();
				if(!empty($existingProfile)){
					$this->Flash->error(__('User already registered with this username.'), 'error');
					$errorCount = 1;
				}	
			}

			if($errorCount==0){
				
				if(isset($saveUser) && !empty($saveUser)){
					$saveData = $this->Users->patchEntity($user, $saveUser);
				}
				else {
					$saveData = $this->Users->patchEntity($user, $this->request->data);
				}
				
				if($user = $this->Users->save($saveData)) {
					//UPDATE SESSION DATA, DO NOT FORGET THIS ASSHOLE!!
					$this->Auth->setUser($user->toArray());
					if(isset($saveUser['image']))
						$this->request->session()->write('Auth.User.image', $saveUser['image']);
					$this->Flash->success(__('Your profile has been updated'), 'success');

					$this->request->data = $this->Users->find('all', array(
						'conditions' => array('Users.id' => $this->Auth->user('id')),
						'contain' => 'UploadImages'
					))->first()->toArray();

		       	} else {
					$this->Flash->error(__('Your profile could not be updated, please try again'), 'error');
				}
			}
			
		} else {
			$this->request->data = $this->Users->find('all', array(
				'conditions' => array('Users.id' => $this->Auth->user('id')),
				'contain' => 'UploadImages'
			))->first()->toArray();
		}
	}

	public function coach_assign() {
		$this->set('title', 'Select Coach');
		$this->loadComponent('Common');
		$featuredTrainers = $this->Users->find()->where(['is_featured' => 1,'group_id' => USERGROUPID])->select(['id','image','first_name','last_name','short_description','achievements']);
        
		
		if($this->request->is(array('post', 'put'))) {

			$user = $this->Users->get($this->Auth->user('id'), [
				'contain' => ['Trainers']
			]);
			$userDatum = $user->toArray();
			$this->request->data['profile_status'] = 3;
			$saveData = $this->Users->patchEntity($user, $this->request->data);

			if($user = $this->Users->save($saveData)) {
				//UPDATE SESSION DATA, DO NOT FORGET THIS ASSHOLE!!
				$this->Auth->setUser($user->toArray());

				if(isset($this->request->data['assign_coach']) && $this->request->data['assign_coach']!=1) {
					
					$this->loadModel('Subscriptions');
					$subscriptionData = $this->Subscriptions->find()->where(['id' => $this->Auth->user('subscription_id')])->first();
					$date = date("Y-m-d");// current date
					$expirationData = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " +".$subscriptionData['days']." days")); // membership expiration date 
					
					$trainerDetails = $this->Users->find()->where(['Users.id' => $this->request->data['trainer_id']])->select(['id','first_name','last_name','email'])->first();
					
					// send email to coach when user joins their group
					$token1      		=   ['{{coach_name}}', '{{user_name}}', '{{user_email}}', '{{phone}}', '{{membership}}', '{{expiration}}'];
					$tokenVal1   		=   [$trainerDetails->first_name.' '.$trainerDetails->last_name, $this->Auth->user('first_name')." ".$this->Auth->user('last_name'), $this->Auth->user('email'), $this->Auth->user('mobile'), $subscriptionData->s_name, $expirationData];
					
					$sendMail1   		=   $this->Common->_send_email($trainerDetails->email, $token1, $tokenVal1, 'user-joined-group');
					$sendMail1   		=   $this->Common->_send_email('development@intensofy.com', $token1, $tokenVal1, 'user-joined-group');
					
				} else { }
				
				$linkAssignCoach	=	BASE_URL."backoffice/users/edit/".$this->Auth->user('id');
				$token      		=   ['{{user}}','{{link_url}}'];
				$tokenVal   		=   [$this->Auth->user('first_name')." ".$this->Auth->user('last_name'),$linkAssignCoach];

				$sendMail   		=   $this->Common->_send_email('sean@foodfuelsweightloss.com',$token,$tokenVal,'assign_coach');
				$sendMail   		=   $this->Common->_send_email('suyash@intensofy.com',$token,$tokenVal,'assign_coach');
				
				$this->loadModel('ActivityLogs');
				$checkCount = $this->ActivityLogs->find()->where(['user_id' => $user->trainer_id, 'member_id' => $this->Auth->user('id')])->count();
                if(!$checkCount && $this->Auth->user('group_id') == CLIENTGROUPID && $this->Auth->user('trainer_id') != $this->request->data['trainer_id']){
                	$this->ActivityLogs->updateLog($this->request->data['trainer_id'],8,$id,time());
				}
					
				$redirect = $this->selectTarget();
				$this->redirect($redirect);
			}	
		} else {
			$this->request->data = $this->Users->find('all', array(
				'conditions' => array('Users.id' => $this->Auth->user('id')),
				'contain' => ['Trainers' => ['fields' => ['Trainers.first_name','Trainers.last_name','Trainers.email','Trainers.id']]]
			))->first()->toArray();
		}

		$this->set('featuredTrainers',$featuredTrainers);
	}

	/**
	* Method Name : profile_upload()
	* Author Name : Bharat Borana
	* Creation Date : 24-07-2014
	* Description : is Used to upload the profile image.
	*/

	public function profile_upload()
	{
		$this->viewBuilder()->layout('ajax');
		
		if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name']))
		{
			$img = $_FILES['file'];
			$image_array = $img;
			$image_info = pathinfo($image_array['name']);
			$image_new_name = $this->Auth->user('id');
			$thumbnails = Configure::read('profile_thumb');
			$params = array('size'=>'10240');
			$size_dimensions = array('width'=>150, 'height'=>150);
			$resize_dimesions = array('width'=>650, 'height'=>650);

			$this->Uploader->upload($image_array, USER_IMAGE_PATH, false, $image_new_name, $params, $size_dimensions, $resize_dimesions);
			if($this->Uploader->error)
			{
				$file_error = $this->Uploader->errorMessage;
				$result['status'] = 'failed';
				$result['message'] = $file_error;
			}
			else
			{
				$result['image'] = BASE_URL.USER_IMAGE_URL.$this->Uploader->filename.'?rand='.rand(0,99999);
				$user = $this->Users->get($this->Auth->user('id'));
				$userData['tmp_image_ext'] = $this->Uploader->filename;
				$userPatch = $this->Users->patchEntity($user, $userData);
				$this->Users->save($userPatch);
				$result['status'] = 'success';
			}
			echo json_encode($result);
			exit();
		}
		else
		{
			$file_error = $this->Uploader->errorMessage;
			$result['status'] = 'failed';
			$result['message'] = 'Error! Please try again.';
			echo json_encode($result);
			exit();
		}
	}

	/**
	* Method Name : before_after_upload()
	* Author Name : Bharat Borana
	* Creation Date : 25-01-2016
	* Description : is Used to upload the before after image.
	*/

	public function before_after_upload()
	{
		$this->viewBuilder()->layout('ajax');
		
		if((isset($_FILES['before_image']['name']) && !empty($_FILES['before_image']['name'])) || (isset($_FILES['after_image']['name']) && !empty($_FILES['after_image']['name'])))
		{
			if(isset($_FILES['before_image']['name']) && !empty($_FILES['before_image']['name'])){
			 	$img = $_FILES['before_image'];
				$thumbnails = Configure::read('crop_before');
				$image_new_name = 'before'.$this->Auth->user('id');
			}
			else{
				$thumbnails = Configure::read('crop_after');
				$img = $_FILES['after_image'];
				$image_new_name = 'after'.$this->Auth->user('id');
			}

			$image_array = $img;
			$image_info = pathinfo($image_array['name']);
			
			$params = array('size'=>'10240');
			$size_dimensions = array('width'=>150, 'height'=>300);
			$resize_dimesions = array('width'=>650, 'height'=>650);

			$this->Uploader->upload($image_array, USER_IMAGE_PATH, false, $image_new_name, $params, $size_dimensions, $resize_dimesions);
			if($this->Uploader->error)
			{
				$file_error = $this->Uploader->errorMessage;
				$result['status'] = 'failed';
				$result['message'] = $file_error;
			}
			else
			{
				$result['image'] = BASE_URL.USER_IMAGE_URL.$this->Uploader->filename.'?rand='.rand(0,99999);
				$user = $this->Users->get($this->Auth->user('id'));
				$userData['tmp_image_ext'] = $this->Uploader->filename;
				$userPatch = $this->Users->patchEntity($user, $userData);
				$this->Users->save($userPatch);
				$result['status'] = 'success';
			}
			echo json_encode($result);
			exit();
		}
		else
		{
			$file_error = $this->Uploader->errorMessage;
			$result['status'] = 'failed';
			$result['message'] = 'Error! Please try again.';
			echo json_encode($result);
			exit();
		}
	}



	/**
	* Method Name : cropnrotate()
	* Author Name : Bharat Borana
	* Creation Date : 23-01-2016
	* Description : is Used to upload the profile image.
	*/

	function cropnrotate()
	{
		$this->viewBuilder()->layout('ajax');
		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'crop';
		$image_name = explode('?',$_REQUEST['image_name']);
		$image_path = USER_IMAGE_PATH.$image_name[0];
		
		$result['status'] = 'failed';
		$result['message'] = 'Please try after some time.';
		
		$file_dimension['x'] = trim($_REQUEST['img_x']);
		$file_dimension['y'] = trim($_REQUEST['img_y']);
		$file_dimension['width'] = trim($_REQUEST['img_width']);
		$file_dimension['height'] = trim($_REQUEST['img_heigth']);
		$file_dimension['rotate'] = trim($_REQUEST['img_rotate']);
		$filedata['source_path'] = $image_path;
		$filedata['file_name'] = $image_name[0];
		$filedata['dest_dir'] = USER_IMAGE_PATH;
		$params['thumb'] = Configure::read('profile_thumb');
		$params['remove'] = false;

		//Update the name of image of user
		$user_info = $this->Users->get($this->Auth->user('id'));
		if(isset($user_info) && !empty($user_info)){
			$user_info = $user_info->toArray();
		}

		if($user_info['image']!='' && $user_info['tmp_image_ext'] != $user_info['image'])
		{
			if (file_exists(USER_IMAGE_PATH.$user_info['image']))
			{
				@unlink(USER_IMAGE_PATH.$user_info['image']);
				@unlink(USER_IMAGE_PATH.'Profile_'.$user_info['image']);
				@unlink(USER_IMAGE_PATH.'Large_'.$user_info['image']);
				@unlink(USER_IMAGE_PATH.'Profile_'.$user_info['image']);
				@unlink(USER_IMAGE_PATH.'thumb_'.$user_info['image']);
			}
		}

		$this->Uploader->crop($filedata, $file_dimension, true, $params);

		$result['status'] = 'success';
		$result['message'] = 'Uploaded successfully.';
		$result['image'] = BASE_URL.USER_IMAGE_URL.PROFILE_IMAGE.$image_name[0];
		
		if ($user_info['tmp_image_ext'] != '')
		{
			$user = $this->Users->get($this->Auth->user('id'));
			$userData['tmp_image_ext'] = '';
			$userData['image'] = $user_info['tmp_image_ext'];
			
			$userPatch = $this->Users->patchEntity($user, $userData);
			$this->Users->save($userPatch);
		}
		
		echo json_encode($result);
		exit;
	}

	function cropnrotateBeforeAfter()
	{
		$this->viewBuilder()->layout('ajax');
		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'crop';
		$image_name = explode('?',$_REQUEST['image_name']);
		$image_path = USER_IMAGE_PATH.$image_name[0];
		
		$result['status'] = 'failed';
		$result['message'] = 'Please try after some time.';
		
		$file_dimension['x'] = trim($_REQUEST['img_x']);
		$file_dimension['y'] = trim($_REQUEST['img_y']);
		$file_dimension['width'] = trim($_REQUEST['img_width']);
		$file_dimension['height'] = trim($_REQUEST['img_heigth']);
		$file_dimension['rotate'] = trim($_REQUEST['img_rotate']);
		$filedata['source_path'] = $image_path;
		$filedata['file_name'] = $image_name[0];
		$filedata['dest_dir'] = USER_IMAGE_PATH;
		if(trim($_REQUEST['type']) == 'before')
			$params['thumb'] = Configure::read('crop_before');
		else
			$params['thumb'] = Configure::read('crop_after');

		$params['remove'] = false;

		//Update the name of image of user
		$user_info = $this->Users->get($this->Auth->user('id'));
		if(isset($user_info) && !empty($user_info)){
			$user_info = $user_info->toArray();
		}

		if($user_info[trim($_REQUEST['type']).'_image']!='' && $user_info['tmp_image_ext'] != $user_info['image'])
		{
			if (file_exists(USER_IMAGE_PATH.$user_info[trim($_REQUEST['type']).'_image']))
			{
				/*
				@unlink(USER_IMAGE_PATH.$user_info[trim($_REQUEST['type']).'_image']);
				if(trim($_REQUEST['type']) == 'before')
					@unlink(USER_IMAGE_PATH.USER_BEFORE.$user_info['image']);
				else
					@unlink(USER_IMAGE_PATH.USER_AFTER.$user_info['image']);
				*/
			}
		}

		$this->Uploader->crop($filedata, $file_dimension, true, $params);

		$result['status'] = 'success';
		$result['message'] = 'Uploaded successfully.';

		if(trim($_REQUEST['type']) == 'before')
			$result['image'] = BASE_URL.USER_IMAGE_URL.USER_BEFORE.$image_name[0];
		else
			$result['image'] = BASE_URL.USER_IMAGE_URL.USER_AFTER.$image_name[0];
			
		if ($user_info['tmp_image_ext'] != '')
		{
			$user = $this->Users->get($this->Auth->user('id'));
			$userData['tmp_image_ext'] = '';
			$userData['is_featured'] = 0;
			if(trim($_REQUEST['type']) == 'before')
				$userData['before_image'] = $user_info['tmp_image_ext'];
			else
				$userData['after_image'] = $user_info['tmp_image_ext'];
			
			$userPatch = $this->Users->patchEntity($user, $userData);
			$this->Users->save($userPatch);
		}
		
		echo json_encode($result);
		exit;
	}

	public function delete_profile_image()
	{
		$this->viewBuilder()->layout('ajax');
		
		$this->User->id = $this->Auth->user('id');

		//Remove all images
		$user_info = $this->User->findById($this->User->id);
		if (file_exists(USER_IMAGE_PATH.$user_info['User']['image']))
		{
			unlink(USER_IMAGE_PATH.$user_info['User']['image']);
			unlink(USER_IMAGE_PATH.'Profile_'.$user_info['User']['image']);
			unlink(USER_IMAGE_PATH.'Large_'.$user_info['User']['image']);
			unlink(USER_IMAGE_PATH.'Profile_'.$user_info['User']['image']);
			unlink(USER_IMAGE_PATH.'thumb_'.$user_info['User']['image']);
		}

		$data['User']['image'] = '';
		if($this->User->save($data))
		{
			$result['status'] = 'success';
		}
		else
		{
			$result['status'] = 'failed';
			$result['message'] = IMAGE_NOT_DELETED;
		}
		echo json_encode($result);
		exit();
	}

	public function overview() {
		$this->request->data = $this->Users->find('all', array(
			'conditions' => array('Users.id' => $this->Auth->user('id')),
			'contain' => 'UserImages'
		))->first();

		if(!empty($this->request->data))
			$this->request->data = $this->request->data->toArray();
	}


	private function _getFbImageAndSave($data) {
		if(isset($data['pic_url']) && !empty($data['pic_url'])){
			$this->viewBuilder()->layout('ajax');
			
			$image_path = $data['pic_url'];
			
			$result['status'] = 'failed';
			$result['message'] = 'Please try after some time.';
			
			list($width, $height, $type, $attr) = getimagesize($image_path);

			$file_dimension['x'] = 0;
			$file_dimension['y'] = 0;
			$file_dimension['width'] = $width;
			$file_dimension['height'] = $height;
			$file_dimension['rotate'] = 0;
			$filedata['source_path'] = $image_path;
			$filedata['file_name'] = md5(microtime(true).mt_rand(1,3000)).".jpg";
			$filedata['dest_dir'] = USER_IMAGE_PATH;
			$params['thumb'] = Configure::read('profile_thumb');
			$params['remove'] = false;

			$this->Uploader->crop($filedata, $file_dimension, true, $params);

			$result['status'] = 'success';
			$result['message'] = 'Uploaded successfully.';
			$result['image'] = BASE_URL.USER_IMAGE_URL.PROFILE_IMAGE.$filedata['file_name'];
			
			return $filedata['file_name'];
		}
	}

	public function changeProfilePic() {
		$this->request->allowMethod(['post', 'put']);
		$id = $this->request->data['id'];
		/*
		if(($id) && !$this->_isOwnedBy('UserImages', $id)) {
			throw new NotFoundException('Page not Found');
		}
		*/
		$userImage = $this->Users->UserImages->find('all', [
			'conditions' => ['id' => $id, 'user_id' => $this->Auth->user('id')],
			'fields' => ['id', 'name']
		])->first()->toArray();
		$user['profile_picture'] = $userImage['name'];
		$saveUser = $this->Users->patchEntity($this->Users->get($this->Auth->user('id')), $user);
		if($this->Users->save($saveUser)) {
			$this->request->session()->write('Auth.User.profile_picture', $user['profile_picture']);
			$this->Flash->success(__('Your profile picture has been updated'));
			echo json_encode(array('return' => 1));
		} else {
			echo json_encode(array('return' => 0));
		}
		exit;
	}

	public function remove_image() {
		$this->request->allowMethod(['post', 'delete']);
		$id = $this->request->data['id'];
		$userImage = $this->Users->UserImages->find('all', [
			'conditions' => ['id' => $id, 'user_id' => $this->Auth->user('id')],
			'fields' => ['id', 'name']
		])->first();
		$userImageData = $userImage->toArray();
		if ($this->Users->UserImages->delete($userImage)) {
			$this->Users->updateAll(
				array('profile_picture' => null),
				array('id' => $this->Auth->user('id'), 'profile_picture' => $userImageData['name'])
			);
			$expression = new QueryExpression('image_count = image_count - 1');
			$this->Users->updateAll(
				array($expression),
				array('id' => $this->Auth->user('id'))
			);
			$this->request->session()->write('Auth.User.profile_picture', null);
			$this->Flash->success(__('The image has been deleted'));
			echo json_encode(array('return' => 1));
		} else {
			$this->Flash->error(__('The image could not be deleted. Please, try again'));
			echo json_encode(array('return' => 0));
		}
		exit;
	}

	public function change_password() {
		$this->set('title', 'Change Password');
		$userObj = $this->Users->get($this->Auth->user('id'), ['fields' => ['id', 'password']]);
		$userdata = $userObj->toArray();
		if($this->request->is('post')){
			$dbPassword = $userdata['password'];

			$passCheck = (new DefaultPasswordHasher)->check($this->request->data['old_password'], $dbPassword);
			if(!$passCheck) {
				$this->Flash->error(__('Please fill the correct old password'));
			}
			elseif($this->request->data['password'] != $this->request->data['confirm_password']){
				$this->Flash->error(__('Password and confirm password does not match.'));
			}
			else {
				$saveUser = $this->Users->patchEntity($userObj, ['password' => $this->request->data['password']]);
				$saveUser = $this->Users->save($saveUser);
				if($saveUser ) {
					$this->Flash->success(__('Your password has been updated'));
					$this->request->session()->write('Auth.User.password', $saveUser->password);
				} else {
					$this->Flash->error(__('The password could not be updated. Please try again'));
				}
			}
		}
		$this->set('userdata',$userdata);
	}

	public function set_lang($lang){
		$this->request->session()->write('Config.language', $lang);
		$this->redirect($_SERVER['HTTP_REFERER']);
	}

	public function set_timezone(){
		if($this->request->is('ajax')){
			$timeZone = $this->request->data['timezone'];
			$this->request->session()->write('client_timezone', $timeZone);
		}
		echo 1;
		exit;
	}

	/**
	 * Contact method
	 *
	 * @return void Redirects on successful add, renders view otherwise.
	 */
	public function enquiry()
	{
		$this->viewBuilder()->layout('ajax');
		$this->loadModel('Enquiries');
		$enq = $this->Enquiries->newEntity();
		if ($this->request->is('post')) {
			$enq = $this->Enquiries->patchEntity($enq, $this->request->data);
			if ($this->Enquiries->save($enq)) {
				echo json_encode(array('status' => 1, 'message' => __('Thank you for contacting with us. We will back to you shortly.')));
			} else {
				$errors = $this->_getValidationMessages($enq->errors());
				echo json_encode(array('status' => 0, 'message' => __($errors)));
			}
		}
		exit;
	}

	public function subscribe_me()
	{
		$this->viewBuilder()->layout('ajax');
		$this->loadModel('Subscribers');
		$enq = $this->Subscribers->newEntity();
		if ($this->request->is('post')) {
			$enq = $this->Subscribers->patchEntity($enq, $this->request->data);
			if ($this->Subscribers->save($enq)) {
				echo json_encode(array('status' => 1, 'message' => __('Subscribed successfully. Thank you for your interest.')));
			} else {
				$errors = $this->_getValidationMessages($enq->errors());
				echo json_encode(array('status' => 0, 'message' => __($errors)));
			}
		}
		exit;
	}

	public function testEmail(){
		$tokenArray = ['{{name}}','{{activation_url}}'];
		$tokenValueArray = ["Bharat Borana", "http://rediffmail.com"];
		$this->loadComponent('Common');
		$this->Common->_send_email("boranab@mailinator.com",$tokenArray, $tokenValueArray,'verify-email');
		exit;
	}

	public function payment_select(){
		$this->set('title', 'Payment');
        
		$this->loadModel('Subscriptions');
        $Subscriptions = $this->Subscriptions->find()->toArray();
        $this->set('Subscriptions',$Subscriptions);
    }

    public function pay_me($id=null){
		$this->set('title', 'Payment');
		
		$this->loadModel('Subscriptions');
		$this->loadModel('UserSubscriptions');
    	if(!$id){
    		$userEntity = $this->Users->get($this->Auth->user('id'));
        	$id = $userEntity->subscription_id;
    	}
    				
    	$Subscriptions = $this->Subscriptions->find()->where(['Subscriptions.id' => $id, 'Subscriptions.status' => 1])->first();
		if(!empty($Subscriptions))
			$Subscriptions = $Subscriptions->toArray();
    
        if($this->request->is(array('post', 'put'))) {
        	$this->loadComponent('Common');
        	
        	$discAmount	=	0;
			$promotion_code_id = 0;
			if(isset($this->request->data['discount_code']) && !empty($this->request->data['discount_code'])){
				$this->loadModel('PromotionCodes');
				$promoCode 			= $this->PromotionCodes->checkMyCode($this->request->data['discount_code'],$Subscriptions['amount']);
				$discAmount 		= $promoCode['discAmount'];
				$promotion_code_id 	= $promoCode['promotion_code_id'];
			}

			$afterDiscount = $Subscriptions['amount']-$discAmount;

			if($afterDiscount < 0)
				$afterDiscount = 0;


			$this->request->data['user_subscriptions'][0]['subscription_id'] 	= $Subscriptions['id'];
			$this->request->data['user_subscriptions'][0]['paid_date'] 			= new Time(Date('Y-m-d'));
			$this->request->data['user_subscriptions'][0]['start_date'] 		= new Time(Date('Y-m-d'));
			$this->request->data['user_subscriptions'][0]['end_date'] 			= new Time(Date('Y-m-d',strtotime('+ '.$Subscriptions['days'].' days')));
			$this->request->data['user_subscriptions'][0]['discounted_amount'] 	= 0;
			$this->request->data['user_subscriptions'][0]['total_amount'] 		= $Subscriptions['amount'];
			$this->request->data['user_subscriptions'][0]['promotion_code_id'] 	= $promotion_code_id;
			$this->request->data['user_subscriptions'][0]['discounted_amount'] 	= $discAmount;
			$this->request->data['user_subscriptions'][0]['final_amount'] 		= $afterDiscount;
			$this->request->data['user_subscriptions'][0]['status_id']			=  1; 

			$this->request->data['is_paid'] 	= 1;
			$this->request->data['paid_date'] 	= new Time(Date('Y-m-d'));

			if($afterDiscount == 0){
				$this->loadComponent('Common');
				$randString = $this->Common->generateRandomString(25);
			
				$this->request->data['user_subscriptions'][0]['transaction_id']		= $randString;
				$userEntity = $this->Users->get($this->Auth->user('id'));
				$user = $this->Users->patchEntity($userEntity, $this->request->data, ['associated' => ['UserSubscriptions']]);
				if ($save_user = $this->Users->save($user)){

					$this->Auth->setUser($user->toArray());
					$this->Flash->success('Your payment has been successful. Your transaction id is '.$this->request->data['user_subscriptions'][0]['transaction_id']);
					$redirect = Router::url(['controller' => 'users', 'action' => 'coach_assign'], true);
	        		$this->redirect($redirect);
				}
			}
			else{
				$detailsAre['METHOD'] 			= urlencode("DoDirectPayment"); 	 #The action taken in the Pay request (that is, the PAY action)
				$detailsAre['PAYMENTACTION'] 	= urlencode("Sale"); 	#Standard Sandbox App ID
				$detailsAre['VERSION'] 			= urlencode("58.0"); 		#Address from which request is sent
				$detailsAre['CURRENCYCODE'] 	= urlencode("USD"); 	#The currency, e.g. US dollars
				$detailsAre['CREDITCARDTYPE'] 	= $this->request->data['card_type'];
				$detailsAre['AMT'] 				= $afterDiscount; 
				$detailsAre['ACCT']  			= $this->request->data['card_number']; 
				$detailsAre['EXPDATE']  		= $this->request->data['expires_on_month'].$this->request->data['expires_on_year']; 
				$detailsAre['CVV2']  			= $this->request->data['security_code']; 
				$detailsAre['FIRSTNAME'] 		= $this->Auth->user('first_name');
				$detailsAre['LASTNAME'] 		= $this->Auth->user('last_name'); 
				
				$url = 'https://api-3t.paypal.com/nvp';
				$tokenData = $this->Common->pay_me($detailsAre,$url);
				
				if(isset($tokenData['ACK']) && $tokenData['ACK']=='Success'){
					/*
					$detailsAre['PAYMENTACTION'] 	= urlencode("Sale"); 	#Standard Sandbox App ID
					$url = 'https://api-3t.paypal.com/nvp';
					$tokenData = $this->Common->pay_me($detailsAre,$url);
					if(isset($tokenData['ACK']) && $tokenData['ACK']=='Success'){
					}
					elseif(isset($tokenData['ACK']) && $tokenData['ACK']=='Failure'){
						$this->Flash->error($tokenData['L_LONGMESSAGE0']);
					}
					*/
					
					$this->request->data['user_subscriptions'][0]['transaction_id']		= $tokenData['TRANSACTIONID'];
					$userEntity = $this->Users->get($this->Auth->user('id'));
					$user = $this->Users->patchEntity($userEntity, $this->request->data, ['associated' => ['UserSubscriptions']]);
					if ($save_user = $this->Users->save($user)){

						$this->Auth->setUser($user->toArray());
				
						$this->Flash->success('Your payment has been successful. Your transaction id is '.$tokenData['TRANSACTIONID']);
						$redirect = Router::url(['controller' => 'users', 'action' => 'coach_assign'], true);
		        		$this->redirect($redirect);
					}	
				}
				elseif(isset($tokenData['ACK']) && $tokenData['ACK']=='Failure'){
					$this->Flash->error($tokenData['L_LONGMESSAGE0']);
				}
			}
        }
        else{
        	if(!empty($Subscriptions)){
				$userSub['subscription_id'] = $Subscriptions['id'];

        		$userEntity = $this->Users->get($this->Auth->user('id'));
        		$updatedEntity = $this->Users->patchEntity($userEntity,$userSub);
        		$this->Users->save($updatedEntity);
	        }
	        else{
	        	$this->Flash->error(__('Invalid subscription. Please try again.'));
	        	return $this->redirect(['action' => 'payment_select']);
	        }
        }
		
        $this->set('Subscriptions',$Subscriptions);
    }

    public function get_trainer()
	{
		$this->viewBuilder()->layout('ajax');

		$this->autoRender = false;

		$term = strtolower($this->request->query['term']);

		$conditions = ['Users.group_id' => USERGROUPID, 'OR' => [ 'Users.first_name LIKE' => "$term%", 'Users.last_name LIKE' => "$term%", 'Users.email LIKE' => "$term%"], 'Users.status_id' => 1];

		$trainers = $this->Users
				    ->find()
				    ->select(['id', 'first_name', 'last_name'])
				    ->where($conditions);

		$trainers = $trainers->toArray();
		$resposeArray = array();
		if(isset($trainers) && !empty($trainers)){
			foreach ($trainers as $key => $value) {
				$resposeArray[] = ['id' => $value['id'], 'value' => $value['first_name']." ".$value['last_name']];
			}
		}
		echo json_encode($resposeArray);
		exit;
	}

    public function checkout(){
			if(isset($this->request->pass[0]) && isset($this->request->pass[1])){
				$email			=	$this->request->pass[0];
				$veriToken	=	$this->request->pass[1];
			}
			if(empty($email) || empty($veriToken)){
				$email 			= $this->request->data['email'];
				$veriToken 	= $this->request->data['verification_token'];
			}

			$this->loadModel('Offers');
			$offerDetails = $this->Offers->findDetails(['Offers.verification_token'	=>	$veriToken , 'Offers.close_date >='	=> Date('Y-m-d H:i:s',strtotime('-24 Hours'))]);
			
			if(!empty($offerDetails)){
				if(empty($offerDetails['voucher'])){
					$this->request->data['email']								=	$offerDetails['user']['email'];
					$this->request->data['verification_token']	=	$offerDetails['verification_token'];		
				}
				else{
					$this->Flash->error(__('You have already paid for this offer. Please contact administrator for more information.'));
					return $this->redirect(array('controller'	=>	'Cmspages',	'action'	=>	'index',	6));		
				}
			}
			else{
				$this->Flash->error(__('Your offer has expired/not found. Please contact administrator for more information.'));
				return $this->redirect(array('controller'	=>	'Cmspages',	'action'	=>	'index',	6));
			}

					
			if($this->request->is('post')){

				$this->request->data['user_id']							=	$offerDetails['user_id'];
				$this->request->data['offer_id']						=	$offerDetails['id'];
				
				$discAmount	=	0;
				$promotion_code_id = 0;
				if(isset($this->request->data['promotional_code']) && !empty($this->request->data['promotional_code'])){
					$this->loadModel('PromotionCodes');
					$promoCode 					= $this->PromotionCodes->checkMyCode($this->request->data['promotional_code'],$offerDetails['offer_value']);
					$discAmount 				= $promoCode['discAmount'];
					$promotion_code_id 	= $promoCode['promotion_code_id'];
				}

				$afterDiscount = $offerDetails['offer_value']-$discAmount;

				if($afterDiscount < 0)
					$afterDiscount = 0;

				$this->request->data['offer_value']								=	$offerDetails['offer_value'];
				$this->request->data['discount_amt']							=	$discAmount;
				$this->request->data['promotion_code_id']						=	$promotion_code_id;
				$this->request->data['total_value']								=	$afterDiscount;
				if($afterDiscount == 0){
					//voucher code random string
					$this->loadComponent('Common');
					$voucherCode	=	$this->Common->generateRandomString(10);

					$this->loadModel('TempInvoices');
					$id = 1;
					$lastInvoiceId = $this->TempInvoices->find()->select('TempInvoices.id')->order('TempInvoices.id DESC')->first();
					if(!empty($lastInvoiceId)){
						$lastInvoice = $lastInvoiceId->toArray();
						$id = $lastInvoice['id'];
						$tempInv = $this->TempInvoices->newEntity();
						$this->TempInvoices->save($tempInv);
					}

					$invoiceName 					= 'INV'.$id;
					$paymentName 					= 'Zero offer value';
					$paymentTransactionId = 'NA(Zero offer value)';
					$paidAmount						=	$afterDiscount;

					$myFinalVoucher	=	[
										'user_id'			=>	$offerDetails['user_id'],
										'offer_id'		=>	$offerDetails['id'],
										'code'				=>	$voucherCode,
										'code_expiry'	=>	Date('Y-m-d',strtotime(Date('Y-m-d',strtotime('+1 months')).' +1 weeks')),
									];

					$myFinalVoucher['voucher_address']  
													=	[
															'first_name'			=>	$this->request->data['first_name'],
															'last_name'				=>	$this->request->data['last_name'],
															'address_line_1'	=>	$this->request->data['address_line_1'],
															'address_line_2'	=>	$this->request->data['address_line_2'],
															'address_line_3'	=>	$this->request->data['address_line_3'],
															'city'						=>	$this->request->data['city'],
															'state'						=>	$this->request->data['state'],
															'postal_code'			=>	$this->request->data['postal_code'],
															'mobile'					=>	$this->request->data['mobile'],
														];

					$myFinalVoucher['payment_detail'] 
													=	[
															'payment_type'			=>	0,
															'transaction_id'		=>	$paymentTransactionId,
															'offer_value'				=>	$offerDetails['offer_value'],
															'discount_amt'			=>	$discAmount,
															'promotion_code_id'	=>	$promotion_code_id,
															'total_value'				=>	$paidAmount,
														];

						$myFinalVoucher['invoice'] 
													=	[
															'user_id'			=>	$offerDetails['user_id'],
															'number'			=>	$invoiceName,
														];

						$myFinalVoucher['offer'] 
													=	[
															'id'				=>	$offerDetails['id'],
															'status_id'	=>	4,
														];								
					
				$this->loadModel('Vouchers');

				$voucher 	=		$this->Vouchers->newEntity($myFinalVoucher,['associated' => ['VoucherAddresses','PaymentDetails','Invoices','Offers']]);
				if ($this->Vouchers->save($voucher)) {

				$address 	=	'';
				if(isset($this->request->data['address_line_1']) && !empty($this->request->data['address_line_1']))
					$address .= $this->request->data['address_line_1'].',';

				if(isset($this->request->data['address_line_2']) && !empty($this->request->data['address_line_2']))
					$address .= $this->request->data['address_line_2'].',';

				if(isset($this->request->data['address_line_3']) && !empty($this->request->data['address_line_3']))
					$address .= $this->request->data['address_line_3'].',';

				if(isset($this->request->data['city']) && !empty($this->request->data['city']))
					$address .= $this->request->data['city'].',';

				if(isset($this->request->data['state']) && !empty($this->request->data['state']))
					$address .= $this->request->data['state'].',';

				if(isset($this->request->data['zipcode']) && !empty($this->request->data['zipcode']))
					$address .= $this->request->data['zipcode'].',';
	  	
				//Send an email to winner
              	$token      =   ['{{name}}','{{item_number}}','{{transaction_id}}','{{voucher_code}}','{{amount}}','{{payment_type}}','{{deal_name}}','{{payment_value}}','{{fee}}','{{total}}','{{date}}','{{address}}','{{phone}}'];
              	$tokenVal   =   [
              									$this->request->data['first_name']." ".$this->request->data['last_name'],
              									'ITM'.$offerDetails['service_listing']['item_id'],
              									$paymentTransactionId,
              									$voucherCode,
              									$paidAmount,
              									$paymentName,
              									$offerDetails['service_listing']['deal']['title'],
              									$offerDetails['offer_value'],
              									$discAmount,
              									$paidAmount,
              									Date('Y-m-d H:i:s'),
              									$address,
              									$this->request->data['mobile']
              								];
              
              	$sendMail   =   $this->Common->_send_email($offerDetails['user']['email'],$token,$tokenVal,'offer-success');
				//Send an email to winner
              
              	$this->Flash->success(__('You have successfully purchased this deal.'));
		        return $this->redirect(['action' => 'view',$offerDetails['service_listing']['id']]);
            } else {
                $this->Flash->error(__('The deal could not be saved. Please, try again.'));
            }
				}
				else{
					if(isset($this->request->data['payment_type']) && !empty($this->request->data['payment_type'])){
					
					if($this->request->data['payment_type']==1){
						$detailsAre['METHOD'] 												= "SetExpressCheckout";
						$detailsAre['VERSION'] 												= 93; 
						
						$detailsAre['RETURNURL'] 											= BASE_URL."deals/success"; 		# URL of your payment confirmation page
						$detailsAre['CANCELURL'] 											= BASE_URL."deals/fail"; 		# URL redirect if customer cancels payment
						$detailsAre['EMAIL'] 													= $offerDetails['user']['email'];

						$detailsAre['PAYMENTREQUEST_0_PAYMENTACTION'] = 'SALE';
						$detailsAre['PAYMENTREQUEST_0_AMT'] 					= round($afterDiscount,2);
						$detailsAre['PAYMENTREQUEST_0_CURRENCYCODE'] 	= 'THB';
						$detailsAre['PAYMENTREQUEST_0_DESC'] 					= __('Payment regarding to winning offer.');
						$detailsAre['PAYMENTREQUEST_0_CUSTOM'] 				= $offerDetails['id'];
						
						$detailsAre['PAYMENTREQUEST_0_SHIPTONAME'] 		= $this->request->data['first_name'].' '.$this->request->data['last_name'];
						$detailsAre['PAYMENTREQUEST_0_SHIPTOSTREET'] 	= $this->request->data['address_line_1'];
						$detailsAre['PAYMENTREQUEST_0_SHIPTOSTREET2'] = $this->request->data['address_line_2'].(isset($this->request->data['address_line_3']))?', '.$this->request->data['address_line_3']:'';
						$detailsAre['PAYMENTREQUEST_0_SHIPTOCITY'] 		= $this->request->data['city'];
						$detailsAre['PAYMENTREQUEST_0_SHIPTOSTATE'] 	= $this->request->data['state'];
						$detailsAre['PAYMENTREQUEST_0_SHIPTOZIP'] 		= $this->request->data['postal_code'];
						$detailsAre['PAYMENTREQUEST_0_SHIPTOPHONENUM']= $this->request->data['mobile'];

						$detailsAre['L_PAYMENTREQUEST_0_NAME0']				= $offerDetails['service_listing']['deal']['title'];
						$detailsAre['L_PAYMENTREQUEST_0_DESC0'] 			= strstr($offerDetails['service_listing']['deal']['description'],0,100);
						$detailsAre['L_PAYMENTREQUEST_0_AMT0'] 				= round($afterDiscount,2);
						$detailsAre['L_PAYMENTREQUEST_0_QTY0'] 				= 1;
						$detailsAre['L_PAYMENTREQUEST_0_NUMBER0'] 		= $offerDetails['service_listing']['item_id'];

						$this->loadComponent('Common');
						$tokenData = $this->Common->pay_me($detailsAre);
						
						if(!empty($tokenData) && isset($tokenData['ACK']) && $tokenData['ACK']=='Success')
						{
							$this->request->session()->write('requestData',$this->request->data);
							return $this->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$tokenData['TOKEN']);
						}
						else
						{ 
							$this->Flash->error($tokenData['L_LONGMESSAGE0']);
						}
					}
					else if($this->request->data['payment_type']==2){
						$paysBuysdetailsAre['front_url'] 				= 		BASE_URL."deals/paysbuy_back"; 
						$paysBuysdetailsAre['back_url'] 				= 		BASE_URL."deals/paysbuy_back"; 	
						$paysBuysdetailsAre['email'] 						= 		$offerDetails['user']['email'];
						$paysBuysdetailsAre['after_discount'] 	= 		round($afterDiscount,2);
						
						$paysBuysdetailsAre['name'] 						= 		$this->request->data['first_name'].' '.$this->request->data['last_name'];
						$paysBuysdetailsAre['address'] 					= 		$this->request->data['address_line_1'];
						$paysBuysdetailsAre['address'] 				 .=', '.$this->request->data['address_line_2'].(isset($this->request->data['address_line_3']))?', '.$this->request->data['address_line_3']:'';
						$paysBuysdetailsAre['address'] 				 .=', '.$this->request->data['city'];
						$paysBuysdetailsAre['address'] 		     .=', '.$this->request->data['state'];
						$paysBuysdetailsAre['address'] 		     .=', '.$this->request->data['postal_code'];
						$paysBuysdetailsAre['mobile']						=			$this->request->data['mobile'];

						$paysBuysdetailsAre['deal_title']				= 		$offerDetails['service_listing']['deal']['title'];
						
						$id = 1;
						$this->loadModel('TempInvoices');
						$lastInvoiceId = $this->TempInvoices->find()->select('TempInvoices.id')->order('TempInvoices.id DESC')->first();
						if(!empty($lastInvoiceId)){
							$lastInvoice = $lastInvoiceId->toArray();
							$id = $lastInvoice['id'];
							$tempInv = $this->TempInvoices->newEntity();
							$this->TempInvoices->save($tempInv);
						}

						$invoiceName 					= 'INV'.$id;
						$paysBuysdetailsAre['item_id'] 					=			$invoiceName;

						$this->loadComponent('Paysbuy');
					
						$tokenData = $this->Paysbuy->paynow($paysBuysdetailsAre);
						if(isset($tokenData['status']) && $tokenData['status']==1)
						{
							$this->request->session()->write('requestData',$this->request->data);
							return $this->redirect('https://www.paysbuy.com/api_payment/paynow.aspx?refid='.$tokenData['data']);
						}
						else
						{ 
							$this->Flash->error($tokenData['message']);
						}
					}
					}
				}	
			}
			else{
				//save cookie
				$this->loadModel('Users');
				$cookie_name = "_l";
				$cookie_value = md5($offerDetails['user']['email'] . substr(md5(uniqid(mt_rand(), true)), 0, 8)	);
				setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
				$saveUser = $this->Users->get($offerDetails['user']['id']);
				$saveUser = $this->Users->patchEntity($saveUser, array('cookie_token' => $cookie_value));
				$this->Users->save($saveUser);
			}
			
			$this->set('offerDetails',$offerDetails);
	}

	public function success(){
		$tokenVal = $this->request->query['token'];
		$this->loadComponent('Common');
		$error = 0;	
		if(isset($tokenVal) && !empty($tokenVal)){
			
			$detailsAre['METHOD'] 	= "GetExpressCheckoutDetails";
			$detailsAre['VERSION']	= 93; 
			$detailsAre['TOKEN']		=	$tokenVal;

			$tokenData = $this->Common->pay_me($detailsAre);

			$requestData = $this->request->session()->read('requestData');

			if(isset($tokenData['PAYERSTATUS']) && !empty($tokenData['PAYERSTATUS']) && !empty($requestData)){

				$detailsAre['METHOD'] 												= "DoExpressCheckoutPayment";
				$detailsAre['VERSION']												= 93; 
				$detailsAre['TOKEN']													=	$tokenData['TOKEN'];
				$detailsAre['PAYERID']												=	$tokenData['PAYERID'];
				$detailsAre['PAYMENTREQUEST_0_PAYMENTACTION']	=	'SALE';
				$detailsAre['PAYMENTREQUEST_0_AMT']						=	$tokenData['PAYMENTREQUEST_0_AMT'];
				$detailsAre['PAYMENTREQUEST_0_CURRENCYCODE']	=	$tokenData['PAYMENTREQUEST_0_CURRENCYCODE'];

				$tokenDataFinal = $this->Common->pay_me($detailsAre);

				if(isset($tokenDataFinal['PAYMENTINFO_0_ACK']) && $tokenDataFinal['PAYMENTINFO_0_ACK']=='Success'){
					$this->loadModel('Offers');
					$offerId = $tokenData['PAYMENTREQUEST_0_CUSTOM'];

					$offerDetails = $this->Offers ->findDetails(['Offers.id'	=>	$offerId]);
																				
					$this->request->session()->write('requestData','');
					
					//voucher code random string
					$voucherCode	=	$this->Common->generateRandomString(10);

					$id = 1;
					$this->loadModel('TempInvoices');
					$lastInvoiceId = $this->TempInvoices->find()->select('TempInvoices.id')->order('TempInvoices.id DESC')->first();
					if(!empty($lastInvoiceId)){
						$lastInvoice = $lastInvoiceId->toArray();
						$id = $lastInvoice['id'];
						$tempInv = $this->TempInvoices->newEntity();
						$this->TempInvoices->save($tempInv);
					}

					$invoiceName 					= 'INV'.$id;
					$paymentName 					= 'Paypal Express Checout';
					$paymentTransactionId = $tokenDataFinal['PAYMENTINFO_0_TRANSACTIONID'];
					$paidAmount						=	$tokenDataFinal['PAYMENTINFO_0_AMT'];

					$myFinalVoucher	=	[
															'user_id'			=>	$offerDetails['user_id'],
															'offer_id'		=>	$offerDetails['id'],
															'code'				=>	$voucherCode,
															'code_expiry'	=>	Date('Y-m-d',strtotime(Date('Y-m-d',strtotime('+1 months')).' +1 weeks')),
														];

					$myFinalVoucher['voucher_address']  
													=	[
															'first_name'			=>	$requestData['first_name'],
															'last_name'				=>	$requestData['last_name'],
															'address_line_1'	=>	$requestData['address_line_1'],
															'address_line_2'	=>	$requestData['address_line_2'],
															'address_line_3'	=>	$requestData['address_line_3'],
															'city'						=>	$requestData['city'],
															'state'						=>	$requestData['state'],
															'postal_code'			=>	$requestData['postal_code'],
															'mobile'					=>	$requestData['mobile'],
														];

					$myFinalVoucher['payment_detail'] 
													=	[
															'payment_type'			=>	3,
															'transaction_id'		=>	$paymentTransactionId,
															'offer_value'				=>	$requestData['offer_value'],
															'discount_amt'			=>	$requestData['discount_amt'],
															'promotion_code_id'	=>	$requestData['promotion_code_id'],
															'total_value'				=>	$paidAmount,
														];

						$myFinalVoucher['invoice'] 
													=	[
															'user_id'			=>	$offerDetails['user_id'],
															'number'			=>	$invoiceName,
														];

						$myFinalVoucher['offer'] 
													=	[
															'id'				=>	$tokenData['PAYMENTREQUEST_0_CUSTOM'],
															'status_id'	=>	4,
														];								
					
						$this->loadModel('Vouchers');
						$voucher 	=		$this->Vouchers->newEntity($myFinalVoucher,['associated' => ['VoucherAddresses','PaymentDetails','Invoices','Offers']]);
					  if ($this->Vouchers->save($voucher)) {

					  	$address 	=	'';
					  	if(isset($requestData['address_line_1']) && !empty($requestData['address_line_1']))
								$address .= $requestData['address_line_1'].',';

							if(isset($requestData['address_line_2']) && !empty($requestData['address_line_2']))
								$address .= $requestData['address_line_2'].',';
							
							if(isset($requestData['address_line_3']) && !empty($requestData['address_line_3']))
								$address .= $requestData['address_line_3'].',';
							
							if(isset($requestData['city']) && !empty($requestData['city']))
								$address .= $requestData['city'].',';
							
							if(isset($requestData['state']) && !empty($requestData['state']))
								$address .= $requestData['state'].',';
							
							if(isset($requestData['zipcode']) && !empty($requestData['zipcode']))
								$address .= $requestData['zipcode'].',';
					  	
					  	//Send an email to winner
              $token      =   ['{{name}}','{{item_number}}','{{transaction_id}}','{{voucher_code}}','{{amount}}','{{payment_type}}','{{deal_name}}','{{payment_value}}','{{fee}}','{{total}}','{{date}}','{{address}}','{{phone}}'];
              $tokenVal   =   [
              									$requestData['first_name']." ".$requestData['last_name'],
              									'ITM'.$offerDetails['service_listing']['item_id'],
              									$paymentTransactionId,
              									$voucherCode,
              									$paidAmount,
              									$paymentName,
              									$offerDetails['service_listing']['deal']['title'],
              									$requestData['offer_value'],
              									$requestData['discount_amt'],
              									$paidAmount,
              									Date('Y-m-d H:i:s'),
              									$address,
              									$requestData['mobile']
              								];
              
              $sendMail   =   $this->Common->_send_email($this->Auth->user('email'),$token,$tokenVal,'offer-success');
					  	//Send an email to winner
              
              $this->Flash->success(__('Your Payment has done. and your transaction id is ').$paymentTransactionId);
		          return $this->redirect(['action' => 'view',$offerDetails['service_listing']['id']]);
            } else {
                $this->Flash->error(__('The deal could not be saved. Please, try again.'));
            }								

				}
				else if(isset($tokenData['L_LONGMESSAGE0']) && !empty($tokenData['L_LONGMESSAGE0'])){
					$this->Flash->error($tokenData['L_LONGMESSAGE0']);
					$error = 1;
				}
			}

			if($error==1){
					if(!empty($offerDetails)){
						$this->Flash->error(__('Your payment has failed. Please try again.'));
						$this->request->data = $requestData;
						$this->set('offerDetails',$offerDetails);
						$this->render('checkout');
					}
					else{
						return $this->redirect(array('controller'	=>	'users',	'action'	=>	'home'));
					}	
			}
			else{
				$this->Flash->error(__('Session time out.'));
        return $this->redirect(['controller'=>'users','action' => 'home']);
			}
		}

		exit;
	}

	public function fail(){
		$tokenVal = $this->request->query['token'];
		$this->loadComponent('Common');
			
		if(isset($tokenVal) && !empty($tokenVal)){
			
			$detailsAre['METHOD'] 	= "GetExpressCheckoutDetails";
			$detailsAre['VERSION']	= 93; 
			$detailsAre['TOKEN']		=	$tokenVal;

			$tokenData = $this->Common->pay_me($detailsAre);

			if(isset($tokenData['ACK']) && !empty($tokenData['ACK'])){
				$requestData = $this->request->session()->read('requestData');
				$this->request->session()->write('requestData','');
				
				$this->loadModel('Offers');
				$offerDetails = $this->Offers ->findDetails(['Offers.id'	=>	$tokenData['PAYMENTREQUEST_0_CUSTOM']]);
			
				if(!empty($offerDetails)){
					$this->Flash->error(__('Your payment has failed. Please try again.'));
					if(isset($requestData) && !empty($requestData))
						$this->request->data = $requestData;
					
					$this->set('offerDetails',$offerDetails);
					$this->render('checkout');
				}
				else{
					return $this->redirect(array('controller'	=>	'users',	'action'	=>	'home'));
				}
			}
			else{
				return $this->redirect(array('controller'	=>	'users',	'action'	=>	'home'));	
			}
		}
		else{
			return $this->redirect(array('controller'	=>	'users',	'action'	=>	'home'));	
		}
	}

    public function skipVideo()
    {
    	$this->viewBuilder()->layout('ajax');
		setcookie('skipVideo', 'Yes', time() + (86400 * 30), "/"); // 86400 = 1 day
		echo 1;
		exit;
	}

	public function board(){
		$this->set('title', 'Leaderboard');
		$leaderBoardData = $this->Users->getLeaderBoardData(0,10);
		$this->set('leaderBoardData',$leaderBoardData);
	}

	public function checkPayment(){
		$this->loadModel('UserSubscriptions');
		$this->loadModel('UserSubscriptionBackups');
		$this->loadModel('Users');
		$findAll = $this->UserSubscriptions->find()->where(['end_date < ' => date('Y-m-d') ])->all();

		foreach ($findAll as $key => $value) {
			$freshCopy['user_id'] = $value->user_id;
			$freshCopy['subscription_id'] = $value->subscription_id;
			$freshCopy['paid_date'] = $value->paid_date;
			$freshCopy['start_date'] = $value->start_date;
			$freshCopy['end_date'] = $value->end_date;
			$freshCopy['transaction_id'] = $value->transaction_id;
			$freshCopy['total_amount'] = $value->total_amount;
			$freshCopy['discounted_amount'] = $value->discounted_amount;
			$freshCopy['final_amount'] = $value->final_amount;
			$freshCopy['promotion_code_id'] = $value->promotion_code_id;
			$freshCopy['status_id'] = $value->status_id;
			$freshCopy['is_admin'] = $value->is_admin;
			$freshEntity = $this->UserSubscriptionBackups->newEntity();
			$patchEntity = $this->UserSubscriptionBackups->patchEntity($freshEntity, $freshCopy);
			$this->UserSubscriptionBackups->save($patchEntity);

			$this->Users->updateAll(
				array('is_paid' => 0, 'paid_date' => '', 'trainer_id' => ''),
				array('id' => $value->user_id)
			);

			$userSub = $this->UserSubscriptions->get($value->id);
			$this->UserSubscriptions->delete($userSub);
		}

		exit;
	}

}