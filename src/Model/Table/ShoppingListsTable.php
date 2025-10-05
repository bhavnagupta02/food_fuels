<?php
namespace App\Model\Table;

use App\Model\Entity\ShoppingList;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;

/**
 * ShoppingLists Model
 */
class ShoppingListsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('shopping_lists');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Statuses');
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
            ->requirePresence('document_name', 'create', 'Document required.')
            ->notEmpty('document_name','Document name should not be empty.');
            
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

    public function getCurrentShoppingList($date=null){
        $conditions = [];
        if(!empty($date)){
            $date1 = new \DateTime($date);
            $date2 = new \DateTime();
            $interval = $date1->diff($date2);
            $startingDays   = $interval->days;
            $currentWeekTemp = $startingDays/7;
			
			//$currentWeek = floor($startingDays/7)+1; commented on 27-05-2016
			$currentWeek = (is_numeric($currentWeekTemp) && floor($currentWeekTemp) != $currentWeekTemp) ? floor($currentWeekTemp)+1 : $currentWeekTemp;
            $currentWeek = ($currentWeek > 0) ? $currentWeek%12 : $currentWeek;
			
            /*
            $week_number = date_create(Date('Y-m-d',strtotime($date)));
            $currentWeek = date_create(Date('Y-m-d'));
            $diff=date_diff($week_number,$currentWeek);
            $days = $diff->format("%a");
            $weekSpent = ($days%7 == 0)?($days/7):(round($days/7))+1; 
            */
            $conditions = ['week_no' => $currentWeek];
        }
        $shoppingListData = $this->find()->select(['document_name'])->where($conditions)->first();
		if(!empty($shoppingListData)){
            $shoppingListData = $shoppingListData->toArray();
        }
        return $shoppingListData;
    }
	
	public function getShoppingList($date=null){
        $conditions = [];
        if(!empty($date)){
            $date1 = new \DateTime($date);
            $date2 = new \DateTime();
			$interval = $date1->diff($date2);
            $startingDays   = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
            $currentWeekTemp = $startingDays/7;
			
			//$currentWeek = ceil($startingDays/7); commented on 13-07-2016
			$currentWeek = (is_numeric($currentWeekTemp) && floor($currentWeekTemp) != $currentWeekTemp) ? floor($currentWeekTemp)+1 : $currentWeekTemp;
			$currentWeek = ($currentWeek > 0) ? $currentWeek%12 : $currentWeek;
			$conditions = ['week_no >=' => $currentWeek, 'week_no <=' => $currentWeek+1];
        }
        $shoppingListData = $this->find()->select(['week_no', 'document_name'])->where($conditions)->all();
        //pr($shoppingListData->toArray()); die;
		
		if(!empty($shoppingListData)){
            $shoppingListData = $shoppingListData->toArray();
        }
        return $shoppingListData;
    }
}
