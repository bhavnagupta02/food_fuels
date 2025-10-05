<?php
namespace App\Controller\Backoffice;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;

/**
 * Feeds Controller
 *
 * @property EmailTemplate $EmailTemplate
 * @property PaginatorComponent $Paginator
 */
class FeedsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->loadComponent('DataTable');
		$this->viewBuilder()->layout('backoffice');
	}

	public function ajaxTemplates()
	{
		$this->viewBuilder()->layout('ajax');	
		$this->autoRender = false;
		$this->paginate = array(
			'conditions' 	=> ['Feeds.status_id' => 1],
			'order' 		=> ['Feeds.id' => 'asc'],
			'fields' 		=> array('id', 'title',  'Users.first_name', 'Users.last_name','activity_id','comment_count','like_count','share_count'),
			'contain' 		=> ['Users']
		);
	
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Feeds' ),
			array( 'db' => 'title', 'dt' => 'title', 'myModel' => 'Feeds' ),
			array( 'db' => 'first_name', 'dt' => 'first_name', 'myModel' => 'Users', 'contain' => 'user'),
			array( 'db' => 'last_name', 'dt' => 'last_name', 'myModel' => 'Users' , 'contain' => 'user'),
			array( 'db' => 'activity_id', 'dt' => 'activity_id', 'myModel' => 'Feeds'),
			array( 'db' => 'comment_count', 'dt' => 'comment_count', 'myModel' => 'Feeds'),
			array( 'db' => 'like_count', 'dt' => 'like_count', 'myModel' => 'Feeds'),
			array( 'db' => 'share_count', 'dt' => 'share_count', 'myModel' => 'Feeds'),
		);
	
		echo json_encode($this->DataTable->getResponse());
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$templ_count = $this->Feeds->find('all', ['conditions' => ['Feeds.status_id' => 1]])->count();
		$this->set(compact('templ_count'));
	}


/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$Feeds = $this->Feeds->get($id);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Feeds->patchEntity($Feeds, $this->request->data);
			if ($this->Feeds->save($Feeds)) {
				$this->Flash->success(__('The Recipe has been updated'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The Recipe could not be updated. Please, try again'));
			}
		}
		
		$this->set(compact('Feeds'));
		$this->set('_serialize', ['Feeds']);
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {

		$feeds = $this->Feeds->get($id);
		if ($this->Feeds->delete($feeds)) {
			$this->Flash->success(__('The feed has been deleted'));
		} else {
			$this->Flash->error(__('The feed could not be deleted. Please, try again'));
		}

		return $this->redirect(array('action' => 'index'));
	}
}