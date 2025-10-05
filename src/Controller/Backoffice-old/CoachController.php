<?php
namespace App\Controller\Backoffice;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

/**
 * DailyMealPlans Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class CoachController extends AppController
{

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		$this->loadComponent('DataTable');
		$this->viewBuilder()->layout('backoffice');
		$this->Auth->allow(['allratings','ajaxGetRatings']);/*'forgot_password'*/
	}
	public function allratings(){
		if($_GET['qt']!==''&&isset($_GET['qt'])){
			if($_GET['qt']=='del'){
				$connection = ConnectionManager::get('default');
				$results = $connection->execute("Delete from coach_reviews Where id = ".$_GET['id']."");
			} else {
				$connection = ConnectionManager::get('default');
				$results = $connection->execute("Update coach_reviews Set status = ".$_GET['qt']." Where id = ".$_GET['id']."");
			}
			
		}
		
		$this->loadModel('CoachReviews');
		$this->loadModel('Users');
		$coach_reviews = $this->CoachReviews->find()->toArray();
		foreach($coach_reviews as $key => $record){
			$total = $total + $record['rating'];
			$user_details = $this->Users->find()->where(['id'=>$record['user_id']])->select(['image','first_name','last_name'])->toArray();
			$coach_details = $this->Users->find()->where(['id'=>$record['coach_id']])->select(['image','first_name','last_name'])->toArray();
			$coach_reviews[$key]['coach_details'] = $coach_details;
			$coach_reviews[$key]['user_details'] = $user_details;
		}
		$this->set('reviews',$coach_reviews);
	}
	public function ajaxGetRatings(){
		$this->viewBuilder()->layout('ajax');
	 	$this->autoRender = false;
		
		
	 	$this->paginate = array(
	 		'conditions' => $conditions,
	 		'contain' => array(
	 			'Statuses', 'Countries'
			),
			'order' => ['Users.id' => 'asc'],
			'fields' => array('Users.id', 'Users.first_name', 'Users.last_name', 'Users.dob', 'Statuses.name', 'Users.email', 'Users.is_paid', 'Users.mobile', 'Users.address', 'Users.city', 'Users.zipcode', 'Users.created', 'Countries.country'),
		);
		$this->DataTable->fields = array(
			array( 'db' => 'id', 'dt' => 'id', 'myModel' => 'Users' ),
			array( 'db' => 'first_name', 'dt' => 'first_name', 'myModel' => 'Users' ),
			array( 'db' => 'last_name', 'dt' => 'last_name', 'myModel' => 'Users' ),
			array( 'db' => 'email', 'dt' => 'email' , 'myModel' => 'Users'),
			array(
		        'db'        => 'created',
		        'dt'        => 'created',
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }, 'myModel' => 'Users'
		    ),
		);
		echo json_encode($this->DataTable->getResponse());
	}

}
