<?php
/** 
*  Project : Fitfix 
*  Author : Bharat 
*  Creation Date : 25-June-2015 
*  Description : This is Api controller which will be called by the mobile devices
*/
namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Network\Email\Email;
use Cake\Database\Expression\QueryExpression;

class ApiController extends AppController {

	var $name = 'Api';
	var $limit = 10;
	var $page_number = 1;
	var $logging = false;
	var $layout = false;
	var $autoRender = false;
	var $allowedActions = array('login','register','facebook_login', 'forgot_password', 'getCountries', 'get_subscription','coachmembers','mealplans','leaderBoardData','usermealplan','getfeeds','getrecipes','getcommunitylist','getrecipecomments','getusermessages','getnotifications','getuserrecipe');
	var $paginate_array = array();
	var $return = array();
	var $user_return_fields = array('id','name', 'phone', 'email');
	var $user = array();

/**
 * @name Send Response
 * Purpose : This method prints the response from any method when called
 * @author Bharat Borana
 * @access private
 * @return return the response data
 */
	private function __send_response()
	{
		echo json_encode($this->response);
		exit;
	}
	
	
/**
 * @name BeforeFilter
 * Purpose : This method is called before every call to check if its authentic request
 * @author Bharat Borana
 * @access public
 * @return returns true if authenticated else returns error
 */
	public function beforeFilter(Event $event)
	{
		header('Content-type: application/json');
		parent::beforeFilter($event);
		
		if(!in_array($this->request->action, $this->allowedActions))
		{
			$secret = SECRET_KEY_APP;
			$token = $this->request->header('token');
			$emailId = $this->request->header('email');
			$timestamp = $this->request->header('timestamp');
			$generate_token = sha1($secret . $timestamp . $emailId);
			//if($generate_token != $token)
			if(false)
			{
				$this->response = array(
							'status' => 401,
							'message' => 'Invalid Credentials',
						);
				$this->__send_response();
			} else
			{
				$user = $this->_login();
				if($user)
				{
					$this->user = $user;
					$this->Auth->allow();
				} else
				{
					$this->response = array(
							'status' => 401,
							'message' => 'Invalid Credentials',
						);
					$this->__send_response();
				}
			}
		} else
		{
			$secret = SECRET_KEY_APP;
			$timestamp = $this->request->header('timestamp');
			$token = $this->request->header('token');
			$generate_token = sha1($secret . $timestamp);
			//if($generate_token != $token)
			if(false)
			{
				$this->response = array(
					'status' => 401,
					'message' => 'Invalid Credentials',
				);
				$this->__send_response();
			} else
			{
				$this->Auth->allow($this->request->action);
			}
		}

		if(isset($this->request->query['page']))
		{
			$this->page_number = $this->request->query['page']; 
		}
		if(isset($this->request->query['limit']))
		{
			$this->limit = $this->request->query['limit']; 
		}
		if(isset($this->request->query['show']) && $this->request->query['show'] == 'all')
		{
			$this->limit = MAX_PAGINATION_LIMIT;
		}
		$this->paginate_array = array(
			'limit' => $this->limit,
			'page' => $this->page_number
		);
	}

	private function _login()
	{
		$this->loadModel('Users');
		$user = $this->Users->find(
 			'all', [
 				'conditions' => [
 					'Users.email' => $this->request->header('email'),
 					'Users.group_id' => USERGROUPID
 				]
			]
		)->first();
		if(!empty($user))
		{
			return $user->toArray();
		} else {
			return false;
		}
	}


	private function _clean_file_name($filename)
	{
		return preg_replace("/[^a-z0-9\.]/", "", strtolower($filename));
	}

		 /////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////USER METHODS START///////////////////////////////////////////
	   /////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * @name Register
 * Purpose : This method is used to register the user omn 
 * @author Bharat Borana
 * @access public
 * @return returns true if authenticated else returns error
 */
	public function register()
	{
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 405,
				'message' => 'Method not allowed',
			);
			$this->__send_response();
		}
		$error = '';
		$this->loadModel('Users');

		$this->request->data['last_login'] = new \DateTime();
		$this->request->data['group_id'] = CLIENTGROUPID;
		$this->request->data['status_id'] = 3;
		$this->loadComponent('Common');
		$randString = $this->Common->generateRandomString(25);
		$this->request->data['verification_token'] = $randString;
		
		$user = $this->Users->newEntity($this->request->data);
		$errors = $user->errors();
		if(!empty($errors))
		{
		    foreach ($errors as $key => $value)
		    {
		    	$error .= $value[array_keys($value)[0]].",  ";
		    }
		}

		if(!empty($error))
		{
		    $this->response = array(
				'status' => 400,
				'message' => $error
			);
		} else
		{  
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				
				$linkTag = "http://" . $_SERVER['SERVER_NAME'] . Router::url(array('controller' => 'Users', 'action' => 'verify_user', $user->email, $user->verification_token));

				$tokenArray = ['{{name}}','{{activation_url}}'];
				$tokenValueArray = [$user->first_name." ".$user->last_name, $linkTag];
				$this->Common->_send_email($user->email,$tokenArray, $tokenValueArray,'verify-email');
				
				$user = $this->Users->find(
					'all', [
						'contain' => ['Statuses'],
						'fields' => [
							'id', 'email', 'first_name', 'last_name', 'Statuses.name'
						],
						'conditions' => [
							'Users.id' => $user['id']
						]
					]
				)->first()->toArray();
			
				$this->response = array(
					'status' => 200,
					'new_user' => 1,
					'message' => 'The user has been registered. A verification mail has been sent to your account. Please verify the mail to login.',
					'data' => $user
				);
			} else {
				$this->response = array(
					'status' => 500,
					'message' => 'User cannot be saved right now. Please try again.',
				);
			}
		}
		$this->__send_response();
		exit;
	}

