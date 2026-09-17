@extends('layouts.app')
@section('title',ucfirst($mode))
@section('content')
<section class="auth-layout container"><div class="auth-story"><span class="eyebrow">A BETTER PERSPECTIVE STARTS HERE</span><h1>Your curiosity.<br>Your journey.<br><em>Your edge.</em></h1><p>Research with depth. Tools with purpose.<br>A workspace for the way you invest.</p><div class="auth-symbol">↗</div></div><div class="form-panel"><span class="eyebrow">WELCOME TO SHARESRISE</span><h2>{{ ['login'=>'Good to have you back.','register'=>'Find your edge.','forgot'=>'Let’s get you back in.','reset'=>'A fresh start.'][$mode] }}</h2><p>{{ $mode==='register'?'Start your seven-day research trial. No card required.':($mode==='login'?'Sign in to your investor workspace.':'Choose a secure way back to your account.') }}</p><form method="post" action="{{ ['login'=>route('login'),'register'=>route('register'),'forgot'=>route('password.email'),'reset'=>route('password.update')][$mode] }}">@csrf
@if($mode==='register')<label>Full name<input name="name" value="{{ old('name') }}" autocomplete="name" required maxlength="120"></label>@endif
<label>Email address<input name="email" type="email" value="{{ old('email',request('email')) }}" autocomplete="email" required></label>
@if($mode!=='forgot')<label>Password<input type="password" name="password" autocomplete="{{ $mode==='login'?'current-password':'new-password' }}" required minlength="8"></label>@endif
@if(in_array($mode,['register','reset']))<label>Confirm password<input type="password" name="password_confirmation" autocomplete="new-password" required minlength="8"></label>@endif
@if($mode==='reset')<input type="hidden" name="token" value="{{ $token }}">@endif
@if($mode==='login')<div class="form-between"><label class="consent"><input type="checkbox" name="remember"> Remember me</label><a href="{{ route('password.request') }}">Forgot password?</a></div>@endif
@if($mode==='register')<label class="consent"><input type="checkbox" name="consent" value="1" required> I accept the <a href="{{ route('page','terms') }}">terms of use</a> and <a href="{{ route('page','privacy') }}">privacy policy</a>.</label>@endif
<button class="button full">{{ ['login'=>'Log in','register'=>'Start my free trial','forgot'=>'Send reset link','reset'=>'Reset password'][$mode] }} ↗</button></form><p class="auth-switch">@if($mode==='login')New here? <a href="{{ route('register') }}">Create an account</a>@elseAlready have an account? <a href="{{ route('login') }}">Log in</a>@endif</p></div></section>
@endsection
