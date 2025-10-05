<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage Promotion Codes </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-edit"></i>Promotion Codes (<?php echo $promotion_codes ?>)
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <?php
                                    echo $this->Html->link(
                                        'Add New Promotion Code <i class="fa fa-plus"></i>', [
                                            'controller' => 'Cmspages', 'action' => 'add_promo', 'prefix' => 'backoffice'
                                        ], ['escape' => false, 'class' => 'btn green']
                                        );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover table-bordered" id="cmsList">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Discount Type</th>
                            <th>Valid From</th>
                            <th>Valid Till</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var edit_btn_html = '<a href="javascript:;" class="btn btn-sm purple"><span class="glyphicon glyphicon-pencil"></span> Edit</a>';
        var dtttable = $('#cmsList').DataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $this->Url->build(array('controller' => 'Cmspages', 'action' => 'ajaxPromo')); ?>",
            "columnDefs": [
                {"targets": -1,"data": null,"defaultContent": edit_btn_html},
            ],
            "aoColumns": [
              null,
              null,
              { "bSearchable": false },
              { "bSearchable": false },
              { 
                "render": function (val, type, row) {
                    return val == 1 ? "Fixed amount" : "Percentage";
                }
              },
              { "bSearchable": false },
              { "bSearchable": false },
              { "bSearchable": false },
            ]
        });
        var edit_url = '<?php echo $this->Url->build(array('controller' => 'Cmspages', 'action' => 'edit_promo'))?>';
        $('#cmsList tbody').on( 'click', 'a', function () {
            var data = dtttable.row( $(this).parents('tr') ).data();
            window.location = edit_url + '/' + data[0]; 
        });
    });
</script>