<?php if ($basename == 'login') { ?>
<!-- BEGIN COPYRIGHT -->
<div class="copyright text-black">2015 &copy; Appsolutely Inc.</div>
<!-- END COPYRIGHT -->
<?php } elseif ($basename == '404') { ?>
<?php } elseif ($basename == '500') { ?>
<?php } else { ?>
</div>
<!-- END CONTAINER --> 
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="container">
		 2016 &copy; Gift Card Shop | Brought to you by: <a href="http://http://appsolutely.ph/" title="Get your own CMS for Loyalty Program Now!" target="_blank">Appsolutely Inc</a>
 
	</div>
</div>
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div> 
<!-- END FOOTER -->
<?php } ?>
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="assets/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="assets/js/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.cokie.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.uniform.min.js" type="text/javascript"></script>
<script src="assets/js/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/js/plugins/bootstrap-toastr/toastr.min.js"></script>
<script src="assets/js/jquery.idletimeout.js" type="text/javascript"></script>
<script src="assets/js/jquery.idletimer.js" type="text/javascript"></script>

<!-- BEGIN Date picker scripts -->
<script src="assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js" type="text/javascript"></script> 
<script src="assets/js/plugins/bootstrap-datepicker/daterangepicker.js" type="text/javascript"></script> 
<script src="assets/js/plugins/bootstrap-datepicker/moment.min.js" type="text/javascript"></script>
<script src="assets/js/components-pickers.js" type="text/javascript"></script>
 

<script src="assets/js/core/original/core.js" type="text/javascript"></script>

<script>
    jQuery(document).ready(function() {       
       ComponentsPickers.init();  
    });   
</script>

<?php if ($basename == 'login') { ?>
<script src="assets/js/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script src="assets/js/core/original/login.js" type="text/javascript"></script>


<?php
	} elseif ($basename == 'cards') {
?>  
<script src="assets/js/core/original/cards.js" type="text/javascript"></script>



<?php
	} elseif ($basename == 'card-detail') {
?> 
<script src="assets/js/owl.carousel.min.js" type="text/javascript"></script>
<script src="assets/js/core/original/card-detail.js" type="text/javascript"></script>

<?php
	} elseif ($basename == 'cart') {
?>  
<script src="assets/js/core/original/cart.js" type="text/javascript"></script>


<?php
	} elseif ($basename == 'checkout') {
?>  
<script src="assets/js/core/original/checkout.js" type="text/javascript"></script>


<?php
	} elseif ($basename == 'thankyou') {
?>  
<script src="assets/js/core/original/thankyou.js" type="text/javascript"></script>

<?php } ?>

<!-- END PAGE LEVEL SCRIPTS -->
<!-- <script src="assets/admin/pages/scripts/ui-toastr.js"></script> -->
 
<script src="assets/js/metronic.js" type="text/javascript"></script>
<script src="assets/js/layout.js" type="text/javascript"></script>
<script src="assets/js/plugins/admin/ui-idletimeout.js" type="text/javascript"></script>

<script type="text/javascript">
	var basename = "<?php echo $basename ?>";
</script>

<?php if (($basename == '404') || ($basename == '500')) { ?>
<script src="assets/js/core/error.js"></script>
<?php } ?>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>