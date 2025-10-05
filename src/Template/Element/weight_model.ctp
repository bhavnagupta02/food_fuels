<!--popup-add-weight-->
<div class="sign-up popup">
    <div id="addWeight_popup">
        <!-- ...popup content... -->
        <h2>Add Weight</h2>
        
        <?= $this->Form->create('UserWeight', array('class' => 'validate_form form signup-form', 'id' => 'UserWeightForm', 'url' => array('controller' => 'users', 'action' => 'addweight')));
        $this->Form->templates([
            'label' => false
        ]);
        ?>
        <?= $this->Form->input('weight_date',array('id' => 'weight_date','class' => 'form-control', 'value' => Date('Y-m-d'), 'required', 'placeholder'=> 'Date','type' => 'text','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

        <?= $this->Form->input('weight',array('class' => 'form-control numeric', 'required', 'placeholder'=> 'Weight','type' => 'text','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

        <?= $this->Form->submit('Add',array('class' => 'sub-btn','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
        
        
        <?= $this->Form->end(); ?>
        <button class="my_popup_close addWeight_popup_close">Close</button>
    </div>
</div>
<!-- popup end-->