<?php
namespace App\Controller\Backoffice;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;

/**
 * Subscription Controller
 *
 * @property EmailTemplate $EmailTemplate
 * @property PaginatorComponent $Paginator
 */
class SubscriptionsController extends AppController {

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
			'conditions' => ['Subscriptions.status' => 1],
			'order' => ['Subscriptions.id' => 'asc'],
			'fields' => array('id', 's_name', 'days', 'amount'),
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Subscriptions' ),
			array( 'db' => 's_name', 'dt' => 's_name', 'myModel' => 'Subscriptions' ),
			

			array( 'db' => 'days', 'dt' => 'days', 'myModel' => 'Subscriptions' ),
			array( 'db' => 'amount', 'dt' => 'amount', 'myModel' => 'Subscriptions' ) ,
			array( 'db' => 'amount', 'dt' => 'amount', 'myModel' => 'Subscriptions' ) 
		);
		echo json_encode($this->DataTable->getResponse());
	 }
/**
 * index method
 *
 * @return void
 */
	public function index()	{
		$this->loadModel('Subscriptions');
		$templ_count = $this->Subscriptions->find('all', ['conditions' => ['Subscriptions.status' => 1]])->count();
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
		$Subscriptions = $this->Subscriptions->get($id);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Subscriptions->patchEntity($Subscriptions, $this->request->data);
			if ($this->Subscriptions->save($Subscriptions)) {
				$this->Flash->success(__('The Subscription has been updated'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The Subscription could not be updated. Please, try again'));
			}
		}
		
		$this->set(compact('Subscriptions'));
		$this->set('_serialize', ['Subscription']);
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->EmailTemplate->id = $id;
		if (!$this->EmailTemplate->exists()) {
			throw new NotFoundException(__('Invalid email template'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->EmailTemplate->delete()) {
			$this->Session->setFlash(__('The email template has been deleted.'));
		} else {
			$this->Session->setFlash(__('The email template could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
