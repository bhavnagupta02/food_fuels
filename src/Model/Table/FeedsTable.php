<?php
namespace App\Model\Table;

use App\Model\Entity\Feed;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FeedsTable Model
 */
class FeedsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('feeds');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Statuses');
        $this->belongsTo('Users');
        $this->belongsTo('Recipes');
        $this->belongsTo('FeedType');
        $this->belongsTo('MyFeeds',
                [
                    'foreignKey' => 'feed_id',
                    'className' =>  'Feeds',
                    'joinType' => 'LEFT'
                ]);

         $this->belongsTo('FeedType', [
            'foreignKey' => 'feed_id',
            'className' =>  'Feeds',
            'joinType' => 'LEFT'
        ]);

        //$this->hasMany('FeedType');
        $this->hasMany('UploadImages');
        $this->hasMany('Likes');
        $this->hasMany('Shares');
        $this->hasMany('Comments');
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

    public function getMyFeed($activity_id = null, $user_id = null, $item_per_page = null, $position= null){
         
        $conditions = ['Feeds.status_id' => ACTIVE_STATUS];
        
        if(isset($activity_id) && !empty($activity_id))
            $conditions[] = ['Feeds.activity_id' => $activity_id];

        if(isset($user_id) && !empty($user_id))
            $conditions[] = ['Feeds.user_id' => $user_id];

       /* $conditions = ['FeedType.feed_type' => COACHCOMMUNITY];
        
        if(isset($feed_type) && !empty($feed_type))
            $conditions[] = ['FeedType.feed_type' => $feed_type];*/
        
        $feedList = $this
                        ->find('all', array('limit'=>$item_per_page, 'offset'=>$position))
                        ->where($conditions)
                        ->contain([ 'UploadImages',
                                    'Likes'     =>  [
                                                        'Users' => ['fields' => ['Users.first_name', 'Users.last_name', 'Users.id', 'Users.image', 'Users.username'] ]
                                                    ],
                                    'Comments'  =>  [
                                                        'Users' => ['fields' => ['Users.first_name', 'Users.last_name', 'Users.id', 'Users.image', 'Users.username'] ]
                                                    ],
                                    'Recipes'   =>  ['UploadImages'],
                                    'MyFeeds'     =>  ['UploadImages', 'Recipes'   =>  ['UploadImages']],
                                    'Users'     =>  ['fields' => ['Users.first_name', 'Users.last_name', 'Users.id', 'Users.image', 'Users.username']]
                                ])
                        ->order(['Feeds.created' => 'DESC'])
                        ->all();

        if(isset($feedList) && !empty($feedList))
            $feedList = $feedList->toArray();
 
        return $feedList;
    }

}
