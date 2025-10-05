<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"> Manage Clients </h3>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>Clients (5214)
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<button id="sample_editable_1_new" class="btn green">
									Add New User <i class="fa fa-plus"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
				<table class="table table-striped table-hover table-bordered" id="">
					<thead>
						<tr>
							<th><?php echo $this->Paginator->sort('id', 'User ID'); ?></th>
							<th><?php echo $this->Paginator->sort('first_name', 'First Name'); ?></th>
							<th><?php echo $this->Paginator->sort('last_name', 'Surname'); ?></th>
							<th><?php echo $this->Paginator->sort('dob', 'Date Of Birth'); ?></th>
							<th><?php echo $this->Paginator->sort('gender', 'Gender'); ?></th>
							<th><?php echo $this->Paginator->sort('email', 'E-Mail'); ?></th>
							<th><?php echo $this->Paginator->sort('mobile', 'Phone'); ?></th>
							<th><?php echo $this->Paginator->sort('address', 'Address'); ?></th>
							<th><?php echo $this->Paginator->sort('city', 'City'); ?></th>
							<th><?php echo $this->Paginator->sort('zipcode', 'Zip/Postcode'); ?></th>
							<th><?php echo $this->Paginator->sort('country', 'Country'); ?></th>
							<th><?php echo $this->Paginator->sort('city', 'City'); ?></th>
							<th><?php echo $this->Paginator->sort('package', 'Package'); ?></th>
							<th><?php echo $this->Paginator->sort('payments', 'Payments'); ?></th>
							<th><?php echo $this->Paginator->sort('Curr', 'Curr'); ?></th>
							<th><?php echo $this->Paginator->sort('created', 'User Since'); ?></th>
							<th><?php echo $this->Paginator->sort('last_logged_in', 'Last Logged In'); ?></th>
							<th> Profile </th>
							<th> Package </th>
							<th> Password </th>
							<th> Account </th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($users as $user)
							{
						?>
						<tr>
							<td> 154225 </td>
							<td> Joshua </td>
							<td> Michaels </td>
							<td> 14 Aug 1988 </td>
							<td> Male </td>
							<td> josh.michaels12@gmail.com </td>
							<td> 07774635241 </td>
							<td> 10 Turona Road
							Barnes </td>
							<td> London </td>
							<td> SW13 9FY </td>
							<td> UK </td>
							<td> Pro </td>
							<td> Monthly </td>
							<td> £ </td>
							<td> 32.99 </td>
							<td> 12 Dec 2014 </td>
							<td> 18:04 | 12 Mar 2015 </td>
							<td><a href="javascript:;" class="btn btn-sm purple"> <span class="glyphicon glyphicon-pencil"></span> Edit</a>
							<br>
							</br></td>
							<td><a href="pricing.html" class="btn btn-sm purple"> <span class="glyphicon glyphicon-pencil"></span> Change</a>
							<br>
							</br></td>
							<td><a href="#static" class="btn btn-sm purple" data-toggle="modal"> <span class="glyphicon glyphicon-link"></span> Reset</a>
							<br>
							</br></td>
							<td><a href="javascript:;" class="btn btn-sm purple"> <span class="glyphicon glyphicon-trash"></span> Cancel</a>
							<br>
							</br></td>
							</tr>
							<?php }?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<script>
	$('#sample_editable_1s').dataTable()
</script>