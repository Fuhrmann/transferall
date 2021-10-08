@extends('layouts.app')

@section('content')
    <h3>Seja bem vindo(a), {{ auth()->user()->name }}</h3>
    <p class="lead">Escolha abaixo o que deseja fazer no sistema clicando em um dos botões.</p>

    <a href="{{ route('transaction.create') }}" type="button" class="btn btn-primary">Transferir dinheiro</a>

    <hr>
    <div>
        <h3 class="float-start">Histórico de transações</h3>
        <h3 class="float-end">Seu saldo: R$ {{ auth()->user()->balance() }}</h3>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>Valor</th>
            <th>Enviado para</th>
            <th>Quando</th>
        </tr>
        </thead>
        <tbody>
        @forelse($transacoes as $transacao)
        @empty
            <tr>
                <td colspan="3">Nenhuma transação realizada na sua conta.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
