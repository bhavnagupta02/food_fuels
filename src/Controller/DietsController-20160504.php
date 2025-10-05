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

		$this->loadModel('ShoppingLists');
		$shoppingListData = $this->ShoppingLists->getCurrentShoppingList($this->Auth->user('created'));
		
		$this->loadModel('UserSubscriptions');
		$subscriptionData = $this->UserSubscriptions->find()->where(['user_id' => $this->Auth->user('id')])->first();
		
		$mealArray = [];	
		
		if((!empty($subscriptionData) && $subscriptionData->start_date)){
			$date1 = new \DateTime($subscriptionData->start_date);
			$date2 = new \DateTime();
			$interval = $date1->diff($date2);
			$startingDays    = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
			$currentWeek = floor($startingDays/7)+1;
			$weekDay = $startingDays%7;
			
			$date3 = new \DateTime($subscriptionData->end_date);
			$interval = $date3->diff($date2);
			$remainingDays    = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
			
			if($remainingDays >= $startingDays){
				$this->loadModel('DailyMealPlans');
				$startingWeek = ceil($startingDays/7);
				$mealPlans = $this->DailyMealPlans->find()
						->select(['DailyMealPlans.week_no','DailyMealPlans.meal_date','DailyMealPlans.week_day','DailyMealPlans.id','DailyMealPlans.text_highlight', 'weak_number' => 'WEEK(meal_date,1)'])
						->where(['week_no >=' => $startingWeek, 'week_no <=' => $startingWeek+1])
						->order(['DailyMealPlans.week_no' => 'ASC','DailyMealPlans.week_day' => 'ASC'])
						->contain('Meals')
						->all();
				
				if(!empty($mealPlans)){
					foreach ($mealPlans as $row) {
						$week_no = ($row->week_no)-1;
						$mealDate = date_create($subscriptionData->start_date);
						$mealDate = date_modify($mealDate, '+ '.$week_no.' week');
						$mealDate = date_modify($mealDate, '+ '.$row->week_day.' day');
					   	$row->meal_date = date_format($mealDate, 'Y-m-d');
					   	//$mealArray[Date('W',strtotime($row->meal_date))][] = $row->toArray();
					   	$mealArray[$row->week_no][] = $row->toArray();
					}
				}
			}
		}
		elseif ($this->Auth->user('group_id') == USERGROUPID) {
			$date1 = new \DateTime($this->Auth->user('created'));
			$date2 = new \DateTime();
			$interval = $date1->diff($date2);
			$startingDays    = floor(($interval->y * 365 + $interval->m*30 + $interval->d));
			$currentWeek = floor($startingDays/7)+1;
			$weekDay = $startingDays%7;
			if(true){
				$this->loadModel('DailyMealPlans');
				$startingWeek = ceil($startingDays/7);
				$mealPlans = $this->DailyMealPlans->find()
						->select(['DailyMealPlans.week_no','DailyMealPlans.meal_date','DailyMealPlans.week_day','DailyMealPlans.id','DailyMealPlans.text_highlight', 'weak_number' => 'WEEK(meal_date,1)'])
						->where(['week_no >=' => $startingWeek, 'week_no <=' => $startingWeek+1])
						->order(['DailyMealPlans.week_no' => 'ASC','DailyMealPlans.week_day' => 'ASC'])
						->contain('Meals')
						->all();
				
				if(!empty($mealPlans)){
					foreach ($mealPlans as $row) {
						$week_no = ($row->week_no)-1;
						$mealDate = date_create($this->Auth->user('created'));
						$mealDate = date_modify($mealDate, '+ '.$week_no.' week');
						$mealDate = date_modify($mealDate, '+ '.$row->week_day.' day');
					   	$row->meal_date = date_format($mealDate, 'Y-m-d');
					   	//$mealArray[Date('W',strtotime($row->meal_date))][] = $row->toArray();
					   	$mealArray[$row->week_no][] = $row->toArray();
					}
				}
			}
		}
		$this->set(compact('shoppingListData','mealArray','currentWeek','weekDay'));
	}
}