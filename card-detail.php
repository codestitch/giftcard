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
		</ul>

		<!-- BEGIN ROW-->
		<div class="row"  ng-controller="MyController" ng-init="initCart()">
			<div class="col-md-12">

				<div class="portlet light"> 
					<div class="portlet-title">
						<div class="caption">
							<!-- <i class="icon-bar-chart font-green-haze"></i> -->
							<h1 id="cardnameLabel"> <?php echo ucfirst($_GET['name']); ?></h1>  
							<h4>Send a Lorem eGift Card by selecting your card and filling out the details.</h4>
						</div>   
						<div class="tools">
							<a href="cart.php?name=">
								<div class="fa-item col-md-12 col-sm-12 bg-goldenorange" >
									{{ totalrow }} | <i class="fa fa-shopping-cart"></i> View Cart </span>
								</div>  
							</a>
						</div>  
					</div>
					<div class="portlet-body">    

						<div class="row">

							<!-- Cards HERE -->
							<div class="col-md-12">  

								<div id="owl-demo" class="owl-carousel">  
								</div> 

							</div>   

						</div>  
						<div class="row">

							<!-- SELECTED IMAGE HERE -->
							<div class="col-md-6">   
								<div class="carddetails">
									<img class="imgctr" src="assets\img\cards\thankyou.png">   
								</div> 

							</div>  

							<!-- Dedication HERE -->
							<div class="col-md-6">  

								<div class="form-group form-md-line-input">
									<input type="text" class="form-control" id="form_control_1" placeholder="This is for"> 
									<span class="help-block">Type the name of the person here.</span>
								</div>
								<div class="form-group form-md-line-input">
									<textarea id="messageField" maxlength="150" class="form-control" rows="3" placeholder="Enter your message here."></textarea>
									<label for="form_control_1">Message:</label>
									<p class="help-block justify margin-top-10" style="margin-left: 14px;">
										<span class="label label-sm label-danger">Note:</span> You can only type up to 140 characters.
									</p> 
								</div>

							</div>   

						</div>  

					</div>
				</div>

				 

			</div>
		</div>
		<!-- END ROW -->  

		<div class="row rowptrn">

			<div class="col-md-2"></div>
			<div class="col-md-4">  

				<h4 id="amtTitle">Choose an Amount:</h4>
				
				<div id="choiceamtField" >
					<div class="btn-group" data-toggle="buttons" style="margin-bottom: 35px;">
						<label class="btn btn-default amtclr active">
						<input type="radio" name="amountField" value="500" class="toggle" checked="checked"/> ₱500 </label>
						<label class="btn btn-default amtclr">
						<input type="radio" name="amountField" value="1000" class="toggle" /> ₱1000 </label>
						<label class="btn btn-default amtclr">
						<input type="radio" name="amountField" value="1500" class="toggle" /> ₱1500 </label>
						<label class="btn btn-default amtclr">
						<input type="radio" name="amountField" value="2000" class="toggle" /> ₱2000 </label>
						<label class="btn btn-default amtclr">
						<input type="radio" name="amountField" value="2500" class="toggle" /> ₱2500 </label>
					</div>  
					<a id="amountOpt"> &nbsp;<label> or select desired amount</label></a>
				</div>

				<div id="useramtField" style="display: none;"> 
					<div class="form-group form-md-line-input has-warning col-md-12 " >
						<div class="input-group">
							<span class="input-group-addon">₱</span>
							<input id="desiredamtField" type="number" class="form-control">
							<span class="input-group-addon">.00</span> 
						</div>
					</div> 
					<a class="col-md-12" id="gotoselamt"><label>or Select Amount</label></a> 
				</div>
				<div class="clearfix margin-bottom-10"></div>  

			</div>
			<div class="col-md-4">  

				<h4 id="amtTitle">Specify Quantity:</h4>
				<div class="col-md-4" style="padding: 0;">
					<input id="quantityField" class="form-control" style="float: right !important;" type="number"  value="1" required>
				</div>
				
				
				<div class="clearfix margin-bottom-40"></div>  

			</div>

			<div class="col-md-2"></div>
			
		</div>

		<div class="row rowptrn">


			<div class="col-md-2"></div>
			<div class="col-md-8">

<!-- 				<div class="form-group form-md-line-input form-md-floating-label has-info">
					<select id="cardTypeField" class="form-control" id="form_control_1"> 
						<option value=""></option>
						<option value="virtual">Virtual Card</option>
						<option value="physical">Physical Card</option> 
					</select>
					<label for="form_control_1">Select Card Type</label>
				</div> -->

				<div class="clearfix" id="virtualField" >   

					<div class="form-group col-md-6">
						<label>Sender Name:</label>
						<div class="input-group">
							<input id="nameField" type="text" class="form-control" placeholder="Enter your name here" size="40"> 
						</div>
					</div> 

					<div class="form-group col-md-6">
						<label>Recipient email address:</label>
						<div class="input-group">
							<input id="toemailField" type="text" class="form-control" placeholder="Enter your receipient's email address here" size="40"> 
						</div>
					</div>
					<div class="form-group col-md-6">
						<label>Sender Email:</label>
						<div class="input-group">
							<input id="emailField" type="text" class="form-control" placeholder="Enter your email address here" size="40"> 
						</div>
					</div>

				</div>

<!-- 				<div class="clearfix" id="physicalField" style="display: none;">  
					<div class="form-group col-md-6">
						<label>Select Pick Up Branch</label>
						<select class="form-control">
							<option>Branch Station 1</option>
							<option>Branch Station 2</option>
							<option>Branch Station 3</option>
							<option>Branch Station 4</option>
							<option>Branch Station 5</option>
						</select>
					</div> 
					<div class="form-group col-md-6">
						<blockquote>
							<p>
								Hey! You can pick it up after 24 hours from purchase.	 
							</p>
						</blockquote> 
					</div> 
				</div> -->

				<div class="clearfix margin-bottom-40"></div> 
 
				<a id="addCartBtn" class="btn goldenorange">
					<i class="fa fa-shopping-cart"> </i> Add to Cart
				</a> 
				
			</div>
			<div class="col-md-2"></div>

		</div>

	</div>
</div>
<!-- END CONTENT -->

<?php
	include_once('footer.php');
?>