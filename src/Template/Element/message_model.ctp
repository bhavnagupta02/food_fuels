<!--popup-message-->
<div class="sign-up popup">
    <div id="message_popup">
        <!-- ...popup content... -->
        <h2>Send Message</h2>
        
        <?= $this->Form->create('Message', array('class' => 'validate_form form signup-form', 'id' => 'MessageForm', 'url' => array('controller' => 'messages', 'action' => 'send')));
        $this->Form->templates([
            'label' => false
        ]);

        $receiver_id = "";
        if(isset($userDetails->trainer->id))
            $receiver_id = $userDetails->trainer->id;
        
        $receiver_name = "";
        if(isset($userDetails->trainer->first_name))
            $receiver_name = $userDetails->trainer->first_name.' '.$userDetails->trainer->last_name;
        ?>

        <?= $this->Form->hidden('receiver_id',['value' => $receiver_id]); ?>
        <?= $this->Form->input('send_to',array('id' => 'send_to','class' => 'form-control', 'value' => $receiver_name, 'required','readonly', 'type' => 'text','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

        <?= $this->Form->textarea('message',array('class' => 'form-control', 'style' => ['height:125px'], 'required', 'placeholder'=> 'Add text here','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>

        <?= $this->Form->submit('Send',array('class' => 'sub-btn','templates' => ['inputContainer' => '<div class="form-row">{{content}}</div>'])); ?>
        
        
        <?= $this->Form->end(); ?>
        <button class="my_popup_close message_popup_close">Close</button>
    </div>
</div>
<!-- popup end-->