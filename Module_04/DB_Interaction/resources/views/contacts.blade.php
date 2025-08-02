@extends('layouts.default')

@section('content')
<div style="background: #00796b; color: #b2dfdb; padding: 30px; border-radius: 10px; max-width: 600px; margin: 20px auto; box-shadow: 0 4px 8px rgba(0,0,0,0.2); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <h1 style="text-align: center; margin-bottom: 20px;">Contacts</h1>
    <p style="font-size: 18px;"><strong>Address:</strong> {{ $address }}</p>
    <p style="font-size: 18px;"><strong>Post Code:</strong> {{ $post_code }}</p>
    <p style="font-size: 18px;"><strong>Email:</strong>
        @if($email === '')
        <span style="color: #ffcc80; font-weight: bold;">Адрес электронной почты не указан</span>
        @else
        {{ $email }}
        @endif
    </p>
    <p style="font-size: 18px;"><strong>Phone:</strong> {{ $phone }}</p>
</div>
@stop