<div class="container">
	<div class="page-header">
		<h1 id="header-username"><?php echo $username ?> <small>Information</small></h1>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-success">
				<div class="panel-heading">User Information</div>
				<div class="panel-body">
					<div class="form-inline">
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" class="form-control" id="username" value="<?php echo $username ?>" placeholder="Username">
						</div>
						<button class="btn btn-success" id="btn-update-username">UPDATE</button>
					</div>
					<hr>
					<?php echo form_open('user/user_update/'.$username, ['id' => 'frm-user-update']) ?>
						<input type="hidden" name="username" id="h_username" value="<?php echo $username ?>">
						<?php echo form_hidden('userId', $user->userId) ?>
						<div class="form-group">
							<label for="">Last Name</label>
							<?php echo form_input(['name' => 'lastname', 'class' => 'form-control', 'placeholder' => 'Last Name'], $user->lastName) ?>
						</div>
						<div class="form-group">
							<label for="">First Name</label>
							<?php echo form_input(['name' => 'firstname', 'class' => 'form-control', 'placeholder' => 'First Name'], $user->firstName) ?>
						</div>
						<div class="form-group">
							<label for="">Middle Name</label>
							<?php echo form_input(['name' => 'middlename', 'class' => 'form-control', 'placeholder' => 'Middle Name'], $user->middleName) ?>
						</div>
						<div class="checkbox">
							<label for="admin">
								<?php echo form_checkbox(array('name' => 'admin', 'id' => 'admin'), TRUE ,boolval($user->is_admin) ? TRUE : FALSE) ?>ADMIN
							</label>&nbsp;
							<label for="supervisor">
								<?php echo form_checkbox(array('name' => 'supervisor', 'id' => 'supervisor'), TRUE ,boolval($user->is_supervisor) ? TRUE : FALSE) ?>SUPERVISOR
							</label>
						</div>
						<div class="form-group" id="loading" style="display: none;">
							<span>Loading please wait </span><?php echo img('assets/img/hourglass.gif', FALSE, array('width' => '24px')) ?>
						</div>
						
						<span id="span-msg"></span>
						<button class="btn btn-primary" id="btn-return"><span class="glyphicon glyphicon-arrow-left"></span> Back</button>
						<button class="btn btn-danger" id="btn-change-pass"><span class="glyphicon glyphicon-pencil"></span> Change Password</button>
						<button class="btn btn-success" id="save"><i class="glyphicon glyphicon-floppy-disk"></i> Save</button>
					
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-success">
				<div class="panel-heading">Systems</div>
				<div class="panel-body">
						<div class="form-group">
							<?php 
							$data = array();
							foreach ($system as $key => $value) {
								$data[$value->systemId] = $value->name;
							}
							echo form_multiselect('select-systems[]', $data, $selected, 'id="select-systems"');
							?>
						</div>
						<!-- <button class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button> -->
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-change-pass" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Change Password</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('user/update_password', ['id' => 'form-change-pass']) ?>
        	<input type="hidden" name="username" value="<?php echo $username ?>" readonly="true">
			<div class="form-group">
				<label for="password" class="control-label">Password</label>
				<?php echo form_password('password', null, ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password']) ?>
			</div>
			<div class="form-group">
				<label for="cpassword" class="control-label">ConfirmPassword</label>
				<?php echo form_password('cpassword', null, ['id' => 'cpassword', 'class' => 'form-control', 'placeholder' => 'Confirm Password']) ?>
			</div>
			<span id="msg"></span>
        <?php echo form_close() ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="pass-save">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="<?php echo base_url('assets/js/jquery.blockUI.js') ?>"></script>
<script>
	$(document).ready(function() {
		$('#btn-return').click(function(e) {
			e.preventDefault();
			$(location).attr('href', '<?php echo site_url('index'); ?>');
		});

		$('#select-systems').multiSelect({
			selectableHeader: '<p><strong>Systems</strong></p>',
  			selectionHeader: "<p><strong>Allowed</strong></p>",
		});
		
		$('#save').click(function(e) {
			e.preventDefault();

			$('#loading').show();
			$('#span-msg').empty();

			$.ajax({
				url: $('#frm-user-update').attr('action'),
				type: 'POST',
				data: $('#frm-user-update').serialize(),
				dataType: 'json'
			}).done(function(data) {
				$('#loading').hide();

				if (data.status == true) {
					toastr.success(data.msg, 'Success');
				} else if (data.status == false) {
					toastr.error(data.msg, 'Error');
				} else {
					$('#span-msg').html(data.error);
				}
			}).fail(function(data) {
				$('#loading').hide();
				toastr.error('Error in controller: user/user_update', 'Error');
			})
		});

		$('#modal-change-pass').on('hidden.bs.modal', function(e) {
			$('#msg').empty();
			$('#password').val('');
			$('#cpassword').val('');
		});

		$(document).on('click', '#btn-change-pass', function(e) {
			e.preventDefault();

			$('#modal-change-pass').modal('show');
		});

		$('#pass-save').click(function(e) {
			e.preventDefault();
			var form = $('#form-change-pass');
			$('#msg').empty();

			$('.modal').block({ css: { 
	            border: 'none', 
	            padding: '15px', 
	            backgroundColor: '#000', 
	            '-webkit-border-radius': '10px', 
	            '-moz-border-radius': '10px', 
	            opacity: .5, 
	            color: '#fff' 
	        } }); 
			 
	        $.post(form.attr('action'), form.serialize(), null, 'json')
	        .then(function(data) {
	        	$('.modal').unblock();
	        	if (data.val == false) {
	        		$('#msg').html(data.msg);
	        	} else if (data.status == true) {
	        		toastr.success(data.msg, 'Success');
	        		$('#modal-change-pass').modal('hide');
	        	} else {
	        		toastr.warning(data.msg, 'Failed');
	        	}
	        })
	        .fail(function() {
	        	toastr.error('Error in changing user password', 'Error');
	        	$('.modal').unblock();
	        });
		});

		$(':input').keyup(function(e) {
			e.preventDefault();

			if (e.keyCode == 13) {
				$('#pass-save').trigger('click');
			}
		});

		$('#btn-update-username').click(function(e) {
			if ($('#h_username').val() == $('#username').val()) {
				toastr.info('No need to update username', 'Matched username');
			} else {
				var username = $('#username').val();
				$.post('<?php echo site_url('user/updateUsername') ?>', {username: '<?php echo $username ?>', u_username: username}, null, 'json')
				.done(function(data) {
					if (data.status) {
						$('#h_username').val(username);
						$('#header-username').text(username);
						toastr.success(data.msg, 'Success');
					} else {
						toastr.error(data.msg, 'System error or network');
					}
				})
				.fail(function() {
					toastr.error('Cannot process request...', 'System error or network');
				});
			}
		});
	})
</script>