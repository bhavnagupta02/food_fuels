<?php
namespace App\Model\Table;

use App\Model\Entity\UserSetting;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserSettings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class UserSettingsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('user_settings');
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
            ->add('weight_sett', 'valid', ['rule' => 'numeric'])
            ->requirePresence('weight_sett', 'create')
            ->notEmpty('weight_sett');
            
        $validator
            ->add('height_sett', 'valid', ['rule' => 'numeric'])
            ->requirePresence('height_sett', 'create')
            ->notEmpty('height_sett');
            
        $validator
            ->add('distance_sett', 'valid', ['rule' => 'numeric'])
            ->requirePresence('distance_sett', 'create')
            ->notEmpty('distance_sett');
            
        $validator
            ->add('energy_sett', 'valid', ['rule' => 'numeric'])
            ->requirePresence('energy_sett', 'create')
            ->notEmpty('energy_sett');
            
        $validator
            ->add('currency_sett', 'valid', ['rule' => 'numeric'])
            ->requirePresence('currency_sett', 'create')
            ->notEmpty('currency_sett');
            
        $validator
            ->add('date_sett', 'valid', ['rule' => 'numeric'])
            ->requirePresence('date_sett', 'create')
            ->notEmpty('date_sett');
            
        $validator
            ->add('time_sett', 'valid', ['rule' => 'numeric'])
            ->requirePresence('time_sett', 'create')
            ->notEmpty('time_sett');

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
        return $rules;
    }
}
