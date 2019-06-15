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
            <i class="fas fa-users"></i>
            Staffs</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Added on</th>
                  </tr>
                </thead>
                <tbody>
                    <?php foreach($staff as $row): ?>
                      <tr>
                        <td><?=$row->name?></td>
                        <td><?=$row->email?></td>
                        <th><?=$row->addedon?></th>
                      </tr>
                    <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>