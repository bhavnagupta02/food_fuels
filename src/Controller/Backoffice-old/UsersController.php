<?php
namespace App\Controller\Backoffice;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\I18n\I18n;
use Cake\Network\Email\Email;
use Cake\I18n\Time;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->loadComponent('DataTable');
		$this->viewBuilder()->layout('backoffice');
		$this->Auth->allow(['add', 'login', 'logout', 'verify_user']);/*'forgot_password'*/
	}


	 public function ajaxTrainers()
	 {
	 	$this->viewBuilder()->layout('ajax');
	 	$this->autoRender = false;
		
		$conditions = ['Users.group_id' => USERGROUPID];
	 	$this->paginate = array(
	 		'conditions' => $conditions,
	 		'contain' => array(
	 			'Statuses', 'Countries'
			),
			'order' => ['Users.id' => 'asc'],
			'fields' => array('Users.id', 'Users.first_name', 'Users.last_name', 'Users.dob', 'Statuses.name', 'Users.email', 'Users.is_paid', 'Users.mobile', 'Users.address', 'Users.city', 'Users.zipcode', 'Users.created', 'Countries.country'),
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Users' ),
			array( 'db' => 'first_name', 'dt' => 'first_name', 'myModel' => 'Users' ),
			array( 'db' => 'last_name', 'dt' => 'last_name', 'myModel' => 'Users' ),
			array( 'db' => 'email', 'dt' => 'email' , 'myModel' => 'Users'),
			array(
		        'db'        => 'created',
		        'dt'        => 'created',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'Users'
		    ),
		);
		echo json_encode($this->DataTable->getResponse());
	 }
	
	/**
	 * Index method
	 *
	 * @return void
	 */
	public function trainers()
	{
		$trainer_count = $this->Users->find('all', ['conditions' => ['Users.group_id' => USERGROUPID]])->count();
		$this->set(compact('trainer_count'));
	}

	public function ajaxWeights()
	{
	 	$this->viewBuilder()->layout('ajax');
	 	$this->autoRender = false;
		
		$this->loadModel('UserWeights');

		$conditions = [];
		if(isset($user_id) && !empty($user_id)){
			$conditions = ['UserWeights.user_id' => $user_id];
		}

	 	$this->paginate = array(
	 		'conditions' => $conditions,
	 		'order' => ['UserWeights.id' => 'asc'],
			'fields' => array('id', 'user_id', 'weight', 'weight_date', 'Users.first_name', 'Users.last_name'),
			'contain' => ['Users'],
			/*'join' => ['table' => 'users', 'alias' => 'Users', 'type' => 'inner', 'conditions' => ['UserWeights.user_id' => 'Users.id']]*/
		);

		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'UserWeights' ),
			array( 'db' => 'first_name', 'dt' => 'first_name', 'myModel' => 'Users', 'contain' => 'user'),
			array( 'db' => 'last_name', 'dt' => 'last_name', 'myModel' => 'Users' , 'contain' => 'user'),
			array( 'db' => 'weight', 'dt' => 'weight', 'myModel' => 'UserWeights' ),
			array(
		        'db'        => 'weight_date',
		        'dt'        => 'weight_date',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'UserWeights'
		    ),
		);
		echo json_encode($this->DataTable->getResponse('','UserWeights'));
	}
	
	/**
	 * Index method
	 *
	 * @return void
	 */
	public function weight($user_id = null)
	{
		$conditions = [];
		$this->loadModel('UserWeights');

		if(isset($user_id) && !empty($user_id)){
			$conditions = ['UserWeights.user_id' => $user_id];
		}
		$weight_count = $this->UserWeights->find('all')->count($conditions);
		$this->set(compact('weight_count'));
	}

	public function ajaxClients()
	{
	 	$this->viewBuilder()->layout('ajax');
		$this->autoRender = false;
		$conditions = ['Users.group_id' => CLIENTGROUPID];
	 	$this->paginate = array(
	 		'conditions' => $conditions,
	 		'contain' => array(
	 			'Statuses', 'Countries', 'Trainers'
			),
			'order' => ['Users.id' => 'asc'],
			'fields' => array('Users.id', 'Users.first_name', 'Users.last_name', 'Users.username', 'Users.gender', 'Users.dob','Users.assign_coach','Users.leaderboard_show','Users.total_weight_loss_percent','Users.month_weight_loss_percent','Users.week_weight_loss_percent','Users.is_verified','Statuses.name', 'Users.mobile', 'Users.goal_weight', 'Users.is_paid', 'Users.email', 'Users.mobile', 'Users.address', 'Users.city', 'Users.zipcode', 'Countries.country', 'Users.created', 'Trainers.first_name', 'Trainers.last_name'),
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Users' ),
			array( 'db' => 'first_name', 'dt' => 'first_name', 'myModel' => 'Users' ),
			array( 'db' => 'last_name', 'dt' => 'last_name', 'myModel' => 'Users' ),
			array( 'db' => 'username', 'dt' => 'username', 'myModel' => 'Users' ),
			array( 'db' => 'assign_coach', 'dt' => 'assign_coach','myModel' => 'Users'),
			array( 'db' => 'first_name', 'dt' => 'trainers.first_name', 'myModel' => 'trainer', 'contain' => 'trainer'),
			array( 'db' => 'last_name', 'dt' => 'trainers.last_name', 'myModel' => 'trainer', 'contain' => 'trainer'),
			array( 'db' => 'is_verified', 'dt' => 'is_verified' , 'myModel' => 'Users'),
			array( 'db' => 'mobile', 'dt' => 'mobile' , 'myModel' => 'Users'),
			array( 'db' => 'email', 'dt' => 'email' , 'myModel' => 'Users'),
			/*
			array( 'db' => 'gender', 'dt' => 'gender' , 'myModel' => 'Users'),
			array( 'db' => 'goal_weight', 'dt' => 'goal_weight' , 'myModel' => 'Users'),
			array( 'db' => 'is_paid', 'dt' => 'is_paid','myModel' => 'Users'),
			array( 'db' => 'leaderboard_show', 'dt' => 'leaderboard_show' , 'myModel' => 'Users'),
			array( 'db' => 'name', 'dt' => 'name', 'myModel' => 'status', 'contain' => 'status'),
			array(
		        'db'        => 'dob',
		        'dt'        => 'dob',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'Users'
		    ),
		    */
		);
	
		echo json_encode($this->DataTable->getResponse());
	 }

	/**
	 * Index method
	 *
	 * @return void
	 */
	public function clients()
	{
		$client_count = $this->Users->find('all', ['conditions' => ['Users.group_id' => CLIENTGROUPID]])->count();
		$this->set(compact('client_count'));
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
	public function add()
	{
		$this->loadComponent('Common');
		$user = $this->Users->newEntity();
		if ($this->request->is('post'))
		{
			$this->request->data['last_login'] = date('Y-m-d H:i:s');
			// In a controller action
			$this->loadComponent('Common');
			$randString = $this->Common->generateRandomString(25);
			$user_data['verification_token'] = $randString; 
			
			$this->request->data['verification_token'] = $randString;
			
			if(!empty($this->request->data['subscription_id'])) {
				$this->loadModel('Subscriptions');
				$subscriptionDetails = $this->Subscriptions->get($this->request->data['subscription_id']);
				
				if(isset($subscriptionDetails->days) && !empty($subscriptionDetails->days))
				{
					$this->request->data['user_subscriptions'][0]['subscription_id'] 	= $subscriptionDetails->id;
					$this->request->data['user_subscriptions'][0]['paid_date'] 			= new Time(Date('Y-m-d'));
					$this->request->data['user_subscriptions'][0]['start_date'] 		= new Time(Date('Y-m-d'));
					$this->request->data['user_subscriptions'][0]['end_date'] 			= new Time(Date('Y-m-d',strtotime('+ '.$subscriptionDetails->days.' days')));
					$this->request->data['user_subscriptions'][0]['discounted_amount'] 	= 0;
					$this->request->data['user_subscriptions'][0]['total_amount'] 		= $subscriptionDetails->amount;
					$this->request->data['user_subscriptions'][0]['final_amount'] 		= $subscriptionDetails->amount;
					$this->request->data['user_subscriptions'][0]['is_admin'] 			= 1;
					$this->request->data['is_paid'] 	= 1;
					$this->request->data['paid_date'] 	= new Time(Date('Y-m-d'));
				}
			}
			$user = $this->Users->patchEntity($user, $this->request->data, ['associated' => ['UserSubscriptions']]);
			if ($save_user = $this->Users->save($user))
			{
				$this->loadModel('ActivityLogs');
				$checkCount = $this->ActivityLogs->find()->where(['user_id' => $save_user->trainer_id, 'member_id' => $save_user->id])->count();
                if(!$checkCount){
                	$this->ActivityLogs->updateLog($save_user->trainer_id,8,$save_user->id,time());
				}
				$this->sendVerificationMail($this->request->data);
				
				// send email to coach when he is being assigned to the user
				if(!empty($this->request->data['trainer_id'])) {
					
					$updatedUserInfo = $this->Users->find()->where(['Users.id' => $save_user->id])->select(['id','first_name','last_name','email','mobile','subscription_id'])->first();
					
					$this->loadModel('Subscriptions');
					$subscriptionData = $this->Subscriptions->find()->where(['id' => $save_user->subscription_id])->first();
					if(!empty($subscriptionData)) {
						$date 			  = date("Y-m-d");// current date
						$expirationData   = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " +".$subscriptionData['days']." days")); // membership expiration date 
						$membership 	  = $subscriptionData->s_name;
					} else {
						$expirationData   = 'N/A'; 
						$membership 	  = 'N/A';
					}	
					
					$trainerDetails = $this->Users->find()->where(['Users.id' => $this->request->data['trainer_id']])->select(['id','first_name','last_name','email'])->first();
					
					$token1      		=   ['{{coach_name}}', '{{user_name}}', '{{user_email}}', '{{phone}}', '{{membership}}', '{{expiration}}'];
					$tokenVal1   		=   [$trainerDetails->first_name.' '.$trainerDetails->last_name, $updatedUserInfo->first_name.' '.$updatedUserInfo->last_name, $updatedUserInfo->email, $updatedUserInfo->mobile, $membership, $expirationData];
					
					$sendMail1   		=   $this->Common->_send_email($trainerDetails->email, $token1, $tokenVal1, 'user-joined-group');
					$sendMail1   		=   $this->Common->_send_email('development@intensofy.com', $token1, $tokenVal1, 'user-joined-group');
					
					$token      		=   ['{{coach_name}}', '{{user_name}}', '{{user_email}}', '{{phone}}', '{{membership}}'];
					$tokenVal   		=   [$trainerDetails->first_name.' '.$trainerDetails->last_name, $updatedUserInfo->first_name.' '.$updatedUserInfo->last_name, $updatedUserInfo->email, $updatedUserInfo->mobile, $membership];
					$emailTemplate		=	'coach_assigned';

				} else {
					$userId 			= 	$save_user->id;
					$linkAssignCoach	=	BASE_URL."backoffice/users/edit/".$userId;
					$token      		=   ['{{user}}','{{link_url}}'];
					$tokenVal   		=   [$user->first_name." ".$user->last_name,$linkAssignCoach];
					$emailTemplate		=	'assign_coach';	
				}
				// send email to admin
				$sendMail   		=   $this->Common->_send_email('sean@foodfuelsweightloss.com', $token, $tokenVal, $emailTemplate);
				$sendMail   		=   $this->Common->_send_email('suyash@intensofy.com', $token, $tokenVal, $emailTemplate);
				
				$this->Flash->success(__('This user has been registered successfully. A verification email has been sent on his entered email id.'),'success');
				return $this->redirect(['action' => 'clients']);
			} else {
				$this->Flash->success(__('This user could not be saved. Please, try again'),'error');
			}
		}

		$this->loadModel('Subscriptions');
    
		$statuses = $this->Users->Statuses->find('list')->toArray();
		$countries = $this->Users->Countries->find('list')->toArray();
	    $subscriptions = $this->Subscriptions
				    ->find()
				    ->select(['id', 's_name'])
				    ->formatResults(function($results) {
				        return $results->combine(
				            'id',
				            function($row) {
				                return $row['s_name'];
				            }
				        );
				    })
				    ->where(['Subscriptions.status' => 1])
				    ->toArray();

		$trainers = $this->Users
				    ->find()
				    ->select(['id', 'first_name', 'last_name'])
				    ->formatResults(function($results) {
				        return $results->combine(
				            'id',
				            function($row) {
				                return $row['first_name'] . ' ' . $row['last_name'];
				            }
				        );
				    })
				    ->where(['Users.status_id' => 1, 'Users.group_id' => USERGROUPID])
				    ->toArray();

		$this->set(compact('user', 'statuses', 'countries', 'trainers', 'subscriptions'));
		$this->set('_serialize', ['user']);
	}	

	/**
	 * Add Coach method
	 *
	 * @return void Redirects on successful add, renders view otherwise.
	 */
	public function add_coach()
	{
		$user = $this->Users->newEntity();
		if ($this->request->is('post'))
		{
			$this->request->data['last_login'] = date('Y-m-d H:i:s');
			// In a controller action
			$this->loadComponent('Common');
			$randString = $this->Common->generateRandomString(25);
			$user_data['verification_token'] = $randString; 
			
			$this->request->data['verification_token'] = $randString;
			
			if(isset($this->request->data['images'][0]['name']) && !empty($this->request->data['images'][0]['name']))
			{
				$i = 1;
				foreach ($this->request->data['images'] as $key => $value)
				{
					$ext = substr(strtolower(strrchr($value['name'], '.')), 1);
					$FileName = str_replace(' ', '-',$this->request->data['first_name']).mt_rand().'-'.time().'.'.$ext;
					
					move_uploaded_file($value['tmp_name'],USER_IMAGE_PATH.$FileName);
					
					// store the filename in the array to be saved to the db
					$this->request->data['user_images'][$i]['name'] = $FileName;
					$this->request->data['user_images'][$i]['type'] = 'user';
					$i++;
				}
			}
			unset($this->request->data['images']);
			$user = $this->Users->patchEntity($user, $this->request->data, ['associated' => ['UserImages']]);
			if ($save_user = $this->Users->save($user))
			{
				$this->sendVerificationMail($this->request->data);
				$this->Flash->success(__('This Coach has been registered as a trainer. A verification email has been sent on his entered email id.'),'success');
				return $this->redirect(['action' => 'trainers']);
			} else {
				$this->Flash->success(__('This coach could not be saved. Please, try again'),'error');
			}
		}
		$statuses = $this->Users->Statuses->find('list');
		$countries = $this->Users->Countries->find('list');
		$this->set(compact('user', 'statuses', 'countries'));
		$this->set('_serialize', ['user']);
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
		$this->loadComponent('Common');
		$user = $this->Users->get($id, [
			'contain' => ['Statuses','UploadImages','Countries']
		]);
		$oldUser = $user->toArray();
		
		$this->loadModel('UserSubscriptions');
		$alreadyExists = $this->UserSubscriptions->find()->where(['user_id' => $id])->last();
		
    	if(!empty($alreadyExists)){

    		$user->subscription_id = $alreadyExists->subscription_id;
    		$user->end_date 				= $alreadyExists->end_date;
			$user->paid_date 				= $alreadyExists->paid_date;
			$user->start_date 				= $alreadyExists->start_date;
    		$user->user_subscriptions_id 	= $alreadyExists->id;
    		//$userSubEntity = $this->UserSubscriptions->newEntity($userSub);
    		//$this->UserSubscriptions->save($userSubEntity);
    	}
	    
		if ($this->request->is(['patch', 'post', 'put'])) {
			if(isset($this->request->data['subscription_id']) && !empty($this->request->data['subscription_id'])){
				$this->loadModel('Subscriptions');
				$subscriptionDetails = $this->Subscriptions->get($this->request->data['subscription_id']);
				
				if(isset($subscriptionDetails->days) && !empty($subscriptionDetails->days))
				{
					$this->request->data['user_subscriptions'][0]['subscription_id'] 	= $subscriptionDetails->id;
					$this->request->data['user_subscriptions'][0]['paid_date'] 			= new Time(Date('Y-m-d'));
					$this->request->data['user_subscriptions'][0]['start_date'] 		= new Time(Date('Y-m-d'));
					//$this->request->data['user_subscriptions'][0]['end_date'] 			= new Time(Date('Y-m-d',strtotime('+ '.$subscriptionDetails->days.' days')));
					$this->request->data['user_subscriptions'][0]['end_date'] 			= new Time(Date('Y-m-d',strtotime($this->request->data['end_date'])));
					$this->request->data['user_subscriptions'][0]['discounted_amount'] 	= 0;
					$this->request->data['user_subscriptions'][0]['total_amount'] 		= $subscriptionDetails->amount;
					$this->request->data['user_subscriptions'][0]['final_amount'] 		= $subscriptionDetails->amount;
					$this->request->data['user_subscriptions'][0]['is_admin'] 			= 1;
					$this->request->data['is_paid'] 	= 1;
					$this->request->data['paid_date'] 	= new Time(Date('Y-m-d'));
				}
				$user = $this->Users->patchEntity($user, $this->request->data, ['associated' => ['UserSubscriptions']]);
			}
			else{
				$user = $this->Users->patchEntity($user, $this->request->data);
			}
			
			if ($this->Users->save($user)) {
				$this->Flash->success(__('The user has been updated'));
				
				$this->loadModel('ActivityLogs');
				$checkCount = $this->ActivityLogs->find()->where(['user_id' => $user->trainer_id, 'member_id' => $id])->count();
                if(!$checkCount && $oldUser['group_id'] == CLIENTGROUPID && $oldUser['trainer_id'] != $this->request->data['trainer_id']){
                	$this->ActivityLogs->updateLog($user->trainer_id,8,$id,time());
				}
				
				if($this->request->data['user_subscriptions_id'] && $this->request->data['end_date']){
					$userSubEntity = $this->UserSubscriptions->get($this->request->data['user_subscriptions_id']);
					$userSubUpdate['end_date']=$this->request->data['end_date'];
					$userSubUpdate['start_date'] = $this->request->data['start_date'];
					$userSub = $this->UserSubscriptions->patchEntity($userSubEntity,$userSubUpdate);
					$this->UserSubscriptions->save($userSub);
				}
				
				// send email to coach when he is being assigned to the user
				if(!empty($this->request->data['trainer_id']) && ($oldUser['trainer_id'] != $this->request->data['trainer_id'])) {
					
					$updatedUserInfo = $this->Users->find()->where(['Users.id' => $id])->select(['id','first_name','last_name','email','mobile','subscription_id'])->first();
					
					$this->loadModel('Subscriptions');
					$subscriptionData = $this->Subscriptions->find()->where(['id' => $updatedUserInfo->subscription_id])->first();
					if(!empty($subscriptionData)) {
						$date 			  = date("Y-m-d");// current date
						$expirationData   = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " +".$subscriptionData['days']." days")); // membership expiration date 
						$membership 	  = $subscriptionData->s_name;
					} else {
						$expirationData   = 'N/A'; 
						$membership 	  = 'N/A';
					}	
					
					$trainerDetails = $this->Users->find()->where(['Users.id' => $this->request->data['trainer_id']])->select(['id','first_name','last_name','email'])->first();
					
					$token1      		=   ['{{coach_name}}', '{{user_name}}', '{{user_email}}', '{{phone}}', '{{membership}}', '{{expiration}}'];
					$tokenVal1   		=   [$trainerDetails->first_name.' '.$trainerDetails->last_name, $updatedUserInfo->first_name.' '.$updatedUserInfo->last_name, $updatedUserInfo->email, $updatedUserInfo->mobile, $membership, $expirationData];
					
					$sendMail1   		=   $this->Common->_send_email($trainerDetails->email, $token1, $tokenVal1, 'user-joined-group');
					$sendMail1   		=   $this->Common->_send_email('development@intensofy.com', $token1, $tokenVal1, 'user-joined-group');
					
					// send email to admin when new coach is being assigned to the user
					$linkAssignCoach	=	BASE_URL."backoffice/users/edit/".$id;
					$token      		=   ['{{user}}','{{link_url}}'];
					$tokenVal   		=   [$user->first_name." ".$user->last_name,$linkAssignCoach];

					//$sendMail   		=   $this->Common->_send_email('sean@foodfuelsweightloss.com',$token,$tokenVal,'assign_coach');
					//$sendMail   		=   $this->Common->_send_email('suyash@intensofy.com',$token,$tokenVal,'assign_coach');
				}
				
				/*
				if($user->group_id == CLIENTGROUPID)
					return $this->redirect(['action' => 'clients']);
				else
					return $this->redirect(['action' => 'trainers']);
				*/
			} else {
				$this->Flash->error(__('The user could not be updated. Please, try again'));
			}
		}
		
		$statuses = $this->Users->Statuses->find('list')->toArray();
		$countries = $this->Users->Countries->find('list')->toArray();
		$this->loadModel('Subscriptions');
		$subscriptions = $this->Subscriptions
				    ->find()
				    ->select(['id', 's_name'])
				    ->formatResults(function($results) {
				        return $results->combine(
				            'id',
				            function($row) {
				                return $row['s_name'];
				            }
				        );
				    })
				    ->where(['Subscriptions.status' => 1])
				    ->toArray();
    	
		$conditions = ['Users.status_id' => 1, 'Users.group_id' => USERGROUPID];

		$trainers = $this->Users
				    ->find()
				    ->select(['id', 'first_name', 'last_name'])
				    ->formatResults(function($results) {
				        return $results->combine(
				            'id',
				            function($row) {
				                return $row['first_name'] . ' ' . $row['last_name'];
				            }
				        );
				    })
				    ->where($conditions);
		
		$this->set(compact('user', 'statuses', 'countries', 'trainers', 'subscriptions'));
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
		$user = $this->Users->get($id);
		if ($this->Users->delete($user)) {
			$this->Flash->success(__('The user has been deleted'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again'));
		}

		if($user->group_id == CLIENTGROUPID)
			return $this->redirect(['action' => 'clients']);
		else
			return $this->redirect(['action' => 'trainers']);
	}

	/**
	 * Edit Weight method
	 *
	 * @param string|null $id User id.
	 * @return void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit_weight($id = null)
	{
		$this->loadModel('UserWeights');
		$user = $this->UserWeights->get($id);
		$oldUser = $user->toArray();
		
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->UserWeights->patchEntity($user, $this->request->data);
			
			if ($this->UserWeights->save($user)) {
				$this->Flash->success(__('The user weights has been updated'));
				
				return $this->redirect(['action' => 'weight']);
			} else {
				$this->Flash->error(__('The user weights could not be updated. Please, try again'));
			}
		}
		
		$this->set(compact('user'));
		$this->set('_serialize', ['user']);
	}

	/**
	 * Delete Weight method
	 *
	 * @param string|null $id User id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function delete_weight($id = null)
	{
		$this->loadModel('UserWeights');
		$user = $this->UserWeights->get($id);
		if ($this->UserWeights->delete($user)) {
			$this->Flash->success(__('The user weight has been deleted'));
		} else {
			$this->Flash->error(__('The user weight could not be deleted. Please, try again'));
		}

		return $this->redirect(['action' => 'weight']);
	}

	public function login()
	{ 
		//$this->layout = 'login';
		$this->viewBuilder()->layout('login');
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user && $user['group_id'] == ADMINGROUPID) {
				//check/save login cookie
				if(isset($this->request->data['remember_me']) && $this->request->data['remember_me']) {
					//save cookie
					$this->_saveLoginCookie($user['email'], $user['id']);
				}
				$redirect = ['prefix' => 'backoffice', 'controller' => 'Users', 'action' => 'dashboard'];
				$this->Auth->setUser($user);
				return $this->redirect($redirect);
			} else {
				$this->Flash->error(__('Invalid email or password, try again'));
			}
		} else {
			$user = $this->Auth->user('id');
			if(!empty($user) && $this->Auth->user('group_id') == ADMINGROUPID) {
				$redirect = ['prefix' => 'backoffice', 'controller' => 'Users', 'action' => 'dashboard'];
				return $this->redirect($redirect);
			} else {
				//check login cookie
				if(isset($_COOKIE['_al']) && !empty($_COOKIE['_al'])) {
					$user = $this->Users->find('all', array(
						'conditions' => array('Users.cookie_token' => $_COOKIE['_al'], 'Users.status_id' => 1)
					))->first();
					if(!empty($user) && $user->group_id == ADMINGROUPID) {
						//user exists, just log it in
						$this->Auth->setUser($user->toArray());
						$redirect = ['prefix' => 'backoffice', 'controller' => 'Users', 'action' => 'dashboard'];
						return $this->redirect($redirect);
					}
				}
			}
			$this->set(compact('user'));
		}
	}

	public function logout()
	{
		$cookie_name = "_al";
		setcookie('_al', false, time() - 3600, '/');
		unset($_COOKIE['_al']);
		$this->Auth->logout();
		return $this->redirect(['action' => 'login']);
	}

	private function _saveLoginCookie($user_email, $user_id) {
		//save cookie
		$cookie_name = "_al";
		$cookie_value = md5($user_email . substr(md5(uniqid(mt_rand(), true)), 0, 8)	);
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
		$saveUser = $this->Users->get($user_id);
		$saveUser = $this->Users->patchEntity($saveUser, array('cookie_token' => $cookie_value));
		$this->Users->save($saveUser);
	}

	public function dashboard()
	{
		$this->viewBuilder()->layout('backoffice');
	}

	public function sendVerificationMail($data)
	{
		$url = $this->getVerificationUrl($data['email'], $data['verification_token']);
		$body = "	<p>Dear {$data['first_name']},</p>
					<p>Welcome to Food Fuels. Thank you for signing up.</p>
					<p>Your login email is {$data['email']} and your password is {$data['password']}</p>
					<p>Please verify your email by clicking on the following link: " . $url . "</p>
					<p>Thanks.</p>";
		$email = new Email('default');
		$email->from('noreply@foodfuelsweightloss.com');
		$email->to($data['email']);
		$email->subject('Welcome to food fuels');
		$email->replyTo('noreply@foodfuelsweightloss.com');
		$email->emailFormat('html');
		
		$email->send($body);	
				
	}

	function getVerificationUrl($user_email = '', $token = '')
	{
		$linkTag = BASE_URL . 'users/verify_user/' . $user_email . '/' . $token;
		$url = "<a href='$linkTag'>Verify mail</a><br><br>";
		$url .= "If the above link doesn't work, please copy and paste the following URL into your browser's address bar and press Enter:<br> <br>$linkTag";
		return $url;
	}

	public function change_password($id=null) {
		$this->set('title', 'Change Password');

		$user = $this->Users->get($id, ['fields' => ['id', 'password','group_id']]);

		$userdata = $user->toArray();
		if($this->request->is(['post','put'])){
			$dbPassword = $userdata['password'];
			if($this->request->data['password'] != $this->request->data['confirm_password']){
				$this->Flash->error(__('Password and confirm password does not match.'));
			}
			else {
				$saveUser = $this->Users->patchEntity($user, ['password' => $this->request->data['password']]);
				$saveUser = $this->Users->save($saveUser);
				if($saveUser ) {
					$this->Flash->success(__('Your password has been updated'));

					if($user->group_id == CLIENTGROUPID)
						return $this->redirect(['action' => 'clients']);
					else
						return $this->redirect(['action' => 'trainers']);
				} else {
					$this->Flash->error(__('The password could not be updated. Please try again'));
				}
			}
		}
		$this->set('user',$user);
	}

}
