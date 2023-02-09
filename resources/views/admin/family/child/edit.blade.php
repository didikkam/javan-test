@extends('layouts.admin')

@section('title', 'Edit')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.family.index') }}">Keluarga</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        @include('admin.includes.messages')
        <section class="content">
            <form action="{{ route('admin.family.child.store', $parent_id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.family.index') }}" class="btn btn-secondary">
                                Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <div class="form-group row">
                            <label for="parent_id" class="col-sm-2 col-form-label">Anak Dari</label>
                            <div class="col-sm-10">
                                <select name="parent_id" class="form-control select2-parent_id">
                                    @if(isset($data->parent))
                                        <option value="{{$data->parent->id}}">{{$data->parent->name}}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="{{ old('name', $data->name) }}" class="form-control"
                                    id="name" placeholder="Nama">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-10">
                                <select name="sex" class="form-control" required>
                                    <option value="l" {{ 'l' == old('sex', $data->sex) ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="p" {{ 'p' == old('sex', $data->sex) ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('after-scripts')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.select2-parent_id').select2({
            placeholder: "Nama Keluarga",
            theme: 'bootstrap4',
            ajax: {
                url: `{{ route('admin.family.child.selectSearch', $parent_id) }}`,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        }).on('select2:opening', function(e) {
            $(this).data('select2').$dropdown.find(':input.select2-search__field').attr('placeholder',
                'Cari Berdasarkan Nama')
        })
    </script>
@endpush
