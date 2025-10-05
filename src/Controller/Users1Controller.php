<?php
namespace App\Controller\Backoffice;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Network\Email\Email;

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
		//$this->loadComponent('DataTable');
		$this->Auth->allow(['add', 'login', 'logout', 'verify_user']);/*'forgot_password'*/
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id User id.
	 * @return void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function profile($id = null)
	{
		$user = $this->Users->get($id, [
			'contain' => ['Statuses','UserImages']
		]);
		
		if ($this->request->is(['patch', 'post', 'put'])) {
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
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success(__('The user has been updated'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The user could not be updated. Please, try again'));
			}
		}
		$statuses = $this->Users->Statuses->find('list');
		$this->set(compact('user', 'statuses'));
		$this->set('_serialize', ['user']);
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id User id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
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
		return $this->redirect($this->Auth->logout());
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

	public function sendVerificationMail($data)
	{
		$url = $this->getVerificationUrl($data['email'], $data['verification_token']);
		$body = "	<p>Dear {$data['first_name']},</p>
					<p>Welcome to FitFix. Thank you for signing up.</p>
					<p>Your login email is {$data['email']} and your password is {$data['password']}</p>
					<p>Please verify your email by clicking on the following link: " . $url . "</p>
					<p>Thanks.</p>";
		$email = new Email('default');
		$email->from('webmaster@fitfix.co.fr');
		$email->to($data['email']);
		$email->subject('Welcome to fitfix');
		$email->replyTo('noreply@fitfix.co.fr');
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
