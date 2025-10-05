<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['display', 'home', 'register_success', 'faq_list']);/*'forgot_password'*/
    }

    public function display()
    {
        $path = func_get_args();

        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

    public function home(){
        $user = $this->Auth->user('id');
        if(!empty($user) && $this->Auth->user('group_id') == CLIENTGROUPID) {
            return $this->redirect($this->Auth->redirectUrl());
        }
        elseif(!empty($user) && $this->Auth->user('group_id') == USERGROUPID) {
            return $this->redirect($this->redirect(['controller' => 'trainers', 'action' => 'home']));
        } 
        $this->set('title', 'Home');
        
        $this->loadModel('Users');
        $featuredTrainers = $this->Users->find()->where(['is_featured' => 1,'group_id' => USERGROUPID])->select(['id','image','first_name','last_name','short_description','achievements']);
        $featuredClient = $this->Users->find()->where(['is_featured' => 1,'group_id' => CLIENTGROUPID])->select(['id','before_image','after_image','first_name','last_name','username'])->limit(6);
        
        if(isset($trainers) && !empty($trainers))
            $trainers = $trainers->toArray();

        $this->loadModel('Subscriptions');
        $Subscriptions = $this->Subscriptions->find()->toArray();
        //pr($Subscriptions);exit;
        $this->set(compact('Subscriptions','featuredTrainers','featuredClient'));
        if(isset($this->request->query['actionType'])){
            $this->set('type',$this->request->query['actionType']);
        }
        if(isset($this->request->query['email'])){
            $this->set('user_email',$this->request->query['email']);
        }
    }

    public function enquiry(){
        echo "<pre>";
        print_r($this->request->data);
        exit;
    }

    public function register_success(){
        $this->set('title', 'Thank you');
        //$this->loadModel('Subscriptions');
        //$Subscriptions = $this->Subscriptions->find()->toArray();
        //$this->set('Subscriptions',$Subscriptions);
    }

    /**
	 * Displays a view
	 * view file located at src/Template/Faqs/edit_faq.ctp
	 * @param mixed What page to display
	 * @return void
	 * @throws NotFoundException When the view file could not be found
	 * or MissingViewException in debug mode.
	 * function to edit FAQs 
	 */
         public function faq_list() {
		$this->set('title', 'FAQs About FoodFuels');	
		$this->loadModel('Faqs');
		
		$faqList = $this->Faqs->find()->where(['Faqs.status_id' =>1])->order(['Faqs.id'=>'ASC'])->toArray();
		$this->set(compact('faqList'));
	}
    

}
