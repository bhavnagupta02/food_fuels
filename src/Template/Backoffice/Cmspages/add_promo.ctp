<!-- BEGIN PAGE CONTENT-->
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i><?php echo ($this->request['action']=='promo_add')?__("Add Promocode"):__("Edit Promocode"); ?>
        </div>
    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <?=
        $this->Form->create($promotion_codes,['class' => 'form-horizontal form_met_validate', 'enctype' => 'multipart/form-data']);
        echo $this->Form->hidden('id');

        $this->Form->templates([
            'label' => false
        ]);
        ?>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            You have some form errors. Please check below.
        </div>
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Promo Code</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-document"></i> </span>
                            <?php
                                echo $this->Form->input(
                                    'title', array(
                                        'class' => 'form-control',
                                        'required'
                                    )
                                );
                                echo $this->Html->link(__('Generate Code'),'javascript:void(0);',['class'=>'generateCode']);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Description</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-document"></i> </span>
                            <?php
                                echo $this->Form->input(
                                    'description', array(
                                        'class' => 'form-control',
                                        'type'  =>  'textarea',
                                        'required'
                                    )
                                );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Discount Type</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-document"></i> </span>
                            <?php echo $this->Form->input('discount_type', array('class' => 'form-control', 'options' =>  [   1   =>  __('Fixed amount'), 2   =>  __('Percentage')], 'default'    =>  1, 'required')); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Uses Type</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-document"></i> </span>
                            <?php echo $this->Form->input('uses_type', array('class' => 'form-control', 'options' =>  [   1   =>  __('Single Use'), 2   =>  __('Multiple Use')], 'default'    =>  1, 'required')); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Amount</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-document"></i> </span>
                            <?php
                                echo $this->Form->input(
                                    'amount', array(
                                        'class' => 'form-control numeric',
                                        'max'=>10000,
                                        'required'
                                    )
                                );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Valid From</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-document"></i> </span>
                            <?php
                                echo $this->Form->input(
                                    'valid_from', array(
                                        'class' => 'form-control datepicker',
                                        'type'  =>  'text',
                                        'required'
                                    )
                                );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Valid Till</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon"> <i class="fa fa-document"></i> </span>
                            <?php
                                echo $this->Form->input(
                                    'valid_till', array(
                                        'class' => 'form-control datepicker',
                                        'type'  =>  'text',
                                        'required'
                                    )
                                );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Status</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <?php echo $this->Form->input('status_id', array('class' => 'form-control','options' =>  [   1   =>  __('Active'),   2   =>  __('Inactive')])); ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
        </form>
        <!-- END FORM-->
    </div>
</div>
<script>
    $(document).ready(function (){
        $('input[name="discount_type"]').change(function(){
            if($(this).val()==1){
                $('#amount').attr('max',10000);
            }
            else{
                $('#amount').attr('max',100);   
            }
        });

        $('.generateCode').click(function(){
            var charSet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var randomString = '';
            for (var i = 0; i < 7; i++) {
                var randomPoz = Math.floor(Math.random() * charSet.length);
                randomString += charSet.substring(randomPoz,randomPoz+1);
            }
            $('#title').val(randomString);
        });

        $("#valid-from").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date(),
            onSelect: function(selected) {
                $("#valid-till").datepicker("option","minDate", selected)
            }
        });

        $("#valid-till").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date(),
            onSelect: function(selected) {
                $("#valid-from").datepicker("option","maxDate", selected)
            }
        });
    });
</script>