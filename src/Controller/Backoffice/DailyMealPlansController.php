<?php
namespace App\Controller\Backoffice;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;

/**
 * DailyMealPlans Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class DailyMealPlansController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->loadComponent('DataTable');
		$this->viewBuilder()->layout('backoffice');
	}


	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index()
	{
		$meal_count = $this->DailyMealPlans->find('all', ['contain' => ['Meals']])->count();
		$this->set(compact('meal_count'));
	}

	public function ajaxDailyplans()
	{
	 	$this->viewBuilder()->layout('ajax');
	 	$this->autoRender = false;
		
		$this->loadModel('DailyMealPlans');

		$this->paginate = array(
	 		'order' => ['DailyMealPlans.week_no' => 'desc'],
			'fields' => array('DailyMealPlans.id', 'DailyMealPlans.week_no', 'DailyMealPlans.week_day', 'DailyMealPlans.created'),
			'contain' => ['Meals']
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'DailyMealPlans' ),
			array( 'db' => 'week_no', 'dt' => 'week_no', 'myModel' => 'DailyMealPlans' ),
			array( 'db' => 'week_day', 'dt' => 'week_day', 'myModel' => 'DailyMealPlans' ),
			/*
			array(
			        'db'        => 'meal_date',
			        'dt'        => 'meal_day',
			        'formatter' => function( $d, $row ) {
			            return Date('l', strtotime($d));
			        }, 'myModel' => 'DailyMealPlans'
			    ),
			array(
			        'db'        => 'meal_date',
			        'dt'        => 'meal_date',
			        'formatter' => function( $d, $row ) {
			            return date( 'jS M y', strtotime($d));
			        }, 'myModel' => 'DailyMealPlans'
			    ),
			*/    
		);
		echo json_encode($this->DataTable->getResponse());
	}

	public function ajaxMeals($plan_id=null)
	{	$this->viewBuilder()->layout('ajax');
	 	$this->autoRender = false;
		
		$this->loadModel('Meals');

		$conditions = [];
		if(isset($plan_id) & !empty($plan_id)){
			$conditions = ['Meals.daily_meal_plan_id' => $plan_id];
		}

		$this->paginate = array(
			'conditions' 	=> $conditions,
	 		'order' 		=> ['Meals.time' => 'asc'],
			'fields' 		=> ['Meals.id', 'Meals.time', 'Meals.heading', 'Meals.daily_meal_plan_id', 'Meals.created'],
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Meals' ),
			array( 'db' => 'heading', 'dt' => 'heading', 'myModel' => 'Meals' ),
			array(
		        'db'        => 'time',
		        'dt'        => 'time',
		        'formatter' => function( $d, $row ) {
		            return date( 'h:i', strtotime($d));
		        }, 'myModel' => 'Meals'
		    ),
			array(
		        'db'        => 'created',
		        'dt'        => 'created',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'Meals'
		    ),
		);
		echo json_encode($this->DataTable->getResponse('','Meals'));
	}

	/**
	 * Meals method
	 *
	 * @return void
	 */
	public function meals($plan_id = null)
	{
		$this->loadModel('Meals');
		$conditions = [];
		if(isset($plan_id) && !empty($plan_id)){
			$conditions = ['Meals.daily_meal_plan_id' => $plan_id];
		}
		$meal_count = $this->Meals->find('all', ['conditions' => $conditions])->count();
		$this->set(compact('meal_count','plan_id'));
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
	public function add_plan()
	{
		$plan = $this->DailyMealPlans->newEntity();
		if ($this->request->is('post'))
		{
			$prevPlanList = $this->DailyMealPlans->find()->where(['week_no' => $this->request->data['week_no'],'week_day' => $this->request->data['week_day']])->first();
			if(empty($prevPlanList)){
				$plan = $this->DailyMealPlans->patchEntity($plan, $this->request->data);
				if ($save_plan = $this->DailyMealPlans->save($plan))
				{
					$this->Flash->success(__('This plan has successfully added.'),'success');
				}
				else{
					$this->Flash->error(__('This plan can not be saved.'),'error');
				}
			}
			else{
				$this->Flash->error(__('This meal plan already existing.'),'error');
			}
			
			return $this->redirect(['action' => 'index']);
		}
		$statuses = $this->DailyMealPlans->Statuses->find('list')->toArray();
		
		$this->set(compact('plan', 'statuses'));
		$this->set('_serialize', ['plan']);
	}	

	/**
	 * Add Meal method
	 *
	 * @return void Redirects on successful add, renders view otherwise.
	 */
	public function add_meal($plan_id = null)
	{
		$this->loadModel('Meals');

		$meal = $this->Meals->newEntity();
		if ($this->request->is('post'))
		{
			$this->request->data['daily_meal_plan_id'] = $plan_id;
			$meal = $this->Meals->patchEntity($meal, $this->request->data);
			if ($save_plan = $this->Meals->save($meal))
			{
				$this->Flash->success(__('This plan has successfully added.'),'success');
				return $this->redirect(['action' => 'meals',$plan_id]);
			} else {
				$this->Flash->success(__('This plan could not be saved. Please, try again'),'error');
			}
		}
		$statuses = $this->Meals->Statuses->find('list')->toArray();

		$this->loadModel('Recipes');

		$recipeList = $this->Recipes->find('all')->where(['Recipes.status_id' => ACTIVE_STATUS])->contain(['UploadImages','Categories','Users'=>['fields' => ['first_name', 'last_name', 'username', 'email', 'id', 'image']]]);
        if(isset($recipeList) && !empty($recipeList))
            $recipeList = $recipeList->toArray();

		$this->set(compact('meal', 'statuses','recipeList'));

		$this->set('_serialize', ['meal']);
	}	

	/**
	 * edit method
	 *
	 * @return void Redirects on successful edit, renders view otherwise.
	 */
	public function edit_plan($plan_id = null)
	{
		$plan = $this->DailyMealPlans->get($plan_id);
		
		$meals = $this->DailyMealPlans->Meals->find()->where(['daily_meal_plan_id'=>$plan_id])->all();
		
		if ($this->request->is(['patch', 'post', 'put']))
		{
			/*
			if(!empty($prevPlanList)){
				//$prevPlanList->delete();
			}
			else{
				$date1 = new \DateTime($this->request->data['meal_date']);
				$date2 = new \DateTime($this->request->data['to_date']);
				$interval = $date1->diff($date2);
				$days    = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
				$requestData = $this->request->data;
				
				if($days > 0){
					for ($i=0; $i <= $days; $i++) { 
						$requestSingle = array();
						$date = strtotime($this->request->data['meal_date']."+".$i." days");
	    				$requestSingle['meal_date'] =  date("m-d-Y", $date);
	    				
						if(Date("m-d-Y",strtotime($plan->meal_date)) != $requestData['meal_date']){
							unset($requestSingle['id']);
						}

						if(isset($meals) && !empty($meals)){
							foreach ($meals as $key => $value) {
								$mealArray['heading']						= $value->heading;
								$mealArray['time']							= $value->time;
								$mealArray['title_option_1'] 				= $value->title_option_1;
								$mealArray['short_description_option_1'] 	= $value->short_description_option_1;
								$mealArray['long_description_option_1'] 	= $value->long_description_option_1;
								$mealArray['title_option_2'] 				= $value->title_option_2;
								$mealArray['short_description_option_2'] 	= $value->short_description_option_2;
								$mealArray['long_description_option_2'] 	= $value->long_description_option_2;
								$mealArray['status_id'] 					= $value->status_id;
								$requestSingle['meals'][] = $mealArray;
							}
						}

						$planEntity = $this->DailyMealPlans->newEntity($requestSingle,['associated' => ['Meals']]);
						if ($save_plan = $this->DailyMealPlans->save($planEntity))
						{
							
						}
					}
					$this->Flash->success(__('This plan has successfully added.'),'success');
				}
				else{
					$plan = $this->DailyMealPlans->patchEntity($plan, $requestData);
					if ($save_plan = $this->DailyMealPlans->save($plan))
					{
						$this->Flash->success(__('This plan has successfully updated.'),'success');
					}
					else{
						$this->Flash->success(__('This plan can not be saved right now.'),'error');
					}
				}
			}
			*/
			$prevPlanList = $this->DailyMealPlans->find()->where(['week_no' => $this->request->data['week_no'],'week_day' => $this->request->data['week_day']])->first();
			if(empty($prevPlanList)){
				$plan = $this->DailyMealPlans->patchEntity($plan, $this->request->data);
				if ($save_plan = $this->DailyMealPlans->save($plan))
				{
					$this->Flash->success(__('This plan has successfully added.'),'success');
					return $this->redirect(['action' => 'index']);
				}
				else{
					$this->Flash->error(__('This plan can not be saved.'),'error');
				}
			}
			else{
				$this->Flash->error(__('This meal plan already existing.'),'error');
			}
		}

		$statuses = $this->DailyMealPlans->Statuses->find('list')->toArray();
		
		$this->set(compact('plan', 'statuses'));
		$this->set('_serialize', ['plan']);
	}	

	/**
	 * Edit Meal method
	 *
	 * @return void Redirects on successful edit, renders view otherwise.
	 */
	public function edit_meal($meal_id = null)
	{
		$this->loadModel('Meals');

		$meal = $this->Meals->get($meal_id);
		if ($this->request->is(['patch', 'post', 'put']))
		{
			$meal = $this->Meals->patchEntity($meal, $this->request->data);
			if ($save_plan = $this->Meals->save($meal))
			{
				$this->Flash->success(__('This plan has successfully updated.'),'success');
				return $this->redirect(['action' => 'meals',$meal['daily_meal_plan_id']]);
			} else {
				$this->Flash->success(__('This plan could not be updated. Please, try again'),'error');
			}
		}
		$statuses = $this->Meals->Statuses->find('list')->toArray();
		
		$this->loadModel('Recipes');

		$recipeList = $this->Recipes->find('all')->where(['Recipes.status_id' => ACTIVE_STATUS])->contain(['UploadImages','Categories','Users'=>['fields' => ['first_name', 'last_name', 'username', 'email', 'id', 'image']]]);
        if(isset($recipeList) && !empty($recipeList))
        $recipeList = $recipeList->toArray();

		$this->set(compact('meal', 'statuses','recipeList'));

		$this->set('_serialize', ['meal']);
	}	

	/**
	 * Delete method
	 *
	 * @param string|null $id DailyMealPlans id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		//$this->request->allowMethod(['post', 'delete']);
		$mealplan = $this->DailyMealPlans->get($id);
		if ($this->DailyMealPlans->delete($mealplan)) {
			$this->Flash->success(__('The meal plan has been deleted'));
		} else {
			$this->Flash->error(__('The meal plan could not be deleted. Please, try again'));
		}
		return $this->redirect(['action' => 'index']);
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id DailyMealPlans id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function delete_meal($id = null)
	{
		//$this->request->allowMethod(['post', 'delete']);
		$this->loadModel('Meals');

		$meal = $this->Meals->get($id);
		
		if ($this->Meals->delete($meal)) {
			$this->Flash->success(__('The meal has been deleted'));
		} else {
			$this->Flash->error(__('The meal could not be deleted. Please, try again'));
		}
		return $this->redirect(['action' => 'meals',$meal->daily_meal_plan_id]);
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
					<p>Welcome to Food fuels. Thank you for signing up.</p>
					<p>Your login email is {$data['email']} and your password is {$data['password']}</p>
					<p>Please verify your email by clicking on the following link: " . $url . "</p>
					<p>Thanks.</p>";
		$email = new Email('default');
		$email->from('noreply@foodfuelsweightloss.com');
		$email->to($data['email']);
		$email->subject('Welcome to foodfuels');
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

}
