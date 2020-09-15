<div class="container">
	<div class="page-header">
		<h1>Password Reset <small>Updates lost or forgotten password</small></h1>
	</div>
	<?php echo form_open('user/get_info', ['class' => 'form-inline']) ?>
		<div class="form-group">
			<label for="username" class="control-label">Username</label>
			<?php echo form_input('username', set_value('username', ''), ['class' => 'form-control', 'placeholder' => 'Please input username', 'id' => 'username']) ?>
		</div>
		<button class="btn btn-default">Get Info</button>
	<?php echo form_close() ?>
	<?php echo form_input('') ?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('form').submit(function(e) {
			e.preventDefault();
			var form = $(this);
			var username = $('#username');

			username.parent('div').removeClass('has-error');

			if (username.val() == '') {
				toastr.warning('Please input username first', 'Username');
				username.parent('div').addClass('has-error');
				username.focus();
				return;
			}

			$.post(form.attr('action'), form.serialize(), null, 'json')
			.done(function(data) {
				if (data == null) {
					toastr.info('Username doesn\'t exist', 'Username');
				} else {
					
				}
			})
			.fail(function(data) {
				toastr.error('Error in fetching user information', 'Error');
			})
		});
	});
</script>