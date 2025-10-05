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
 * Enquiries Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class EnquiriesController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->loadComponent('DataTable');
		$this->viewBuilder()->layout('backoffice');
	}

	public function ajaxContacts()
	{
	 	$this->viewBuilder()->layout('ajax');
		$this->autoRender = false;
		$this->loadModel('Enquiries');
		$this->paginate = array(
	 		'order' => ['Enquiries.created' => 'desc'],
			'fields' => array('id', 'name', 'email', 'phone', 'coach', 'comments', 'created'),
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Enquiries' ),
			array( 'db' => 'name', 'dt' => 'name', 'myModel' => 'Enquiries' ),
			array( 'db' => 'email', 'dt' => 'email' , 'myModel' => 'Enquiries'),
			array( 'db' => 'phone', 'dt' => 'phone','myModel' => 'Enquiries'),
			array( 'db' => 'coach', 'dt' => 'coach','myModel' => 'Enquiries'),
			array( 'db' => 'comments', 'dt' => 'comments' , 'myModel' => 'Enquiries'),
			array(
		        'db'        => 'created',
		        'dt'        => 'created',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'Enquiries'
		    ),
		);
		echo json_encode($this->DataTable->getResponse());
	 }

	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index()
	{
		$contact_count = $this->Enquiries->find('all')->count();
		$this->set(compact('contact_count'));
	}

	public function ajaxSubscription()
	{
	 	$this->viewBuilder()->layout('ajax');
		$this->autoRender = false;
		$this->loadModel('Subscribers');
		$this->paginate = array(
	 		'order' => ['created' => 'desc'],
			'fields' => array('id', 'email', 'created'),
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Subscribers' ),
			array( 'db' => 'email', 'dt' => 'email' , 'myModel' => 'Subscribers'),
			array(
		        'db'        => 'created',
		        'dt'        => 'created',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'Subscribers'
		    ),
		);
		echo json_encode($this->DataTable->getResponse('','Subscribers'));
	 }

	/**
	 * Index method
	 *
	 * @return void
	 */
	public function subscriber_index()
	{
		$this->loadModel('Subscribers');
		$sub_count = $this->Subscribers->find('all')->count();
		$this->set(compact('sub_count'));
	}
	
	/**
	 * Delete method
	 *
	 * @param string|null $id Enquiry id.
	 * @return void Redirects to index.
	 * @throws \Cake\Network\Exception\NotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->loadModel('Enquiries');
		$enquiry = $this->Enquiries->get($id);
		if ($this->Enquiries->delete($enquiry)) {
			$this->Flash->success(__('The enquiry has been deleted'));
		} else {
			$this->Flash->error(__('The enquiry could not be deleted. Please, try again'));
		}
		return $this->redirect(['action' => 'index']);
	}


	/**
 * Multiple Delete Enquiries
 *
 * @param string|null $id User id.
 * @return void Redirects to index.
 * @throws \Cake\Network\Exception\NotFoundException When record not found.
 */
public function multi_delete_enquiry()
{
    if (isset($this->request->data['multi_del']) && !empty($this->request->data['multi_del'])) 
    {
        $results = $this->request->data['multi_del'];
        //print_r($results);die;
        $data_id_array = explode(",", $results); 
        //print_r($data_id_array);die;
        foreach ($data_id_array as $enquiry_id) {
		$this->loadModel('Enquiries');
		$query = $this->Enquiries->get($enquiry_id);
		$this->Enquiries->delete($query);
		}
		echo true;die;
	}
}
}
