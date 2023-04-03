@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('форма обратной связи') }}</div>
                   @include('messages')
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('submitMassage') }}" method="POST" class="col-md-12" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title_message">Тема сообщения</label>
                            <input type="text" name="title_message" placeholder="Тема сообщения" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="message">Текст сообщения</label>
                            <textarea name="message" id="" cols="30" rows="10" placeholder="Текст сообщения" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="uploadFile" class="form-label">Загрузить файл</label>
                            <input class="form-control" type="file" name="uploadFile" id="uploadFile">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Отправить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
