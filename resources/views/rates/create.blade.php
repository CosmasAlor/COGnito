@extends('layouts.app')

@section('content')
    <section class="content">
        <h3>Create New Rate</h3>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('rates.store') }}" method="POST">
                    @csrf
                    @include('rates.form')
                    <button type="submit" class="btn btn-success">Create Rate</button>
                    <a href="{{ route('rates.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
      </section>
@endsection