@extends('layouts.app')
@section('title', __('lang_v1.import_sales'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.import_sales')</h1>
</section>

<!-- Main content -->
<section class="content">
    @if (session('notification') || !empty($notification))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @if(!empty($notification['msg']))
                        {{$notification['msg']}}
                    @elseif(session('notification.msg'))
                        {{ session('notification.msg') }}
                    @endif
                </div>
            </div>  
        </div>     
    @endif
    <div class="row">
        <div class="col-md-12">
            @component('components.widget')
                {!! Form::open(['url' => action([\App\Http\Controllers\ImportSalesController::class, 'preview']), 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!}
                    <div class="row">
                        <div class="col-sm-6">
                        <div class="col-sm-8">
                            <div class="form-group">
                                {!! Form::label('name', __( 'product.file_to_import' ) . ':') !!}
                                {!! Form::file('sales', ['required' => 'required']); !!}
                              </div>
                        </div>
                        <div class="col-sm-4">
                        <br>
                            <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">@lang('lang_v1.upload_and_review')</button>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <br>
                            <a href="{{ asset('files/import_sales_template.xlsx') }}" class="tw-dw-btn tw-dw-btn-success tw-text-white" download><i class="fa fa-download"></i> @lang('lang_v1.download_template_file')</a>
                        </div>
                    </div>

                {!! Form::close() !!}
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['title' => __('lang_v1.instructions')])
            <table class="table table-condensed">
                <tr>
                    <td>1.</td>
                    <td>@lang('lang_v1.upload_data_in_excel_format')</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>@lang('lang_v1.choose_location_and_group_by')</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>@lang('lang_v1.map_columns_with_respective_sales_fields')</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>
                        <table class="table table-striped table-">
                            <tr>
                                <th>@lang('lang_v1.importable_fields')</th>
                                <th>@lang('lang_v1.instructions')</th>
                            </tr>
                            @foreach($import_fields as $key => $value)
                                <tr>
                                    <td>
                                        {{$value['label']}}
                                    </td>
                                    <td>
                                        <small>{{$value['instruction'] ?? ''}}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['title' => __('lang_v1.imports')])
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>@lang('lang_v1.import_batch')</th>
                        <th>@lang('lang_v1.import_time')</th>
                        <th>@lang('business.created_by')</th>
                        <th>@lang('lang_v1.s')</th>
                        @can('sell.delete')
                            <th>@lang('messages.action')</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($imported_sales_array as $key => $value)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{@format_datetime($value['import_time'])}}</td>
                            <td>{{$value['created_by']}}</td>
                            <td>
                                {{implode(', ', $value['invoices'])}} <br>
                                <p class="text-muted text-right">
                                <small>(@lang('sale.total'): {{count($value['invoices'])}})</small>
                                </p>
                            </td>
                            @can('sell.delete')
                                <td><a href="{{action([\App\Http\Controllers\ImportSalesController::class, 'revertSaleImport'], $key)}}" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error revert_import"><i class="fas fa-undo"></i> @lang('lang_v1.revert_import')</a></td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endcomponent
        </div>
    </div>
</section>
@stop
@section('javascript')
<script type="text/javascript">
    $(document).on('click', 'a.revert_import', function(e){
        e.preventDefault();
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                window.location = $(this).attr('href');
            } else {
                return false;
            }
        });
    });
</script>
@endsection