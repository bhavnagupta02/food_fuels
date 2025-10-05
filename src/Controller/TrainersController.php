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

/**
 * Static content controller
 *
 * This controller will render views from Template/Trainers/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class TrainersController extends AppController
{

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public $helpers  = ['Custom'];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function index()
    {
        # code...
    }

    public function home() {
        $this->set('title', 'Dashboard');

        if($this->Auth->user('group_id') == CLIENTGROUPID) {
            return $this->redirect($this->redirect(['controller' => 'users', 'action' => 'home']));
        } 

        $this->loadModel('Users');
        $userDetails    =   $this->Users->get($this->Auth->user('id'),['contain' => ['Clients' => ['UserSubscriptions' => function($q) { return $q->select(['id','user_id','start_date','end_date']); }, 'UserWeights' => function ($q) {
                                                                                                                                        return $q
                                                                                                                                            ->select(['weight_date','weight','user_id'])
                                                                                                                                            ->where(['Date(UserWeights.weight_date) >=' => Date('Y-m-d',strtotime('-7 days'))]);
                                                                                                                                    } ,'fields' => ['id','first_name','email','last_name','image','goal_weight','trainer_id','created','total_weight_loss','month_weight_loss','week_weight_loss']], 'UploadImages' => ['conditions' => [ 'OR' => ['UploadImages.type = "pics" OR UploadImages.type = "videos"'] ]] ]]);
		 // remove expired client from list
		foreach($userDetails->clients as $key=>$userRow) {
			if(!empty($userRow->user_subscriptions)) {
				$lastKey = key( array_slice( $userRow->user_subscriptions, -1, 1, TRUE ) );
				if(isset($userRow->user_subscriptions[$lastKey]) && !empty($userRow->user_subscriptions[$lastKey]->end_date)) {
					if(strtotime($userRow->user_subscriptions[$lastKey]->end_date) < strtotime(Date('Y-m-d'))) {
						unset($userDetails->clients[$key]);
					}	
				}
			}  else {
				unset($userDetails->clients[$key]);
			}		
		}
		
        $this->loadModel('ShoppingLists');
        $shoppingListData = $this->ShoppingLists->getCurrentShoppingList($this->Auth->user('created'));
        
        $this->loadModel('ActivityLogs');
        $notiCount = $this->ActivityLogs->find()->where(['ActivityLogs.user_id' => $this->Auth->user('id'), 'ActivityLogs.seen' => 0])->count();
        
        $this->loadModel('Feeds');
        $feedList = $this->Feeds->getMyFeed('','',5);

        $this->loadModel('ConversationReplies');
        $msgCount =     $this->ConversationReplies
                                ->find()
                                ->where(['ConversationReplies.seen' => 0, 'ConversationReplies.user_id !=' => $this->Auth->user('id')])
                                ->innerJoinWith(
                                    'Conversations', function ($q) {
                                        return $q->where(['OR' => [ ['Conversations.sender_id' => $this->Auth->user('id')],['Conversations.receiver_id' => $this->Auth->user('id')] ] ]);
                                    })
                                ->count();
        
        $leaderBoardData = $this->Users->getLeaderBoardData(0);
        
        if($this->request->is('post')){
            $this->loadModel('UploadImages');
            
            if(isset($this->request->data['UploadImage']['name']) && !empty($this->request->data['UploadImage']['name']))
            {
                foreach ($this->request->data['UploadImage']['name'] as $key => $value)
                {
                    $ext = substr(strtolower(strrchr($value['name'], '.')), 1);
                    $FileName = mt_rand().'-'.time().'.'.$ext;
                    
                    move_uploaded_file($value['tmp_name'],MYPIC_IMAGE_PATH.$FileName);
                    
                    // store the filename in the array to be saved to the db
                    $uploadData['name'] = $FileName;
                    $uploadData['type'] = 'pics';
                    $uploadData['user_id'] = $this->Auth->user('id');
                    $uploadEntity = $this->UploadImages->newEntity($uploadData);
                    $this->UploadImages->save($uploadEntity);
                }
            }

            if(isset($this->request->data['UploadVideo']['name']) && !empty($this->request->data['UploadVideo']['name']))
            {
                $ext = substr(strtolower(strrchr($value['name'], '.')), 1);
                $FileName = mt_rand().'-'.time().'.'.$ext;
                
                move_uploaded_file($value['tmp_name'],MYPIC_IMAGE_PATH.$FileName);
                
                // store the filename in the array to be saved to the db
                $uploadData['name'] = $FileName;
                $uploadData['type'] = 'pics';
                $uploadData['user_id'] = $this->Auth->user('id');
                $uploadEntity = $this->UploadImages->newEntity($uploadData);
                $this->UploadImages->save($uploadEntity);
            }
        }

        $this->loadModel('UserSubscriptions');
        $subscriptionData = $this->UserSubscriptions->find()->where(['user_id' => $this->Auth->user('id')])->first();
        
        $mealPlans = [];    
        
        $date1 = new \DateTime($this->Auth->user('created'));
        $date2 = new \DateTime();
        $interval = $date1->diff($date2);
        $startingDays    = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
        $currentWeekTemp = $startingDays/7;
		$currentWeek = (is_numeric($currentWeekTemp) && floor($currentWeekTemp) != $currentWeekTemp) ? floor($currentWeekTemp)+1 : $currentWeekTemp;
		/* If starting week is going to exceed from 12 then start it from initial */
		$currentWeek 	= ($currentWeek > 0 && $currentWeek != 12) ? $currentWeek%12 : $currentWeek;
        $weekDay = $startingDays%7;
        
		$this->loadModel('DailyMealPlans');
        $startingWeek = ceil($startingDays/7);
		$startingWeek = ($startingWeek > 0 && $startingWeek != 12) ? $startingWeek%12 : $startingWeek;
		
        $mealPlans = $this->DailyMealPlans->find()
                ->select(['DailyMealPlans.week_no','DailyMealPlans.meal_date','DailyMealPlans.week_day','DailyMealPlans.id','DailyMealPlans.text_highlight', 'weak_number' => 'WEEK(meal_date,1)'])
                ->where(['week_no' => $startingWeek, 'week_day' => $weekDay+1])
                ->order(['DailyMealPlans.week_no' => 'ASC','DailyMealPlans.week_day' => 'ASC'])
                ->contain('Meals')
                ->first();
        $this->set(compact('userDetails','shoppingListData','mealPlans','leaderBoardData','notiCount','msgCount','feedList'));
    }

    public function edit_profile() {
        $this->set('title', 'Edit Profile');
        $this->loadModel('Users');
        if($this->request->is(array('post', 'put'))) {
            $user = $this->Users->get($this->Auth->user('id'), [
                'contain' => ['UploadImages']
            ]);
            $userDatum = $user->toArray();
            $this->request->data['profile_status'] = 1;
            $errorCount = 0; 
            if(isset($this->request->data['email']) && ($userDatum['email'] != $this->request->data['email']))
            {
                $existingProfile = $this->Users->find()->where(['Users.email' => $this->request->data['email']])->first();
                if(!empty($existingProfile)){
                    $this->Flash->error(__('User already registered with this email id.'), 'error');
                    $errorCount = 1;
                }   
            }

            if(isset($this->request->data['username']) && ($userDatum['username'] != $this->request->data['username']))
            {
                $existingProfile = $this->Users->find()->where(['Users.username' => $this->request->data['username']])->first();
                if(!empty($existingProfile)){
                    $this->Flash->error(__('User already registered with this username.'), 'error');
                    $errorCount = 1;
                }   
            }

            if($errorCount==0){
                //save new user
                //get fb image and save in system
                
                if(isset($this->request->data['image']) && !empty($this->request->data['image'])){
                    $tempFile           = $this->request->data['image']['tmp_name'];
                    $targetPath         = USER_IMAGE_PATH;
                    $name               = time() . $this->request->data['image']['name'];
                    $targetFile         = rtrim($targetPath,'/') . DS . $name;

                    // Validate the file type
                    $fileTypes          = array('jpg','jpeg','png'); // File extensions
                    $fileParts          = pathinfo($name);

                    if(isset($fileParts['extension']) && in_array(strtolower($fileParts['extension']), $fileTypes)) {
                        move_uploaded_file($tempFile, $targetFile);
                        $saveUser['image'] = $name;
                    } else {
                        $this->Flash->error(__('File type not supported'), 'error');
                    }
                }
                else{
                    unset($this->request->data['image']);
                }
                
                if(isset($saveUser) && !empty($saveUser)){
                    $saveData = $this->Users->patchEntity($user, $saveUser, array('associated' => 'UploadImages'));
                }
                else {
                    $saveData = $this->Users->patchEntity($user, $this->request->data);
                }
                
                if($user = $this->Users->save($saveData)) {
                    //UPDATE SESSION DATA, DO NOT FORGET THIS ASSHOLE!!
                    $this->Auth->setUser($user->toArray());
                    if(isset($saveUser['image']))
                        $this->request->session()->write('Auth.User.image', $saveUser['image']);
                        //$this->Flash->success(__('Your profile has been updated'), 'success');
                        $redirect = $this->selectTarget();
                        $this->redirect($redirect);
                } else {
                    $this->Flash->error(__('Your profile could not be updated, please try again'), 'error');
                }
            }
            
        } else {
            $this->request->data = $this->Users->find('all', array(
                'conditions' => array('Users.id' => $this->Auth->user('id'))
            ))->first()->toArray();
        }
    }

    public function my_profile() {
        $this->set('title', 'My Profile');
        $this->loadModel('Users');
        if($this->request->is(array('post', 'put'))) {
            $user = $this->Users->get($this->Auth->user('id'));
            $userDatum = $user->toArray();
            $errorCount = 0; 
            if(isset($this->request->data['email']) && ($userDatum['email'] != $this->request->data['email']))
            {
                $existingProfile = $this->Users->find()->where(['Users.email' => $this->request->data['email']])->first();
                if(!empty($existingProfile)){
                    $this->Flash->error(__('User already registered with this email id.'), 'error');
                    $errorCount = 1;
                }   
            }

            if(isset($this->request->data['username']) && ($userDatum['username'] != $this->request->data['username']))
            {
                $existingProfile = $this->Users->find()->where(['Users.username' => $this->request->data['username']])->first();
                if(!empty($existingProfile)){
                    $this->Flash->error(__('User already registered with this username.'), 'error');
                    $errorCount = 1;
                }   
            }

            if($errorCount==0){
                //save new user
                //get fb image and save in system
               
                if(isset($saveUser) && !empty($saveUser)){
                    $saveData = $this->Users->patchEntity($user, $saveUser);
                }
                else {
                    $saveData = $this->Users->patchEntity($user, $this->request->data);
                }
                
                if($user = $this->Users->save($saveData)) {
                    //UPDATE SESSION DATA, DO NOT FORGET THIS ASSHOLE!!
                    $this->Auth->setUser($user->toArray());
                    if(isset($saveUser['image']))
                        $this->request->session()->write('Auth.User.image', $saveUser['image']);
                        //$this->Flash->success(__('Your profile has been updated'), 'success');
                    $this->request->data = $this->Users->find('all', array(
                        'conditions' => array('Users.id' => $this->Auth->user('id')),
                        'contain' => 'UploadImages'
                    ))->first()->toArray();
                } else {
                    $this->Flash->error(__('Your profile could not be updated, please try again'), 'error');
                }
            }
            
        } else {
            $this->request->data = $this->Users->find('all', array(
                'conditions' => array('Users.id' => $this->Auth->user('id'))
            ))->first()->toArray();
        }
    }
}