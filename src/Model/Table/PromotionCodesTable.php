<?php
namespace App\Model\Table;

use App\Model\Entity\Deal;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PromotionCodes Model
 */
class PromotionCodesTable extends Table
{
    public function initialize(array $config)
    {
        $this->table('promotion_codes');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function checkMyCode($promoCode,$offerValue){
        $returnArray['discAmount'] = 0;
        $returnArray['promotion_code_id'] = 0;

        $conditions = [
                        'status_id'     => 1,
                        'valid_from <=' => Date('Y-m-d'),
                        'valid_till >=' => Date('Y-m-d')           
                       ];
        $promoCodeDetails = $this->findByTitle($promoCode)->where($conditions)->first();
        if(!empty($promoCodeDetails)){
            $promoCodeDetails  =   $promoCodeDetails->toArray();
            
            if(($promoCodeDetails['uses_type'] == 1 && $promoCodeDetails['no_of_use'] == 0) || $promoCodeDetails['uses_type'] == 2){
                 $returnArray['promotion_code_id'] = $promoCodeDetails['id'];

                if($promoCodeDetails['discount_type']==1){
                    $returnArray['discAmount'] =   number_format($promoCodeDetails['amount'], 2);
                }
                else{
					$result = ($offerValue/100)*$promoCodeDetails['amount'];
                    $returnArray['discAmount'] =   number_format($result,2);
                } 
            }
        } 

        return $returnArray;  
    }
}
