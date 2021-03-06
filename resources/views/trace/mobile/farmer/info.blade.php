@extends('layouts.login')

@section('title', 'Add Inventory')

@section('content')
    <div class="ibox-content">
        <div class="text-center">
            <img src="{{ URL::to('/images/logo.png') }}" alt="agrabah-logo" class="logo img-fluid mt-3" width="250">
        </div>
    </div>

    <section class="container">
        <div class="row mt-2">
            <div class="col">
                <a href="{{ route('home') }}" class="btn btn-block btn-info">Dashboard</a>
            </div>
            <div class="col">
                <a href="{{ route('farmer-login') }}" class="btn btn-block btn-warning">Scan Farmer ID</a>
            </div>
        </div>
    </section>

    <section class="container mt-2">

        <div class="row">

            {{--            <div class="col-sm-3">--}}
            {{--                <div class="ibox float-e-margins">--}}
            {{--                    <div class="ibox-title">--}}
            {{--                        <h5>Farmer <small>Info</small></h5>--}}
            {{--                    </div>--}}
            {{--                    <div class="ibox-content">--}}
            {{--                        <div class="mb-2">--}}
            {{--                            <h3 class="mb-0">{!! $data->account_id !!}</h3>--}}
            {{--                            <small class="text-success">Account ID</small>--}}
            {{--                        </div>--}}
            {{--                        <div class="mb-2">--}}
            {{--                            <h3 class="mb-0">{!! $data->profile->first_name !!} {!! $data->profile->last_name !!}</h3>--}}
            {{--                            <small class="text-success">Name</small>--}}
            {{--                        </div>--}}
            {{--                        --}}{{--                        <div class="mb-2">--}}
            {{--                        --}}{{--                            <button class="btn btn-block btn-success btn-xl btn-action">Add Item</button>--}}
            {{--                        --}}{{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{!! $data->profile->first_name !!} {!! $data->profile->last_name !!} <small>Inventory</small></h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quality</th>
                                    <th class="text-right" style="width: 100px;">Qty</th>
                                    <th class="text-right" style="width: 50px" data-sort-ignore="true"><i class="fa fa-cogs text-success"></i></th>
                                </tr>
                                </thead>
                                <tbody id="inv-list">
                                @foreach($data->listing as $list)
                                    <tr>
                                        <td>{{ $list->product->display_name }}</td>
                                        <td>{{ $list->quality }}</td>
                                        <td class="text-right">{{ $list->quantity }} {{ $list->unit }}</td>
                                        <td class="text-right">
                                            <div class="btn-group text-right">
                                                <button class="btn btn-white btn-xs btn-action" data-action="remove-item" data-id="{{ $list->id }}"><i class="fa fa-times text-danger"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                {{--                                <tr>--}}
                                {{--                                    <td class="text-right" colspan="4">--}}
                                {{--                                        <button type="button" class="btn btn-success btn-action" data-action="add-item">Add</button>--}}
                                {{--                                    </td>--}}
                                {{--                                </tr>--}}
                                </tfoot>
                            </table>
                        </div>
                        <div class="row">
                            {{--                            <div class="col"></div>--}}
                            <div class="col">
                                <button type="button" class="btn btn-success btn-action btn-block p-2" data-action="add-inventory" data-master="{!! $data->master_id !!}" data-farmer="{!! $data->id !!}"><h2><strong>ADD INVENTORY</strong></h2></button>
                            </div>
                            {{--                            <div class="col"></div>--}}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>


    <div class="modal inmodal fade" id="modal" data-type="" tabindex="-1" role="dialog" aria-hidden="true" data-category="" data-variant="" data-bal="">
        <div id="modal-size">
            <div class="modal-content">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-action" data-action="store-inventory">Save</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    {!! Html::style('css/template/plugins/select2/select2.min.css') !!}
    {!! Html::style('css/template/plugins/select2/select2-bootstrap4.min.css') !!}
    {{--{!! Html::style('') !!}--}}
@endsection

@section('scripts')
    {!! Html::script('js/template/plugins/select2/select2.full.min.js') !!}
    {{--    {!! Html::script('') !!}--}}
    <script>
        $(document).ready(function(){
            var modal = $('#modal');
            $(document).on('click', '.btn-action', function(){
                switch($(this).data('action')){
                    case 'add-inventory':
                        var lists = new Array();
                        jQuery.ajaxSetup({async:false});
                        $.get('{!! route('product-list') !!}', function(data){
                            if(data.length > 0){
                                for(var a = 0; a < data.length; a++){
                                    lists.push('<option value="'+ data[a].id +'">'+ data[a].display_name +'</option>');
                                }
                            }
                        });
                        modal.data('master', $(this).data('master'));
                        modal.data('farmer', $(this).data('farmer'));
                        modal.find('.modal-title').text('Inventory Info');
                        modal.find('#modal-size').removeClass().addClass('modal-dialog modal-sm');
                        modal.find('.modal-body').empty().append('' +
                            '<div class="form-group">' +
                            '<label>Products <small class="text-danger">*</small></label>' +
                            '<select name="product" class="select2 form-control">' +
                            '<option value=""></option>' +
                            lists.join('') +
                            '</select>' +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="col">' +
                            '<div class="form-group">' +
                            '<label>Quality <small class="text-danger">*</small></label>' +
                            '<select name="quality" class="form-control">' +
                            '<option value="">select</option>' +
                            '<option value="High">High</option>' +
                            '<option value="Medium">Medium</option>' +
                            '<option value="Low">Low</option>' +
                            '</select>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col">' +
                            '<div class="form-group">' +
                            '<label>Unit <small class="text-danger">*</small></label>' +
                            '<select name="unit" class="form-control"></select>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="col">' +
                            '<div class="form-group">' +
                            '<label>Qty <small class="text-danger">*</small></label>' +
                            '<input type="number" name="quantity" value="0" class="form-control numonly amount-input text-right">' +
                            '</div>' +
                            '</div>' +
                            '<div class="col">' +
                            '<div class="form-group">' +
                            '<label>Unit price <small class="text-danger">*</small></label>' +
                            '<input type="text" name="price" value="0" class="form-control numonly amount-input text-right">' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="col form-group">' +
                            '<label>Total amount <small class="text-danger">*</small></label>' +
                            '<input type="text" name="total" placeholder="0.00" class="form-control text-right" readonly>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                            '<label>Remark</label>' +
                            '<textarea name="remark" class="form-control no-resize"></textarea>' +
                            '</div>' +
                            '<div id="batch-box">' +
                            '<button type="button" class="btn btn-success btn-block btn-action" data-action="add-batch">Add Batch ID</button>' +
                            '</div>' +
                            '');

                        // $(".select2_demo_3").select2({
                        //     theme: 'bootstrap4',
                        //     placeholder: "Select product",
                        //     allowClear: true
                        // });

                        $(".select2").select2({
                            theme: 'bootstrap4',
                            tags: true,
                            dropdownParent: $("#modal"),
                            placeholder: "Select product"
                        });

                        modal.modal({backdrop: 'static', keyboard: false});
                        break;
                    case 'store-inventory':
                        var invDetails = new Array();
                        invDetails.push(modal.data('master'));
                        invDetails.push(modal.data('farmer'));
                        invDetails.push(modal.find('select[name=product]').val());
                        invDetails.push(modal.find('select[name=quality]').val());
                        invDetails.push(modal.find('select[name=unit]').val());
                        invDetails.push(modal.find('input[name=quantity]').val());
                        invDetails.push(modal.find('input[name=price]').val());
                        invDetails.push(modal.find('input[name=total]').val());
                        invDetails.push(modal.find('textarea[name=remark]').val());
                        if(modal.find('.batch-id')[0]){
                            invDetails.push(modal.find('.batch-id').val());
                        }else{
                            invDetails.push(null);
                        }


                        console.log(invDetails);

                        $.post('{!! route('inv-listing-store') !!}', {
                                _token: '{!! csrf_token() !!}',
                                details: invDetails
                            }, function(data){
                                console.log(data);
                                $('#inv-list').append('' +
                                    '<tr>' +
                                        '<td>'+ data.product.display_name +'</td>' +
                                        '<td>'+ data.quality +'</td>' +
                                        '<td class="text-right">'+ data.quantity +' '+ data.unit +'</td>' +
                                        '<td class="text-right">' +
                                            '<div class="btn-group text-right">' +
                                                '<button class="btn btn-white btn-xs btn-action" data-action="remove-item" data-id="'+ data.id +'"><i class="fa fa-times text-danger"></i></button>' +
                                            '</div>' +
                                        '</td>' +
                                    '</tr>' +
                                    '');
                                modal.modal('toggle');
                            });

                            break;
                    case 'remove-item':
                        var tr = $(this).closest('tr');
                        $.get('{!! route('inv-listing-delete') !!}', {
                            id: $(this).data('id')
                        }, function(data){
                            tr.remove();

                            console.log('deleted');
                        });
                        break;
                    case 'add-batch':

                        $.get('{!! route('inv-batch-list-get') !!}', function(data){
                            console.log(data);

                            if(data.length > 0){
                                var list = new Array();
                                for(var a = 0; a < data.length; a++){
                                    list.push('<option value="'+ data[a] +'">'+ data[a] +'</option>');
                                }
                                modal.find('#batch-box').empty().append('' +
                                    '<div class="form-group">' +
                                    '<label>Batch ID</label>' +
                                    '<select name="batch-id" class="form-control batch-id">' +
                                    // '<option value="">select</option>' +
                                    list +
                                    '</select>' +
                                    '</div>' +
                                    '<button type="button" class="btn btn-warning btn-block btn-action" data-action="new-batch">New Batch ID</button>' +
                                    '');
                                showBatchList(data[0]);
                            }else{
                                var randomVal = Math.random().toString(36).substr(2, 20);
                                modal.find('#batch-box').empty().append('' +
                                    '<div class="form-group">' +
                                    '<label>Batch ID</label>' +
                                    '<div class="input-group">' +
                                    '<input type="text" name="batch-id" value="'+ randomVal +'" class="form-control batch-id" readonly>' +
                                    '<span class="input-group-append">' +
                                    '<button type="button" class="btn btn-white btn-action" data-action="refresh-batch-id"><i class="fa fa-refresh text-success"></i></button>' +
                                    '</span>' +
                                    '</div>' +
                                    '</div>' +
                                    '');
                            }
                        });

                        // modal.find('#batch-box').empty().append();
                        break;
                    case 'refresh-batch-id':
                        var reRandomVal = Math.random().toString(36).substr(2, 20);
                        modal.find('#batch-box').find('input[name=batch-id]').val(reRandomVal);
                        break;
                    case 'new-batch':
                        var randomVal = Math.random().toString(36).substr(2, 20);
                        modal.find('#batch-box').empty().append('' +
                            '<div class="form-group">' +
                            '<label>Batch ID</label>' +
                            '<div class="input-group">' +
                            '<input type="text" name="batch-id" value="'+ randomVal +'" class="form-control batch-id" readonly>' +
                            '<span class="input-group-append">' +
                            '<button type="button" class="btn btn-white btn-action" data-action="refresh-batch-id"><i class="fa fa-refresh text-success"></i></button>' +
                            '</span>' +
                            '</div>' +
                            '</div>' +
                            '');
                        break;
                }

                // modal.modal('toggle');
            });

            function showBatchList(value){
                var lists = new Array();
                jQuery.ajaxSetup({async:false});
                $.get('{!! route('inv-batch-list') !!}', {
                    id: value
                }, function(data){
                    console.log(data);
                    if(data.length > 0){
                        for(var a = 0; a < data.length; a++){
                            lists.push('' +
                                '<tr>' +
                                '<td>'+ data[a].product.display_name +'</td>' +
                                '<td>'+ data[a].quality +'</td>' +
                                '<td class="text-right">'+ data[a].quantity +' '+ data[a].unit +'</td>' +
                                '</tr>' +
                                '');
                        }
                    }
                });
                modal.find('#batch-box').append('' +
                    '<div class="table-responsive">' +
                    '<table class="table table-striped">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Product</th>' +
                    '<th>Quality</th>' +
                    '<th class="text-right" style="width: 100px;">Qty</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>' +
                    lists.join('') +
                    '</tbody>' +
                    '</table>' +
                    '</div>' +
                    '');
            }

            $(document).on('change', 'select[name=product]', function(){
                console.log('change');
                var lists = new Array();
                jQuery.ajaxSetup({async:false});
                $.get('{!! route('product-unit-list') !!}', {
                    id: $(this).val()
                }, function(data){
                    if(data.length > 0){
                        for(var a = 0; a < data.length; a++){
                            lists.push('<option value="'+ data[a].name +'">'+ data[a].name +'</option>');
                        }
                    }
                });
                modal.find('select[name=unit]').empty().append(lists);
            });

            $(document).on('change', 'select[name=batch-id]', function(){
                modal.find('#batch-box').children().last().remove();
                showBatchList($(this).val());
            });

            $(document).on('keyup change', '.amount-input', function(){

                // console.log('price: '+ modal.find('input[name=price]').val());
                // console.log('quantity: '+ modal.find('input[name=quantity]').val());

                var amount = 0,
                    price = parseFloat(modal.find('input[name=price]').val()),
                    quantity = parseInt(modal.find('input[name=quantity]').val()),
                    total = modal.find('input[name=total]');
                amount = quantity * price;
                total.val(amount);
            });


            {{-- var table = $('#table').DataTable({--}}
            {{--     processing: true,--}}
            {{--     serverSide: true,--}}
            {{--     ajax: {--}}
            {{--         url: '{!! route('') !!}',--}}
            {{--         data: function (d) {--}}
            {{--             d.branch_id = '';--}}
            {{--         }--}}
            {{--     },--}}
            {{--     columnDefs: [--}}
            {{--         { className: "text-right", "targets": [ 0 ] }--}}
            {{--     ],--}}
            {{--     columns: [--}}
            {{--         { data: 'name', name: 'name' },--}}
            {{--         { data: 'action', name: 'action' }--}}
            {{--     ]--}}
            {{-- });--}}

            {{--table.ajax.reload();--}}

        });
    </script>
@endsection
