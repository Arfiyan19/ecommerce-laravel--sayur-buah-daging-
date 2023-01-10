@extends('backend.layouts.master')
@section('title','E-SHOP || Topup Edit')
@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Topup</h5>
    <div class="card-body">
        <form method="post" action="{{route('saldo.update',$topup->id)}}">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="inputName" class="col-form-label">Name <span class="text-danger">*</span></label>
                <input id="inputName" type="text" name="name" placeholder="Enter Name" value="{{$topup->name}}" class="form-control">
                @error('name')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="inputSaldo" class="col-form-label">Saldo Awal <span class="text-danger">*</span></label>
                <input id="inputSaldo" type="text" name="saldo" placeholder="Enter Saldo" value="Rp. {{number_format($topup->saldo,2)}}" class="form-control">
                @error('saldo')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="inputSaldoPending" class="col-form-label">Saldo Pending
                    <span class="text-danger">*</span></label>
                <input id="inputSaldoPending" type="text" name="saldo_pending" placeholder="Enter Saldo Pending" value="Rp. {{number_format($topup->saldo_pending,2)}}" class="form-control">
                @error('saldo_pending')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="inputBuktiTF" class="col-form-label">Bukti Transfer
                    <span class="text-danger">*</span></label>
                <div>
                    <img src="{{$topup->bukti_tf}}" class="img-fluid zoom" style="max-width:500px" alt="{{$topup->bukti_tf}}">
                </div>
                @error('bukti_tf')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="col-form-label">Saldo Pending Status<span class="text-danger">*</span></label>
                <select name="saldo_pending_status" class="form-control">
                    <option value="aktif" {{(($topup->saldo_pending_status=='aktif') ? 'selected' : '')}}>Aktif </option>
                    <option value="in_aktif" {{(($topup->saldo_pending_status=='in_aktif') ? 'selected' : '')}}>In Aktif</option>
                </select>
                @error('saldo_pending_status')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <!-- 
            <div class="form-group">
                <label for="inputDesc" class="col-form-label">Description</label>
                <textarea class="form-control" id="description" name="description">{{$topup->description}}</textarea>
                @error('description')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                            <i class="fa fa-picture-o"></i> Choose
                        </a>
                    </span>
                    <input id="thumbnail" class="form-control" type="text" name="photo" value="{{$topup->photo}}">
                </div>
                <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                @error('photo')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control">
                    <option value="active" {{(($topup->status=='active') ? 'selected' : '')}}>Active</option>
                    <option value="inactive" {{(($topup->status=='inactive') ? 'selected' : '')}}>Inactive</option>
                </select>
                @error('status')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div> -->
            <div class="form-group mb-3">
                <button class="btn btn-success" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
        $('#description').summernote({
            placeholder: "Write short description.....",
            tabsize: 2,
            height: 150
        });
    });
</script>
@endpush