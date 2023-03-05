<link rel="stylesheet" href="<?= base_url() ?>assets/front-end/data/vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/front-end/data/vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // fun numerik no telp
    function check_int(evt) {
      var charCode = ( evt.which ) ? evt.which : event.keyCode;
      return ( charCode >= 48 && charCode <= 57 || charCode == 8 );
    };
</script>
    <!-- header -->
    <section class="section-slide">
        <div class="wrap-slick1">
            <div class="slick1">
                <?php $no=0; foreach($headerls->result() as $key) { ?>
                <div class="item-slick1 item<?= $no++;?>-slick1" style="background-image:url(<?= base_url('assets/images/header/'.$key->gambar) ?>)">
                    <div class="wrap-content-slide1 sizefull flex-col-c-m p-l-15 p-r-15 p-t-150 p-b-170">
                        <span class="caption1-slide1 txt1 t-center animated visible-false m-b-15" data-appear="fadeInDown">
                            Welcome to
                        </span>
                        <h5 class="caption2-slide1 tit1 t-center animated visible-false m-b-37" data-appear="fadeInUp">
                            <?= $ten->nama ?>
                        </h5>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="wrap-slick1-dots"></div>
        </div>
    </section>

    <section id="login" class="request request--services spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="request__form animated animatedFadeInLeft fadeInLeft">
                        <div class="section-title">
                            <span>Login</span>
                            <h2>Untuk Booking.!</h2>
                        </div>
                        <form class="wrap-form-reservation size22 m-l-r-auto" action="<?= site_url('home/login') ?>" method="POST" enctype= "multipart/form-data">
                            <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <span class="txt9">Email</span>
                                        <div class="size12 bo-rad-5 m-t-3 m-b-23">
                                            <input class="bo-rad-5 sizefull txt10 p-l-20" type="email" name="username" placeholder="Masukkan Email Anda" required />
                                        </div>
                                        <span class="txt9">Password</span>
                                        <div class="size12 bo-rad-5 m-t-3 m-b-23">
                                            <input class="bo-rad-5 sizefull txt10 p-l-20" type="text" name="password" placeholder="Masukan Password" maxlength="8" onkeypress='return check_int(event)' required />
                                        </div>
                                    </div>
                            </div>
                            <?= $this->session->flashdata('pesan'); ?>
                            <?= $this->session->flashdata('error'); ?>
                            <hr>
                            <div class="wrap-btn-booking flex-c-m m-t-10">
                                <button type="submit" class="btn3 flex-c-m sizefull p-3 txt11 trans-0-4">
                                Login!
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- DataTables -->
<script src="<?= base_url() ?>assets/front-end/data/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/front-end/data/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/front-end/data/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url() ?>assets/front-end/data/vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/front-end/data/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url() ?>assets/front-end/data/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url() ?>assets/front-end/data/vendors/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="<?= base_url() ?>assets/front-end/data/assets/js/init-scripts/data-table/datatables-init.js"></script>  