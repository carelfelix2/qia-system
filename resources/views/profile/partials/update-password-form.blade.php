<div class="mb-3">
    <label class="form-label">{{ __('Update Password') }}</label>
    <p class="text-muted">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
</div>

<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
        <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
        @error('current_password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
        <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
        @error('password_confirmation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex">
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

        @if (session('status') === 'password-updated')
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
