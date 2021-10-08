@extends('layouts.app')

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-info">
            {!! session('message') !!}
        </div>
    @endif

    <h3>Seja bem vindo(a), {{ auth()->user()->name }}</h3>
    <p class="lead">Escolha abaixo o que deseja fazer no sistema clicando em um dos botões.</p>

    <a href="{{ route('transaction.create') }}" type="button" class="btn btn-primary">Transferir dinheiro</a>
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        <button type="submit" class="btn btn-secondary">Sair</button>
        @csrf
    </form>

    <hr>
    <div>
        <h3 class="float-start">Histórico de transações</h3>
        <h3 class="float-end">Seu saldo: R$ {{ auth()->user()->balance() }}</h3>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th></th>
            <th>Valor</th>
            <th>Quando</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($transactions as $transaction)
            <tr>
                <td>{{ $transaction->payeeWallet->owner->name }}</td>
                <td>R$ {{ $transaction->ammount }}</td>
                <td>{{ $transaction->created_at }}</td>
                <td><a href="{{ route('transaction.show', $transaction) }}" class="btn btn-primary">Detalhes</a></td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Nenhuma transação realizada na sua conta.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
