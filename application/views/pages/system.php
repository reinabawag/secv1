<div class="container">
	<div class="page-header">
		<h1>Systems <small>American Wire Systems</small></h1>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Create System
				</div>
				<div class="panel-body">
					<?php echo form_open('user/add_system', ['id' => 'form-add-system']) ?>
						<div class="form-group">
							<label for="system" class="control-label">System Name</label>
							<?php echo form_input(['name' => 'system', 'class' => 'form-control', 'placeholder' => 'System Name', 'id' => 'system']) ?>
						</div>
						<button class="btn btn-primary">ADD</button>
					<?php echo form_close() ?>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Active Systems
				</div>
				<div class="panel-body">
					<table class="table" id="table-systems">
						<thead>
							<tr>
								<th>System ID</th>
								<th>Name</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('assets/js/jquery.form.min.js') ?>"></script>
<script>
	$(document).ready(function() {
		loadTable();

		$('#form-add-system').ajaxForm({
			dataType: 'json',
			beforeSubmit: function() {
				$('input[name="system"]').parent('div').removeClass('has-warning');
			},
			success: function(data) {
				if (data.status) {
					toastr.success(data.message);
					$('#table-systems tbody').append('<tr><td>'+$('input[name="system"]').val()+'</td></tr>');
					$('input[name="system"]').val('');
				} else if (data.error != '') {
					toastr.warning(data.error);
					$('input[name="system"]').parent('div').addClass('has-warning');
				} else {
					toastr.warning(data.message);
				}
			},
			error: function() {
				toastr.error('Error in adding system', 'Error');
			}
		});

		function loadTable() {
			$.ajax({
				url: '<?php echo site_url('user/get_systems') ?>',
				type: 'GET',
				dataType: 'json'
			}).done(function(data) {
				$.each(data, function(index, elem) {
					$('#table-systems tbody').append('<tr><td>'+elem.systemId+'</td><td>'+elem.name+'</td></tr>');
				});
			}).fail(function() {
				toastr.error('Error in fetching systems data', 'Error');
			});
		};
	})
</script>