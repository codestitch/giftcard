<?php
	include_once('header.php');
?>
	<!-- BEGIN CONTENT -->
<div class="page-content">
	<div class="container-fluid"> 

		<!-- BEGIN ROW-->
		<div class="row" ng-controller="MyController" ng-init="initCart()">
			<div class="col-md-12">

				<div class="portlet light"> 
					<div class="portlet-title">
						<div class="caption">
							<!-- <i class="icon-bar-chart font-green-haze"></i> -->
							<h1 > Buy a Lorem Gift Card</h1> 
							<h4>The perfect way to treat a friend or treat yourself. A Lorem Card is always welcome.</h4>
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

							<!-- <div class="clearfix margin-bottom-40"></div>  -->
							<!-- Cards HERE -->
							<div class="col-md-3"> 
								<a href="card-detail.php?name=anytime">
									<div class="cardgroup"> 
											<img class="cardimage" src="assets\img\cards\anytime.png">  
										<div class="cardtitle">
											<h4 class="cardtitleinner">Anytime</h4>  
										</div>
									</div> 
								</a>
							</div>  

							<div class="col-md-3"> 
								<a href="card-detail.php?name=baby">
									<div class="cardgroup"> 
											<img class="cardimage" src="assets\img\cards\baby.png">  
										<div class="cardtitle">
											<h4 class="cardtitleinner">Baby</h4>  
										</div>
									</div>
								</a>
							</div>  
 

							<div class="col-md-3"> 
								<a href="card-detail.php?name=friendship">
									<div class="cardgroup"> 
											<img class="cardimage" src="assets\img\cards\friendship.png">  
										<div class="cardtitle">
											<h4 class="cardtitleinner">Friendship</h4>  
										</div>
									</div>
								</a>
							</div>  

							<div class="col-md-3"> 
								<a href="card-detail.php?name=Sensei">
									<div class="cardgroup"> 
											<img class="cardimage" src="assets\img\cards\Sensei.png">  
										<div class="cardtitle">
											<h4 class="cardtitleinner">Sensei</h4>  
										</div>
									</div>
								</a>
							</div>  



							<div class="col-md-3"> 
								<a href="card-detail.php?name=bday">
									<div class="cardgroup"> 
											<img class="cardimage" src="assets\img\cards\bday.png">  
										<div class="cardtitle">
											<h4 class="cardtitleinner">Birthday</h4>  
										</div>
									</div>
								</a>
							</div> 

							<div class="col-md-3"> 
								<a href="card-detail.php?name=love">
									<div class="cardgroup"> 
											<img class="cardimage" src="assets\img\cards\love.png">  
										<div class="cardtitle">
											<h4 class="cardtitleinner">Love</h4>  
										</div>
									</div>
								</a>
							</div> 

							<div class="col-md-3"> 
								<a href="card-detail.php?name=summer">
									<div class="cardgroup"> 
											<img class="cardimage" src="assets\img\cards\summer.png">  
										<div class="cardtitle">
											<h4 class="cardtitleinner">Summer</h4>  
										</div>
									</div>
								</a>
							</div> 

							<div class="col-md-3"> 
								<a href="card-detail.php?name=favcoffee">
									<div class="cardgroup"> 
											<img class="cardimage" src="assets\img\cards\favcoffee.png">  
										<div class="cardtitle">
											<h4 class="cardtitleinner">Favcoffee</h4>  
										</div>
									</div>
								</a>
							</div> 
  

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