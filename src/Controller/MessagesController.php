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
class MessagesController extends AppController
{
	//public $helpers  = ['Custom'];

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}
  
	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->redirect(['action' => 'inbox']);
	}

	public function send()
	{
		$this->viewBuilder()->layout(false);
		$response_array = array('status' => 0, 'message' => __('Message sending failed.'));

		if($this->request->is(array('post','put'))){ 
			$userId = $this->Auth->user('id');

			$receiver_id = $this->request->data['receiver_id'];
			$this->loadModel('Conversations');
			$this->loadModel('ConversationReplies');
			
			//Update Activity Log
			$this->loadModel('ActivityLogs');
			
			$converstion = $this->Conversations->find()->where(['OR'=>array(array('Conversations.sender_id'=>$userId,'Conversations.receiver_id'=>$receiver_id),array('Conversations.sender_id'=>$receiver_id,'Conversations.receiver_id'=>$userId))])->first();
			if(!empty($converstion)){
				$converstion = $converstion->toArray();
			}
			//$log = $this->Conversation->getDataSource()->getLog(false, false);
			
				
			$replyData['timestamp'] = time();
			$replyData['reply'] = $this->request->data['message'];
			$replyData['user_id'] = $userId;
			if($converstion)
			{	
				$replyData['conversation_id'] = $converstion['id'];
				$conversationUpdate = $this->Conversations->get($converstion['id']);
				
				$data['id'] = $converstion['id'];
				$conversationPatch = $this->Conversations->patchEntity($conversationUpdate,$data);
				$this->Conversations->save($conversationPatch);
				
				//Check this combination in activity log if found then just update that record with current timestamp
				$checkActivity = $this->ActivityLogs->find()->where(['ActivityLogs.user_id'=>$receiver_id,'ActivityLogs.activity_id'=>1,'ActivityLogs.conversation_id'=>$converstion['id']])->first();
				if(empty($checkActivity)){
					//Update Activity Log For New Sender Conversation Activity
					$this->ActivityLogs->updateLog($receiver_id,1,$converstion['id'],time());
				}else{
					$checkActivity = $checkActivity->toArray();
					
					//Update Activity Log For Existing Sender Conversation Activity
					$this->ActivityLogs->updateLog($receiver_id,1,$converstion['id'],time(),$checkActivity['id']);
				}
			}
			else {
				$conData['timestamp'] = time();
				$conData['sender_id'] = $userId;
				$conData['receiver_id'] = $receiver_id;
				$conversationEntity = $this->Conversations->newEntity();
				$conversationPatch = $this->Conversations->patchEntity($conversationEntity,$conData);
				if($conversation = $this->Conversations->save($conversationPatch))
				{
					$replyData['conversation_id'] = $conversation->id;
					
					//Update Activity Log For Receiver Conversation Activity
					$this->ActivityLogs->updateLog($receiver_id,1,$conversation->id,time());
				}
			}

			if(!empty($replyData['conversation_id']))
			{
				$conversationREntity = $this->ConversationReplies->newEntity();
				$conversationRPatch = $this->ConversationReplies->patchEntity($conversationREntity,$replyData);
				if($this->ConversationReplies->save($conversationRPatch))
				{
					$response_array = array('status' => 1, 'message' => __('Message sent successfully.'));
				}
				else
				{
					$response_array = array('status' => 0, 'message' => __('Message sending failed.'));
				}
			}
			else
			{
				$response_array = array('status' => 0, 'message' => __('Message sending failed.'));
			}
		}
		echo json_encode($response_array);
		exit;
	}
	
	/**
	 * Method	: message_list
	 * Author 	: Bharat Borana
	 * Created	: 15 Jan, 2015
	 */
	public function inbox()
	{
	 	$this->set('title', 'My Inbox');
	 	$userId = $this->Auth->user('id');

 		$this->loadModel('Conversations');
		$messages = $this->Conversations->find()->where(array('OR'=>array('Conversations.sender_id'=>$userId,'Conversations.receiver_id'=>$userId)))->contain(array('Sender'=> array('fields' => ['id','first_name', 'last_name','image']),
									   'Receiver'=> array('fields' => ['id','first_name', 'last_name','image']),
										'ConversationReplies'))->order(['Conversations.modified'=>'DESC'])->all();
		
		//pr($messages);die;
		$this->set(compact(array('messages','userId')));
	}

	/**
	 * Method	: message_detail
	 * Author 	: Bharat Borana
	 * Created	: 15 Jan, 2015
	 */
	public function detail($conversation_id)
	{
	 	$this->set('title', 'My Inbox');
	 	$this->loadModel('ConversationReplies');
 		$this->loadModel('Users');
 		$userId = $this->Auth->user('id');
 		$userDetails = $this->Users->get($userId);

		$this->ConversationReplies->updateAll(['seen' => 1],['conversation_id' => $conversation_id,'user_id !=' => $userId]);

 		$messages = $this->ConversationReplies->find()->where(['Conversations.id'=>$conversation_id, 'OR' => array('Conversations.sender_id'=>$userId, 'Conversations.receiver_id'=>$userId)])->contain(['Users','Conversations'])->order(['ConversationReplies.id' => 'DESC'])->all();

 		if(isset($messages) && !empty($messages))
 			$messages = $messages->toArray();

 		$this->set(compact(array('userDetails','messages')));
 	}

	  /**
	  * Method	: Add Message data
	  * Author 	: Bharat Borana
	  * Created	: 15 Jan 2015
	  * Purpose	: Add Message using ajax
	  */
	public function add_message()
	{
	  	$this->viewBuilder()->layout(false);
		
	  	if($this->request->is('ajax')){	
			$this->loadModel('ConversationReplies');
			$this->loadModel('Conversations');
			
			$userId = $this->Auth->user('id');
			//Update Activity Log
			$this->loadModel('ActivityLogs');
			
			$converstion = $this->Conversations->find()->where(['Conversations.id'=>$this->request->data['conversation_id'],'OR'=>array('Conversations.sender_id'=>$userId,'Conversations.receiver_id'=>$userId)])->first();
			if(isset($converstion) && !empty($converstion)){
				$converstion = $converstion->toArray();
			}
			
			$replyData['timestamp'] = time();
			$replyData['reply'] = $this->request->data['reply'];
			$replyData['user_id'] = $userId;
			if($converstion)
			{
				$replyData['conversation_id'] = $converstion['id'];
				$conversationEntity = $this->Conversations->get($converstion['id']);
				$this->Conversations->save($conversationEntity);
			}

			$freshMessages = '';
			
			if(!empty($replyData['conversation_id']))
			{
				$conReply = $this->ConversationReplies->newEntity($replyData);
				if($this->ConversationReplies->save($conReply))
				{
					if($converstion['receiver_id'] == $userId)
						$receiver_id = $converstion['sender_id'];
					else
						$receiver_id = $converstion['receiver_id'];
					
					//Check this combination in activity log if found then just update that record with current timestamp
					$checkActivity = $this->ActivityLogs->find()->where(['ActivityLogs.user_id'=>$receiver_id,'ActivityLogs.activity_id'=>1,'ActivityLogs.conversation_id'=>$converstion['id']])->first();
					if(empty($checkActivity)){
						//Update Activity Log For New Sender Conversation Activity
						$this->ActivityLogs->updateLog($receiver_id,1,$converstion['id'],time());
					}else{
						$checkActivity = $checkActivity->toArray();	
						//Update Activity Log For Existing Sender Conversation Activity
						$this->ActivityLogs->updateLog($receiver_id,1,$converstion['id'],time(),$checkActivity['id']);
					}


					$messages = $this
									->ConversationReplies
									->find()
									->where(['ConversationReplies.conversation_id' => $this->request->data['conversation_id'], 'ConversationReplies.timestamp >' => $this->request->data['last_reply_id']])
									->contain(['Users' => ['fields' => ['Users.id','Users.first_name','Users.last_name','Users.image'] ]])
									->order(['ConversationReplies.id' => 'DESC'])
									->all();

					if(isset($messages) && !empty($messages)){
						$messages = $messages->toArray();
					}	
				}
			}
			$this->set(compact(array('userDetails','messages')));
	  	}
	} 

	  /**
	  * Method	: Get Message data
	  * Author 	: Bharat Borana
	  * Created	: 15 Jan 2016
	  * Purpose	: Get Message using ajax
	  */
	  public function get_message()
	  {
	  	$freshMessages = '';
	  	$userDetails = '';
	  	$this->viewBuilder()->layout(false);
		
	  	if($this->request->is('ajax')){	
			$this->loadModel('ConversationReplies');
			if(isset($this->request->data['conversation_id']) && isset($this->request->data['last_reply_id']))
			{	
				$messages = $this
							->ConversationReplies
							->find()
							->where(['ConversationReplies.conversation_id' => $this->request->data['conversation_id'], 'ConversationReplies.timestamp >' => $this->request->data['last_reply_id']])
							->contain(['Users' => ['fields' => ['Users.id','Users.first_name','Users.last_name','Users.image'] ]])
							->order(['ConversationReplies.id' => 'DESC'])
							->all();
				
				if(isset($messages) && !empty($messages))
 					$messages = $messages->toArray();
 			}
		}

		$this->set(compact(array('messages')));
		$this->render('add_message');
	  } 


}