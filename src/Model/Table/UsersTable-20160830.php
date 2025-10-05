<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Routing\Router;


/**
 * Users Model
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Trainers', [
            'foreignKey' => 'trainer_id',
            'className' =>  'Users',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('Clients', [
            'foreignKey' => 'trainer_id',
            'className' =>  'Users',
            'joinType' => 'LEFT',
            'sort' => ['first_name' => 'ASC']
        ]);        
        
        $this->belongsTo('Countries');

        $this->hasMany('UploadImages',[
            'dependent' => true
            ]);

        $this->hasMany('UserSubscriptions');

        $this->hasMany('ActivityLogs',[
            'dependent' => true
            ]);
        
        $this->belongsTo('Statuses');
        $this->hasMany('UserWeights',[
            'sort' => ['weight_date' => 'DESC'],
            'dependent' => true
        ]);
        
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->requirePresence('email', 'create', 'Email required.')
            ->requirePresence('password', 'create', 'Password required.')
            ->requirePresence('first_name', 'create', 'First name required.')
            //->requirePresence('last_name', 'create', 'Last name required.')
            ->requirePresence('group_id', 'create', 'Group id required.')
            ->notEmpty('terms', 'Please select terms and conditions.')
            ->add('email', 'validFormat',[
                    'rule' => 'email',
                    'message' => 'E-mail must be valid.'
                ])
            ->add('email', [ 'unique' => ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Email address already existing.'] ])
            ->notEmpty('password','Please enter password.')
            ->notEmpty('first_name','First name should not be empty.')
            //->notEmpty('last_name','Last name should not be empty.')
            ->notEmpty('group_id','Please select your group type.');
            
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email'], __('This email is already in use')));
        $rules->add($rules->existsIn(['group_id'], 'Groups'));
		$rules->add($rules->existsIn(['country_id'], 'Countries'));
        return $rules;
    }

    public function getLeaderBoardData($type=1, $limit=5){
        $orderBy = [];
        $leaderResultData['week']   = $this
                                    ->find()
                                    ->select(['Users.id','Users.username','Users.first_name','Users.last_name','Users.after_image','Users.image','Users.week_weight_loss_percent','Users.month_weight_loss_percent','Users.total_weight_loss_percent','Users.trainer_id','Users.leaderboard_show'])
                                    ->where(['Users.status_id' => ACTIVE_STATUS, 'Users.group_id' => CLIENTGROUPID, 'Users.leaderboard_show' => 1])
                                    ->contain(['Trainers' => ['fields' => ['Trainers.id','Trainers.first_name','Trainers.last_name','Trainers.image']]])
                                    ->order(['Users.week_weight_loss_percent' => 'DESC'])
                                    ->limit($limit)
                                    ->all()
                                    ->toArray();

        $leaderResultData['month']   = $this
                                    ->find()
                                    ->select(['Users.id','Users.username','Users.first_name','Users.last_name','Users.after_image','Users.image','Users.week_weight_loss_percent','Users.month_weight_loss_percent','Users.total_weight_loss_percent','Users.trainer_id','Users.leaderboard_show'])
                                    ->where(['Users.status_id' => ACTIVE_STATUS, 'Users.group_id' => CLIENTGROUPID, 'Users.leaderboard_show' => 1])
                                    ->contain(['Trainers' => ['fields' => ['Trainers.id','Trainers.first_name','Trainers.last_name','Trainers.image']]])
                                    ->order(['Users.month_weight_loss_percent' => 'DESC'])
                                    ->limit($limit)
                                    ->all()
                                    ->toArray();             

        $leaderResultData['total']   = $this
                                    ->find()
                                    ->select(['Users.id','Users.username','Users.first_name','Users.last_name','Users.after_image','Users.image','Users.week_weight_loss_percent','Users.month_weight_loss_percent','Users.total_weight_loss_percent','Users.trainer_id','Users.leaderboard_show'])
                                    ->where(['Users.status_id' => ACTIVE_STATUS, 'Users.group_id' => CLIENTGROUPID, 'Users.leaderboard_show' => 1])
                                    ->contain(['Trainers' => ['fields' => ['Trainers.id','Trainers.first_name','Trainers.last_name','Trainers.image']]])
                                    ->order(['Users.total_weight_loss_percent' => 'DESC'])
                                    ->limit($limit)
                                    ->all()
                                    ->toArray();                                                        
        
        return $leaderResultData;
    }

    /*
     * Method   : getDetailedUserData
     * Author   : Bharat Borana
     * Created  : 31 Dec, 2014
     * @Retrieve detailed user data including kitchendata and order data 
     */
    public function getAllActivities($data = null, $limit = 15){
        $userDetails = array(); 
        if(!empty($data['user_id'])){
            $offset = (!empty($data['offset'])) ? $data['offset'] : 0;
            
            if(isset($data['last_fetch']) && !empty($data['last_fetch'])){
                $userActivities = $this
                                ->find()
                                ->where(['Users.id'=>$data['user_id']])
                                ->select(['Users.id','Users.first_name','Users.last_name','Users.image',])
                                ->contain(
                                    ['ActivityLogs' =>  function($q){
                                        return $q
                                        ->contain([
                                            'Activities'  =>  function ($q) {
                                               return $q
                                                    ->select(['Activities.title','Activities.status'])
                                                    ->where(['Activities.status'=>1]);
                                            },
                                            'Likes'=>array(
                                                'Users'=>array(
                                                    'fields'=>array('Users.id','Users.first_name','Users.last_name','Users.image'),
                                                ),
                                                'Recipes'=>array(
                                                    'fields'=>array('Recipes.id','Recipes.title'),
                                                ),
                                                'Feeds'=>array(
                                                    'fields'=>array('Feeds.id','Feeds.title'),
                                                ),
                                            ),
                                            'Shares'=>array(
                                                'Users'=>array(
                                                    'fields'=>array('Users.id','Users.first_name','Users.last_name','Users.image'),
                                                ),
                                                'Recipes'=>array(
                                                    'fields'=>array('Recipes.id','Recipes.title'),
                                                ),
                                                'Feeds'=>array(
                                                    'fields'=>array('Feeds.id','Feeds.title'),
                                                ),
                                            ),
                                            'Comments'=>array(
                                                'Users'=>array(
                                                    'fields'=>array('Users.id','Users.first_name','Users.last_name','Users.image'),
                                                ),
                                                'Recipes'=>array(
                                                    'fields'=>array('Recipes.id','Recipes.title'),
                                                ),
                                                'Feeds'=>array(
                                                    'fields'=>array('Feeds.id','Feeds.title'),
                                                ),
                                            ),
                                            'Conversations'=>array(
                                                'ConversationReplies'=>function ($q) {
                                                   return $q
                                                        ->select(['ConversationReplies.reply','ConversationReplies.user_id','ConversationReplies.conversation_id','ConversationReplies.created'])
                                                        ->contain(['Users'=>array(
                                                            'fields'=>array('Users.id','Users.first_name','Users.last_name','Users.image'),
                                                        )])
                                                        ->order(['ConversationReplies.created' => 'DESC'])
                                                        ->limit(1);
                                                },
                                            ),
                                            'Members'=>array(
                                                'fields'=>array('Members.id','Members.first_name','Members.last_name','Members.image','Members.mobile','Members.email'),
                                                'UserSubscriptions' => ['Subscriptions']
                                            )
                                        ])
                                        ->where(['ActivityLogs.created >'=>$data['last_fetch'],'ActivityLogs.status_id'=>1])
                                        ->order(['ActivityLogs.modified' => 'DESC']);    
                                    }
                                    ]
                                )->first();
            }else{
                $userActivities = $this
                                ->find()
                                ->where(['Users.id'=>$data['user_id']])
                                ->select(['Users.id','Users.first_name','Users.last_name','Users.image',])
                                ->contain(
                                    ['ActivityLogs' =>  function($q){
                                        return $q
                                        ->contain([
                                            'Activities'  =>  function ($q) {
                                               return $q
                                                    ->select(['Activities.title','Activities.status'])
                                                    ->where(['Activities.status'=>1]);
                                            },
                                            'Likes'=>array(
                                                'Users'=>array(
                                                    'fields'=>array('Users.id','Users.first_name','Users.last_name','Users.image'),
                                                ),
                                                'Recipes'=>array(
                                                    'fields'=>array('Recipes.id','Recipes.title'),
                                                ),
                                                'Feeds'=>array(
                                                    'fields'=>array('Feeds.id','Feeds.title','Feeds.recipe_id'),
                                                    'Recipes' => array('fields' => ['Recipes.id', 'Recipes.title'])
                                                ),
                                            ),
                                            'Shares'=>array(
                                                'Users'=>array(
                                                    'fields'=>array('Users.id','Users.first_name','Users.last_name','Users.image'),
                                                ),
                                                'Recipes'=>array(
                                                    'fields'=>array('Recipes.id','Recipes.title'),
                                                ),
                                                'Feeds'=>array(
                                                    'fields'=>array('Feeds.id','Feeds.title','Feeds.recipe_id'),
                                                    'Recipes' => array('fields' => ['Recipes.id', 'Recipes.title'])
                                                ),
                                            ),
                                            'Comments'=>array(
                                                'Users'=>array(
                                                    'fields'=>array('Users.id','Users.first_name','Users.last_name','Users.image'),
                                                ),
                                                'Recipes'=>array(
                                                    'fields'=>array('Recipes.id','Recipes.title'),
                                                ),
                                                'Feeds'=>array(
                                                    'fields'=>array('Feeds.id','Feeds.title','Feeds.recipe_id'),
                                                    'Recipes' => array('fields' => ['Recipes.id', 'Recipes.title'])
                                                ),
                                            ),
                                            'Conversations'=>array(
                                                'ConversationReplies'=>function ($q) {
                                                   return $q
                                                        ->select(['ConversationReplies.reply','ConversationReplies.user_id','ConversationReplies.conversation_id','ConversationReplies.created'])
                                                        ->contain(['Users'=>array(
                                                            'fields'=>array('Users.id','Users.first_name','Users.last_name','Users.image'),
                                                        )])
                                                        ->order(['ConversationReplies.created' => 'DESC'])
                                                        ->limit(1);
                                                },
                                            ),
                                            'Members'=>array(
                                                'fields'=>array('Members.id','Members.first_name','Members.last_name','Members.image','Members.mobile','Members.email'),
                                                'UserSubscriptions' => ['Subscriptions']
                                            )
                                        ])
                                        ->where(['ActivityLogs.status_id'=>1])
                                        ->order(['ActivityLogs.modified' => 'DESC']);    
                                    }
                                    ]
                                )->first();
            }
            
            
            if(!empty($userActivities)){
                $userActivities = $userActivities->toArray();
                
                if(isset($userActivities['image']) && !empty($userActivities['image']) && file_exists(USER_IMAGE_URL.$userActivities['image']))
                {
                    $userActivities['image'] = BASE_URL.USER_IMAGE_URL.$userActivities['image']; 
                }
                else
                {
                    $userActivities['image'] = 'choose-file.png';  
                }
                        
                $userDetails['Users']['id']         = $userActivities['id'];
                $userDetails['Users']['first_name'] = $userActivities['first_name'];
                $userDetails['Users']['last_name']  = $userActivities['last_name'];
                $userDetails['Users']['image']      = $userActivities['image'];
                
                $finalDashboardData = array();
                foreach($userActivities['activity_logs'] as $actKey => $actData){
                    
                    if(isset($actData['activity']) && !empty($actData['activity'])){
                        $rowData = array();
                        $rowData['activitylog_id'] = $actData['id'];
                        $rowData['activity_title'] = $actData['activity']['title'];
                        $rowData['activity_id'] = $actData['activity_id'];
                        $rowData['modified'] = $actData['modified'];
                        $rowData['timestamp'] = $actData['timestamp'];
                        if($actData['activity_id']==1)
                        {
                            $rowData['data'] = $actData['conversation'];
                        }
                        else if($actData['activity_id']==2){
                            $rowData['data'] = $actData['share'];
                        }
                        else if($actData['activity_id']==3){
                            $rowData['data'] = $actData['like'];
                        }
                        else if($actData['activity_id']==4){
                            $rowData['data'] = $actData['comment'];
                        }
                        else if($actData['activity_id']==5){
                            $rowData['data'] = $actData['like'];
                        }
                        else if($actData['activity_id']==6){
                            $rowData['data'] = $actData['comment'];
                        }
                        else if($actData['activity_id']==7){
                            $rowData['data'] = $actData['share'];
                        }
                        else if($actData['activity_id']==8){
                            $rowData['data'] = $actData['member'];
                        }

                        if(isset($rowData['data']['feed']) && isset($rowData['data']['feed']['recipe']['title']) && empty($rowData['data']['feed']['title'])){
                            $rowData['data']['feed']['title'] = $rowData['data']['feed']['recipe']['title'];
                        }

                        if(isset($rowData['data']) && !empty($rowData['data']))
                            $finalDashboardData[] = $rowData;
                    }
                }
                $userDetails['ActivityLogs'] = $finalDashboardData;
            }
        }
        return $userDetails;
    }
}
