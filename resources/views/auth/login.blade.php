@extends('layouts.guest')

@section('content')
    <main class="form-signin">
        @error('email')
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
        @enderror

        <form action="{{ route('login') }}" method="POST">
            <h1 class="h3 mb-3 fw-normal">Realizar login</h1>

            <div class="form-floating">
                <input type="email" id="email" class="form-control" placeholder="email@example.com" name="email">
                <label for="email">Email</label>
            </div>
            <div class="form-floating">
                <input type="password" id="senha" class="form-control" placeholder="Senha" name="password">
                <label for="senha">Senha</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Fazer login</button>
            @csrf
        </form>
    </main>
@endsection
