<div class="container-fluid">

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?=site_url('admin')?>">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Staffs</li>
  </ol>


  <!-- DataTables Example -->
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-user-plus"></i>
    Add New User</div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-3">&nbsp;</div>
        <div class="col-sm-6">
          <form method="post" action="">
            <?=getFlash('response')?>
            <div class="form-group">
              <label>Staff Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Staff Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block">Add Staff</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>