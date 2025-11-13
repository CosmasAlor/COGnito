@extends('layouts.app')
@section('title', __('unit.units'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Rates
            <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">Manage Rates (SSP against USD)</small>
        </h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('unit.all_your_units')])
            @can('unit.create')
                @slot('tool')
                    <div class="box-tools">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <a href="{{ route('rates.create') }}" class="btn btn-primary">Create New Rate</a>
                        </div>
                    </div>
                @endslot
            @endcan
            @can('unit.view')
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Month and Date</th>
                                <th>Date Created</th>
                                <th>Buying Rate</th>
                                 <th>Selling Rate</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th>Updated on</th>
                                <th>View & Edit</th>
                                <th>Action</th>
                                 <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rates as $rate)
                            <tr>
                                <td>{{ $rate->effective_date->format('M d, Y') }}</td>
                                <td>{{ $rate->created_at }}</td>
                                <td>{{ $rate->formatted_rate }}</td>
                                
                            <td>{{ $rate->formatted_selling_rate }}</td>
                            <td>{{ $rate->currency }}</td>
                                
                                
                                <td>
                                    <span class="badge {{ $rate->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $rate->updated_at }}</td>
                                <td>
                                    <a href="{{ route('rates.show', $rate) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('rates.edit', $rate) }}" class="btn btn-sm btn-warning">Edit</a>

                                </td>
                                
                                <td>
                                    
                                    <form action="{{ route('rates.toggle-status', $rate) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $rate->is_active ? 'btn-warning' : 'btn-success' }}">
                                            {{ $rate->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                                
                                
                                <td>
                                    <form action="{{ route('rates.destroy', $rate) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>  
                                

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endcan
        @endcomponent

        <div class="modal fade unit_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
    
    
    
    @section('css')
<style>

        
.main-sidebar {
    display: block !important;
    transform: translateX(0) !important;
}

.sidebar-menu {
    display: block !important;
}
    
</style>
@endsection

@section('javascript')

<script src="{{ asset('js/app.js') }}"></script>

@endsection
    

@endsection