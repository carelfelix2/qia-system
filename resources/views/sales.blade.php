@extends('layouts.sales')
@section('title', 'Sales Dashboard')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hallo {{ auth()->user()->name }} 👨‍💼</h3>
            </div>
            <div class="card-body">
                <p>Berhasil masuk dashboard sales.</p>
            </div>
        </div>
    </div>
</div>
@endsection
