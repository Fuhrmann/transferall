@extends('layouts.app')

@section('content')
    <h4 class="mb-3">Detalhes da transferência</h4>
    <table class="table">
        <tbody>
        <tr>
            <td><b>Enviado para:</b></td>
            <td>{{ $transaction->payeeWallet->owner->name }}</td>
        </tr>
        <tr>
            <td><b>Valor:</b></td>
            <td>R$ {{ $transaction->ammount }}</td>
        </tr>
        <tr>
            <td><b>Data/hora:</b></td>
            <td>{{ $transaction->created_at }}</td>
        </tr>
        </tbody>
    </table>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>
@endsection
