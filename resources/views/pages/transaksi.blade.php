@extends('Layouts.Base')
@section('content')
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div id="loading" class="loading">
            <img src="{{ asset('img/loading.gif') }}" alt="Loading..." />
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="row mx-4">
                <form action="" class="col-lg-6">
                    <div class="form-group row mt-4">
                        <label for="" class="col-sm-3 col-form-label">Product</label>
                        <div class="col-sm-9">
                            <select name="id_product" id="id_product" class="form-control">
                                <option value="">-- Pilih Product --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Nama pembeli</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label for="" class="col-sm-3 col-form-label">Qty</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar product</h6>
            </div>
            <div class="p-3">
                <div class="row" id="data-container">
                    <div class="table-responsive p-3">
                        <table id="dataTable" class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Product</th>
                                    <th>Nama Pembeli</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Total Harga</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data from database will be shown here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        //get data
        // $(document).ready(function() {
        //     $.ajax({
        //         url: "{{ url('/cms/product') }}",
        //         method: "GET",
        //         dataType: "json",
        //         success: function(response) {
        //             console.log(response);
        //             var tableBody = "";
        //             $.each(response.data, function(index, item) {
        //                 tableBody += "<tr>";
        //                 tableBody += "<td>" + (index + 1) + "</td>";
        //                 tableBody += "<td>" + item.kode_product + "</td>";
        //                 tableBody += "<td>" + item.nama_product + "</td>";
        //                 tableBody += "<td>" + "Rp. " + item.harga_product.toLocaleString('id') +
        //                     "</td>";
        //                 tableBody += "<td>" +
        //                     "<button type='button' class='btn btn-primary edit-modal' data-toggle='modal' data-target='#EditModal' " +
        //                     "data-uuid='" + item.uuid + "' " +
        //                     "<i class='fa fa-edit'>Edit</i></button>" +
        //                     "<button type='button' class='btn btn-danger delete-confirm' data-uuid='" +
        //                     item.uuid + "'><i class='fa fa-trash'></i></button>" +
        //                     "</td>";

        //                 tableBody += "</tr>";
        //             });
        //             $('#dataTable').DataTable().destroy();
        //             $("#dataTable tbody").empty();
        //             $("#dataTable tbody").append(tableBody);
        //             $('#dataTable').DataTable({
        //                 "paging": true,
        //                 "ordering": true,
        //                 "searching": true
        //             });
        //         },
        //         error: function() {
        //             console.log("Failed to get data from server");
        //         }
        //     });
        // });

        $.ajax({
            url: "{{ url('cms/product/') }}",
            method: "GET",
            dataType: "json",
            success: function(response) {
                console.log(response);
                var options = '';
                $.each(response.data, function(index, item) {
                    options += '<option value="' + item.id + '">' + item.nama_product + '</option>';
                });
                $('#id_product').append(options);


            },
            error: function() {
                console.log("Failed to get data from server");
            }
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var formTambah = $('#formTambah');

            formTambah.on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                // Tampilkan loader
                $('#loading-overlay').show();
                $.ajax({
                    type: 'POST',
                    url: '{{ url('cms/product/create') }}',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#loading-overlay').hide();
                        if (data.message === 'check your validation') {
                            var error = data.errors;
                            var errorMessage = "";

                            $.each(error, function(key, value) {
                                errorMessage += value[0] + "<br>";
                            });

                            Swal.fire({
                                title: 'Error',
                                html: errorMessage,
                                icon: 'error',
                                timer: 5000,
                                showConfirmButton: true
                            });
                        } else {
                            console.log(data);
                            Swal.fire({
                                title: 'Success',
                                text: 'Data Success Create',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'OK'
                            }).then(function() {
                                location.reload();
                            });
                        }
                    },
                    error: function(data) {
                        $('#loading-overlay').hide();

                        var error = data.responseJSON.errors;
                        var errorMessage = "";

                        $.each(error, function(key, value) {
                            errorMessage += value[0] + "<br>";
                        });

                        Swal.fire({
                            title: 'Error',
                            html: errorMessage,
                            icon: 'error',
                            timer: 5000,
                            showConfirmButton: true
                        });
                    }
                });
            });
        });

        //edit
        $(document).on('click', '.edit-modal', function() {
            var uuid = $(this).data('uuid');
            $.ajax({
                url: "{{ url('v1/9d97457b-1922-4f4a-b3fa-fcba980633a2/author/get') }}/" + uuid,
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    $('#uuid').val(data.data.uuid);
                    $('#enama').val(data.data.nama);
                    $('#eemail').val(data.data.email);
                    $('#EditModal').modal('show');
                },
                error: function() {
                    alert("error");
                }
            });
        });

        //update
        $(document).ready(function() {
            var formEdit = $('#formEdit');

            formEdit.on('submit', function(e) {
                e.preventDefault();

                var uuid = $('#uuid').val();
                var formData = new FormData(this);
                // Tampilkan loader
                $('#loading-overlay').show();

                $.ajax({
                    type: "POST",
                    url: "{{ url('v1/4a3f479a-eb2e-498f-aa7b-e7d6e3f0c5f3/author/update/') }}/" +
                        uuid,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        $('#loading-overlay').hide();
                        if (data.message === 'check your validation') {
                            var error = data.errors;
                            var errorMessage = "";

                            $.each(error, function(key, value) {
                                errorMessage += value[0] + "<br>";
                            });

                            Swal.fire({
                                title: 'Error',
                                html: errorMessage,
                                icon: 'error',
                                timer: 5000,
                                showConfirmButton: true
                            });
                        } else {
                            console.log(data);
                            $('#loading-overlay').hide();
                            Swal.fire({
                                title: 'Success',
                                text: 'Data Success Update',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'OK'
                            }).then(function() {
                                location.reload();
                            });
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON.errors;
                        var errorMessage = "";

                        $.each(errors, function(key, value) {
                            errorMessage += value + "<br>";
                        });

                        Swal.fire({
                            title: "Error",
                            html: errorMessage,
                            icon: "error",
                            timer: 5000,
                            showConfirmButton: true
                        });
                    }
                });
            });
        });

        //delete
        $(document).on('click', '.delete-confirm', function(e) {
            e.preventDefault();
            var uuid = $(this).data('uuid');
            Swal.fire({
                title: 'Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Delete',
                cancelButtonText: 'Cancel',
                resolveButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('v1/83df59b0-7c1a-4944-8fbb-2c06670dfa01/author/delete/') }}/" +
                            uuid,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "uuid": uuid
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.code === 200) {
                                Swal.fire({
                                    title: 'Data berhasil dihapus',
                                    icon: 'success',
                                    timer: 5000,
                                    showConfirmButton: true
                                }).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal menghapus data',
                                    text: response.message,
                                    icon: 'error',
                                    timer: 5000,
                                    showConfirmButton: true
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Terjadi kesalahan',
                                text: 'Gagal menghapus data',
                                icon: 'error',
                                timer: 5000,
                                showConfirmButton: true
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
