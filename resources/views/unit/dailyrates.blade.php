@extends('layouts.app')

@section('title', trans('unit.units'))

@section('content')

<!-- Content Header -->
<section class="content-header">
    <h1>
        Daily Rates
        <small>Manage Daily Exchange Rates SSP against USD</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">




    <div class="box box-primary">
        <div class="box-header with-border">
            @if(auth()->user()->can('unit.createrate'))
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-primary btn-modal" 
                        data-href="{{ action('UnitController@createrate') }}" 
                        data-container=".unit_modal">
                        <i class="fa fa-plus"></i> {{ trans('messages.add') }}
                    </button>
                </div>
            @endif
        </div>

        @if(auth()->user()->can('unit.view'))
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="rate_table">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Date Added</th>
                                <th>
                                    
                                    Date Updated
                             
                                </th>
                                <th>{{ trans('messages.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade unit_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"></div>

</section>
<!-- /.content -->

@endsection
