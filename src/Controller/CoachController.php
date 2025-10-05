<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use \DrewM\MailChimp\MailChimp;
use Cake\Network\Email\Email;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */

class CoachController extends AppController
{

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['all', 'view','review_rating']);/*'forgot_password'*/
    }
    public function all(){
		
        $this->set('title', 'All Coaches');
        $connection = ConnectionManager::get('default');
        $this->loadModel('Users');
        $this->loadModel('CoachReviews');
        $featuredTrainers = $this->Users->find()->where(['is_featured' => 1,'group_id' => USERGROUPID])->select(['id','image','first_name','last_name','short_description','achievements'])->toArray();
       
        foreach($featuredTrainers as $key=>$trainer){
            $coach_reviews = $this->CoachReviews->find()->where(['coach_id'=>$trainer['id'],'status'=>1])->select(['id','user_id','rating','reviews'])->toArray();
            $total = 0;
            $totalRatings = count($coach_reviews);
            foreach($coach_reviews as $ikey => $record){
                $total = $total + $record['rating'];
            }
            $avg = $total/$totalRatings;
            $featuredTrainers[$key]['rating'] = $avg;
            //print_r($avg. "<br />");
        }
        //print_r($featuredTrainers);die;
        $this->set(compact('featuredTrainers'));

        //$this->set('featuredTrainers',$featuredTrainers);   
    }

    public function view($id){
		$this->set('title', 'Coach');
        $connection = ConnectionManager::get('default');
        $this->loadModel('Users');
        $this->loadModel('CoachReviews');
        $details = $this->Users->find()->where(['is_featured' => 1,'group_id' => USERGROUPID,'id'=>$id])->select(['id','image','first_name','last_name','short_description','achievements']);
        /*print_r($details);die;*/
        $coach_reviews = $this->CoachReviews->find()->where(['coach_id'=>$id,'status'=>1])->select(['id','user_id','rating','reviews'])->toArray();
        $total = 0;
        $totalRatings = count($coach_reviews);
        foreach($coach_reviews as $key => $record){
			$total = $total + $record['rating'];
			$user_details = $this->Users->find()->where(['id'=>$record['user_id']])->select(['image','first_name','last_name'])->toArray();
			$coach_reviews[$key]['user_details'] = $user_details;
			//print_r($user_details);die;
		}
		$avg = $total/$totalRatings;
        $this->set(compact('details','coach_reviews','avg','totalRatings'));
	}
	
    public function review_rating(){
		
        $this->set('title', 'Coach Reviews');
        if($this->request->is(array('post','put'))){ 
        $user_id = $this->Auth->user('id');
        $user_fname = $this->Auth->user('first_name');
        $user_lname = $this->Auth->user('last_name');
        $user_email = $this->Auth->user('email');
        $user_name =  $user_fname." ".$user_lname;
		
        $coach_id = $this->request->data['coach_id'];
        $coach_email = $this->request->data['coach_email'];
        //print_r($coach_email);die;
		$rating = $this->request->data['rating'];        
		$reviews = $this->request->data['review'];

        $this->loadModel('CoachReviews');
        $this->loadModel('Users');
        $this->loadComponent('Common');

       $connection = ConnectionManager::get('default');
       $results = $connection->execute("Insert Into coach_reviews(user_id, coach_id,rating,reviews,status)Values(".$user_id.", ".$coach_id.",'".$rating."','".$reviews."',0)");
        //print_r($results);die;

		$to      = 'sean@foodfuelsweightloss.com,'.$coach_email;
		$subject = 'Admin notification for coach ratings by'." ".$user_name;

		$headers = "From: " . $user_email . "\r\n";
		$headers .= "Reply-To: ". $user_email . "\r\n";
		//$headers .= "CC: susan@example.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$message = '<html><body>';

        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
        $message .= "<tr style='background: #eee;'><td><strong>User Name:</strong> </td><td>" . $user_name . "</td></tr>";
        $message .= "<tr><td><strong>User Email</strong> </td><td>" . $user_email . "</td></tr>";
        $message .= "<tr><td><strong>Coach Rating:</strong> </td><td>" . $rating . "</td></tr>";

        $message .= "<tr><td><strong>Coach Review:</strong> </td><td>" . $reviews . "</td></tr>";
        $message .= "</table>";
        $message .= "</body></html>";

		mail($to, $subject, $message, $headers);
		}
		return $this->redirect(['controller' => 'users', 'action' => 'home']);
    }

    /*public function coach_private()
    {
        if($this->Auth->user('group_id') == USERGROUPID) {
         //print_r($this->Auth->user('id'));die;
        $this->checkMembershipExpired(); //check user's membership stauts
        //print_r($this->checkMembershipExpired());die;
    }
        $this->loadModel('Feeds');
        //$this->loadModel('Users');
        $this->set('title', 'CoachCommunity');
        $feedList = $this->Feeds->getMyFeed('','',10,0);
        if ($this->request->is('post')) {
            $feedData = $this->request->data;
            $feeds = $this->Feeds->newEntity();
            $this->request->data['user_id']     = $this->Auth->user('id');
            //print_r($this->Auth->user('id'));die;
            $this->request->data['activity_id'] = 5;
            $this->request->data['timestamp']   = time();
            $feeds = $this->Feeds->patchEntity($feeds, $this->request->data);
            if ($feeds = $this->Feeds->save($feeds)) {
                $this->redirect(['action' => 'coach_private']);
            } else {
                $this->Flash->error("Your post can not be shared.", 'error');
            }
        }

        $this->set('feedList', $feedList);
    }*/
    
}
