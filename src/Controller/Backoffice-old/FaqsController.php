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
class FaqsController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->loadComponent('DataTable');
		$this->viewBuilder()->layout('backoffice');
	}
	
	/**
	 * No View
	 * @return void
	 * function to load faqs using ajax 
	 */
	public function ajaxFaqs($plan_id=null)
	{	$this->viewBuilder()->layout('ajax');
	 	$this->autoRender = false;
		$this->loadModel('Faqs');

		$conditions = [];
		$this->paginate = array(
			'conditions' 	=> $conditions,
	 		'order' 		=> ['Faqs.id' => 'desc'],
			'fields' 		=> ['Faqs.id', 'Faqs.question', 'Faqs.answer', 'Faqs.created'],
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Faqs' ),
			array( 'db' => 'question', 'dt' => 'question', 'myModel' => 'Faqs' ),
			array( 'db' => 'answer', 'dt' => 'answer', 'myModel' => 'Faqs' ),
			array(
		        'db'        => 'created',
		        'dt'        => 'created',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'Faqs'
		    ),
		);
		echo json_encode($this->DataTable->getResponse('','Faqs'));
	}

	/**
	 * Displays a view
	 * view file located at src/Template/Faqs/index.ctp
	 * @param mixed What page to display
	 * @return void
	 * @throws NotFoundException When the view file could not be found
	 *	or MissingViewException in debug mode.
	 * function to list FAQs 
	 */
	public function index($plan_id = null)
	{
		$this->loadModel('Faqs');
		$conditions = [];
		$faq_count = $this->Faqs->find('all')->count();
		$this->set(compact('faq_count'));
	}

	/**
	 * Displays a view
	 * view file located at src/Template/Faqs/add_faq.ctp
	 * @param mixed What page to display
	 * @return void
	 * @throws NotFoundException When the view file could not be found
	 * or MissingViewException in debug mode.
	 * function to add FAQs 
	 */
	public function add_faq($plan_id = null)
	{
		$this->loadModel('Faqs');

		$faq = $this->Faqs->newEntity();
		if ($this->request->is('post'))
		{
			$faq = $this->Faqs->patchEntity($faq, $this->request->data);
			if ($save_plan = $this->Faqs->save($faq))
			{
				$this->Flash->success(__('This faq has successfully added.'),'success');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->success(__('This faq could not be saved. Please, try again'),'error');
			}
		}
		$statuses = $this->Faqs->Statuses->find('list')->toArray();
		
		$this->set(compact('faq', 'statuses'));
		$this->set('_serialize', ['faq']);
	}	

	/**
	 * Displays a view
	 * view file located at src/Template/Faqs/edit_faq.ctp
	 * @param mixed What page to display
	 * @return void
	 * @throws NotFoundException When the view file could not be found
	 * or MissingViewException in debug mode.
	 * function to edit FAQs 
	 */
	public function edit_faq($faq_id = null)
	{
		$this->loadModel('Faqs');

		$faq = $this->Faqs->get($faq_id);
		if ($this->request->is(['patch', 'post', 'put']))
		{
			$faq = $this->Faqs->patchEntity($faq, $this->request->data);
			if ($save_plan = $this->Faqs->save($faq))
			{
				$this->Flash->success(__('This faq has successfully updated.'),'success');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->success(__('This faq could not be updated. Please, try again'),'error');
			}
		}
		$statuses = $this->Faqs->Statuses->find('list')->toArray();
		
		$this->set(compact('faq', 'statuses'));
		$this->set('_serialize', ['faq']);
	}	

	/**
	 * Delete method
	 *
	 * @param string|null $id Faq id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 * function to delete the FAQ
	 */
	public function delete_faq($id = null)
	{
		$this->loadModel('Faqs');
		$faq = $this->Faqs->get($id);
		
		if ($this->Faqs->delete($faq)) {
			$this->Flash->success(__('The faq has been deleted'));
		} else {
			$this->Flash->error(__('The faq could not be deleted. Please, try again'));
		}
		return $this->redirect(['action' => 'index']);
	}

	public function login()
	{ 
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
