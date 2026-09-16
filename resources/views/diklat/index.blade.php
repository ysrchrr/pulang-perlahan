@extends('layouts.app')
@push('styles')
    <style>
        #table-kegiatan_wrapper,
        #table-kegiatan_wrapper .table-responsive,
        #table-kegiatan_wrapper .dataTables_scroll,
        #table-kegiatan_wrapper .dataTables_scrollBody,
        #table-kegiatan_wrapper .dataTables_scrollHead,
        #table-kegiatan,
        #table-kegiatan tbody,
        #table-kegiatan tr,
        #table-kegiatan td,
        .card,
        .card-body {
            overflow: visible !important;
        }

        #table-kegiatan td {
            position: relative;
        }

        #table-kegiatan .dropdown-menu {
            z-index: 2000;
        }

        body>.dropdown-menu.dropdown-menu-floating {
            position: fixed;
            z-index: 99999;
            display: block;
        }
    </style>
@endpush
@section('contents')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">{{ $page_title }}</h4>
                                <div class="flex-shrink-0">
                                    <div class="btn-group">
                                        <a href="{{ route('diklat-enrollment') }}" class="btn btn-primary">
                                            <i class="fa-solid fa-plus"></i> Enroll Baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed"
                                        id="table-kegiatan">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Kegiatan</th>
                                                <th class="text-center">Periode Pelaksanaan</th>
                                                <th class="text-center">Lokasi</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            loadTable();
        });

        function loadTable() {
            if ($.fn.DataTable.isDataTable('#table-kegiatan')) {
                $('#table-kegiatan').DataTable().destroy();
            }
            $('#table-kegiatan').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: "{{ route('diklat-data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center va-middle'
                    },
                    {
                        data: 'nama_kegiatan',
                        name: 'nama_kegiatan',
                        className: 'va-middle'
                    },
                    {
                        data: 'periode_pelaksanaan',
                        name: 'periode_pelaksanaan',
                        className: 'va-middle'
                    },
                    {
                        data: 'lokasi_kegiatan',
                        name: 'lokasi_kegiatan',
                        className: 'va-middle'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'va-middle text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });
        }
    </script>
@endpush
