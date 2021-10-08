@extends('layouts.app')

@section('content')
    @if($errors->any())
        {!!  implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
    @endif
    <h4 class="mb-3">Formulário de transferência</h4>
    <form action="{{ route('transaction.store') }}" method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="wallet_payee_id">Selecione o beneficiário:</label>
                <select name="wallet_payee_id" id="wallet_payee_id" class="form-control" required>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->owner->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="ammount">Informe a quantia:</label>
                <input type="text" class="form-control" id="ammount" name="ammount" placeholder="R$" required>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">Transfer</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>
            </div>
            @csrf
        </div>
    </form>
@endsection
