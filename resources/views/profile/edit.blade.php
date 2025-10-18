@php
    $user = auth()->user();
    $layout = 'layouts.authenticated';
    if ($user->hasRole('admin')) {
        $layout = 'layouts.admin';
    } elseif ($user->hasRole('sales')) {
        $layout = 'layouts.sales';
    } elseif ($user->hasRole('teknisi')) {
        $layout = 'layouts.teknisi';
    }
@endphp

@extends($layout)

@section('title', 'Settings')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Settings
                </h2>
                <div class="text-muted mt-1">Manage your account settings and preferences</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Profile Information</h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Update Password</h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Danger Zone</h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
