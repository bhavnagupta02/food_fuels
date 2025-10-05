<?php
namespace App\Model\Table;

use App\Model\Entity\EmailTemplate;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Enquiries Model
 */
class EnquiriesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('enquiries');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
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
            ->requirePresence('name', 'create', 'Name required.')
            ->requirePresence('phone', 'create', 'Phone number required.')
            ->requirePresence('comments', 'create', 'Please enter your comments.')
            ->add('email', 'validFormat',[
                    'rule' => 'email',
                    'message' => 'E-mail must be valid.'
                ])
            ->add('email', [ 'unique' => ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Contact request from this email already exists.'] ])
            ->notEmpty('name','Name should not be empty.')
            ->notEmpty('phone','Phone should not be empty.')
            ->notEmpty('comments','Please enter your comments.');
                    
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
}