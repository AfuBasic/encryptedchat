
<style type="text/css">
  .list-group a {
    color: #888;
  }
</style>
<div class="container-fluid">

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?=site_url('admin')?>">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Staffs</li>

    <li class="pull-right" style="margin-left: 40px;">Hello, <?=user()->name?></li>
  </ol>


  <!-- DataTables Example -->
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-list"></i>
    Conversations</div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-12">
          <?php if(empty($conversations)): ?>
            <div class="alert alert-danger">No Conversations</div>
            <?php else: ?>
              <ul class="list-group">
                <?php foreach($conversations as $row): ?>
                  <a href="<?=site_url('home/chat/'.$row->rec_id)?>">
                    <li class="list-group-item">
                      <?=$row->name?>
                    </li>
                  </a>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

  </div>