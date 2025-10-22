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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Profile Photo</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <span class="avatar avatar-xl mb-3" id="sidebar-avatar" style="background-image: url({{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://preview.tabler.io/static/avatars/000m.jpg' }})"></span>

                            <script>
                            function previewImage(input) {
                                if (input.files && input.files[0]) {
                                    const file = input.files[0];
                                    const reader = new FileReader();

                                    reader.onload = function(e) {
                                        document.getElementById('sidebar-avatar').style.backgroundImage = `url(${e.target.result})`;
                                        document.getElementById('save-photo-btn').style.display = 'block';
                                    };

                                    reader.readAsDataURL(file);
                                }
                            }

                            // Hide save button when form is submitted
                            document.getElementById('photo-upload-form').addEventListener('submit', function() {
                                document.getElementById('save-photo-btn').style.display = 'none';
                            });
                            </script>

                            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="photo-upload-form">
                                @csrf
                                @method('patch')

                                <input type="file" name="profile_photo" id="profile_photo_input" class="d-none" accept="image/*" onchange="previewImage(this)">
                                <div class="mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="document.getElementById('profile_photo_input').click()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M15 8h.01"/>
                                            <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"/>
                                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l4 4"/>
                                            <path d="M14 14l1 -1c.653 -.629 1.347 -.629 2 0l.5 .5"/>
                                            <circle cx="19" cy="19" r="3"/>
                                            <path d="M21 17v4h-4"/>
                                        </svg>
                                        {{ __('Choose Photo') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm w-100 mt-2" id="save-photo-btn" style="display: none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10"/>
                                        </svg>
                                        {{ __('Save Photo') }}
                                    </button>
                                </div>
                                <small class="text-muted">{{ __('Max size: 2MB. Supported formats: JPEG, PNG, JPG, GIF.') }}</small>
                                @error('profile_photo')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror

                                @if (session('status') === 'profile-updated' && request()->hasFile('profile_photo'))
                                    <div class="alert alert-success mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10"/>
                                        </svg>
                                        {{ __('Profile photo updated successfully!') }}
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>

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
