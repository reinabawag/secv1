<div class="container">
  <button class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-plus"></i> ADD USER</button>
  <br><br>
  <table id="table-users" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th>Username</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Link</th>
        </tr>
    </thead>
  </table>
</div> <!-- /container -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ADD USER</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('user/insert', ['id' => 'form-add-user']) ?>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Username">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
          </div>
          <div class="form-group">
            <label for="cpassword">Confirm Password</label>
            <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password">
          </div>
          <span id="form-message"></span>
        <?php echo form_close() ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-user">Save changes</button >
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets/js/jquery.form.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/datatables.min.js') ?>"></script>
<script>
  $(document).ready(function() {
    $('#save-user').click(function(e) {
      $('#form-add-user').ajaxSubmit({
        dataType: 'json',
        beforeSubmit: function() {
          $('#form-message').empty();
        },
        success: function(data) {
          if (data.error != '') {
            $('#form-message').html(data.error);
          }

          if (data.status) {
            toastr.success(data.msg);
            $('#myModal').modal('hide');
            window.location = '<?php echo site_url('view/') ?>'+$('#username').val();
            $('#form-add-user :input').val('');
            table.ajax.reload();
          } 
        },
        error: function() {
          toastr.error('Error in adding new user', 'Error');
        }
      })
    });

    $('#myModal').on('hidden.bs.modal', function(e) {
      $('#form-add-user :input').val('');
      $('#form-message').empty();
    });

    var table = $('#table-users').DataTable({
      serverSide: true,
      ajax: {
        url: '<?php echo site_url('user/get_users') ?>',
        type: 'POST',
      }
    });
  })
</script>