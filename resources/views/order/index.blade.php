@extends('layouts.master')

@section('title')
    Order
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboard
        @endslot
        @slot('title')
            Order
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{-- <a href="{{ route('order.create') }}"
                        class="btn btn-outline-danger float-end">{{ __('Create Order') }}</a> --}}
                </div>
                <div class="card-body">
                    {{-- <h4 class="card-title">Filters</h4> --}}
                    <div class="">
                        <table class="table mb-0" id="user_table" data-unique-id="id" data-toggle="table"
                            data-ajax="ajaxRequest" data-side-pagination="server" data-pagination="true"
                            data-total-field="count" data-data-field="items" data-show-columns="false"
                            data-show-toggle="false" data-filter-control="true" data-filter-control-container="#filters"
                            data-show-columns-toggle-all="false">
                            <div id="filters" class="row bootstrap-table-filter-control">
                                {{-- <div class="col-md-2">
                                <label class="form-label">Brand</label>
                                <select class="form-control bootstrap-table-filter-control-product_brand.name" data-placeholder="Select Brand Name" data-field="product_brand.name">
                                    <option value=""></option>
                                </select>
                            </div> --}}
                            <div class="col-md-2">
                                <label class="form-label">Business Name</label>
                                <input type="text" class="form-control bootstrap-table-filter-control-order_by.name" placeholder="Enter Business Name" data-field="order_by.name">
                            </div>
                                {{-- <div class="col-md-1">
                                <label class="form-label">Scent Type</label>
                                <select class="form-control bootstrap-table-filter-control-scent_type.name" data-placeholder="Select Scent Type" data-field="scent_type.name">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Size</label>
                                <input type="text" class="form-control bootstrap-table-filter-control-size" placeholder="Enter Size">
                            </div> --}}
                                {{-- <div class="col-md-2">
                                <label class="form-label">Fragrance Tone</label>
                                <select class="form-control bootstrap-table-filter-control-fragrance_tone_1.name" data-field="fragrance_tone_1.name">
                                    <option value="">Select Fragrance Tone</option>
                                </select>
                            </div> --}}
                                {{-- <div class="col-md-1">
                                <label class="form-label">Price</label>
                                <input type="text" class="form-control bootstrap-table-filter-control-price" placeholder="Enter Price">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Campaign</label>
                                <select class="form-control bootstrap-table-filter-control-campaign.name" data-placeholder="Select Campaign" data-field="campaign.name">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Gender</label>
                                <select class="form-control bootstrap-table-filter-control-gender" data-placeholder="Select Gender" data-field="gender">
                                    <option value=""></option>
                                </select>
                            </div> --}}
                            </div>
                            <button type="button" id="delete_all" style="margin-bottom:20px;margin-top:20px"
                                class="btn btn-outline-danger" data-url="{{ route('product.destroy.selected') }}"   onclick="delete_all()">Delete All Selected</button>
                            <thead>
                                <tr>
                                    <th data-field="counter" data-sortable="true">#</th>
                                    <th data-field="checkbox"><input type="checkbox" id="select_all"
                                            onchange="select_all(this)"></th>
                                    {{-- <th data-field="product_brand.name" data-filter-control="select" data-sortable="true">Brand </th> --}}
                                    {{-- <th data-field="uid" data-filter-control="input" data-sortable="true">Order No.    </th> --}}
                                    {{-- <th data-field="qty" data-filter-control="select" data-sortable="true">Total Products </th> --}}
                                    <th data-field="order_by.name" data-filter-control="select" data-sortable="true">Business Name </th>
                                    <th data-field="created_at" data-filter-control="select" data-sortable="true">Date</th>
                                    {{-- <th data-field="price" data-filter-control="input" data-sortable="true">Price </th> --}}
                                    {{-- <th data-field="campaign.name" data-filter-control="select" data-sortable="true">Campaign </th> --}}
                                    {{-- <th data-field="gender" data-filter-control="select" data-sortable="true">Gender</th> --}}
                                    {{-- <th data-field="status"  data-sortable="true">Status</th> --}}
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
@endsection
@section('script')
    <link href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css" rel="stylesheet">

    <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <script
        src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/filter-control/bootstrap-table-filter-control.min.js">
    </script>

    <script>
        let $table = $('#user_table');
        $table.bootstrapTable({
            pageSize: 100,
            columns: [{}, {}, {},{}, {
                field: 'operate',
                sortable: false,
                title: 'Action',
                align: 'center',
                valign: 'middle',
                clickToSelect: false,
                formatter: function(value, row, index) {
                    let url = "{{ route('order.edit', ['id' => ':queryId']) }}";
                    url = url.replace(':queryId', row.id);
                    let show_url = "{{ route('order.show', ['id' => ':queryId']) }}";
                    show_url = show_url.replace(':queryId', row.id);
                    let status = row.status == 'Active' ? 'Deactive' : 'Active';
                    var class_name = row.status == 'Active' ? 'btn-outline-danger' :
                        'btn-outline-primary';
                        // <button onClick="remove(${row.id}, ${index})" class="btn btn-sm btn-outline-danger">Delete</button>
                    //
                    // <a class="btn btn-sm btn-outline-info" data-bs-toggle="modal" onclick="setOrderId(${row.id},'${row.lr_no}','${row.receipt_image_url}')" data-bs-target="#exampleModal" >Details</a>&nbsp;
                    let action =
                        `   <a href="${show_url}" class="btn btn-sm btn-outline-info">View</a>&nbsp;`;
                        // if(row.status != 'Completed'){
                        //     action += `&nbsp;<select class="form-control mt-2" id="status_` +
                        // index + `" onchange="changeStatus(${row.id}, ${index}, 'status_` + index + `')">
                        //     <option value="Pending" ${row.status == 'Pending' ? `selected` : ''}>Pending</option>
                        //     <option value="Completed" ${row.status == 'Completed' ? 'selected' : ''}>Completed</option>
                        //     <option value="Dispatched" ${row.status == 'Dispatched' ? 'selected' :''}>Dispatched</option>
                        //     <option value="Cancel" ${row.status == 'Cancel' ? 'selected' : ''}>Cancel</option>
                        //     </select>`;
                        // }else{
                        //     action += `<input type="text" class="form-control" value="${row.status}" disabled readonly>`;
                        // }
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

        window.tableFilterStripHtml = function(value) {
            return value.replace(/<[^>]+>/g, '').trim();
        }

        function setOrderId(order_id,lr_no,img) {
            $('#order_id').val(order_id);
            $('#lr_no').val(lr_no);
            var img = "<img src='"+img+"' height='50' width='50' onerror=this.src='/images/placeholder.png'>";
            $('#old_image').html(img);
        }

        function remove(id, index) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('order.destroy', ['id' => ':queryId']) }}";
                    url = url.replace(':queryId', id);
                    $.ajax({
                        url: url,
                        type: "get",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data, textStatus, jqXHR) {
                            console.log(data);
                            if (data.success) {
                                $table.bootstrapTable('removeByUniqueId', id);

                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal
                                            .resumeTimer)
                                    }
                                })
                                Toast.fire({
                                    icon: 'success',
                                    title: data.message
                                });

                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                        }
                    });
                }
            })

        }

        function changeStatus(id, index, status) {
            var status = $('#' + status).val();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn bg-success mt-2",
                cancelButtonClass: "btn bg-danger ms-2 mt-2",
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('order.change_status', ['id' => ':queryId']) }}";
                    url = url.replace(':queryId', id);
                    $.ajax({
                        url: url,
                        type: "post",
                        data: {
                            status: status
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data, textStatus, jqXHR) {
                            if (data.success) {
                                $table.bootstrapTable('refresh');
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal
                                            .resumeTimer)
                                    }
                                })
                                Toast.fire({
                                    icon: 'success',
                                    title: data.message
                                });

                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                        }
                    });
                }
            })

        }


        function addDetails() {
            var order_id = $('#order_id').val();
            let url = "{{ route('order.addDetails') }}";
            // url = url.replace(':queryId', id);
            $.ajax({
                url: url,
                type: "post",
                data: new FormData($('#orderDetails')[0]),
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data, textStatus, jqXHR) {
                    if (data.success) {
                        $table.bootstrapTable('refresh');
                        $('#close_order_dtls').trigger('click');
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal
                                    .resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $.each(jqXHR.responseJSON.errors,function(field_name,error){
                        $(document).find('[name='+field_name+']').after('<span class="text-strong text-danger">' +error+ '</span>')
                    })
                }
            });
        }

        function select_all(e) {
            $(".sub_chk").prop('checked', e.checked);
        }

        function delete_all() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: "btn bg-success mt-2",
                cancelButtonClass: "btn bg-danger ms-2 mt-2",
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var allVals = [];
                    $(".sub_chk:checked").each(function() {
                        allVals.push($(this).attr('data-id'));
                    });

                    if (allVals.length <= 0) {
                        alert("Please select at least one product.");
                    } else {
                        var join_selected_values = allVals.join(",");
                        $.ajax({
                            url: $('#delete_all').data('url'),
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: 'ids=' + join_selected_values,
                            success: function(data, textStatus, jqXHR) {
                                if (data['success']) {
                                    $table.bootstrapTable('refresh');
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal
                                                .stopTimer)
                                            toast.addEventListener('mouseleave', Swal
                                                .resumeTimer)
                                        }
                                    })
                                    Toast.fire({
                                        icon: 'success',
                                        title: data.success
                                    });
                                } else if (data['error']) {
                                    alert('Whoops Something went wrong!!');
                                } else {
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function(data) {
                                alert('Whoops Something went wrong!!');
                            }
                        });
                    }
                }
            })

        }
    </script>

    @if (Session::has('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            Toast.fire({
                icon: 'success',
                title: "{{ Session::get('success') }}"
            });
        </script>
    @endif
@endsection
