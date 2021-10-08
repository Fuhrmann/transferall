@extends('layouts.app')

@section('content')
    <h4 class="mb-3">Formulário de transferência</h4>
    <div class="row">
        <form action="{{ route('transaction.store') }}" method="POST">
            <div class="col-md-6 mb-3">
                <label for="payee_id">Selecione o beneficiário:</label>
                <select name="payee_id" id="payee_id" class="form-control" required></select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="ammount">Informe a quantia:</label>
                <input type="text" class="form-control" id="ammount" placeholder="R$" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Transfer</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>
            </div>
            @csrf
        </form>
    </div>
@endsection