/**
 * @name Login
 * Purpose : This method is used to login the user omn 
 * @author Bharat Borana
 * @access public
 * @return returns true if authenticated else returns error
 */
	public function login()
	{ 
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 405,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}

		$error = '';
		$this->loadModel('Users');
		$user_data = $this->request->data;
		if(!isset($user_data['email']) || empty($user_data['email']))
			$error .= 'Email address is required for login.';
		
		if(!isset($user_data['password']) || empty($user_data['password']))
			$error .= 'Password is required for login.';
		
		if(empty($error)){
			$user = $this->Auth->identify();
			if($user)
			{
				$user_data = $this->Users->get($user['id']);
				$user_data->last_login = new \DateTime();
				if($this->Users->save($user_data))
				{
					$user = $this->Users->find(
						'all', [
							'contain' => ['Statuses'],
							
							'conditions' => [
								'Users.id' => $user['id']
							]
						]
					)->first()->toArray();
					//print_r(var_dump($user));
					foreach($user as $key=>$val){
						if(gettype($val)=='string'&&$val==''){
							$user[$key]=0;
						}if($key == 'total_weight_loss' &&$val==NULL){
							$user['total_weight_loss'] = 0;
						} if($key == 'month_weight_loss' &&$val==NULL){
							$user['month_weight_loss'] = 0;
						}if($key == 'week_weight_loss'&&$val==NULL){
							$user['week_weight_loss'] = 0;
						}
					}
					if(isset($user['trainer_id'])&&$user['trainer_id']!=''){
						$featuredTrainers = $this->Users->find()->where(['group_id' => USERGROUPID,'id'=>$user['trainer_id']])->select(['image','first_name','last_name'])->first();
						$coachData = $featuredTrainers->toArray();
						$user['coach_name'] = $coachData['first_name']." ".$coachData['last_name'];
						$user['coach_image']= Router::url(BASE_URL. USER_IMAGE_URL . $coachData['image'], true);
						$this->loadModel('UserSubscriptions');
						$subscriptionData = $this->UserSubscriptions->find()->where(['user_id' => $user['id']])->select(['start_date'])->first();
						$start_date=$subscriptionData->toArray();
						$user['start_date'] = $start_date['start_date'];
						
					}
					
					if($user['image'])
					{
						$user['image'] = Router::url(BASE_URL. USER_IMAGE_URL . $user['image'], true);
					}
                    if($user['before_image']){
                        $user['before_image'] = Router::url(BASE_URL. USER_IMAGE_URL . $user['before_image'], true);
                    }
                    if($user['after_image']){
                        $user['after_image'] = Router::url(BASE_URL. USER_IMAGE_URL . $user['after_image'], true);
                    }
					if($user['status_id'] == 1 && $user['is_verified'] == 1)
					{
						$this->response = array(
							'status' => 200,
							'new_user' => 0,
							'message' => 'User sucessfully logged in',
							'data' => $user
						);
					}
					else if($user['status_id']==1 && $user['is_verified']==0){
						$this->response = array(
							'status' => 400,
							'new_user' => 0,
							'message' => 'Please verify your email address.',
							'data' => ''
						);
					}
					else if($user['status_id']==2){
						$this->response = array(
							'status' => 400,
							'new_user' => 0,
							'message' => 'Your account has deactivated.',
							'data' => ''
						);
					}
					else if($user['status_id']==3){
						$this->response = array(
							'status' => 400,
							'new_user' => 0,
							'message' => 'Please verify your email address.',
							'data' => ''
						);
					}
				}
				else
				{
					$this->response = array(
							'status' => 400,
							'message' => 'User Can not be saved.',
							'data' => ''
						);
				}
			}
			else{
				$this->response = array(
							'status' => 400,
							'message' => 'Invalid user credentials. Please try again.',
							'data' => ''
						);
			}
		}
		else{
			$this->response = array(
							'status' => 400,
							'message' => $error,
							'data' => ''
						);
		}
		
		$this->__send_response();
	}

