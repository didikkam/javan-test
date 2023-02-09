@extends('layouts.admin')

@section('title', 'Keluarga')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Keluarga</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Keluarga</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        @include('admin.includes.messages')
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <table id="tb_data" class="table table-bordered table-hover dataTable dtr-inline" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </section>
    </div>
@endsection

@push('after-scripts')
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/bootstrap4.min.css') }}">
    <script type="text/javascript" charset="utf8" src="{{ asset('assets/plugins/dataTables/dataTables.min.js') }}"></script>
    <script type="text/javascript" charset="utf8" src="{{ asset('assets/plugins/dataTables/bootstrap4.min.js') }}"></script>
    <script>
        var tabel = null;
        $(document).ready(function() {
            tabel = $('#tb_data').DataTable({
                order: [
                    [0, 'desc']
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.family.datatable_view') }}",
                    type: "GET",
                    "data": function(d){
                    	var data_form = {
                        grandson_parent_id: "{{isset($grandson_parent_id) ? $grandson_parent_id : ''}}",
                        granddaughter_parent_id: "{{isset($granddaughter_parent_id) ? $granddaughter_parent_id : ''}}",
                        aunt_parent_id: "{{isset($aunt_parent_id) ? $aunt_parent_id : ''}}",
                        malecousin_parent_id: "{{isset($malecousin_parent_id) ? $malecousin_parent_id : ''}}",
                    	};
                    	data_form = JSON.stringify(data_form);
                    	data_form = JSON.parse(data_form);
                    	d.form = data_form;
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: "text-center",
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'sex',
                        name: 'sex',
                        render: function(data, type, row, meta) {
                            var btn = ``;
                            if(row.sex=='l'){
                                btn = "Laki-laki";
                            }
                            if(row.sex=='p'){
                                btn = "Perempuan";
                            }
                            return btn;
                        },
                    },
                    {
                        data: "id",
                        render: function(data, type, row, meta) {
                            var btn = ``;
                            btn +=
                                `<a class="ml-1 btn btn-info btn-sm" href="{{ route('admin.family.index') }}/` +
                                row.id + `/aunt" title="Bibi"><i class="fas fa-search"></i> Bibi</a>`;
                            btn +=
                                `<a class="ml-1 btn btn-info btn-sm" href="{{ route('admin.family.index') }}/` +
                                row.id + `/malecousin" title="Sepupu Laki-Laki"><i class="fas fa-search"></i> Sepupu Laki-Laki</a>`;
                            return btn;
                        },
                        className: "text-center",
                        orderable: false
                    },
                ]
            });
        });
    </script>
@endpush
