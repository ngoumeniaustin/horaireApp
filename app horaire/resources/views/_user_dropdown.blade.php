<div class="userInfo">
    @auth
    <div class="dropleft">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class='fa fa-user'></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <div class="text-center" style='color:#2C3E50; font-size:1.2em;'><i class='fa-solid fa-circle-user' style='color:#2C3E50'></i></i> {{Auth::user()->name}}</div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" style='color:#2C3E50; font-size:1em;' href="{{route('logout')}}"><i class='fa fa-sign-out' style='color: #2C3E50'></i> Sign-out</a>
        </div>
    </div>
    @else
    <a class="btn btn-danger" href="{{route('auth.google')}}">Sign in with <i class="fa-brands fa-google"></i></a>
</div>
    @if (session('error'))
    <div class="alert alert-danger user-error">
        {{ session('error') }}
    </div>
    @endif
    @endauth