/**
 * @name BeforeFilter
 * Purpose : This method is used for facebook login and signup request
 * @author Bharat Borana
 * @access public
 * @return returns user details
 */
 	function facebook_login()
	{
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 405,
				'message' => 'Method not allowed',
			);
			$this->__send_response();
		}
		$error = '';
		$this->loadModel('Users');

		$data = $this->request->data;
		
		if(!isset($data['email']) || empty($data['email']))
		{
			$error .= 'Email address is required for login.';
		}

		if(!isset($data['facebook_id']) || empty($data['facebook_id']))
		{
			$error .= 'Facebook id is required.';
		}
		
		if(!isset($data['first_name']) || empty($data['first_name']))
		{
			$error .= 'First name is required.';
		}
		
		if(!isset($data['last_name']) || empty($data['last_name']))
		{
			$error .= 'Last name is required.';
		}
		
		if(!empty($error))
		{
			$this->response = array(
				'status' => 400,
				'message' => $error,
			);
			$this->__send_response();
		}
		else
	 	{
	 		$user_data = $this->Users->find()->where(['Users.facebook_id' => $data['facebook_id']])->first();
	 		if(!empty($user_data))
			{
				$user_data = $user_data->toArray();
				$this->response = array(
					'status' => 200,
					'message' => 'User Successfully Logged in',
					'data' => $user_data
				);
			} else
			{
				$user_data = $this->Users->find()->where(['Users.email' => $data['email']])->first();
				if($user_data)
				{
					// Encrypt some data.
					$fresh_data['is_verified'] = 1;
					$fresh_data['status_id'] = 1;
					
					$fresh_data['group_id'] = CLIENTGROUPID;
					$fresh_data['facebook_id'] = $data['facebook_id'];
					
					$user_data = $this->Users->get($user_data->id);
					$user_data = $this->Users->patchEntity($user_data, $fresh_data, ['validate' => false]);
					if($this->Users->save($user_data)){
						$user_data = $user_data->toArray();
						$this->response = array(
							'status' => 200,
							'new_user' => 1,
							'message' => 'User Successfully Logged in.',
							'data' => $user_data
						);
					} else
					{
						$this->response = array(
							'status' => 500,
							'message' => 'User cannot be saved right now. Please try again.',
							'data' => ''
						);
					}
				}
				else
				{
					$user_data['is_verified'] = 1;
					$user_data['status_id'] = 1;
					$user_data['first_name'] = $data['first_name'];
					$user_data['last_name'] = $data['last_name'];
					$user_data['email'] = $data['email'];
					$user_data['group_id'] = CLIENTGROUPID;
					$user_data['phone'] = (!empty($data['phone'])) ? $data['phone'] : '';
					$user_data['facebook_id'] = $data['facebook_id'];
					$user_data['password'] = '$$##||&||##$$';
					
					$user = $this->Users->newEntity($user_data,['validate'=>false]);
					if($this->Users->save($user,['validate'=>false]))
					{
						$user_data = $this->Users->find(
				 			'all', ['conditions' => ['Users.email' => $user['email']]]
						)->first()->toArray();
						$this->response = array(
							'status' => 200,
							'new_user' => 1,
							'message' => 'User registered successfully.',
							'data' => $user_data
						);
					} else
					{
						$this->response = array(
							'status' => 500,
							'message' => 'User cannot be saved right now. Please try again.',
							'data' => ''
						);
					}
				}

			}
		}
	 	$this->__send_response();
	}

