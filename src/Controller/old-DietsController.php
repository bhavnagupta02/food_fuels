<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\I18n;  

/**
 * Diets Controller
 *
 * @property \App\Model\Table\DietsTable $Diets
 */
class DietsController extends AppController
{
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}
  
	/**
	 * Index method
	 *
	 * @return void
	 */
	public function index() {
		$this->set('title', 'Meal Plan');
		if($this->Auth->user('group_id') == CLIENTGROUPID) {
			$this->checkMembershipExpired(); //check user's membership stauts
		}
		
		$this->loadModel('ShoppingLists');
		$this->loadModel('UserSubscriptions');
		$subscriptionData = $this->UserSubscriptions->find()->where(['user_id' => $this->Auth->user('id')])->order(['UserSubscriptions.id' => 'DESC'])->first();
		
		$currentDate = date('Y-m-d');
		$mealArray = [];
		$tempMealArray = [];
		$originalStartingWeek = '';
		
		if((!empty($subscriptionData) && $subscriptionData->start_date)){
			$date1 = new \DateTime($subscriptionData->start_date);
			$date2 = new \DateTime();
			
			$interval = $date1->diff($date2);
			$startingDays    = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
			$currentWeekTemp = $startingDays/7;
			
			//$currentWeek = floor($startingDays/7)+1; commented on 27-05-2016
			$currentWeek = (is_numeric($currentWeekTemp) && floor($currentWeekTemp) != $currentWeekTemp) ? floor($currentWeekTemp)+1 : $currentWeekTemp;
			/* If starting week is going to exceed from 12 then start it from initial */
			$currentWeek = ($currentWeek > 0) ? $currentWeek%12 : $currentWeek;
			
			$weekDay = $startingDays%7;
			
			$date3 = new \DateTime($subscriptionData->end_date);
			$interval = $date3->diff($date2);
			$remainingDays    = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
			
			//if($remainingDays >= $startingDays){ //commented on 2016-05-04
			if(strtotime($subscriptionData->end_date) >= strtotime($currentDate)) {	
				$this->loadModel('DailyMealPlans');
				$originalStartingWeek = $startingWeek = ceil($startingDays/7);
				
				/* If starting week is going to exceed from 12 then start it from initial */
				$startingWeek = ($startingWeek > 0) ? $startingWeek%12 : $startingWeek;
				
				$mealPlans = $this->DailyMealPlans->find()
						->select(['DailyMealPlans.week_no','DailyMealPlans.meal_date','DailyMealPlans.week_day','DailyMealPlans.id','DailyMealPlans.text_highlight', 'weak_number' => 'WEEK(meal_date,1)'])
						->where(['week_no >=' => $startingWeek, 'week_no <=' => $startingWeek+1])
						->order(['DailyMealPlans.week_no' => 'ASC','DailyMealPlans.week_day' => 'ASC'])
						->contain('Meals')
						->all();
				
				//$shoppingListInfos = $this->ShoppingLists->getShoppingList($this->Auth->user('created')); //commented on 14-07-2016
				$shoppingListInfos = $this->ShoppingLists->getShoppingList($subscriptionData->start_date);
				
				if(!empty($mealPlans)) {
					$weekNoForMealPlan = $originalStartingWeek;
					foreach ($mealPlans as $keyMeal => $row) {
						//$week_no = ($row->week_no)-1;
						
						if($weekNoForMealPlan != 12 && ($weekNoForMealPlan % 12) == $row->week_no) { 
							$weekNoForMealPlan = $weekNoForMealPlan;
						} else if($weekNoForMealPlan == 12 && $weekNoForMealPlan == $row->week_no) {
							$weekNoForMealPlan = $weekNoForMealPlan;
						} else {
							$weekNoForMealPlan = $weekNoForMealPlan+1;
						}
						
						$week_no = ($weekNoForMealPlan)-1;
						$mealDate = date_create($subscriptionData->start_date);
						$mealDate = date_modify($mealDate, '+ '.$week_no.' week');
						$mealDate = date_modify($mealDate, '+ '.$row->week_day.' day');
					   	$row->meal_date = date_format($mealDate, 'Y-m-d');
						
						// add document name in meal plan array
						if(!empty($shoppingListInfos)) {
							foreach($shoppingListInfos as $shoppingData) {
								
								if($shoppingData['week_no']==$row->week_no) {
									//echo $shoppingData['week_no'].'--'.$row->week_no.'<br/>';
									$row->document_name = $shoppingData['document_name'];
								}	
							}	
						}
						
						if(strtotime($subscriptionData->end_date) >= strtotime($row->meal_date)) {
							$mealArray[$row->week_no][] = $row->toArray();
						}
					   	//$mealArray[Date('W',strtotime($row->meal_date))][] = $row->toArray();
					}
				}
			}
			//pr($mealArray); die;
		}
		elseif ($this->Auth->user('group_id') == USERGROUPID) {
			$date1 = new \DateTime($this->Auth->user('created'));
			$date2 = new \DateTime();
			$interval = $date1->diff($date2);
			$startingDays    = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
			$currentWeekTemp = $startingDays/7;
			
			//$currentWeek = floor($startingDays/7)+1; commented on 27-05-2016
			$currentWeek = (is_numeric($currentWeekTemp) && floor($currentWeekTemp) != $currentWeekTemp) ? floor($currentWeekTemp)+1 : $currentWeekTemp;
			
			$weekDay = $startingDays%7;
			if(true){
				$this->loadModel('DailyMealPlans');
				$startingWeek = ceil($startingDays/7);
				$originalStartingWeek = 1;
				$mealPlans = $this->DailyMealPlans->find()
						->select(['DailyMealPlans.week_no','DailyMealPlans.meal_date','DailyMealPlans.week_day','DailyMealPlans.id','DailyMealPlans.text_highlight', 'weak_number' => 'WEEK(meal_date,1)'])
						->where(['week_no >= 1', 'week_no <= 12'])
						->order(['DailyMealPlans.week_no' => 'ASC','DailyMealPlans.week_day' => 'ASC'])
						->contain('Meals')
						->all();
				
				$shoppingListInfos = $this->ShoppingLists->getShoppingList($this->Auth->user('created'));
				if(!empty($mealPlans)) {
					foreach ($mealPlans as $row) {
						$week_no = ($row->week_no)-1;
						$mealDate = date_create($this->Auth->user('created'));
						$mealDate = date_modify($mealDate, '+ '.$week_no.' week');
						$mealDate = date_modify($mealDate, '+ '.$row->week_day.' day');
					   	$row->meal_date = date_format($mealDate, 'Y-m-d');
						
						// add document name in meal plan array
						if(!empty($shoppingListInfos)) {
							foreach($shoppingListInfos as $shoppingData) {
								if($shoppingData['week_no']==$row->week_no) {
									$row->document_name = $shoppingData['document_name'];
								}	
							}	
						}
						
					   	//$mealArray[Date('W',strtotime($row->meal_date))][] = $row->toArray();
					   	$mealArray[$row->week_no][] = $row->toArray();
					}
				}
			}
		}
		$this->set(compact('subscriptionData','shoppingListInfos','mealArray','currentWeek','weekDay','originalStartingWeek'));
	}
}