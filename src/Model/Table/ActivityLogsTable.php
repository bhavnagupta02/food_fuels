<?php
namespace App\Model\Table;

use App\Model\Entity\ActivityLog;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ActivityLogsTable Model
 */
class ActivityLogsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('activity_logs');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    
        $this->belongsTo('Users');
        $this->belongsTo('Activities');
        $this->belongsTo('Likes');
        $this->belongsTo('Comments');
        $this->belongsTo('Shares');
        $this->belongsTo('Conversations');
        $this->belongsTo('Members',
            ['foreignKey' => 'member_id',
            'className' =>  'Users'
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
            ->allowEmpty('id', 'create');
            
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
        return $rules;
    }

    public function updateLog($userId=null,$activityId=null,$relevantId=null,$timestamp=null,$update=0){
        if(!empty($userId)){
            $actData['user_id'] = $userId;
            $actData['activity_id'] = $activityId;
            if($activityId==1) //Conversation px_update_record(pxdoc, data, num)
                $actData['conversation_id'] = $relevantId;
            else if($activityId==2) //Conversation updated 
                $actData['share_id'] = $relevantId;
            else if($activityId==3) //Liked your recipe
                $actData['like_id'] = $relevantId;
            else if($activityId==4) //Commented on your recipe page
                $actData['comment_id'] = $relevantId;
            else if($activityId==5) //Liked your post.
                $actData['like_id'] = $relevantId;
            else if($activityId==6) //Commented on your post.
                $actData['comment_id'] = $relevantId;
            else if($activityId==7) //Commented on your post.
                $actData['share_id'] = $relevantId;
            else if($activityId==8) //Commented on your post.
                $actData['member_id'] = $relevantId;    
            
            if(!empty($timestamp)) // Timestamp for an activity
                $actData['timestamp'] = $timestamp;
            
            if(!empty($activityId) && ($activityId>0 && $activityId<=8)){
                if(!$update){
                    $freshEntity = $this->newEntity($actData);
                    $this->save($freshEntity);
                }else{
                    $prevData = $this->get($update);
                    $prevEntity = $this->patchEntity($prevData,$actData);
                    $this->save($prevEntity);
                }
            }   
        }
    }
}
