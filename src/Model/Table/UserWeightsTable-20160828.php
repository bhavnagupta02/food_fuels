<?php
namespace App\Model\Table;

use App\Model\Entity\UserWeight;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * UserWeightTable Model
 */
class UserWeightsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('user_weights');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
	    $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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


    public function findAndUpdateMyLoss($UserID = null){
        if(!empty($UserID)){
            $maxWeightCurrent = $this->find()->select(['weight_date', 'weight'])->where(['user_id' => $UserID,'Date(weight_date) <=' => Date('y-m-d')])->order(['weight_date' => 'DESC'])->first();
            $maxWeightAll = $this->find()->select(['max_weight' => 'max(weight)','weight_date', 'weight'])->where(['user_id' => $UserID,'Date(weight_date) <=' => Date('y-m-d')])->first();
            $maxWeightWeek = $this->find()->select(['max_weight' => 'max(weight)','weight_date'])->where(['user_id' => $UserID,'WEEK(weight_date)' => Date('W')])->first();
            $maxWeightMonth = $this->find()->select(['max_weight' => 'max(weight)','weight_date','current_month' => 'MONTH(weight_date)'])->where(['user_id' => $UserID,'MONTH(weight_date)' => Date('m')])->group('MONTH(weight_date)')->first();
           
            $currentWeight = $MaxWeightTotal = $MaxWeightWeek = $MaxWeightMonth = 0;
            
            if(isset($maxWeightCurrent) && !empty($maxWeightCurrent))
                $currentWeight = $maxWeightCurrent->weight;
            
            if(isset($maxWeightAll) && !empty($maxWeightAll))
                $MaxWeightTotal = $maxWeightAll->max_weight;
            
            if(isset($maxWeightWeek) && !empty($maxWeightWeek))
                $MaxWeightWeek = $maxWeightWeek->max_weight;
            
            if(isset($maxWeightMonth) && !empty($maxWeightMonth))
                $MaxWeightMonth = $maxWeightMonth->max_weight;
            
            $totalWeightLoss = $MaxWeightTotal-$currentWeight;
            $totalWeightLossPercentage = round(($totalWeightLoss*100)/$MaxWeightTotal,0);
            
            $weekWeightLoss = $MaxWeightWeek-$currentWeight;
            $WeekWeightLossPercentage = round(($weekWeightLoss*100)/$MaxWeightWeek,0);
            
            $monthWeightLoss = $MaxWeightMonth-$currentWeight;
            $monthWeightLossPercentage = round(($monthWeightLoss*100)/$MaxWeightMonth,0);
            
            $usersTable = TableRegistry::get('Users');
            $user = $usersTable->get($UserID); // Return article with id 12

            $user->id = $UserID;
            $user->total_weight_loss = $totalWeightLoss;
            $user->month_weight_loss = $monthWeightLoss;
            $user->week_weight_loss = $weekWeightLoss;
            
            $user->total_weight_loss_percent = $totalWeightLossPercentage;
            $user->month_weight_loss_percent = $monthWeightLossPercentage;
            $user->week_weight_loss_percent = $WeekWeightLossPercentage;
            
            $usersTable->save($user);

            /*
            ALTER TABLE  `users` ADD  `total_weight_loss_percent` INT( 3 ) NULL DEFAULT  '0' AFTER  `week_weight_loss` ,
            ADD  `month_weight_loss_percent` INT( 3 ) NULL DEFAULT  '0' AFTER  `total_weight_loss_percent` ,
            ADD  `week_weight_loss_percent` INT( 3 ) NULL DEFAULT  '0' AFTER  `month_weight_loss_percent` ;
            */
      
        } 
        return true;
    }
}
