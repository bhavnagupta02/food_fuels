<?php
namespace App\Controller\Backoffice;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class CmspagesController extends AppController {

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
			'conditions' => ['Cmspages.status_id' => 1],
			'order' => ['Cmspages.id' => 'asc'],
			'fields' => array('id', 'title', 'sub_title', 'slug'),
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Cmspages' ),
			array( 'db' => 'title', 'dt' => 'title', 'myModel' => 'Cmspages' ),
			array( 'db' => 'sub_title', 'dt' => 'sub_title', 'myModel' => 'Cmspages' ),
			array( 'db' => 'slug', 'dt' => 'slug', 'myModel' => 'Cmspages' ),
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
		$cms_pages = $this->Cmspages->find('all', ['conditions' => ['Cmspages.status_id' => 1]])->count();
		$this->set(compact('cms_pages'));
	}

	/**
     * Edit method
     *
     * @param string|null $id Deal id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
       $cmspage = $this->Cmspages->get($id);
    
        if ($this->request->is(['patch', 'post', 'put'])) {
            $cmspageData = $this->request->data;
            
            $Cmspage = $this->Cmspages->patchEntity($cmspage, $this->request->data);
            if ($result = $this->Cmspages->save($Cmspage)) {
                $this->Flash->success('The cmspage has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The cmspage could not be saved. Please, try again.');
            }
        }
        $this->set('cmspage',$cmspage);
        $this->set('_serialize', ['cmspage']);
    }
	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
		$this->Cmspage->id = $id;
		if (!$this->Cmspage->exists()) {
			throw new NotFoundException(__('Invalid Cms page'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Cmspage->delete()) {
			$this->Session->setFlash(__('The Cms page has been deleted.'));
		} else {
			$this->Session->setFlash(__('The Cms page could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function ajaxShoppinglists()
	{
		$this->loadModel('ShoppingLists');
		$this->viewBuilder()->layout('ajax');	
		$this->autoRender = false;
		$this->paginate = array(
			'order' => ['ShoppingLists.id' => 'asc'],
			'fields' => ['id', 'document_name', 'status_id', 'week_no', 'created', 'Statuses.name'],
			'contain' => ['Statuses']
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'ShoppingLists' ),
			array( 'db' => 'document_name', 'dt' => 'document_name', 'myModel' => 'ShoppingLists' ),
			array(  'db' => 'week_no', 'dt' => 'week_no', 'myModel' => 'ShoppingLists'),
			array(
		        'db'        => 'created',
		        'dt'        => 'created',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'Users'
		    ),
		);
		echo json_encode($this->DataTable->getResponse('','ShoppingLists'));
	}
	/**
	* index method
	*
	* @return void
	*/
	public function shopping_list()
	{
		$this->loadModel('ShoppingLists');
		$shopping_lists = $this->ShoppingLists->find()->count();
		$this->set(compact('shopping_lists'));
	}

	/**
	 * Add Shopping method
	 *
	 * @return void Redirects on successful add, renders view otherwise.
	 */
	public function add_shopping()
	{
		$this->loadModel('ShoppingLists');
		$shoppinglist = $this->ShoppingLists->newEntity();
		if ($this->request->is('post'))
		{
			$this->request->data['last_login'] = date('Y-m-d H:i:s');
			// In a controller action
			$prevShoppingList = $this->ShoppingLists->find()->where(['week_no' => $this->request->data['week_no']])->first();

			if(isset($this->request->data['document_name']['name']) && !empty($this->request->data['document_name']['name']))
			{
					$ext 		= substr(strtolower(strrchr($this->request->data['document_name']['name'], '.')), 1);
					$FileName 	= str_replace(' ', '-',$this->request->data['document_name']['name']);
					
					move_uploaded_file($this->request->data['document_name']['tmp_name'],LIST_IMAGE_PATH.$FileName);
					
					// store the filename in the array to be saved to the db
					$this->request->data['document_name'] = $FileName;
			}
			//unset($this->request->data['document_name']);
			
			if(!empty($prevShoppingList)){
				//$shoppinglist = $this->ShoppingLists->get($prevShoppingList->id);
				$shoppinglist = $prevShoppingList;
			}
			$shopinglist = $this->ShoppingLists->patchEntity($shoppinglist, $this->request->data);
			if ($shopping = $this->ShoppingLists->save($shoppinglist))
			{	
				if($shopping->status_id == ACTIVE_STATUS){
					$this->ShoppingLists->updateAll(['status_id' => IN_ACTIVE_STATUS],['id !=' => $shopping->id]);
				}
				
				$this->Flash->success(__('Shopping list has been added.'),'success');
				return $this->redirect(['action' => 'shopping_list']);
			} else {
				$this->Flash->success(__('Shopping list can not be added. Please, try again'),'error');
			}
		}
		$this->set(compact('shoppinglist'));
		$this->set('_serialize', ['shoppinglist']);
	}	

	/**
	 * Edit method
	 *
	 * @param string|null $id User id.
	 * @return void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function edit_shopping($id = null)
	{
		$user = $this->Users->get($id, [
			'contain' => ['Statuses','UploadImages','Countries']
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
				if($user['User']['group_id'] == CLIENTGROUPID)
					return $this->redirect(['action' => 'clients']);
				else
					return $this->redirect(['action' => 'trainers']);
			} else {
				$this->Flash->error(__('The user could not be updated. Please, try again'));
			}
		}
		
		$statuses = $this->Users->Statuses->find('list')->toArray();
		$countries = $this->Users->Countries->find('list')->toArray();
		$trainers = $this->Users->find('list',['keyField' => 'id', 'valueField' => array('first_name', 'last_name')])->where(['Users.status_id' => 1, 'Users.group_id' => USERGROUPID])->toArray();
		
		$this->set(compact('user', 'statuses', 'countries', 'trainers'));
		$this->set('_serialize', ['user']);
	}

	public function ajaxPromo()
	{
		$this->loadModel('PromotionCodes');
		$this->viewBuilder()->layout('ajax');	
		$this->autoRender = false;
		$this->paginate = array(
			'conditions' => ['PromotionCodes.status_id' => 1],
			'order' => ['PromotionCodes.id' => 'asc'],
			'fields' => array('id', 'title', 'description', 'amount', 'discount_type', 'valid_from', 'valid_till' ),
		);

		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'PromotionCodes' ),
			array( 'db' => 'title', 'dt' => 'title', 'myModel' => 'PromotionCodes' ),
			array( 'db' => 'description', 'dt' => 'sub_title', 'myModel' => 'PromotionCodes' ),
			array( 'db' => 'amount', 'dt' => 'amount', 'myModel' => 'PromotionCodes' ),
			array( 'db' => 'discount_type', 'dt' => 'discount_type', 'myModel' => 'PromotionCodes' ),
			array(
		        'db'        => 'valid_from',
		        'dt'        => 'valid_from',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'Users'
		    ),
		    array(
		        'db'        => 'valid_till',
		        'dt'        => 'valid_till',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'Users'
		    )
		);

		echo json_encode($this->DataTable->getResponse('','PromotionCodes'));
	}
	/**
	* Promo index method
	*
	* @return void
	*/
	public function promo_index()
	{
		$this->loadModel('PromotionCodes');
		$promotion_codes = $this->PromotionCodes->find('all', ['conditions' => ['PromotionCodes.status_id' => 1]])->count();
		$this->set(compact('promotion_codes'));
	}

	/**
	 * Promo add method
	 *
	 * @return void
	 */
	public function add_promo() {

		$this->loadModel('PromotionCodes');
		$promotion_codes = $this->PromotionCodes->newEntity();
    if ($this->request->is('post')) {
			$PromotionCodes = $this->PromotionCodes->patchEntity($promotion_codes, $this->request->data);
      
      if ($result = $this->PromotionCodes->save($PromotionCodes)) {
          $this->Flash->success('The Promotion codes has been saved.');
          return $this->redirect(['action' => 'promo_index']);
      } else {
          $this->Flash->error('The Promotion codes could not be saved. Please, try again.');
      }
		}
		
		$this->set(compact('promotion_codes'));
	  $this->set('_serialize', ['promotion_codes']);
	}

	/**
   * Promo Edit method
   *
   * @param string|null $id promotion code id.
   * @return void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Network\Exception\NotFoundException When record not found.
   */
    public function edit_promo($id = null)
    {
    	$this->loadModel('PromotionCodes');
			$promotion_codes = $this->PromotionCodes->get($id);
	    if ($this->request->is(['patch', 'post', 'put'])) {
				$PromotionCodes = $this->PromotionCodes->patchEntity($promotion_codes, $this->request->data);
	      
	      if ($result = $this->PromotionCodes->save($PromotionCodes)) {
	          $this->Flash->success('The Promotion codes has been saved.');
	          return $this->redirect(['action' => 'promo_index']);
	      } else {
	          $this->Flash->error('The Promotion codes could not be saved. Please, try again.');
	      }
			}
			$this->set(compact('promotion_codes'));
	    $this->set('_serialize', ['promotion_codes']);
	    $this->render('add_promo');
    }

}
