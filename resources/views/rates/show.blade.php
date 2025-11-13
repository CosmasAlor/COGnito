@extends('layouts.app')

@section('content')
    <section class="content">

        <h3>Rate Details: {{ $rate->code }}</h3>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> {{ $rate->name }}</p>
                        <p><strong>Code:</strong> {{ $rate->code }}</p>
                        <p><strong>Rate:</strong> {{ $rate->formatted_rate }}</p>
                        <p><strong>Currency:</strong> {{ $rate->currency }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Effective Date:</strong> {{ $rate->effective_date->format('M d, Y') }}</p>
                        <p><strong>Expiry Date:</strong> {{ $rate->expiry_date ? $rate->expiry_date->format('M d, Y') : 'N/A' }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge {{ $rate->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $rate->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                        <p><strong>Current:</strong> 
                            <span class="badge {{ $rate->isCurrent() ? 'bg-success' : 'bg-warning' }}">
                                {{ $rate->isCurrent() ? 'Yes' : 'No' }}
                            </span>
                        </p>
                    </div>
                </div>
                
                @if($rate->description)
                <div class="mt-3">
                    <strong>Description:</strong>
                    <p>{{ $rate->description }}</p>
                </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('rates.edit', $rate) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('rates.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
  </section>
@endsection