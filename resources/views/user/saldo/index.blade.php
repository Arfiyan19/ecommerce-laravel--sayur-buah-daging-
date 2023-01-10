@extends('user.layouts.master')

@section('main-content')
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('user.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Order Lists</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="saldo-dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Saldo</th>
                        <th>Bank</th>
                        <th>Saldo Pending</th>
                        <th>Saldo Pending Status</th>
                        <th>Bukti Transfer</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$saldo->name}}</td>
                        <td>{{$saldo->email}}</td>
                        <td>{{$saldo->role}} </td>
                        <td>Rp. {{number_format($saldo->saldo,2)}}</td>
                        <td>Rp. {{number_format($saldo->saldo_pending,2)}}</td>
                        <td>{{$saldo->bank}}</td>
                        <td>
                            @if($saldo->saldo_pending_status=='in_aktif')
                            <span class="badge badge-warning">{{$saldo->saldo_pending_status}}</span>
                            @elseif($saldo->saldo_pending_status=='aktif')
                            <span class="badge badge-success">{{$saldo->saldo_pending_status}}</span>
                            @endif
                        </td>
                        <td>
                            <img src="{{$saldo->bukti_tf}}" alt="" style="width: 50%;">
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>
    div.dataTables_wrapper div.dataTables_paginate {
        display: none;
    }
</style>
@endpush

@push('scripts')

<!-- Page level plugins -->
<script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Page level custom scripts -->
<script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
<script>
    $('#saldo-dataTable').DataTable({
        "columnDefs": [{
            "orderable": false,
            "targets": [8]
        }]
    });

    // Sweet alert

    function deleteData(id) {

    }
</script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.dltBtn').click(function(e) {
            var form = $(this).closest('form');
            var dataID = $(this).data('id');
            // alert(dataID);
            e.preventDefault();
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    } else {
                        swal("Your data is safe!");
                    }
                });
        })
    })
</script>
@endpush