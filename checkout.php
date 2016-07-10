<?php
	include_once('header.php');
?>

<!-- BEGIN CONTENT -->
<div class="page-content">
	<div class="container-fluid"> 

		<ul class="page-breadcrumb breadcrumb">
			<li>
				<a href="cards.php">Home</a>
				<i class="fa fa-circle"></i>
			</li>
			<li>
				<a href="card-detail.php?name=<?php echo $_GET['name']; ?>">Card Details - <?php echo $_GET['name']; ?></a>
				<i class="fa fa-circle"></i>
			</li> 
			<li>
				<a href="cart.php?name=<?php echo $_GET['name']; ?>">Shopping Cart </a>
				<i class="fa fa-circle"></i>
			</li>  
		</ul>

		<!-- BEGIN ROW-->
		<div class="row">
			<div class="col-md-12">

				<div class="portlet light"> 
					<div class="portlet-title">
						<div class="caption">
							<!-- <i class="icon-bar-chart font-green-haze"></i> -->
							<h1>Checkout</h1> 
							<h4>The perfect way to treat a friend or treat yourself. A Lorem Card is always welcome.</h4>
						</div>   
					</div>
					<div class="portlet-body" ng-controller="MyController" ng-init="initCart()">    

						<div class="row">


							<div id="content">
				            <div class="container">

				                <div class="row"> 

			                    	<div class="col-md-1" >  </div>

			                    	<div class="col-md-7 clearfix" id="basket"> 

			                    		<div class="cartbox">  


	                                	<div class="table-responsive">

				                    			<div class="col-md-12">
						                        <p class="text-muted lead">Billing Details.</p>
						                     </div>

				                        	<div class="form-group">
														<label class="col-md-3 control-label">First Name</label>
														<div class="col-md-9">
															<input type="text" class="form-control" placeholder="Enter First Name"> 
															<span class="help-block"> </span>
														</div>
													</div>
				                        	<div class="form-group">
														<label class="col-md-3 control-label">Last Name</label>
														<div class="col-md-9">
															<input type="text" class="form-control" placeholder="Enter Last Name"> 
															<span class="help-block"> </span>
														</div>
													</div>
				                        	<div class="form-group">
														<label class="col-md-3 control-label">City</label>
														<div class="col-md-9">
															<input type="text" class="form-control" placeholder="Enter City"> 
															<span class="help-block"> </span>
														</div>
													</div>
				                        	<div class="form-group">
														<label class="col-md-3 control-label">Address</label>
														<div class="col-md-9">
															<input type="text" class="form-control" placeholder="Enter Address"> 
															<span class="help-block"> </span>
														</div>
													</div>
				                        	<div class="form-group">
														<label class="col-md-3 control-label">Telephone</label>
														<div class="col-md-9">
															<input type="text" class="form-control" placeholder="Enter Telephone"> 
															<span class="help-block"> </span>
														</div>
													</div>  
				                        	<div class="form-group">
														<label class="col-md-3 control-label">Email</label>
														<div class="col-md-9">
															<input id="emailField" ng-model="data[0].senderemail" type="text" class="form-control" placeholder="Enter Email"> 
															<span class="help-block"> </span>
														</div>
													</div>    
 

												</div>


	                                <div class="box-footer">
	                                    <div class="pull-left">
	                                        <a href="cart.php?name=<?php echo $_GET['name']; ?>" class="btn btn-default"><i class="fa fa-chevron-left"></i> Return to Cart</a>
	                                    </div>
	                                    <div class="pull-right"> 
	                                        <a id="orderBtn" data-toggle="modal" href="#basic" class="btn goldenorange">Place Order <i class="fa fa-chevron-right"></i>
	                                        </a>
	                                        <!-- <a class="btn default" data-toggle="modal" href="#basic"> View Demo </a> -->
	                                    </div>
	                                </div>


			                        </div>
			                        <!-- /.box --> 

			                    	</div>
			                    <!-- /.col-md-9 -->

			                    <div class="col-md-3">
			                        <div class="cartbox" id="order-summary">
			                            <div class="box-header">
			                                <h3>Order summary</h3>
			                            </div> 

			                            <div ng-if="data.length" class="table-responsive">
			                                <table class="table">
			                                    <tbody id="summarytable" ng-repeat="item in data track by $index">
			                                        <tr>
			                                            <td ng-if="!data.length"></td> 
			                                            <td ng-if="data.length">({{ item.quantity }}) {{ item.cardname }}</td> 

			                                            <th ng-if="!data.length">₱ 0</th>
			                                            <th ng-if="data.length">₱ {{ item.amount * item.quantity | number }}</th>
			                                        </tr>  
			                                        <tr ng-if="$last" class="total">
			                                            <td>Total</td>
			                                            <th ng-if="!data.length">₱ 0</th>
			                                            <th ng-if="data.length">₱ {{ data | mysum }}</th>
			                                        </tr>
			                                    </tbody>
			                                </table>
			                            </div>

			                             <div ng-if="!data.length" class="table-responsive">
			                                <table class="table">
			                                    <tbody> 
			                                        <tr class="total">
			                                            <td>Total</td>
			                                            <th>₱ 0</th>
			                                        </tr>
			                                    </tbody>
			                                </table>
		                           	 </div>

			                        </div> 

			                    </div>
			                    <!-- /.col-md-3 -->

				                </div>

				            </div>
				            <!-- /.container -->
				        </div>
				        <!-- /#content -->
 
  

						</div> 

					</div>
				</div>

				 

			</div>
		</div>
		<!-- END ROW -->  

	</div>
</div>
<!-- END CONTENT -->



<!-- modal -->
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">You've Done Your Purchase</h4>
			</div>
			<div class="modal-body">
				 Hey! Thank you for purchasing! You can check your email to view order details.
			</div>
			<div class="modal-footer"> 
				<a href="cards.php" class="btn goldenorange">Alright!</a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php
	include_once('footer.php');
?>