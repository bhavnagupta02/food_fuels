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
use Cake\View\View;
/**
 * Static content controller
 *
 * This controller will render views from Template/Recieps/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class RecipesController extends AppController
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
    }

    /**
     * Index method
     *
     * @return void Redirects on successful listing.
     */
    public function index()
    {
        if($this->Auth->user('group_id') == CLIENTGROUPID) {
		$this->checkMembershipExpired(); //check user's membership stauts
	}
        
        $this->set('title', 'Recipes');
        $recipeList = $this->Recipes->find('all', array('limit'=>10))->where(['Recipes.status_id' => ACTIVE_STATUS])->contain(['UploadImages','Categories','Users'=>['fields' => ['first_name', 'last_name', 'username', 'email', 'id', 'image']]]);
        if(isset($recipeList) && !empty($recipeList))
            $recipeList = $recipeList->toArray();

        $this->set('recipeList', $recipeList);
    }

    /**
     * Search method
     *
     * @return void Redirects on successful search.
     */
    public function search()
    {
        # code...
        $this->viewBuilder()->layout(false);
        
        if($this->request->is('ajax'))
        { 
            $conditions = ['Recipes.status_id' => ACTIVE_STATUS];
            if(isset($this->request->data['category_id']) && !empty($this->request->data['category_id'])){
                $catData['OR']['Recipes.category_id'] = $this->request->data['category_id'];
                $catData['OR']['Recipes.category_id LIKE'] = '%"'.$this->request->data['category_id'].'"%';
                $conditions[] = $catData;
            }

            if(isset($this->request->data['search']) && !empty($this->request->data['search'])){
                $searchData['OR']['Recipes.title LIKE ']        = '%'.$this->request->data['search'].'%';
                $searchData['OR']['Recipes.description LIKE']   = '%'.$this->request->data['search'].'%';
                $searchData['OR']['Recipes.ingredients LIKE']   = '%'.$this->request->data['search'].'%';
                $conditions[] = $searchData;
            }

            $orderBy = '';
            if(isset($this->request->data['rating']) && !empty($this->request->data['rating'])){
                $orderBy    =   ['Recipes.like_count' => 'DESC'];
            }
        
            $recipeList = $this->Recipes->find()->where($conditions)->contain(['UploadImages','Categories','Users'=>['fields' => ['first_name', 'last_name', 'username', 'email', 'id', 'image']]])->order($orderBy)->all();
            if(isset($recipeList) && !empty($recipeList))
                $recipeList = $recipeList->toArray();
        }
        $this->set('recipeList', $recipeList);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->set('title', 'Add Recipes');
        $recipe = $this->Recipes->newEntity();
        if ($this->request->is('post')) {
            
            $dishData = $this->request->data;
            $categories = '';
            if(isset($dishData['category_id']) && !empty($dishData['category_id'])){
                foreach ($dishData['category_id'] as $key => $dValue) {
                    if($dValue == 1){
                        if(empty($categories))
                            $categories = '"'.$key.'"';
                        else
                            $categories .= ', "'.$key.'"';        
                    }
                }

                $this->request->data['category_id'] =   $categories;     
            }
            
            
            $this->request->data['user_id'] = $this->Auth->user('id');
            $recipe = $this->Recipes->patchEntity($recipe, $this->request->data);
            if ($dish = $this->Recipes->save($recipe)) {
                /*
                Upload Dish photos and save this to Upload Image database.
                */
                $uploadData = array();
                $this->loadModel('UploadImages');
                        
                if(isset($dishData['UploadImage']['name'][0]['name']) && !empty($dishData['UploadImage']['name'][0]['name']))
                {
                    foreach ($dishData['UploadImage']['name'] as $key => $value)
                    {
                        $ext = substr(strtolower(strrchr($value['name'], '.')), 1);
                        $FileName = $key.mt_rand().'-'.time().'.'.$ext;
                        
                        move_uploaded_file($value['tmp_name'],DISH_IMAGE_PATH.$FileName);
                        
                        // store the filename in the array to be saved to the db
                        $uploadData['name'] = $FileName;
                        $uploadData['type'] = 'dish';
                        $uploadData['recipe_id'] = $dish->id;
                        $uploadData['user_id'] = $this->Auth->user('id');
                        $uploadImage = $this->UploadImages->newEntity($uploadData);
                        $this->UploadImages->save($uploadImage);
                    }
                }
                //$this->Flash->success(__('This dish has been successfully added.'), 'success');
                $this->redirect(['action' => 'index']);
            } else {
                $errors = $this->_getValidationMessages($recipe->errors());
                $this->Flash->error($errors, 'error');
            }
        }
        $statuses = $this->Recipes->Statuses->find('list')->toArray();
        $categories = $this->Recipes->Categories->find('list')->toArray();
        $this->set(compact(['statuses','categories']));
    }

    /**
     * Edit method
     *
     * @return void Redirects on successful edit, renders view otherwise.
     */
    public function edit($id=null)
    {
        $this->set('title', 'Edit Recipes');
        $recipe = $this->Recipes->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
        
            
            $dishData = $this->request->data;
            $categories = '';
            if(isset($dishData['category_id']) && !empty($dishData['category_id'])){
                foreach ($dishData['category_id'] as $key => $dValue) {
                    if($dValue == 1){
                        if(empty($categories))
                            $categories = '"'.$key.'"';
                        else
                            $categories .= ', "'.$key.'"';        
                    }
                }

                $this->request->data['category_id'] =   $categories;     
            }
            
            
            $this->request->data['user_id'] = $this->Auth->user('id');
            $recipe = $this->Recipes->patchEntity($recipe, $this->request->data);
            if ($dish = $this->Recipes->save($recipe)) {
                /*
                Upload Dish photos and save this to Upload Image database.
                */
                $uploadData = array();
                $this->loadModel('UploadImages');
                        
                if(isset($dishData['UploadImage']['name'][0]['name']) && !empty($dishData['UploadImage']['name'][0]['name']))
                {
                    foreach ($dishData['UploadImage']['name'] as $key => $value)
                    {
                        $ext = substr(strtolower(strrchr($value['name'], '.')), 1);
                        $FileName = $key.mt_rand().'-'.time().'.'.$ext;
                        
                        move_uploaded_file($value['tmp_name'],DISH_IMAGE_PATH.$FileName);
                        
                        // store the filename in the array to be saved to the db
                        $uploadData['name'] = $FileName;
                        $uploadData['type'] = 'dish';
                        $uploadData['recipe_id'] = $dish->id;
                        $uploadData['user_id'] = $this->Auth->user('id');
                        $uploadImage = $this->UploadImages->newEntity($uploadData);
                        $this->UploadImages->save($uploadImage);
                    }
                }
                //$this->Flash->success(__('This dish has been successfully added.'), 'success');
                $this->redirect(['action' => 'my_recipe']);
            } else {
                $errors = $this->_getValidationMessages($recipe->errors());
                $this->Flash->error($errors, 'error');
            }
        }

        $statuses = $this->Recipes->Statuses->find('list')->toArray();
        $categories = $this->Recipes->Categories->find('list')->toArray();
        $this->set(compact(['statuses','categories','recipe']));
    }

    /**
     * Details method
     *
     * @return void Redirects on successful detail page, renders view otherwise.
     */
    public function details($id){
        
        $recipeDetails = $this->Recipes->find()->where(['Recipes.id' => $id])->contain(['Categories','UploadImages','Comments' => ['Users' => ['fields' => ['Users.id','Users.email','Users.first_name','Users.last_name','Users.image']]]])->first();
                    
        $this->loadModel('ShoppingLists');
        $shoppingListData = $this->ShoppingLists->getCurrentShoppingList($this->Auth->user('created'));
        
        if(isset($recipeDetails) && !empty($recipeDetails)){
            $recipeDetails = $recipeDetails->toArray();
            
            $this->set('title', $recipeDetails['title']);

            $recipeList = $this->Recipes->find()->where(['Recipes.id !=' => $recipeDetails['id'], 'Recipes.status_id' => ACTIVE_STATUS,'Recipes.category_id' => $recipeDetails['category_id']])->contain(['UploadImages','Categories','Users'=>['fields' => ['first_name', 'last_name', 'username', 'email', 'id', 'image']]])->limit(5)->all();
            if(isset($recipeList) && !empty($recipeList))
                $recipeList = $recipeList->toArray();

            $this->set(compact(['recipeList','recipeDetails','shoppingListData']));
        }
    }

    /**
     * My recipes method
     *
     * @return void Redirects on my recipes.
     */
    public function my_recipe(){
        # code...
        $this->set('title', 'My Recipes');
        $userId = $this->Auth->user('id');
        $recipeList = $this->Recipes->find()->where(['Recipes.status_id' => ACTIVE_STATUS,'Recipes.user_id' => $userId])->contain(['UploadImages','Categories','Users'=>['fields' => ['first_name', 'last_name', 'username', 'email', 'id', 'image']]])->all();
        if(isset($recipeList) && !empty($recipeList))
            $recipeList = $recipeList->toArray();

        $this->set('recipeList', $recipeList);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
        $user = $this->Recipes->get($id);
        if ($this->Recipes->delete($user)) {
            $this->Flash->success(__('The recipe has been deleted'));
        } else {
            $this->Flash->error(__('The recipe could not be deleted. Please, try again'));
        }
        return $this->redirect(['action' => 'my_recipe']);
    }

    public function fetch_pages(){
        $this->viewBuilder()->layout('ajax');
        $item_per_page = 10;
         $dishData = $this->request->data;
        $page_number = filter_var($dishData["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
        $position = (($page_number-1) * $item_per_page);
        
        
        $recipeList = $this->Recipes->find('all', array('limit'=>$item_per_page, 'offset'=>$position))->where(['Recipes.status_id' => ACTIVE_STATUS])->contain(['UploadImages','Categories','Users'=>['fields' => ['first_name', 'last_name', 'username', 'email', 'id', 'image']]]);
        if(isset($recipeList) && !empty($recipeList))
            $recipeList = $recipeList->toArray();
            
            
            
            $this->set('recipeList',$recipeList);
             
            		
    }
}