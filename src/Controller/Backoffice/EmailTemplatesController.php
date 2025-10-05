<?php
namespace App\Controller\Backoffice;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;

/**
 * EmailTemplates Controller
 *
 * @property EmailTemplate $EmailTemplate
 * @property PaginatorComponent $Paginator
 */
class EmailTemplatesController extends AppController {

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
			'conditions' => ['EmailTemplates.status_id' => 1],
			'order' => ['EmailTemplates.id' => 'asc'],
			'fields' => array('id', 'subject', 'slug', 'from_name'),
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'EmailTemplates' ),
			array( 'db' => 'subject', 'dt' => 'subject', 'myModel' => 'EmailTemplates' ),
			array( 'db' => 'slug', 'dt' => 'slug', 'myModel' => 'EmailTemplates' ),
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
		$templ_count = $this->EmailTemplates->find('all', ['conditions' => ['EmailTemplates.status_id' => 1]])->count();
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
		$EmailTemplates = $this->EmailTemplates->get($id);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->EmailTemplates->patchEntity($EmailTemplates, $this->request->data);
			if ($this->EmailTemplates->save($EmailTemplates)) {
				$this->Flash->success(__('The Email Template has been updated'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The Email Template could not be updated. Please, try again'));
			}
		}
		
		$this->set(compact('EmailTemplates'));
		$this->set('_serialize', ['EmailTemplates']);
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
