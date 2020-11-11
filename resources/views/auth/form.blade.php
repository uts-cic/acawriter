<form class="form-horizontal" method="POST" action="{{ route('login') }}" autocomplete="off">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="email" class="control-label">Email address</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
        <div class="invalid-feedback">
            <strong>{{ $message }}</strong>
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password" class="control-label">Password</label>
        <input id="password" type="password" class="form-control @error('email') is-invalid @enderror" name="password" required>
        @error('password')
        <div class="invalid-feedback">
            <strong>{{ $message }}</strong>
        </div>
        @enderror
    </div>

    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>
        </div>
    </div>

    <div class="form-group">
        @if (Route::has('password.request'))
        <div class="row">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    Login
                </button>
            </div>
            <div class="col-md-6">
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a>
            </div>
        </div>
        @else
        <button type="submit" class="btn btn-primary">Log in</button>
        @endif
    </div>


</form>
