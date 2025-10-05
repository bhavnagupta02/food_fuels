<?php
namespace App\Model\Table;

use App\Model\Entity\UserStat;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserStats Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Clients
 */
class UserStatsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('user_stats');
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
            
        $validator
            ->add('stat_date', 'valid', ['rule' => 'date'])
            ->requirePresence('stat_date', 'create')
            ->notEmpty('stat_date');
            
        $validator
            ->add('weight', 'valid', ['rule' => 'numeric'])
            ->requirePresence('weight', 'create')
            ->notEmpty('weight');
            
        $validator
            ->add('height', 'valid', ['rule' => 'numeric'])
            ->requirePresence('height', 'create')
            ->notEmpty('height');
            
        $validator
            ->add('neck', 'valid', ['rule' => 'numeric'])
            ->requirePresence('neck', 'create')
            ->notEmpty('neck');
            
        $validator
            ->add('chest', 'valid', ['rule' => 'numeric'])
            ->requirePresence('chest', 'create')
            ->notEmpty('chest');
            
        $validator
            ->add('upperarm', 'valid', ['rule' => 'numeric'])
            ->requirePresence('upperarm', 'create')
            ->notEmpty('upperarm');
            
        $validator
            ->add('forearm', 'valid', ['rule' => 'numeric'])
            ->requirePresence('forearm', 'create')
            ->notEmpty('forearm');
            
        $validator
            ->add('hip', 'valid', ['rule' => 'numeric'])
            ->requirePresence('hip', 'create')
            ->notEmpty('hip');
            
        $validator
            ->add('thigh', 'valid', ['rule' => 'numeric'])
            ->requirePresence('thigh', 'create')
            ->notEmpty('thigh');
            
        $validator
            ->add('calf', 'valid', ['rule' => 'numeric'])
            ->requirePresence('calf', 'create')
            ->notEmpty('calf');
            
        $validator
            ->add('heart_rate', 'valid', ['rule' => 'numeric'])
            ->requirePresence('heart_rate', 'create')
            ->notEmpty('heart_rate');
            
        $validator
            ->add('bmi', 'valid', ['rule' => 'numeric'])
            ->requirePresence('bmi', 'create')
            ->notEmpty('bmi');
            
        $validator
            ->add('bmr', 'valid', ['rule' => 'numeric'])
            ->requirePresence('bmr', 'create')
            ->notEmpty('bmr');
            
        $validator
            ->add('ideal_body_weight', 'valid', ['rule' => 'numeric'])
            ->requirePresence('ideal_body_weight', 'create')
            ->notEmpty('ideal_body_weight');
            
        $validator
            ->add('max_heart_rate', 'valid', ['rule' => 'numeric'])
            ->requirePresence('max_heart_rate', 'create')
            ->notEmpty('max_heart_rate');
            
        $validator
            ->add('body_fat', 'valid', ['rule' => 'numeric'])
            ->requirePresence('body_fat', 'create')
            ->notEmpty('body_fat');
            
        $validator
            ->add('lean_muscle_mass', 'valid', ['rule' => 'numeric'])
            ->requirePresence('lean_muscle_mass', 'create')
            ->notEmpty('lean_muscle_mass');
            
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['client_id'], 'Users'));
        return $rules;
    }
}
