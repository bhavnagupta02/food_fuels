<?php
namespace App\Controller\Backoffice;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;

/**
 * Recipes Controller
 *
 * @property EmailTemplate $EmailTemplate
 * @property PaginatorComponent $Paginator
 */
class RecipesController extends AppController {

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
			'conditions' 	=> ['Recipes.status_id' => 1],
			'order' 		=> ['Recipes.id' => 'asc'],
			'fields' 		=> array('id', 'title', 'Categories.name', 'Users.first_name', 'Users.last_name'),
			'contain' 		=> ['Users','Categories']
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Recipes' ),
			array( 'db' => 'title', 'dt' => 'title', 'myModel' => 'Recipes' ),
			array( 'db' => 'first_name', 'dt' => 'first_name', 'myModel' => 'Users', 'contain' => 'user'),
			array( 'db' => 'last_name', 'dt' => 'last_name', 'myModel' => 'Users' , 'contain' => 'user'),
			array( 'db' => 'name', 'dt' => 'name', 'myModel' => 'Categories' , 'contain' => 'category'),
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
		$templ_count = $this->Recipes->find('all', ['conditions' => ['Recipes.status_id' => 1]])->count();
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
		$Recipes = $this->Recipes->get($id);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Recipes->patchEntity($Recipes, $this->request->data);
			if ($this->Recipes->save($Recipes)) {
				$this->Flash->success(__('The Recipe has been updated'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('The Recipe could not be updated. Please, try again'));
			}
		}
		
		$this->set(compact('Recipes'));
		$this->set('_serialize', ['Recipes']);
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$recipe = $this->Recipes->get($id);
		if ($this->Recipes->delete($recipe)) {
			$this->Flash->success(__('The recipe has been deleted'));
		} else {
			$this->Flash->error(__('The recipe could not be deleted. Please, try again'));
		}

		return $this->redirect(array('action' => 'index'));
	}
}