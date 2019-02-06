<!DOCTYPE html>
  <html>
    <head>
      @if($throttle == 'bulan')
        <title>Arus Kas {{ date_ind($request->m)." ".$request->y }}</title>
      @elseif($throttle == 'tahun')
        <title>Arus Kas {{ $request->y }}</title>
      @endif


        <link href="{{ asset('assets/vendors/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">


        <!-- datepicker -->
        <link href="{{ asset('assets/vendors/datapicker/datepicker3.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendors/daterangepicker/daterangepicker.css') }}" rel="stylesheet">

        <!-- Toastr style -->
        <link href="{{ asset('assets/vendors/toastr/toastr.min.css')}}" rel="stylesheet">

        <link href="{{ asset('assets/vendors/bootstrap-treegrid/css/jquery.treegrid.css') }}" rel="stylesheet">

        <script type="text/javascript" src="{{ asset('assets/plugins/jquery-1.12.3.min.js') }}"></script>

      <style> 
        
        @page { margin: 10px; }
          .page_break { page-break-before: always; }
          .page-number:after { content: counter(page); }
          #table-data{
          font-size: 8pt;
          margin-top: 10px;
          border: 1px solid #555;
          }
          #table-data th{
            border: 1px solid #aaa;
            border-collapse: collapse;
            border-bottom: 3px solid #ccc;
            background: #fff;
            padding: 8px 15px;
            font-size: 10pt;
          }
          #table-data td{
            border: 1px solid #ccc;
            vertical-align: top;
          }
          #table-data td.currency{
            text-align: right;
            padding-right: 5px;
          }
          #table-data td.no-border{
            border: 0px;
          }
          #table-data td.total.not-same{
             color: red !important;
             -webkit-print-color-adjust: exact;
          }
          #table-data-inside{
            font-size: 9pt;
            margin-top: 5px;
          }
          #table-data-inside td{
            padding: 5px 15px;
            border: 0px solid #333;
          }
          #table-data-inside td.lv1{
            font-weight: 1000;
            padding: 5px 10px;
          }
          #table-data-inside td.lv2{
            font-weight: 600;
            padding: 5px 10px;
            color:blue;
            font-style: italic;
          }
          #table-data-inside td.lv3{
            font-weight: 600;
            padding: 5px 10px;
            font-style: italic;
          }
          #table-data-inside td.money{
            text-align: right;
            padding: 5px 10px;
            font-weight: bold;
          }
          #table-data-inside td.total{
            border-top: 2px solid #777; ;
          }
          #navigation ul{
            float: right;
            padding-right: 110px;
          }
          #navigation ul li{
            color: #fff;
            font-size: 15pt;
            list-style-type: none;
            display: inline-block;
            margin-left: 40px;
          }
           #form-table{
              font-size: 8pt;
            }
            #form-table td{
              padding: 5px 0px;
            }
            #form-table .form-control{
              height: 30px;
              width: 90%;
              font-size: 8pt;
            }
        #table-data-inside tbody > tr:has(td.lv1){
          background-color: #777777;
        }
        .bg-gray2{
          background-color: #919191;
        }
        .bg-gray3{
          background-color: #BABABA;
        }
      </style>

      <style type="text/css" media="print">
        @page { size: portrait; }
        #navigation{
            display: none;
          }
          .page-break { display: block; page-break-before: always; }
      </style>

    </head>

    <body style="background: #555;">

      <div class="col-md-12" id="navigation" style="background: rgba(0, 0, 0, 0.4); box-shadow: 0px 2px 5px #444; position: fixed; z-index: 2;">
        <div class="row">
          <div class="col-md-7" style="background: none; padding: 15px 15px; color: #fff; padding-left: 120px;">
            PT Jawa Pratama Mandiri
          </div>
          <div class="col-md-5" style="background: none; padding: 10px 15px 5px 15px">
            <ul>
              <li><i class="fa fa-sliders" style="cursor: pointer;" onclick="$('#modal_setting_neraca').modal('show')" data-toggle="tooltip" data-placement="bottom" title="Tampilkan Setting Arus Kas"></i></li>

              <li><i class="fa fa-print" style="cursor: pointer;" id="print" data-toggle="tooltip" data-placement="bottom" title="Print Laporan"></i></li>
              <li><i id="btnExport" class="fa fa-file" style="cursor: pointer;" data-toggle="tooltip" data-placement="bottom" title="Export Excel"></i></li>
            </ul>
          </div>
        </div>
      </div>

      <div id="isi" class="col-md-10 col-md-offset-1" style="background: white; padding: 10px 15px; margin-top: 80px;">
  
        <table width="100%" border="0" style="border-bottom: 1px solid #333;">
          <thead>
            <tr>
              <th style="text-align: left; font-size: 14pt; font-weight: 600">Laporan Arus Kas Dalam {{ ucfirst($throttle) }} </th>
            </tr>

            <tr>
              <th style="text-align: left; font-size: 12pt; font-weight: 500">PT Jawa Pratama Mandiri</th>
            </tr>

            <tr>
              <th style="text-align: left; font-size: 8pt; font-weight: 500; padding-bottom: 10px;">(Angka Disajikan Dalam Rupiah, Kecuali Dinyatakan Lain)</th>
            </tr>
          </thead>
        </table>

        <table width="100%" border="0" style="font-size: 8pt;">
          <thead>
            <tr>
              <td style="text-align: left; padding-top: 5px;">
                @if($throttle == 'bulan')
                  Laporan Per Bulan {{ date_ind($request->m)." ".$request->y }}
                @elseif($throttle == 'tahun')
                  Laporan Per Tahun {{ $request->y }}
                @endif
              </td>
              
            </tr>
          </thead>
        </table>

        <table id="table-data" width="80%" border="0" style="min-height: 455px; margin: 10px auto;">
          <tbody>
            
            <tr>
              <td style="border-right: 3px solid #ccc;">
                <table class="aktiva-tree" id="table-data-inside" border="0" width="100%">
                  <tbody>
                    <?php $total_aktiva = $total_pasiva = 0; ?>
                    @foreach($detail as $data_detail)
                      @if($data_detail->jenis == 1)

                        <tr class="treegrid-{{ str_replace('.', '-', $data_detail->nomor_id) }} treegrid-parent-{{ str_replace('.', '-', $data_detail->id_parrent) }}" id="{{ $data_detail->nomor_id }}">
                          <td style="font-weight: 1000;" class="{{ 'lv'.$data_detail->level }}" >{{ $data_detail->keterangan }}</td>
                          <td class="money" style="display: none">
                          {{ get_total_arus_kas_parrent($data_detail->nomor_id, 2, 'A', $data_real, $detail) }}
                          </td>
                        </tr>

                      @elseif($data_detail->jenis == 2)

                        <tr class="treegrid-{{ str_replace('.', '-', $data_detail->nomor_id) }} treegrid-parent-{{ str_replace('.', '-', $data_detail->id_parrent) }} collapse" id="{{ $data_detail->nomor_id }}">
                          <td style="color:blue;font-style: italic;font-weight: 650;padding-left: 10px;" class="{{ 'lv'.$data_detail->level }}" >{{ $data_detail->keterangan }}</td>
                          <td class="money">
                             {{ get_total_arus_kas_parrent($data_detail->nomor_id, 3, 'A', $data_real, $detail) }}
                          </td>
                        </tr>

                        @foreach($data_detail->detail as $detail_dt)
                          <tr>
                            <tr class="treegrid-{{ str_replace('.', '-', $detail_dt->id_group) }} treegrid-parent-{{ str_replace('.', '-', $detail_dt->id_parrent) }} collapse" id="{{ $detail_dt->nomor_id }}">
                              <td style="font-weight: 600;color:green; font-style: italic;padding-left: 20px">{{ $detail_dt->nama }}</td>
                              <td class="money">
                                {{ get_total_arus_kas_parrent($detail_dt->nomor_id, 5, 'A', $data_real, $detail, $detail_dt->id_parrent) }}
                              </td>
                            </tr>
                          </tr>

                          @foreach($detail_dt->akun as $akun)
                            <?php
                              $total = 0;
                              if($akun->akun_dka == 'D'){
                                $total = (($akun->kas_debet + $akun->bank_debet) - ($akun->kas_kredit + $akun->bank_kredit));
                                $total = $total * -1;
                              }else{
                                $total = (($akun->kas_kredit + $akun->bank_kredit) - ($akun->kas_debet + $akun->bank_debet));
                              }
                              $total_aktiva += $total;
                            ?>

                            <tr>
                              <tr class="treegrid-{{ str_replace('.', '-', $akun->id_akun) }} treegrid-parent-{{ str_replace('.', '-', $akun->main_id) }} collapse" id="{{ $akun->id_akun }}">
                                <td style="font-weight: 500; font-style: italic;padding-left: 30px;">{{ $akun->nama_akun }}</td>
                                <td class="money">
                                  {{ ($total < 0) ? '('.number_format(str_replace('-', '', $total), 2).')' : number_format(str_replace('-', '', $total), 2) }}
                                </td>
                              </tr>
                            </tr>
                          @endforeach

                        @endforeach

                      @elseif($data_detail->jenis == 4)
                        <tr class="treegrid-{{ str_replace('.', '-', $data_detail->nomor_id) }} treegrid-parent-{{ str_replace('.', '-', $data_detail->id_parrent) }}" id="{{ $data_detail->nomor_id }}">
                          <td class="{{ 'lv'.$data_detail->level }}" >&nbsp;</td>
                          <td></td>
                        </tr>

                       @elseif($data_detail->jenis == 3)
                        <tr class="treegrid-{{ str_replace('.', '-', $data_detail->nomor_id) }} treegrid-parent-{{ str_replace('.', '-', $data_detail->id_parrent) }}" id="{{ $data_detail->nomor_id }}">
                          <td class="{{ 'lv'.$data_detail->level }}" style="font-weight: 600; font-style: italic;padding-left: 10px;">{{ $data_detail->keterangan }}</td>
                          <td class="money total">
                            {{ get_total_arus_kas_parrent($data_detail->nomor_id, 4, 'A', $data_real, $detail) }}
                          </td>
                        </tr>

                      @endif
                    @endforeach
                     
                  </tbody>
                </table>
              </td>
            </tr>
            
          </tbody>

          <tfoot>
            <tr>
              <td>
                <table width="100%" style="font-size: 9pt;">

                  <tr>
                    <td style="font-weight: bold; padding: 5px 10px; font-weight: bold" width="50%" colspan="2">
                     &nbsp;
                    </td>
                  </tr>

                  <tr>
                    <td style="font-weight: bold; padding: 5px 10px; font-weight: bold" width="50%">
                      Total Aktivitas Kas
                    </td>

                    <td style="font-weight: 600; padding: 5px 10px;" class="text-right">
                      {{ ($total_aktiva >= 0) ? number_format($total_aktiva, 2) : "( ".number_format(str_replace("-", "", $total_aktiva), 2)." )" }}
                    </td>
                  </tr>

                  <tr>
                    <td style="font-weight: bold; padding: 5px 10px; font-weight: bold" width="50%">
                      Saldo Awal Kas Periode Terpilih
                    </td>

                    <td style="font-weight: 600; padding: 5px 10px;" class="text-right">
                      {{ ($data_saldo->saldo >= 0) ? number_format($data_saldo->saldo, 2) : "( ".number_format(str_replace("-", "", $data_saldo->saldo), 2)." )" }}
                    </td>
                  </tr>

                  <tr>
                    <td style="font-weight: bold; padding: 5px 10px; font-weight: bold" width="50%">
                      Saldo Awal Kas Kas Seharusnya
                    </td>

                    <td style="font-weight: 600; padding: 5px 10px;" class="text-right">
                      {{ ($data_saldo->saldo + $total_aktiva >= 0) ? number_format($data_saldo->saldo + $total_aktiva, 2) : "( ".number_format(str_replace("-", "", $data_saldo->saldo + $total_aktiva), 2)." )" }}
                    </td>
                  </tr>

                </table>
              </td>
            </tr>
          </tfoot>

        </table>

        <table id="table" width="100%" border="0" style="font-size: 8pt; margin-top: 4px;">
          <thead>
            <tr>
              
            </tr>
          </thead>
        </table>

      </div>

      <!-- modal -->
    <div id="modal_setting_neraca" class="modal">
      <div class="modal-dialog" style="width: 30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Setting Tampilan Arus Kas</h4>
            <input type="hidden" class="parrent"/>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="table_setting_form">
              <div class="col-md-12" style="border: 1px solid #ddd; border-radius: 5px; padding: 10px;">
                <table border="0" id="form-table" class="col-md-12">
                  <tr>
                    <td width="30%" class="text-center">Jenis Arus Kas</td>
                    <td colspan="2">
                        <select class="form-control" style="width:90%; height: 30px" id="tampil">
                          <option value="bulan">Arus Kas Bulan</option>
                          <option value="tahun">Arus Kas Tahun</option>
                          {{-- <option value="p_bulan">Perbandingan Bulan</option> --}}
                          {{-- <option value="p_tahun">Perbandingan Tahun</option> --}}
                        </select>
                    </td>
                  </tr>

                  <tr>
                    <td width="30%" class="text-center"></td>
                    <td>

                        <input class="form-control text-center date" style="width:90%; height: 30px; cursor: pointer; background: #fff; display:;" readonly data-toggle="tooltip" id="bulan" placeholder="Pilih Bulan">

                        <input class="form-control text-center date_year" style="width:90%; height: 30px; cursor: pointer; background: #fff; display:none;" readonly data-toggle="tooltip" id="bulan_1" placeholder="Bulan Ke-1">

                        <input class="form-control text-center year" style="width:90%; height: 30px; cursor: pointer; background: #fff; display:none;" readonly data-toggle="tooltip" id="tahun_1" placeholder="Tahun Ke-1">

                    </td>

                    <td>

                        <input class="form-control text-center year" style="width:80%; height: 30px; cursor: pointer; background: #fff; display:;" readonly data-toggle="tooltip" id="tahun" placeholder="Pilih Tahun">

                        <input class="form-control text-center date_year" style="width:80%; height: 30px; cursor: pointer; background: #fff; display:none;" readonly data-toggle="tooltip" id="bulan_2" placeholder="Bulan Ke-2">

                        <input class="form-control text-center year" style="width:80%; height: 30px; cursor: pointer; background: #fff; display:none;" readonly data-toggle="tooltip" id="tahun_2" placeholder="Tahun Ke-2">

                    </td>

                  </tr>
                  {{-- <tr>
                    <td class="text-center">Tipe</td>
                    <td colspan="2">
                      <select class="form-control" name="tipe_output" id="tipe_output">
                        <option value="print">Print</option>
                        <option value="excel">Excel</option>
                      </select>
                    </td>
                  </tr>
 --}}                </table>
              </div>
              </form>

              <div class="col-md-12 m-t" style="border-top: 1px solid #eee; padding: 10px 10px 0px 0px;">
                <button class="btn btn-primary btn-sm pull-right" id="submit_setting_neraca">Submit</button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
      <!-- modal -->

      <script type="text/javascript" src="{{ asset('assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>

      <!-- datepicker  --> 
      <script src="{{ asset('assets/vendors/daterangepicker/moment.min.js') }}"></script>
      <script src="{{ asset('assets/vendors/datapicker/bootstrap-datepicker.js') }}"></script>
      <script src="{{ asset('assets/vendors/daterangepicker/daterangepicker.js') }}"></script>

      <!-- Toastr -->
      <script src="{{ asset('assets/vendors/toastr/toastr.min.js') }}"></script>
      <script src="{{ asset('assets/vendors/bootstrap-treegrid/js/jquery.treegrid.js') }}"></script>

      <div id="xlsDownload" class="d-none">
        
      </div>

      <script type="text/javascript">
        $(document).ready(function(){
          $('.aktiva-tree').treegrid({
            onCollapse: function() {
                $(this).children('.money').first().fadeIn('200');
            },
            onExpand: function() {
                $(this).children('.money').first().fadeOut('200');
            }
          });
          $('.aktiva-tree .collapse').treegrid('collapse');
          $('[data-toggle="tooltip"]').tooltip({container : 'body'});
          baseUrl = '{{ url('/') }}';
          // $('.aktiva-tree').treegrid('getRootNodes').on('change', function(){
          //    $(this).children('.money').first().fadeIn('200');
          //    console.log(this);
          // });
          // $('.aktiva-tree').treegrid('getRootNodes').on('expand', function(){
          //    $(this).children('.money').first().fadeOut('200');
          //    console.log(this);
          // });
          // $('.pasiva-tree').treegrid('getRootNodes').on('collapse', function(){
          //    $(this).children('.money').first().fadeIn('200');
          //    // alert('okee');
          // });
          // $('.pasiva-tree').treegrid('getRootNodes').on('expand', function(){
          //    $(this).children('.money').first().fadeOut('200');
          // });
          // script for neraca
            $('.date_year').datepicker( {
                format: "mm/yyyy",
                viewMode: "months", 
                minViewMode: "months"
            });
            $('#dateMonth').datepicker( {
                format: "mm",
                viewMode: "months", 
                minViewMode: "months"
            });
            $('.year').datepicker( {
                format: "yyyy",
                viewMode: "years", 
                minViewMode: "years"
            });
            $('#bulan').datepicker( {
                format: "mm",
                viewMode: "months", 
                minViewMode: "months"
            });
            $("#tampil").change(function(evt){
                evt.stopImmediatePropagation();
                evt.preventDefault();
                cek = $(this);
                if(cek.val() == "bulan"){
                  // alert("okee");
                  $("#bulan_1").css("display", "none"); $("#bulan_2").css("display", "none");
                  $("#tahun_1").css("display", "none"); $("#tahun_2").css("display", "none");
                  $("#bulan").css("display", "inline-block"); $("#bulan").val(""); $("#tahun").css("display", "inline-block");
                  $("#bulan").removeAttr("disabled");
                }else if(cek.val() == "tahun"){
                  $("#bulan_1").css("display", "none"); $("#bulan_2").css("display", "none");
                  $("#tahun_1").css("display", "none"); $("#tahun_2").css("display", "none");
                  $("#bulan").css("display", "inline-block"); $("#tahun").css("display", "inline-block");
                  $("#bulan").attr("disabled", "disabled"); $("#bulan").val("-");
                }else if(cek.val() == "p_bulan"){
                  $("#tahun_1").css("display", "none"); $("#tahun_2").css("display", "none");
                  $("#bulan").css("display", "none"); $("#tahun").css("display", "none");
                  $("#bulan_1").css("display", "inline-block"); $("#bulan_2").css("display", "inline-block");
                }else if(cek.val() == "p_tahun"){
                  $("#bulan_1").css("display", "none"); $("#bulan_2").css("display", "none");
                  $("#bulan").css("display", "none"); $("#tahun").css("display", "none");
                  $("#tahun_1").css("display", "inline-block"); $("#tahun_2").css("display", "inline-block");
                }
               })
               $("#submit_setting_neraca").click(function(event){
                  event.preventDefault();
                  form = $("#table_setting_form"); $(this).attr("disabled", true); $(this).text("Mengubah Tampilan arus_kasArus Kas ...");
                  tampil = $("#tampil").val();
                  if(tampil == "bulan"){
                    data = $("#tampil").val()+"?"+form.serialize()+"&m="+$("#bulan").val()+"&y="+$("#tahun").val();
                    if($("#bulan").val() == "" || $("#tahun").val() == ""){
                      toastr.warning('Bulan Dan Tahun Tidak Boleh Kosong');
                      $(this).removeAttr("disabled"); $(this).text("Submit");
                      return false;
                    }else{
                      window.location = baseUrl+"/master_keuangan/arus_kas/single/"+data;
                    }
                  }else if(tampil == "tahun"){
                    data = $("#tampil").val()+"?"+form.serialize()+"&m="+$("#bulan").val()+"&y="+$("#tahun").val();
                    if($("#bulan").val() == "" || $("#tahun").val() == ""){
                      toastr.warning('Tahun Tidak Boleh Kosong');
                      $(this).removeAttr("disabled"); $(this).text("Submit");
                      return false;
                    }else{
                      window.location = baseUrl+"/master_keuangan/arus_kas/single/"+data;
                    }
                  }else if(tampil == "p_bulan"){
                    data = $("#tampil").val()+"?"+form.serialize()+"&m="+$("#bulan_1").val()+"&y="+$("#bulan_2").val();
                    if($("#bulan_1").val() == "" || $("#bulan_2").val() == ""){
                      toastr.warning('Bulan Tidak Boleh Ada Yang Kosong');
                      $(this).removeAttr("disabled"); $(this).text("Submit");
                      return false;
                    }else{
                      window.location = baseUrl+"/master_keuangan/arus_kas/perbandingan/"+data;
                    }
                  }else if(tampil == "p_tahun"){
                    data = $("#tampil").val()+"?"+form.serialize()+"&m="+$("#tahun_1").val()+"&y="+$("#tahun_2").val();
                    if($("#tahun_1").val() == "" || $("#tahun_2").val() == ""){
                      toastr.warning('Tahun Tidak Boleh Ada Yang Kosong');
                      $(this).removeAttr("disabled"); $(this).text("Submit");
                      return false;
                    }else{
                      window.location = baseUrl+"/master_keuangan/arus_kas/perbandingan/"+data;
                    }
                  }
                  // window.location = baseUrl + "/master_keuangan/saldo_akun?" + form.serialize();
                })
          //end neraca
          $('#print').click(function(evt){
            evt.preventDefault();
            window.print();
          })
          // $('#table-data-inside tbody > tr').each(function(){
          //   $('#table-data-inside tbody > tr').find('.lv1').parent('tr').addClass('bg-gray1');
          //   $('#table-data-inside tbody > tr').find('.lv2').parent('tr').addClass('bg-gray2');
          //   // $('#table-data-inside tbody > tr').find('.lv3').parent('tr').addClass('bg-gray3');
          // });
          $("#btnExport").click(function (e) {
             // window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('div[id=isi]').html()));
             //  e.preventDefault();
            var blob = b64toBlob(btoa($('#isi').html()), "application/vnd.ms-excel", 128);
            var blobUrl = URL.createObjectURL(blob);
            console.log(blob);
            console.log(blobUrl);
            // return false;
            // window.location = blobUrl;
            var d = new Date();
            var da = [
                      d.getDate(),
                      d.getMonth(),
                      d.getFullYear(),
                      d.getSeconds(),
                      d.getMinutes(),
                      d.getHours()
                      ];
            $("#xlsDownload").html("<a href=\""+blobUrl+"\" download=\"LAP_ARUSKAS"+da[0]+"-"+da[1]+"-"+da[2]+" "+da[5]+"-"+da[4]+"-"+da[3]+".xls\" id=\"xlsFile\">Downlaod</a>");
            $("#xlsFile").get(0).click();
            function b64toBlob(b64Data, contentType, sliceSize) {
                contentType = contentType || '';
                sliceSize = sliceSize || 512;
                var byteCharacters = atob(b64Data);
                var byteArrays = [];
                for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                    var slice = byteCharacters.slice(offset, offset + sliceSize);
                    var byteNumbers = new Array(slice.length);
                    for (var i = 0; i < slice.length; i++) {
                        byteNumbers[i] = slice.charCodeAt(i);
                    }
                    var byteArray = new Uint8Array(byteNumbers);
                    byteArrays.push(byteArray);
                }
                var url_string = location.href;
                var url = new URL(url_string);
                var m = url.searchParams.get("m");
                var y = url.searchParams.get("y");
                var cab = url.searchParams.get("cab");
                console.log(m);
                var blob = new Blob(byteArrays, {type: contentType});
                return blob;
            }  
          })
        })
      </script>
    </body>
  </html>
