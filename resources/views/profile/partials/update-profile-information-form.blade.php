<div class="mb-3">
    <label class="form-label">{{ __('Profile Information') }}</label>
    <p class="text-muted">{{ __("Update your account's profile information and email address.") }}</p>
</div>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<!-- Name Update Form -->
<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Name') }}</label>
        <input id="name" name="name" type="text" class="form-control" :value="old('name', $user->name)" required autofocus autocomplete="name" />
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex">
        <button type="submit" class="btn btn-primary">{{ __('Save Name') }}</button>

        @if (session('status') === 'profile-updated')
            <div class="ms-3 d-flex align-items-center text-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M5 12l5 5l10 -10"/>
                </svg>
                {{ __('Saved.') }}
            </div>
        @endif
    </div>
</form>

<hr class="my-4">

<!-- Email Change Form -->
<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email') }}</label>
        <small class="text-muted d-block mb-2">{{ __('Current email: ') . $user->email }}</small>
        <input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" required autocomplete="username" />
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        @if($user->requested_email)
            <div class="alert alert-warning mt-2">
                <strong>{{ __('Email change requested: ') }}</strong>{{ $user->requested_email }} <em>({{ __('Pending admin approval') }})</em>
            </div>
        @endif

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="text-muted">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification" class="btn btn-outline-primary btn-sm">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-2">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="d-flex">
        <button type="submit" class="btn btn-primary">{{ __('Request Email Change') }}</button>

        @if (session('status') === 'email-change-requested')
            <div class="ms-3 d-flex align-items-center text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <circle cx="12" cy="12" r="9"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ __('Email change request submitted. Waiting for admin approval.') }}
            </div>
        @endif
    </div>
</form>
