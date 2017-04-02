<script src="<?php echo base_url('public/assets/global/plugins/jquery-migrate-1.2.1.min.js') ?>" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="<?php echo base_url('public/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/jquery.blockui.min.js') ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url() ?>public/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>public/assets/global/plugins/datatables/plugins/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>public/assets/global/plugins/datatables-yadcf/jquery.dataTables.yadcf.js"></script>
<script src="<?php echo base_url('public/assets/global/plugins/jquery.cokie.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/uniform/jquery.uniform.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/jquery-validation/js/additional-methods.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/select2/select2.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/parsley/parsley.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/parsley/parsley.remote.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/bootbox/bootbox.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/typeahead/handlebars.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/typeahead/typeahead.bundle.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/ion.rangeslider/js/ion-rangeSlider/ion.rangeSlider.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/slider-pips/jquery-ui-slider-pips.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/slider-pips/jquery.blockUI.js') ?>"></script>
<script src="<?php echo base_url('public/assets/global/plugins/bootstrap-summernote/summernote.min.js" type="text/javascript') ?>"></script>

<!-- END PAGE LEVEL PLUGINS -->

<script src="<?php echo base_url('public/assets/global/scripts/metronic.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/admin/layout/scripts/layout.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/admin/pages/scripts/component-picker.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/admin/pages/scripts/component-form-tool.js') ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/admin/includes/js/moment.js') ?>"></script>
<script  type="text/javascript" src="<?php echo base_url('public/assets/admin/includes/js/datetime-moment.js') ?>"></script>
<script>
function confirmLink(m,u) {
	if ( confirm(m) ) {
		window.location = u;
	}
}
    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        ComponentsPickers.init();
        ComponentsFormTools.init();
        //ComponentsIonSliders.init();
        $('.summernote').summernote({height: 300});
    });
    $(document).ready(function(){
    	$("#selLimit").on("change",function(){
    		<?php
    			if(isset($sort_by) && isset($order)){
    			$sort_parameter['sort_by'] = $sort_by;
                $sort_parameter['page'] = 1;
                $sort_parameter['order'] = $order;
    		?>
    			window.location.href = "<?php echo $base_url_pagination?>&limit="+$(this).val();
    		<?php
    			}
    		?>
    	});
    });
  </script>
