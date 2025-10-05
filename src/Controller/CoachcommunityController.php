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


/**
 * Static content controller
 *
 * This controller will render views from Template/Coachcommunity/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CoachcommunityController extends AppController
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
    }

    public function index()
    {
        if($this->Auth->user('group_id') == USERGROUPID) {
		//$this->checkMembershipExpired(); //check user's membership stauts
	}
        $this->loadModel('Feeds');
        $this->loadModel('Users');
        $this->set('title', 'CoachCommunity');
        $feedList = $this->Feeds->getMyFeed('','',10,0);
        //print_r($feedList);die;

        $userid = $this->request->session()->read('Auth.User.id');
        
        $feed_type = 'coach_community';
        $feeds_data = ConnectionManager::get('default');

		if($this->Auth->user('group_id') == USERGROUPID) {
			$coach_users = $feeds_data->execute("SELECT id FROM users where trainer_id ='".$this->Auth->user('id')."'")->fetchAll('assoc');
			$coach_mem = array();
			foreach ($coach_users as $key => $value) {
			$coach_mem[] = $value['id'];
			}
			$all_data = implode(",", $coach_mem);
			$all_ids = $this->Auth->user('id').",".$all_data;
			//print_r($all_ids."<br/>");

			$feedList1 = $feeds_data->execute("SELECT * FROM feeds a, feed_type b where a.user_id IN (".$all_ids.") AND a.id=b.feed_id AND b.feed_type = '".$feed_type."' ")->fetchAll('assoc');
			//print_r($feedList1);die;
		}

		else{
			$user_coach = $feeds_data->execute("SELECT * FROM users where id ='".$this->Auth->user('id')."'")->fetchAll('assoc');
			//print_r($user_coach);die;
			$user_mem = array();
			foreach ($user_coach as $key => $value) {
			$user_mem[] = $value['id'];
			}
			//print_r($value['trainer_id']);die;
			$assign_coach = $value['trainer_id'];
			$all_data = $this->Auth->user('id').",".$assign_coach;
			//print_r($all_data."<br/>");

			$feedList1 = $feeds_data->execute("SELECT * FROM feeds a, feed_type b where a.user_id IN (".$all_data.") AND a.id=b.feed_id AND b.feed_type = '".$feed_type."' ")->fetchAll('assoc');
			//print_r($feedList1);die;
		}

        if ($this->request->is('post')) {
            $feedData = $this->request->data;
            //print_r($feedData);die;
            $feeds = $this->Feeds->newEntity();
            $this->request->data['user_id']     = $this->Auth->user('id');
            //print_r($this->Auth->user('id'));die;
            $this->request->data['activity_id'] = 5;
            $this->request->data['timestamp']   = time();
            $feeds = $this->Feeds->patchEntity($feeds, $this->request->data);
            //if ($feeds = $this->Feeds->save($feeds) && $this->request->data['feed_type'] = COACHCOMMUNITY;) {}
            if ($feeds = $this->Feeds->save($feeds)) {
                $feed_details = ConnectionManager::get('default');
                $results = $feed_details->execute("Insert Into feed_type (feed_id,feed_type)Values('".$feeds['id']."', 'coach_community')");
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error("Your post can not be shared.", 'error');
            }
        }

        $coach_ass = ConnectionManager::get('default');
        $ccoach_users = $coach_ass->execute("SELECT * FROM users where trainer_id ='".$this->Auth->user('id')."'")->fetchAll('assoc');
        //print_r($ccoach_users);die;

         $cuserDetails = $this->Users->get($this->Auth->user('id'),['contain' => ['Trainers' => ['fields' => ['Trainers.id','Trainers.first_name','Trainers.last_name','Trainers.email','Trainers.image','Trainers.short_description']]]]);
       //print_r($cuserDetails);die;

        //$this->set('feedList', $feedList);
        $this->set(compact('feedList', 'feedList1', 'ccoach_users', 'cuserDetails'));
    }

//die;
    
    public function my_photos()
    {
        # code...
        $this->set('title', 'My Photos');
        $feeds = $this->Feeds->newEntity();
        if ($this->request->is('post')) {
            
            $feedData = $this->request->data;
            
            $this->request->data['user_id']     = $this->Auth->user('id');
            $this->request->data['activity_id'] = 1;
            $this->request->data['timestamp']   = time();

            $feeds = $this->Feeds->patchEntity($feeds, $this->request->data);
            if ($feeds = $this->Feeds->save($feeds)) {
                /*
                Upload Dish photos and save this to Upload Image database.
                */
                $uploadData = array();
                $this->loadModel('UploadImages');
                        
                if(isset($this->request->data['UploadImage']['name']) && !empty($this->request->data['UploadImage']['name']))
                {
                    $ext = substr(strtolower(strrchr($this->request->data['UploadImage']['name'], '.')), 1);
                    $FileName = mt_rand().'-'.time().'.'.$ext;
                    
                    move_uploaded_file($this->request->data['UploadImage']['tmp_name'],MYPIC_IMAGE_PATH.$FileName);
                    
                    // store the filename in the array to be saved to the db
                    $uploadData['name'] = $FileName;
                    $uploadData['type'] = 'pics';
                    $uploadData['feed_id'] = $feeds->id;
                    $uploadData['user_id'] = $this->Auth->user('id');
                    $uploadImage = $this->UploadImages->newEntity($uploadData);
                    $this->UploadImages->save($uploadImage);
                }
                //$this->Flash->success(__('This dish has been successfully added.'), 'success');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error("Your post can not be shared.", 'error');
            }
        }

        $feedList = $this->Feeds->getMyFeed(1,$this->Auth->user('id'));
        
        $this->set('feedList', $feedList);
    }       

    public function my_videos()
    {
        # code...
        $this->set('title', 'My Videos');
        $feeds = $this->Feeds->newEntity();
        if ($this->request->is('post')) {
            
            $feedData = $this->request->data;
            
            $this->request->data['user_id']     = $this->Auth->user('id');
            $this->request->data['activity_id'] = 2;
            $this->request->data['timestamp']   = time();
           
            $feeds = $this->Feeds->patchEntity($feeds, $this->request->data);
            if ($feeds = $this->Feeds->save($feeds)) {
                /*
                Upload Dish photos and save this to Upload Image database.
                */
                $uploadData = array();
                $this->loadModel('UploadImages');
                        
                if(isset($this->request->data['UploadImage']['name']) && !empty($this->request->data['UploadImage']['name']))
                {
                    $ext = substr(strtolower(strrchr($this->request->data['UploadImage']['name'], '.')), 1);
                    $FileName = mt_rand().'-'.time().'.'.$ext;
                    
                    move_uploaded_file($this->request->data['UploadImage']['tmp_name'],MYVIDOES_PATH.$FileName);
                    
                    // store the filename in the array to be saved to the db
                    $uploadData['name'] = $FileName;
                    $uploadData['type'] = 'videos';
                    $uploadData['feed_id'] = $feeds->id;
                    $uploadData['user_id'] = $this->Auth->user('id');
                    $uploadImage = $this->UploadImages->newEntity($uploadData);
                    $this->UploadImages->save($uploadImage);
                }
                //$this->Flash->success(__('This dish has been successfully added.'), 'success');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error("Your post can not be shared.", 'error');
            }
        }

        $feedList = $this->Feeds->getMyFeed(2,$this->Auth->user('id'));
        
        $this->set('feedList', $feedList);
    }

    public function shareme()
    {
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('Shares');
        if ($this->request->is('post')) {
            $type   = $this->request->data['type'];
            $userId = $this->Auth->user('id');
            
            $likeData = [];
            $conditionsData          =   ['user_id' => $userId];
            $shareData['timestamp']  =   time();
            $shareData['user_id']    =   $userId;

            if($type == 1 && isset($this->request->data['recipe_id']) && !empty($this->request->data['recipe_id'])){
                $shareData['recipe_id'] = $this->request->data['recipe_id'];
                $conditionsData[]       = ['recipe_id' => $this->request->data['recipe_id']];
            }
            elseif($type == 2 && isset($this->request->data['feed_id']) && !empty($this->request->data['feed_id'])){
                $shareData['feed_id']   = $this->request->data['feed_id'];
                $conditionsData[]       = ['feed_id' => $this->request->data['feed_id']];
            } 

            $alreadycheck = $this->Shares->find()->where($conditionsData)->first();

            if(empty($alreadycheck)){
                $shareData['status_id'] = 1;
                $updateArray = ['share_count = share_count + 1'];
                $enq = $this->Shares->newEntity($shareData);
                if ($rep = $this->Shares->save($enq)) {
                    $this->loadModel('ActivityLogs');
             
                    if($type == 1){
                        $feedData['user_id']        = $this->Auth->user('id');
                        $feedData['recipe_id']      = $shareData['recipe_id'];
                        $feedData['activity_id']    = 3;
                        $feedData['timestamp']      = time();
                        $feeds = $this->Feeds->newEntity($feedData);

                        if ($this->Feeds->save($feeds)) {
                            $this->loadModel('Recipes');
                            $recipes = $this->Recipes->updateAll($updateArray,['id' => $shareData['recipe_id']]);

                            $finalCount = $this->Recipes->get($shareData['recipe_id']);
                            //Update Activity Log For Like Activity
                            $this->ActivityLogs->updateLog($finalCount->user_id,2,$rep->id,time());
                        }
                    }
                    elseif ($type == 2) {
                        $feedData['user_id']        = $this->Auth->user('id');
                        if(isset($shareData['recipe_id']) && !empty($shareData['recipe_id'])){
                            $feedData['recipe_id']        = $shareData['recipe_id'];
                            $feedData['activity_id']    = 3;
                        }
                        else{
                            $feedData['activity_id']    = 4;
                        }
                        $feedData['feed_id']        = $shareData['feed_id'];
                        $feedData['timestamp']      = time();
                        $feeds = $this->Feeds->newEntity($feedData);

                        if ($this->Feeds->save($feeds)) {
                            $this->loadModel('Feeds');
                            $recipes = $this->Feeds->updateAll($updateArray,['id' => $shareData['feed_id']]);

                            $finalCount = $this->Feeds->get($shareData['feed_id']);
                            //Update Activity Log For Like Activity
                            $this->ActivityLogs->updateLog($finalCount->user_id,7,$rep->id,time());
                        }
                    }

                    echo json_encode(array('status' => 1,'shares' => $finalCount->share_count));
                } else {
                    echo json_encode(array('status' => 0));
                }
            }
            else{
                echo json_encode(array('status' => 0));
            }
        }
        
        exit;
    }

    public function likeme()
    {
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('Likes');
        if ($this->request->is('post')) {
            $type   = $this->request->data['type'];
            $userId = $this->Auth->user('id');
            
            $likeData = [];
            $conditionsData         =   ['user_id' => $userId];
            $likeData['timestamp']  =   time();
            $likeData['user_id']    =   $userId;

            if($type == 1 && isset($this->request->data['recipe_id']) && !empty($this->request->data['recipe_id'])){
                $likeData['recipe_id'] = $this->request->data['recipe_id'];
                $conditionsData[] = ['recipe_id' => $this->request->data['recipe_id']];
            }
            elseif($type == 2 && isset($this->request->data['feed_id']) && !empty($this->request->data['feed_id'])){
                $likeData['feed_id'] = $this->request->data['feed_id'];
                $conditionsData[] = ['feed_id' => $this->request->data['feed_id']];
            } 

            $alreadycheck = $this->Likes->find()->where($conditionsData)->first();

            if(empty($alreadycheck)){
                $likeData['status_id'] = 1;
                $updateArray = ['like_count = like_count + 1'];
                $enq = $this->Likes->newEntity($likeData);
            }
            elseif(!empty($alreadycheck) && $alreadycheck->status_id == 1){
                $likeData['status_id'] = 0;
                $updateArray = ['like_count = like_count - 1'];
                $enq = $this->Likes->patchEntity($alreadycheck,$likeData);
            }
            elseif(!empty($alreadycheck) && $alreadycheck->status_id == 0){
                $likeData['status_id'] = 1;
                $updateArray = ['like_count = like_count + 1'];
                $enq = $this->Likes->patchEntity($alreadycheck,$likeData);
            }
            if ($rep = $this->Likes->save($enq)) {
                
                $this->loadModel('ActivityLogs');
                
                if($type == 1){
                    $this->loadModel('Recipes');
                    $recipes = $this->Recipes->updateAll($updateArray,['id' => $likeData['recipe_id']]);
                    $finalCount = $this->Recipes->get($likeData['recipe_id']);
                    //Update Activity Log For Like Activity
                    $this->ActivityLogs->updateLog($finalCount->user_id,3,$rep->id,time());
                }
                elseif ($type == 2) {
                    $this->loadModel('Feeds');
                    $recipes = $this->Feeds->updateAll($updateArray,['id' => $likeData['feed_id']]);
                    $finalCount = $this->Feeds->get($likeData['feed_id']);
                    //Update Activity Log For Like Activity
                    $this->ActivityLogs->updateLog($finalCount->user_id,5,$rep->id,time());
                }

                echo json_encode(array('status' => 1,'likes' => $finalCount->like_count));
            } else {
                echo json_encode(array('status' => 0));
            }
        }
        
        exit;
    }

    public function commentme()
    {
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('Comments');
        if ($this->request->is('post')) {
            $type   = $this->request->data['type'];
            $userId = $this->Auth->user('id');
            
            $commentData = [];
            $conditionsData             =   ['user_id' => $userId];
            $commentData['timestamp']   =   time();
            $commentData['user_id']     =   $userId;
            $commentData['comment']     =   $this->request->data['comment'];

            if($type == 1 && isset($this->request->data['recipe_id']) && !empty($this->request->data['recipe_id'])){
                $commentData['recipe_id'] = $this->request->data['recipe_id'];
                $conditionsData[] = ['recipe_id' => $this->request->data['recipe_id']];
            }
            elseif($type == 2 && isset($this->request->data['feed_id']) && !empty($this->request->data['feed_id'])){
                $commentData['feed_id'] = $this->request->data['feed_id'];
                $conditionsData[] = ['feed_id' => $this->request->data['feed_id']];
            } 

            $commentData['status_id'] = 1;
            $updateArray = ['comment_count = comment_count + 1'];
            $enq = $this->Comments->newEntity($commentData);
            
            if ($rep = $this->Comments->save($enq)) {
                
                $this->loadModel('ActivityLogs');
                
                if($type == 1){
                    $this->loadModel('Recipes');
                    $recipes = $this->Recipes->updateAll($updateArray,['id' => $commentData['recipe_id']]);
                    $finalCount = $this->Recipes->find()->where(['Recipes.id' => $commentData['recipe_id']])->contain(['Comments' => ['Users' => ['fields' => ['Users.id','Users.email','Users.first_name','Users.last_name','Users.image']]]])->first();
                    //Update Activity Log For Like Activity
                    $this->ActivityLogs->updateLog($finalCount->user_id,4,$rep->id,time());
                    $finalCount = $finalCount->toArray();
                }
                elseif ($type == 2) {
                    $this->loadModel('Feeds');
                    $recipes = $this->Feeds->updateAll($updateArray,['id' => $commentData['feed_id']]);
                    $finalCount = $this->Feeds->find()->where(['Feeds.id' => $commentData['feed_id']])->contain(['Comments' => ['Users' => ['fields' => ['Users.id','Users.email','Users.first_name','Users.last_name','Users.image']]]])->first();
                    //Update Activity Log For Like Activity
                    $this->ActivityLogs->updateLog($finalCount->user_id,6,$rep->id,time());
                    $finalCount = $finalCount->toArray();
                }

                $this->set(compact('finalCount'));
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
        $feedData = $this->Feeds->find()->where(['id' => $id, 'user_id' => $this->Auth->user('id')])->first();
        
        if($this->Feeds->delete($feedData)) {
            $this->Flash->success(__('The post has been deleted'));
        } else {
            $this->Flash->error(__('The post could not be deleted. Please, try again'));
        }
        
        if($feedData->activity_id == 2){
            return $this->redirect(['action' => 'my_videos']);
        }
        else{
            return $this->redirect(['action' => 'my_photos']);
        }
        
    }
     public function fetch_feed_pages(){
        header('Content-Type: application/json');
		if(!$this->request->is('POST'))
		{
			$this->response = array(
				'status' => 400,
				'message' => 'Method not allowed',
				'data' => ''
			);
		}
        $this->viewBuilder()->layout('ajax');
        $item_per_page = 10;
         $dishData = $this->request->data;
        $page_number = filter_var($dishData["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
        $position = (($page_number-1) * $item_per_page);
        
        $feedList = array();
        
           
        $feedList = $this->Feeds->getMyFeed('','',$item_per_page, $position);
            
            
        $this->set('feedList', $feedList);
             
            		
    }
}
