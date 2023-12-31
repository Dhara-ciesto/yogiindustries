@extends('layouts.master')

@section('title') Dashboard @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Home @endslot
@slot('title') Dashboard @endslot
@endcomponent
@if(Auth::user()->id == 1)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3>Welcome !</h3>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endif
@if(Auth::user()->role == 1 || Auth::user()->role == 0)
<div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium"><a href="{{ route('product.category.index') }}">Total Catgory </a></p>
                                <h4 class="mb-0">{{$data['category']}}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-copy-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium"><a href="{{ route('product.index') }}">Total Products </a></p>
                                <h4 class="mb-0">{{$data['products']}}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-copy-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium"><a href="{{ route('order.index') }}">Total Order </a></p>
                                <h4 class="mb-0">{{$data['order']}}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-copy-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end col -->


</div>
@endif
<!-- end row -->

{{-- @endsection --}}
@section('script')
<!-- apexcharts -->
{{-- <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script> --}}
<!-- blog dashboard init -->
{{-- <script src="{{ URL::asset('/assets/js/pages/dashboard-blog.init.js') }}"></script> --}}
@endsection

@endsection

@push('js')

<!-- Responsive Table js -->
{{-- <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script> --}}

<!-- Init js -->
{{-- <script src="{{ URL::asset('/assets/js/pages/table-responsive.init.js') }}"></script> --}}

<link href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css" rel="stylesheet">


<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/export/bootstrap-table-export.min.js">
</script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/tableExport.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF/jspdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{asset('assets/js/datatable.js')}}"></script>


    <script>
        function nameFormatter(value, row, index) {
            var data = '<b>Buyer Name: </b>'+ value + "<br><b>Village Name: </b>" + row.village_name + '<br><b>Date: </b>'  +row.created_at;
             return data;
        }
        let $table = $('#user_table');
        $table.bootstrapTable({
            columns: [{}, {},  {
                field: 'operate',
                sortable: 'false',
                title: 'Action',
                align: 'center',
                valign: 'middle',
                clickToSelect: false,
                formatter: function(value, row, index) {
                    let url = "{{ route('order.edit', ['id' => ':queryId']) }}";
                    url = url.replace(':queryId', row.id);
                    let show_url = "{{ route('order.print', ['id' => ':queryId']) }}";
                    show_url = show_url.replace(':queryId', row.id);
                    let status = row.status == 'Active' ? 'Deactive' : 'Active';
                    var class_name = row.status == 'Active' ? 'btn-outline-danger' :
                        'btn-outline-primary';
                    // <a href="${show_url}" class="btn btn-sm btn-outline-info">View</a>&nbsp;
                    let action =
                        `<a href="${show_url}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-print"></i></a>`;
                    return action;
                }
            }]
        });


        function ajaxRequest(params) {
            var url = "{{ route('order.server_side') }}"
            $.get(url + '?' + $.param(params.data)).then(function(res) {
                params.success(res)
            })
        }
        </script>
@endpush