/**
 * @name forgot_password
 * Purpose : This method is used to reset the password 
 * @author Bharat Borana
 * @access public
 * @return returns true if password sent else returns error
 */
	public function forgot_password()
	{
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$error = '';
		$this->loadModel('Users');
		$user_data = $this->request->data;
		if(!isset($user_data['email']) || empty($user_data['email']))
		{
			$error .= 'Email is Required.';
		}
		if(!empty($error))
		{
			$this->response = array(
				'status' => 400,
				'message' => $error
			);
		}else
	 	{
	 		$user_data = $this->Users->find(
	 			'all', [
	 				'conditions' => [
	 					'Users.email' => $user_data['email']
 					]
				]
			)->first();
			if($user_data)
			{
				$user_data = $user_data->toArray();
				$template = "forgot-password";
				$forgot_token = sha1(md5($user_data['password']));
				$forget_array = array('id' => $user_data['id'], 'token' => $forgot_token);
				$forget_url = Router::url(array('controller'=>'users','action'=>'reset_password', '?' => $forget_array),true);
				$token = array('{{name}}','{{email}}','{{forget_url}}');
				$token_value = array($user_data['first_name'] . " " . $user_data['last_name'], $user_data['email'], $forget_url);
                $this->loadComponent('Common');
				$this->Common->_send_email($user_data['email'], $token, $token_value, $template, '');
				$this->response = array(
							'status' => 200,
							'message' => "Password sent on provided email address."
				);
			}
			else
			{
				$this->response = array(
							'status' => 400,
							'message' => "Email address not found."
				);
			}
		}
		$this->__send_response();
	}

	public function dashboard() {
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$error = '';
		$this->loadModel('Users');
		$user_data = $this->request->data;
		if(!isset($user_data['user_id']) || empty($user_data['user_id']))
		{
			$error .= 'User Id is Required.';
		}
		if(!empty($error))
		{
			$this->response = array(
				'status' => 400,
				'message' => $error
			);
		}else
	 	{
	 		$userDetails = $this->Users->get($user_data['user_id'],['contain' => ['Trainers' => ['fields' => ['Trainers.id','Trainers.first_name','Trainers.last_name','Trainers.email','Trainers.image']], 'UploadImages' => ['conditions' => [ 'OR' => ['UploadImages.type = "pics" OR UploadImages.type = "videos"'] ]]]]);
		
			$this->loadModel('ShoppingLists');
			$shoppingListData = $this->ShoppingLists->find()->select(['document_name'])->where(['status_id' => ACTIVE_STATUS])->first()->toArray();
			
			$leaderBoardData = $this->Users->getLeaderBoardData(0);
			
			$this->loadModel('UserWeights');
			$UserWeights = $this->UserWeights->find()
				->select(['weight' => 'weight', 'weight_date' => 'weight_date'])
				->where(['user_id' => $this->Auth->user('id')])
				->order([ 'weight_date' => 'DESC'])
				->limit(10);

			$UserWeightsData = [];
			if(!empty($UserWeights)){
				$UserWeights = $UserWeights->toArray();
				$UserWeights = array_reverse($UserWeights);
			
				foreach($UserWeights as $k => $v){
					$UserWeightsData["x"][] = ($v->weight_date->format('d M Y'));
					$UserWeightsData["y"][] = $v->weight;
				}	
			}	
			
			$this->loadModel('DailyMealPlans');
	        $mealPlans = $this->DailyMealPlans->find()
	            ->select(['DailyMealPlans.meal_date','DailyMealPlans.id','DailyMealPlans.text_highlight'])
	            ->where(['Date(DailyMealPlans.meal_date)' => Date('Y-m-d')])
	            ->contain('Meals')
	            ->first();

			$UserWeightJson =  json_encode($UserWeightsData);
			$this->set(compact('userDetails','UserWeightJson','shoppingListData','mealPlans','leaderBoardData'));
	 	}
	}

	public function edit_profile() {
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$error = '';
		$this->loadModel('Users');
		$user_data = $this->request->data;
		if(!isset($user_data['user_id']) || empty($user_data['user_id']))
		{
			$error .= 'User id is Required. ';
		}
		else
		{
			if(!$this->Users->get($user_data['user_id']))
			{
				$error .= 'User does not exists. ';
			}
		}
		
		if(!empty($error))
		{
			$this->response = array(
				'status' => 400,
				'message' => $error
			);
		}
		else
	 	{	$user = $this->Users->get($user_data['user_id']);
			$userDatum = $user->toArray();
			
			if(isset($user_data['email']) && ($userDatum['email'] != $user_data['email']))
			{
				$existingProfile = $this->Users->find()->where(['Users.email' => $user_data['email']])->first();
				if(!empty($existingProfile)){
					$errorTxt = 'User already registered with this email id.';
				}	
			}

			if(isset($user_data['username']) && ($userDatum['username'] != $user_data['username']))
			{
				$existingProfile = $this->Users->find()->where(['Users.username' => $user_data['username']])->first();
				if(!empty($existingProfile)){
					$errorTxt = 'User already registered with this username.';
				}	
			}

			if(empty($errorTxt)){
				if(!empty($user_data['image']) && substr( $user_data['image'], 0, 10 ) === "data:image")
				{
					$FileName = mt_rand().'-'.time().'.jpeg';
					$NewSaveImageLoc = USER_IMAGE_PATH . $FileName;
					$this->_base64_to_jpeg($user_data['image'],$NewSaveImageLoc);
					
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
						$user_data['image'] = $FileName;
					}
				}

				$saveData = $this->Users->patchEntity($user, $user_data);
				
				if($user = $this->Users->save($saveData)) {
					
					$user = $user->toArray();
				
					$this->response = array(
						'status' => 200,
						'new_user' => 1,
						'message' => 'Your profile has been updated successfully.',
						'data' => $user
					);
			   	} else {
					$this->response = array(
						'status' 	=> 	400,
						'message' 	=>	'Your profile could not be updated, please try again'
					);
				}
			}
			else{
				$this->response = array(
					'status' 	=> 	400,
					'message' 	=>	$errorTxt
				);		
			}
			
		}
	}

	public function get_subscription(){
		header('Content-Type: application/json');
		if(!$this->request->is('GET'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$error = '';
		
		$this->loadModel('Subscriptions');
        $Subscriptions = $this->Subscriptions->find()->all()->toArray();
        $this->response = array(
							'status' => 200,
							'message' => 'Subscriptions found successfully.',
							'data' => $Subscriptions
						);

    }
	public function coachmembers(){
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$coachID = $this->request->data;
		
		$this->loadModel('Users');
		$this->loadModel('UserWeights');
		$result = $this->Users->get($coachID['coach_id'],['contain' => ['Clients' => ['UserSubscriptions' => function($q) { return $q->select(['id','user_id','start_date','end_date']); }, 'UserWeights' => function ($q) {
                                                                                                                                        return $q
                                                                                                                                            ->select(['weight_date','weight','user_id'])
                                                                                                                                            ->where(['Date(UserWeights.weight_date) >=' => Date('Y-m-d',strtotime('-7 days'))]);
                                                                                                                                    } ,'fields' => ['id','first_name','email','last_name','image','goal_weight','trainer_id','created','expire_date','total_weight_loss','month_weight_loss','week_weight_loss']], 'UploadImages' => ['conditions' => [ 'OR' => ['UploadImages.type = "pics" OR UploadImages.type = "videos"'] ]] ]]);
		$response = array();
		$i=0;
		foreach($result->clients as $key => $record){
			//print_r($record);
				$startDate = date('Y-m-d');
				$datefrom = strtotime(date('Y-m-d'), 0);
        									$dateto = strtotime($record->expire_date, 0);
        									$difference = $dateto - $datefrom;
        									$datediff = floor($difference / (604800/7));
				$days = $datediff;
				if($days>0){
					$member_id = $record->id;
					$response[$i]['id'] = $record->id;
					if($record->image==''||$record->image=='null'){
						$response[$i]["image"]= null;
					} else {
						$response[$i]["image"]= Router::url(BASE_URL. USER_IMAGE_URL . $record->image, true);	
					}
					
					$response[$i]['username'] = $record->username;
					$response[$i]['name'] = $record->first_name." ".$record->last_name;
					$response[$i]['total_weight_loss']=$record->total_weight_loss==null?0:$record->total_weight_loss;
					$response[$i]['month_weight_loss'] = $record->month_weight_loss==null?0:$record->month_weight_loss;
					$response[$i]['week_weight_loss'] = $record->week_weight_loss==null?0:$record->week_weight_loss;
					
					$response[$i]['days_left'] = $days;
					$response[$i]['created'] = $record['created'];
					$j = 0;
					for ($x=0; $x<7; $x++)
					{
						if($x==0){
							$date = date("Y-m-d");
						} else{
							$date = date("Y-m-d", strtotime($x." days ago"));
						
						}
						
						$usersWeight = $this->UserWeights->find('all',array('conditions' => array('UserWeights.weight_date' => $date)) )->where(['UserWeights.user_id' => $member_id])->order([ 'UserWeights.weight_date' => 'DESC'])->toArray();
						
						//$usersWeight = $this->User_weight->query('Select * from user_weight Where user_id= '.$member_id.' AND weight_date >= DATE(NOW()) - INTERVAL 7 DAY');
						$wt = 0;
							foreach($usersWeight as $data){
								
								$wt = $data['weight'];	
								
								
							}
							$response[$i]['weight_date_data'][$x]['weight_date'] = $date;
							$response[$i]['weight_date_data'][$x]['weight'] = isset($wt)?$wt:0;
							
						
					}
					$i++;
				}
		}
		
		$this->response = array(
							'status' => 200,
							'message' => 'Members Found.',
							'data' => $response
						);
		$this->__send_response();
	}




public function mealplans(){
	header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		
			if(true){
				$this->loadModel('DailyMealPlans');
				$startingWeek = ceil($startingDays/7);
				$originalStartingWeek = 1;
				$mealPlans = $this->DailyMealPlans->find()
						->select(['DailyMealPlans.week_no','DailyMealPlans.meal_date','DailyMealPlans.week_day','DailyMealPlans.id','DailyMealPlans.text_highlight', 'weak_number' => 'WEEK(meal_date,1)'])
						->where(['week_no >= 1', 'week_no <= 12'])
						->order(['DailyMealPlans.week_no' => 'ASC','DailyMealPlans.week_day' => 'ASC'])
						->contain('Meals')
						->all();
				
			
				if(!empty($mealPlans)) {
					foreach ($mealPlans as $row) {
						$week_no = ($row->week_no)-1;
						
					   	//$mealArray[Date('W',strtotime($row->meal_date))][] = $row->toArray();
					   	$mealArray[$row->week_no][] = $row->toArray();
					}
				}
			}
		foreach($mealArray as $key => $array){
			$i=0;
			foreach($array as $record){
					$mealArray[$key][$i]['text_highlight'] = strip_tags($record['text_highlight']);
					$i++;
			}
			
		}
		$this->response = array(
							'status' => 200,
							'message' => 'Meals Found.',
							'data' => $mealArray
						);
		$this->__send_response();
	
	}
	public function leaderBoardData(){
		
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$this->loadModel('Users');
		$leaderBoardData = $this->Users->getLeaderBoardData(0,10);
		
		foreach($leaderBoardData as $key =>$single){
			$i=0;
			foreach($single as $record){
				$leaderBoardData[$key][$i]['image'] = Router::url(BASE_URL. USER_IMAGE_URL . $record['image'], true);
				$leaderBoardData[$key][$i]['trainer']['image'] = Router::url(BASE_URL. USER_IMAGE_URL . $record['trainer']['image'], true);
				$leaderBoardData[$key][$i]['board'] = $key;
				if(!isset($leaderBoardData[$key][$i]['trainer'])){
						$leaderBoardData[$key][$i]['trainer']['id'] = 0;
						$leaderBoardData[$key][$i]['trainer']['first_name'] = null;
						$leaderBoardData[$key][$i]['trainer']['last_name'] = null;
						$leaderBoardData[$key][$i]['trainer']['image'] = null;
						
				}
				switch($key){
					case "week";
						$leaderBoardData[$key][$i]['rank'] = $i+1;
						break;
					case 'month':
						$leaderBoardData[$key][$i]['rank'] = $i+10+1;
						break;
					case 'total':
						$leaderBoardData[$key][$i]['rank'] = $i+20+1;
						break;
				}
				
				$i++;
			}
				
				
		}
		
		$this->response = array(
							'status' => 200,
							'message' => 'Data Found.',
							'data' => $leaderBoardData
						);
		$this->__send_response();
	}
	public function usermealplan(){
		
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$user_data = $this->request->data;
		
		$this->loadModel('ShoppingLists');
		$this->loadModel('UserSubscriptions');
		
		$subscriptionData = $this->UserSubscriptions->find()->where(['user_id' => $user_data['user_id']])->order(['UserSubscriptions.id' =>'DESC'])->first();
		//print_r($subscriptionData->toArray());die;
		$currentDate = date('Y-m-d');
		$mealArray = [];
		$tempMealArray = [];
		$originalStartingWeek = '';
		
		if((!empty($subscriptionData) && $subscriptionData->start_date)){
			$date1 = new \DateTime($subscriptionData->start_date);
			$date2 = new \DateTime();
			
			$interval = $date1->diff($date2);
			$startingDays    = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
			$currentWeekTemp = $startingDays/7;
			
			//$currentWeek = floor($startingDays/7)+1; commented on 27-05-2016
			$currentWeek = (is_numeric($currentWeekTemp) && floor($currentWeekTemp) != $currentWeekTemp) ? floor($currentWeekTemp)+1 : $currentWeekTemp;
			
			/* If starting week is going to exceed from 12 then start it from initial */
			$currentWeek = ($currentWeek > 0 && $currentWeek != 12) ? $currentWeek%12 : $currentWeek;
			
			$weekDay = $startingDays%7;
			
			$date3 = new \DateTime($subscriptionData->end_date);
			$interval = $date3->diff($date2);
			$remainingDays    = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
			
			//if($remainingDays >= $startingDays){ //commented on 2016-05-04
			if(strtotime($subscriptionData->end_date) >= strtotime($currentDate)) {	
				$this->loadModel('DailyMealPlans');
				$originalStartingWeek = $startingWeek = ceil($startingDays/7);
				
				/* If starting week is going to exceed from 12 then start it from initial */
				$startingWeek = ($startingWeek > 0 && $startingWeek != 12) ? $startingWeek%12 : $startingWeek;
				
				if($startingWeek > 0 && $startingWeek != 12) {
					$nextStartingWeek =  $startingWeek+1;
					$orderBy = 'ASC';
				} else if($startingWeek > 0 && $startingWeek == 12) {
					$nextStartingWeek = 1;
					$orderBy = 'DESC';
				} else {
					$nextStartingWeek =  $startingWeek+1;
					$orderBy = 'ASC';
				}	
				//echo $startingWeek;
				//echo $nextStartingWeek;
				$mealPlans = $this->DailyMealPlans->find()
						->select(['DailyMealPlans.week_no','DailyMealPlans.meal_date','DailyMealPlans.week_day','DailyMealPlans.id','DailyMealPlans.text_highlight', 'weak_number' => 'WEEK(meal_date,1)'])
						->where(['week_no >=' => $startingWeek, 'week_no <=' =>$nextStartingWeek])
						//->where(['week_no IN' => [$startingWeek, $nextStartingWeek]])
						->order(['DailyMealPlans.week_no' => $orderBy,'DailyMealPlans.week_day' => 'ASC'])
						->contain('Meals')
						->all();
				
				//pr($mealPlans); die('here');
				
				//$shoppingListInfos = $this->ShoppingLists->getShoppingList($this->Auth->user('created')); //commented on 14-07-2016
				$shoppingListInfos = $this->ShoppingLists->getShoppingList($subscriptionData->start_date);
				
				if(!empty($mealPlans)) {
					//echo "here";
					$weekNoForMealPlan = $originalStartingWeek;
					foreach ($mealPlans as $keyMeal => $row) {
						//$week_no = ($row->week_no)-1;
						$rowData = $row->toArray();
						if($rowData['week_no']==$currentWeek){
							$rowData['text_highlight'] = 'THIS WEEK';
							$key = "this_week";
						} else{
							$rowData['text_highlight'] = 'NEXT WEEK';
							$key = "next_week";
						}
						if($weekNoForMealPlan != 12 && ($weekNoForMealPlan % 12) == $row->week_no) { 
							$weekNoForMealPlan = $weekNoForMealPlan;
						} else if($weekNoForMealPlan == 12 && $weekNoForMealPlan == $row->week_no) {
							$weekNoForMealPlan = $weekNoForMealPlan;
						} else {
							$weekNoForMealPlan = $weekNoForMealPlan+1;
						}
						
						$week_no = ($weekNoForMealPlan)-1;
						$mealDate = date_create($subscriptionData->start_date);
						$mealDate = date_modify($mealDate, '+ '.$week_no.' week');
						$mealDate = date_modify($mealDate, '+ '.$row->week_day.' day');
					   	$row->meal_date = date_format($mealDate, 'Y-m-d');
						// add document name in meal plan array
						if(!empty($shoppingListInfos)) {
							foreach($shoppingListInfos as $shoppingData) {
								
								if($shoppingData['week_no']==$row->week_no) {
									//echo $shoppingData['week_no'].'--'.$row->week_no.'<br/>';
									$row->document_name = $shoppingData['document_name'];
								}	
							}	
						}
						
						if(strtotime($subscriptionData->end_date) >= strtotime($row->meal_date)) {
							$mealArray[$key][] = $rowData;
						}
					   	//$mealArray[Date('W',strtotime($row->meal_date))][] = $row->toArray();
					}
				}
			}
			//pr($mealArray); die;
		}
		
		$this->response = array(
							'status' => 200,
							'message' => 'Data Found.',
							'data' => $mealArray
						);
		$this->__send_response();
		//print_r($mealArray);die;
	}
	
	public function getrecipes(){
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$this->loadModel('Recipes');
		$user_data = $this->request->data;
		$item_per_page = $user_data['limit'];
		$category_id = $user_data['category_id'];
		$page_number = filter_var($user_data["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
        $position = (($page_number-1) * $item_per_page);
        
        if($category_id != 0){
			$recipeList = $this->Recipes->find('all', array('limit'=>$item_per_page, 'offset'=>$position))->where(['Recipes.status_id' => ACTIVE_STATUS,'category_id LIKE'=>"%$category_id%"])->contain(['UploadImages','Categories','Users'=>['fields' => ['first_name', 'last_name', 'username', 'email', 'id', 'image']]]);
		} else {
			$recipeList = $this->Recipes->find('all', array('limit'=>$item_per_page, 'offset'=>$position))->where(['Recipes.status_id' => ACTIVE_STATUS])->contain(['UploadImages','Categories','Users'=>['fields' => ['first_name', 'last_name', 'username', 'email', 'id', 'image']]]);
		}
        
		
        if(isset($recipeList) && !empty($recipeList))
        $recipeList = $recipeList->toArray();
		$newArray = array();
		foreach($recipeList as $key => $row){
			$row['user']['image'] = Router::url(BASE_URL. USER_IMAGE_URL . $row['user']['image'], true);
			foreach($row['upload_images'] as $ikey => $innerArray){
				 $row['upload_images'][$ikey]['name'] = Router::url(BASE_URL. "media/dish/" . $innerArray['name'], true);
			}
			$newArray[] = $row;
		}
		
		$this->response = array(
							'status' => 200,
							'message' => 'Data Found.',
							'data' => $newArray
						);
		$this->__send_response();
       
	}
	public function getcommunitylist(){
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$this->loadModel('Feeds');
		$item_per_page = 10;
        $dishData = $this->request->data;
        $page_number = filter_var($dishData["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
        $position = (($page_number-1) * $item_per_page);
        
        $feedList = array();
        $feedArray = array();
           
        $feedList = $this->Feeds->getMyFeed('','',$item_per_page, $position);
        foreach($feedList as $key => $row){
			if($row['recipe_id']==''){
				$row['recipe_id'] = 0;
			}
			$row['user']['image'] = Router::url(BASE_URL. USER_IMAGE_URL . $row['user']['image'], true);
			foreach($row['comments'] as $cKey => $comments){
				$row['comments'][$cKey]['user']['image'] = Router::url(BASE_URL. USER_IMAGE_URL . $comments['user']['image'], true);
			}
			
			foreach($row['upload_images'] as $ikey => $innerArray){
				 $row['upload_images'][$ikey]['name'] = Router::url(BASE_URL. MYPIC_IMAGE_URL . $innerArray['name'], true);
			}
			foreach($row['recipe']['upload_images'] as $rKey => $rImages){
				$row['recipe']['upload_images'][$rKey]['name'] = Router::url(BASE_URL. 'media/dish/' . $rImages['name'], true);
			}
			$feedArray[] = $row;
		}    
        
        $this->response = array(
							'status' => 200,
							'message' => 'Data Found.',
							'data' => $feedArray
						);
		$this->__send_response();
		
	}
	
	public function getrecipecomments(){
		
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$this->loadModel('Recipes');
		$data = $this->request->data;
		$recipeDetails = $this->Recipes->find()->where(['Recipes.id' => $data['id']])->contain(['Categories','UploadImages','Comments' => ['Users' => ['fields' => ['Users.id','Users.email','Users.first_name','Users.last_name','Users.image']]]])->first();
		
		$this->response = array(
							'status' => 200,
							'message' => 'Data Found.',
							'data' => $recipeDetails['comments']
						);
		$this->__send_response();
		
		
	}

	public function getusermessages(){
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$userId = $this->request->data;
		$this->loadModel('ConversationReplies');
 		$this->loadModel('Conversations');
		$messages = $this->Conversations->find()->where(array('OR'=>array('Conversations.receiver_id'=>$userId['id'])))->contain(array('Sender'=> array('fields' => ['id','first_name', 'last_name','image']),
'Receiver'=> array('fields' => ['id','first_name', 'last_name','image']),'ConversationReplies'))->order(['Conversations.modified'=>'DESC'])->all();
		$messagesArray = array();
		foreach($messages as $key => $row){
			$row->sender->image =  Router::url(BASE_URL. USER_IMAGE_URL . $row->sender->image, true);
			$row->receiver->image =  Router::url(BASE_URL. USER_IMAGE_URL . $row->receiver->image, true);
			$messagesArray[] = $row;
		}
		$this->response = array(
							'status' => 200,
							'message' => 'Data Found.',
							'data' => $messagesArray
						);
		$this->__send_response();
	}
	public function getnotifications(){
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$userId = $this->request->data;
		$this->loadModel('Users');
		
		$notiArray = $this->Users->getAllActivities(['user_id' => $userId['id']]);
		$newArray = array();
		foreach($notiArray['ActivityLogs'] as $key => $row){
			$row['data']['user']['image']= Router::url(BASE_URL. USER_IMAGE_URL . $row['data']['user']['image'], true);
			if($row['data']['recipe_id']!==''){
				$row['data']['recipe_id'] = (string)$row['data']['recipe_id'];
			}
			if($row['data']['feed_id']!==''){
				$row['data']['feed_id'] = (string)$row['data']['feed_id'];
			}
			foreach($row['data']['conversation_replies'] as $ikey => $innerArray){
				$notiArray['ActivityLogs'][$key]['data']['user'] = $innerArray['user'];
				$notiArray['ActivityLogs'][$key]['data']['user']['image'] = Router::url(BASE_URL. USER_IMAGE_URL . $innerArray['user']['image'], true);
				
			}
			
			
			if(isset($row['data']['conversation_replies'])){
				unset($row);
			}else{
			
				$newArray[] = $row;
			}
		}
		$this->response = array(
							'status' => 200,
							'message' => 'Data Found.',
							'data' => $newArray
						);
		$this->__send_response();
	}
	public function getuserrecipe(){
		
		header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
		$user = $this->request->data;
		$this->loadModel('Recipes');
		$userId = $user['id'];
        $recipeList = $this->Recipes->find()->where(['Recipes.status_id' => ACTIVE_STATUS,'Recipes.user_id' => $userId])->contain(['UploadImages','Categories','Users'=>['fields' => ['first_name', 'last_name', 'username', 'email', 'id', 'image']]])->all();
        if(isset($recipeList) && !empty($recipeList))
            $recipeList = $recipeList->toArray();
        $newArray = array();
        
        foreach($recipeList as $key => $row){
			$row['user']['image'] = Router::url(BASE_URL. USER_IMAGE_URL . $row['user']['image'], true);
			foreach($row['upload_images'] as $ikey => $innerArray){
				 $row['upload_images'][$ikey]['name'] = Router::url(BASE_URL. "media/dish/" . $innerArray['name'], true);
			}
			$newArray[] = $row;
		}
        
        $this->response = array(
							'status' => 200,
							'message' => 'Data Found.',
							'data' => $newArray
						);
		$this->__send_response();   
       
	}

}
?>
