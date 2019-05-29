<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>KPU Kutamandiri | Registrasi</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/web_admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/web_admin/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/web_admin/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/web_admin/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/web_admin/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="<?php echo base_url('admin') ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>KPU</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>KPU </b>Kutamandiri</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php foreach ($avatar as $a) { ?>
                  <img src="<?php echo base_url('assets/upload/user/img/' . $a->nama_file) ?>" class="user-image" alt="User Image">
                <?php } ?>
                <span class="hidden-xs"><?= $this->session->userdata('name') ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <?php foreach ($avatar as $a) { ?>
                    <img src="<?php echo base_url('assets/upload/user/img/' . $a->nama_file) ?>" class="img-circle" alt="User Image">
                  <?php } ?>
                  <p>
                    <?= $this->session->userdata('name') ?> - Web Developer
                    <small>Last Login : <?= $this->session->userdata('last_login') ?></small>
                  </p>
                </li>
                <!-- Menu Body -->

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="<?= base_url('admin/profile') ?>" class="btn btn-default btn-flat"><i class="fa fa-cogs" aria-hidden="true"></i> Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="<?= base_url('admin/sigout') ?>" class="btn btn-default btn-flat"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->

    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <?php foreach ($avatar as $a) { ?>
              <img src="<?php echo base_url('assets/upload/user/img/' . $a->nama_file) ?>" class="img-circle" alt="User Image">
            <?php } ?>
          </div>
          <div class="pull-left info">
            <p><?= $this->session->userdata('name') ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- search form -->

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">MAIN NAVIGATION</li>
          <li>
            <a href="<?= base_url('admin') ?>">
              <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              <span class="pull-right-container">
                <!-- <i class="fa fa-angle-left pull-right"></i> -->
              </span>
            </a>
            <!-- <ul class="treeview-menu">
            <li><a href="<?php echo base_url() ?>assets/web_admin/index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="<?php echo base_url('admin') ?>"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul> -->
          </li>

          <li class="">
            <a href="<?php echo base_url('admin/pemilih') ?>">
              <i class="fa fa-fw fa-users" aria-hidden="true"></i> <span>Data Pemilih</span></a>
          </li>
           </li>
         <li class="">
            <a href="<?php echo base_url('admin/calon') ?>">
              <i class="fa fa-fw fa-users" aria-hidden="true"></i> <span>Data Calon</span></a>
          </li>
          <li class="">
            <a href="<?php echo base_url('admin/dapil') ?>">
              <i class="fa fa-fw fa-users" aria-hidden="true"></i> <span>Daerah Pemilihan</span></a>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-edit"></i> <span>Forms</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?= base_url('admin/form_barangmasuk') ?>"><i class="fa fa-circle-o"></i> Tambah Data Barang Masuk</a></li>
              <li><a href="<?= base_url('admin/form_satuan') ?>"><i class="fa fa-circle-o"></i> Tambah Satuan Barang</a></li>
              <li class=""><a href="<?= base_url('admin/form_barangproduksi') ?>"><i class="fa fa-circle-o"></i> Tambah Barang Produksi</a></li>
            </ul>
          </li>
          <li class="treeview ">
            <a href="#">
              <i class="fa fa-table"></i> <span>Tables</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?= base_url('admin/tabel_barangmasuk') ?>"><i class="fa fa-circle-o"></i> Tabel Barang Masuk</a></li>
              <li><a href="<?= base_url('admin/tabel_barangkeluar') ?>"><i class="fa fa-circle-o"></i> Tabel Barang Keluar</a></li>
              <li><a href="<?= base_url('admin/tabel_satuan') ?>"><i class="fa fa-circle-o"></i> Tabel Satuan</a></li>
              <li><a href="<?= base_url('admin/tabel_jenisbarang')?>"><i class="fa fa-circle-o"></i> Tabel Jenis Barang</a></li>
            </ul>
          </li>


          <li class="header">LABELS</li>
          <li>
            <a href="<?php echo base_url('admin/profile') ?>">
              <i class="fa fa-cogs" aria-hidden="true"></i> <span>Profile</span></a>
          </li>
          <li class="active">
            <a href="<?php echo base_url('admin/users') ?>">
              <i class="fa fa-fw fa-users" aria-hidden="true"></i> <span>Registrasi Pemilih</span></a>
          </li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Registrasi Pemilih
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Registrasi Pemilih</a></li>
          <li class="active">Registrasi</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <div class="container">
              <!-- general form elements -->
              <div class="box box-primary" style="width:94%;">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-fw fa-user" aria-hidden="true"></i> Registrasi Pemilih</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="container">
                  <form action="<?= base_url('admin/proses_tambah_user') ?>" role="form" method="post">

                    <?php if ($this->session->flashdata('msg_berhasil')) { ?>
                      <div class="alert alert-success alert-dismissible" style="width:91%">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success!</strong><br> <?php echo $this->session->flashdata('msg_berhasil'); ?>
                      </div>
                    <?php } ?>

                    <?php if (validation_errors()) { ?>
                      <div class="alert alert-warning alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Warning!</strong><br> <?php echo validation_errors(); ?>
                      </div>
                    <?php } ?>

                    <div class="box-body">
                      <div class="form-group" style="display:block;">
                        <label for="username" style="width:87%;margin-left: 0px;">NIK</label>
                        <input type="text" name="username" style="width: 30%;margin-right: 67px;margin-left: 0px;" class="form-control" id="username" placeholder="NIK">
                      </div>
                      <div class="form-group" style="display:block;">
                        <label for="nama" style="width:87%;margin-left: 0px;">Nama</label>
                        <input type="text" name="nama" style="width: 30%;margin-right: 67px;margin-left: 0px;" class="form-control" id="nama" placeholder="Nama">
                      </div>
                      <div class="form-group" style="display:block;">
                        <label for="password" style="width:73%;">Password</label>
                        <input type="password" name="password" style="width:30%;margin-right: 67px;" class="form-control" id="password" placeholder="Password">
                      </div>
                      <div class="form-group" style="display:block;">
                        <label for="confirm_password" style="width:73%;">Confirm Password</label>
                        <input type="password" name="confirm_password" style="width:30%;margin-right: 67px;" class="form-control" id="confirm_password" placeholder="Confirm Password">
                      </div>
                      <div class="form-group" style="display:block;">
                        <label for="role" style="width:73%;">Role</label>
                        <select class="form-control" name="role" style="width:11%;margin-right: 18px;">
                          <option value="0" selected=""></option>
                          <option value="0">User Biasa</option>
                          <option value="1">User Admin</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="dapil" style="width:73%;">Dapil</label>
                        <select class="form-control" name="dapil" style="width:30%;margin-right: 18px;">
                              <option value="" selected="">-- Pilih --</option>
                              <?php foreach($list_dapil as $s){ ?>
                              <option value="<?=$s->detail_dapil?>"><?=$s->detail_dapil?></option>
                              <?php } ?>
                            </select>
                    </div>
                      <!-- /.box-body -->
                      <?php if (isset($token_generate)) { ?>
                        <input type="hidden" name="token" class="form-control" value="<?= $token_generate ?>">
                      <?php } else {
                      redirect(base_url('admin/form_user'));
                    } ?>

                      <div class="box-footer" style="width:93%;padding-left:0px;">
                        <button type="submit" style="width:20%" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Submit</button>
                      </div>
                  </form>
                </div>
              </div>
              <!-- /.box -->

              <!-- Form Element sizes -->

              <!-- /.box -->


              <!-- /.box -->

              <!-- Input addon -->

              <!-- /.box -->

            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <!-- <div class="col-md-6">
          <!-- Horizontal Form -->

            <!-- /.box -->
            <!-- general form elements disabled -->

            <!-- /.box -->

          </div>
        </div>
        <!--/.col (right) -->
    </div>
    <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; <?= date('Y') ?> Kelompok 3 Sistem Terdistribusi </strong>

  </footer>

  <!-- jQuery 3 -->
  <script src="<?php echo base_url() ?>assets/web_admin/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="<?php echo base_url() ?>assets/web_admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="<?php echo base_url() ?>assets/web_admin/bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url() ?>assets/web_admin/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?php echo base_url() ?>assets/web_admin/dist/js/demo.js"></script>
</body>

</html>