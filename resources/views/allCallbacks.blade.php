@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Все заявки') }}</div>
                <!-- <a href="{{ route('home') }}" class="btn btn-primary col-md-2">{{ __('создать заявку') }}</a> -->
                
            </div>
        </div>



        <table id='tableCallbacks' width='100%' border="1" style='border-collapse: collapse;'>
            <thead>
                <tr>
                    <td>id</td>
                    <td>ID Клиента</td>
                    <td>Время создания Клиента</td>
                    <td>Время отправки сообщения</td>
                    <td>Имя Клиента</td>
                    <td>E-mail Клиента</td>
                    <td>Тема сообщения</td>
                    <td>Текст сообщения</td>
                    <td>Файл</td>
                </tr>
            </thead>
        </table>

        








    </div>
</div>
@endsection


