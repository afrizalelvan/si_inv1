

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Transaksi </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active" ><a href="#"><?= $judul ?></a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $judul ?></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="card-body">
          <form role="form" method="post" id="myForm">
            <input type="hidden" name="jenis" value="adjusment">
            <input type="hidden" name="status" value="insert">
          <div class="row">
            <div class="col-md-6">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Adjustment Stok</h3>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="display: block;">
                <table width="100%">
                  
                  <tr>
                    <td width="30%">Tipe</td>
                    <td width="2%">:</td>
                    <td>
                      <select class="form-control" id="tipe" name="tipe" onchange="hitung()">
                        <option value="">Pilih</option>
                        <option value="Minus">Minus</option>
                        <option value="Plus">Plus</option>
                      </select>
                    </td>
                  </tr>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

         
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-outline card-success">
              <div class="card-header">
                <table width="50%">
                  <tr>
                    <td width="30%">Pilih Produk</td>
                    <td width="2%">:</td>
                    <td>
                      <select class="selectpicker form-control" multiple data-live-search="true" id="produk" name="produk" data-selected-text-format="count > 3">
                        
                      </select>
                    </td>
                    <td>
                      <button type="button" class="btn btn-block btn-default btn-xs" onclick="addProduk()">
                        Tambahkan
                      </button>
                    </td>
                    <td>
                      <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                      </button>
                    </td>
                  </tr>
                </table>

                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="height: 200px;overflow-y: scroll;">
                <table class="table table-striped" width="100%" id="table-produk">
                  <thead>
                    <tr>
                      <th width="10%">#</th>
                      <th width="10%">ID Produk</th>
                      <th width="20%">Nama Produk</th>
                      <th width="20%">Pilih Batch</th>
                      <th width="10%">Qty</th>
                      <th width="10%">Stok Akhir</th>
                      <th width="20%">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                   
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <table class="table table-striped" width="100%">
                  <thead>
                    <tr>
                      <th colspan="4" width="60%">Total</th>
                      <th width="10%" id="txt-tot_qty">0</th>
                      <th width="10%" id="txt-tot_qty_akhir">0</th>
                      <th width="20%"></th>
                    </tr>
                  </thead>
                </table>
              </div>
              <button type="button" class="btn btn-primary" id="btn-simpan" onclick="simpan()">Simpan</button>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          
        </div>
        </div>
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<script type="text/javascript">
  rowNum = 0;
  $(document).ready(function () {
    cek_closing = "<?= $cek_closing ?>"
    if (cek_closing == 'N') {
      alert('Belum closing bulanan')
      window.location.href = '<?= base_url() ?>';
    }
    
     load_store()
     load_stok_ready()
  });

  status ="insert";
  $(".tambah_data").click(function(event) {
    kosong();
    $("#modalForm").modal("show");
    $("#judul").html('<h3> Form Tambah Data</h3>');
    status = "insert";
  });


  function simpan(){
    toastr.clear()

    tot_qty  = parseInt($(`#txt-tot_qty`).html())
    store = $(`#store`).val()
    tipe = $(`#tipe`).val()

    if ( store == "" || tipe == "") {
      toastr.info('Harap lengkapi Form')
      return
    }

    
    if (tot_qty == 0) {
      toastr.info('Silahkan Pilih Produk')
      return
    }

    for (var i = 0; i < bucket; i++) {
      qty = $(`#qty${i}`).val()
      BatchNo = $(`#qty${i}`).val()

      if(qty == "0" || qty == ""){
        toastr.info('qty tidak boleh 0')
        return
      }
      if(BatchNo == ""){
        toastr.info('Bacth tidak boleh kosong')
        return
      }
    }
    
      $.ajax({
          url      : '<?php echo base_url(); ?>/Transaksi/insert/'+status,
          type     : "POST",
          data     : $('#myForm').serialize(),
          dataType: "JSON",
          success: function(data)
          {           
              if (data) {
                toastr.success('Berhasil Disimpan'); 
                kosong();
                
              }else{
                toastr.error('Gagal Simpan'); 
              }
              reloadTable();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
             toastr.error('Terjadi Kesalahan'); 
          }
      });
     
  }

  function kosong(){
     $("#store").val('');
     $("#supplier").val('');
     $("#tipe").val('');
     bucket = 0;
     $("#table-produk tbody").empty();
     hitung()
     status = 'insert';
     $("#btn-simpan").show();
  }


  function tampil_edit(id,act){

    kosong();
    status = 'update';
    $("#modalForm").modal("show");
    if (act =='detail') {
      $("#judul").html('<h3> Detail Data</h3>');
      $("#btn-simpan").hide();
    }else{
      $("#judul").html('<h3> Form Edit Data</h3>');
      $("#btn-simpan").show();
    }
    $("#jenis").val('Update');

    status = "update";

         $.ajax({
              url: '<?php echo base_url('Transaksi/get_edit'); ?>',
              type: 'POST',
              data: {id: id,jenis : "m_toko",field:'id_toko'},
              dataType: "JSON",
          })
          .done(function(data) {
              
              $("#id_toko").val(data.id_toko);
              $("#nm_toko").val(data.nm_toko);
              $("#no_telp").val(data.no_telp);
              $("#s_aktif").val(data.s_aktif);
              $("textarea#alamat").val(data.alamat);
          }) 

  }


  function deleteData(id){
    let cek = confirm("Apakah Anda Yakin?");

    if (cek) {
      $.ajax({
        url   : '<?php echo base_url(); ?>Transaksi/hapus',
        data  : ({id:id,jenis:'m_toko',field:'id_toko'}),
        type  : "POST",
        success : function(data){
          toastr.success('Data Berhasil Di Hapus'); 
          reloadTable();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           toastr.error('Terjadi Kesalahan'); 
        }
      });
    }
    
   
  }

  var bucket = 0;
  function addProduk(){
    toastr.clear()

    produk = $("#produk").val()

    if (produk.length == 0) {
     toastr.info('Silahkan pilih produk');  
    }else{
      for (var i = 0; i < produk.length; i++) {
        var s_produk = produk[i].split("-")
        id_produk = s_produk[0]
        nm_produk = s_produk[1]
        harga = s_produk[2]

        s_cart = true

        for (var j = 0; j <= bucket; j++) {
          id_produk_b = $(`#id_produk${j}`).val()
          status_hapus = $(`#status_hapus${j}`).val()

          if (id_produk == id_produk_b && status_hapus != "ya") {
            s_cart = false

            break
          }
        }
        if (s_cart) {
          option_batch =''

          $.ajax({
              url: '<?php echo base_url('Master/load_stok_detail'); ?>',
              type: 'POST',
              data: {id_produk},
              dataType: "JSON",
          })
          .done(function(data) {
              option_batch =''

              option_batch += `<option value="">Pilih</option>`

              $.each(data,function(index, value){
                  
                  option_batch += `<option value="${value.BatchNo}~${value.ExpDate}~${value.NoDokumen}~${value.qty}">${value.BatchNo}~${value.ExpDate}~${value.NoDokumen}~${value.qty}</option>`
              });
              
              $("#table-produk tbody").append(
                ` <tr id="itemRow${bucket}">
                    <td>
                      <button type="button" class="btn btn-danger btn-xs" onclick="remove(${bucket})">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                    <td>
                      ${data[0].id_produk}
                      <input type="hidden" value="${data[0].id_produk}" name="id_produk[]" id="id_produk${bucket}">
                      <input type="hidden" value="${data[0].nm_produk}" name="nm_produk[]" id="nm_produk${bucket}">
                      <input type="hidden" value="" name="status_hapus[]" id="status_hapus${bucket}">
                      <input type="hidden" value="" name="BatchNo[]" id="BatchNo${bucket}">
                      <input type="hidden" value="" name="ExpDate[]" id="ExpDate${bucket}">
                      <input type="hidden" value="" name="NoDokumen[]" id="NoDokumen${bucket}">
                      <input type="hidden" value="" name="qty_batch[]" id="qty_batch${bucket}">
                    </td>
                    <td>${data[0].nm_produk}</td>
                    <td>
                      <select class="form-control" onchange="setBatch(${bucket})"  id="Batch${bucket}">
                        ${option_batch}
                      </select>
                    </td>
                    <td><input type="number" class="form-control" value=""  name="qty[]" id="qty${bucket}" onkeyup="hitung()" onchange="hitung()"></td>
                    <td><input type="number" class="form-control" value=""  name="qt_akhiry[]" id="qty_akhir${bucket}" readonly></td>
                    <td><input type="text" class="form-control" value=""  name="keterangan[]" id="keterangan${bucket}"></td>
                  </tr>`
              )

              bucket++
                            
                          
          }) 
          
        }
        
      }
      hitung()

      $("#produk").val("")
      $('#produk').trigger('change');
    }
  }

  function hitung(){
    toastr.clear()

    tot_qty = 0 
    tot_qty_akhir = 0 


    tipe = $(`#tipe`).val()

    for (var i = 0; i < bucket; i++) {
      id_produk = $(`#id_produk${i}`).val()
      nm_produk = $(`#nm_produk${i}`).val()
      qty_batch = parseFloat($(`#qty_batch${i}`).val())
      qty = parseFloat($(`#qty${i}`).val())
      qty_akhir = parseFloat($(`#qty_akhir${i}`).val())

     
      if(isNaN(qty)){
          qty = 0;
      }

      if (qty < 0) {
        qty = 0
        $(`#qty${i}`).val(qty)
      }

      if (qty > qty_batch) {
        qty = qty_batch
        $(`#qty${i}`).val(qty)
      }

      if (tipe == "Minus") {
        qty_akhir = qty_batch - qty

        if (qty_akhir < 0) {
          qty_akhir = qty_batch;
          qty = qty_batch
          $(`#qty${i}`).val(qty)
        }
      }else{
        qty_akhir = qty_batch + qty
      }

      $(`#qty_akhir${i}`).val(qty_akhir)

      tot_qty += qty
      tot_qty_akhir += qty_akhir


    }

    $(`#txt-tot_qty`).html(tot_qty)
    $(`#txt-tot_qty_akhir`).html(tot_qty_akhir)
  }

  function remove(id){
    $(`#itemRow${id}`).hide()
    $(`#status_hapus${id}`).val("ya")
    $(`#qty${id}`).val("0")

    hitung()
  }

  function load_stok_ready(){
    
    $('#produk').empty()

     $.ajax({
          url: '<?php echo base_url('Master/load_stok_ready'); ?>',
          type: 'POST',
          // data: {id_supplier},
          dataType: "JSON",
      })
      .done(function(data) {
          
          $.each(data,function(index, value){
              $('#produk').append(
                  `<option value="${value.id_produk}-${value.nm_produk}-${value.harga}">${value.nm_produk}</option>`
                );
              
          });
          $('#produk').selectpicker('refresh');
          
                        
                      
      }) 

  }

  function load_store(){


       $.ajax({
            url: '<?php echo base_url('Master/load_store'); ?>',
            type: 'POST',
            dataType: "JSON",
        })
        .done(function(data) {
            $('#store').append(
              `<option value="">Pilih</option>`
            );

            $.each(data,function(index, value){
                $('#store').append(
                    `<option value="${value.id_toko}-${value.nm_toko}-${value.alamat}-${value.no_telp}">${value.nm_toko}</option>`
                  );
                
            });
            
                          
                        
        }) 

  }

  function setBatch(e){
    Batch = $(`#Batch${e}`).val()
    tipe = $(`#tipe`).val()

    if (Batch == "") {
      return
    }
    Batch = Batch.split("~")
    qty_batch = parseInt(Batch[3])
    
    $(`#qty${e}`).val(qty_batch)
    $(`#BatchNo${e}`).val(Batch[0])
    $(`#ExpDate${e}`).val(Batch[1])
    $(`#NoDokumen${e}`).val(Batch[2])
    $(`#qty_batch${e}`).val(qty_batch)

    qty = parseInt($(`#qty${e}`).val())

    if(isNaN(qty)){
        qty = 0;
    }

    if (tipe == "Minus") {
      qty_akhir = qty_batch - qty
    }else{
      qty_akhir = qty_batch + qty
    }


    $(`#qty_akhir${e}`).val(qty_akhir)

    hitung()

  }

  function load_supplier(){


     $.ajax({
          url: '<?php echo base_url('Master/load_supplier'); ?>',
          type: 'POST',
          dataType: "JSON",
      })
      .done(function(data) {
          $('#supplier').append(
            `<option value="">Pilih</option>`
          );

          $.each(data,function(index, value){
              $('#supplier').append(
                  `<option value="${value.id_supplier}-${value.nm_supplier}-${value.alamat}-${value.no_telp}">${value.nm_supplier}</option>`
                );
              
          });
          
                        
                      
      }) 

  }

  function load_produk_supplier(){
    id_supplier = $('#supplier').val();

    if (id_supplier == "") {
      return;
    }

    id_supplier = id_supplier.split('-')[0];
    $('#produk').empty()

     $.ajax({
          url: '<?php echo base_url('Master/load_produk_supplier'); ?>',
          type: 'POST',
          data: {id_supplier},
          dataType: "JSON",
      })
      .done(function(data) {
          
          $.each(data,function(index, value){
              $('#produk').append(
                  `<option value="${value.id_produk}-${value.nm_produk}-${value.harga}">${value.nm_produk}</option>`
                );
              
          });
          $('#produk').selectpicker('refresh');
          
                        
                      
      }) 

  }

  function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
          decimalCount = Math.abs(decimalCount);
          decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

          const negativeSign = amount < 0 ? "-" : "";

          let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
          let j = (i.length > 3) ? i.length % 3 : 0;

          return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
          console.log(e)
        }
   }

  
</script>