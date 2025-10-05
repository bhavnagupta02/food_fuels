<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\I18n;  


/**
 * Messages Controller
 *
 * @property \App\Model\Table\ConversationsTable $Conversations
 */
class NotificationsController extends AppController
{
	//public $helpers  = ['Custom'];

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}
  
	
	/**
	 * Method	: index
	 * Author 	: Bharat Borana
	 * Created	: 15 Jan, 2015
	 */
	 public function index()
	 {
	 	$this->set('title', 'My Notifications');
	 	$userId = $this->Auth->user('id');
		
		$this->loadModel('Users');
		
		$notiArray = $this->Users->getAllActivities(['user_id' => $userId]);
		
		$this->loadModel('ActivityLogs');
		$this->ActivityLogs->updateAll(['seen' => 1],['user_id' => $userId]);

		$this->set(compact(array('notiArray','userId')));
	 }

}