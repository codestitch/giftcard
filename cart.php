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
				<label id="cardnamelabel" style="display: none"><?php echo $_GET['name']; ?></label>
				<i class="fa fa-circle"></i>
			</li>  
		</ul>

		<!-- BEGIN ROW-->
		<div class="row">
			<div class="col-md-12">

				<div class="portlet light" style="padding: 0 20px 15px 20px !important;"> 
					<div class="portlet-title">
						<div class="caption">
							<!-- <i class="icon-bar-chart font-green-haze"></i> -->
							<h1>Shopping Cart</h1>  
						</div>   
					</div>
					<div class="portlet-body"  ng-controller="MyController" ng-init="initCart()">    

						<div class="row" >


							<div id="content">
				            <div class="container">

				                <div class="row"> 


				                    <div class="col-md-9 clearfix" id="basket">

				                        <div class="cartbox">  

		                                <div class="table-responsive">
		                                    <table class="table">
		                                        <thead>
		                                            <tr>
		                                                <th>Card</th>
		                                                <th>Recipient</th>
		                                                <th>Quantity</th> 
		                                                <th>Unit Price</th> 
		                                                <th colspan="2">Total</th>
		                                            </tr>
		                                        </thead>
		                                        <tbody ng-repeat="item in data track by $index">
		                                            <tr id="{{ $index }}">
		                                                <td style="width: 200px;"> 
                                                        <img src="{{ item.image }}" class="cardimage">   
		                                                </td>
		                                                <td><b>{{ item.recipientemail }}</b>
		                                                </td>
		                                                <td>
		                                                    <input id="quantityField" data-ng-change="change()" type="number" ng-model="item.quantity" class="form-control" ng-pattern="/^[0-9]/" required>
		                                                </td> 
		                                                <td>{{ item.amount  | number }}</td> 
		                                                <td>{{ item.amount * item.quantity | number }}</td>
		                                                <td><a data-ng-click="Remove($index);"><i class="fa fa-trash-o"></i></a>
		                                                </td>
		                                            </tr> 
		                                        </tbody>
		                                       <!--  <tfoot>
		                                            <tr>
		                                                <th colspan="5">Total</th>
		                                                <th ng-if="!data.length" colspan="1">0</th>
		                                                <th ng-if="data.length" colspan="1">{{ data | mysum }}</th>
		                                            </tr>
		                                        </tfoot> -->
		                                    </table>

		                                </div> 

		                                <div class="box-footer">
		                                    <div class="pull-left">
		                                        <a href="cards.php" class="btn btn-default"><i class="fa fa-chevron-left"></i> Continue shopping</a>
		                                    </div> 
		                                    <div ng-if="!data.length" class="pull-right">  
		                                    </div>
		                                    <div ng-if="data.length" class="pull-right">  
		                                        <a data-ng-click="Checkout();" href="checkout.php?name=<?php echo $_GET['name']; ?>" class="btn goldenorange">Proceed to checkout <i class="fa fa-chevron-right"></i>
		                                        </a>
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
				                                            <th ng-if="data.length" style="text-align: right;">₱ {{ item.amount * item.quantity | number }}</th>
				                                        </tr>  
				                                        <tr ng-if="$last" class="total">
				                                            <td>Total</td>
				                                            <th ng-if="!data.length">₱ 0</th>
				                                            <th ng-if="data.length" style="text-align: right;">₱ {{ data | mysum }}</th>
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

<?php
	include_once('footer.php');
?>