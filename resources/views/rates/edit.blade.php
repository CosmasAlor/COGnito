@extends('layouts.app')

@section('content')
    <section class="content">
        <h3>Edit Rate</h3>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('rates.update', $rate) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('rates.form')
                    <button type="submit" class="btn btn-primary">Update Rate</button>
                    <a href="{{ route('rates.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
              </section>

@endsection