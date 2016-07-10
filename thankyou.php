<?php
	include_once('header.php');
?>

<!-- BEGIN CONTENT -->
<div class="page-content" ng-controller="MyController" ng-init="initCart()">
	<div class="container-fluid">  
		<!-- BEGIN ROW-->
		<div class="row">
			<div class="col-md-12">

				<section>

                <div class="heading centerstaged">
                    <h1 class="grow-80">Thank You</h1>
                </div> 
					<div class="centerstaged">
						 <h3>We are processing your order and will send you an email confirmation shortly</h3>
					</div>
            </section> 
			</div>
		</div>
		<!-- END ROW -->  
		<!-- BEGIN ROW-->
		<div class="row">
			<div class="col-md-12">


				<div id="content">
	            <div class="container">
 
            		<div  class="pull-left">
	            		<h4>Item Summary:</h4>
	            	</div>
	            	<div class="pull-right">
                      <h4>Order Number: <b>#0932222</b></h4>
                  </div>  

						<div class="clearfix margin-bottom-10"></div>  
       				<div class="summarybox">  

		                 <div class="table-responsive">
		                     <table class="table summarytable"> 
		                         <tbody ng-repeat="item in data track by $index">
		                             <tr id="{{ $index }}">
		                             		<td style="width: 50px;"></td>
		                                 <td style="width: 200px;"> 
		                                   <img src="{{ item.image }}" class="cardimage">  
		                                   <div class="centerstaged">
		                                   		<h5><b>{{ item.cardname }}</b></h5> 
														</div>
		                                 </td>
		                                 <td>
		                                 	<p><b>Gift Card Details:</b></p>
		                                 	<br/>
		                                 	<p>To: <b>{{ item.recipientemail }}</b></p>
		                                 	<p>From: <b>{{ item.senderemail }}</b></p>
		                                 	<p>Message: <b>{{ item.message }}</b></p>
		                                 </td>  
		                                 <td><b>₱ {{ item.amount * item.quantity | number }}</b></td> 
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
		            </div>
		            <!-- /.box -->

		             <div class="centerstaged">  
	                      <a  id="returnBtn" class="btn goldenorange"> Home </i> </a>
	                  </div>
						<div class="clearfix margin-bottom-30"></div>  

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