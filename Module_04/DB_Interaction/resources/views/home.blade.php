@extends('layouts.default')

@section('content')
<div style="background: linear-gradient(135deg, #00bcd4, #2196f3); color: white; padding: 30px; border-radius: 10px; max-width: 600px; margin: 20px auto; box-shadow: 0 4px 8px rgba(0,0,0,0.2); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <h1 style="text-align: center; margin-bottom: 20px;">Welcome, {{ $name }}!</h1>
    <p style="font-size: 18px;"><strong>Age:</strong>
        @if($age > 18)
        {{ $age }}
        @else
        <span style="color: #ffeb3b; font-weight: bold;">Указанный человек слишком молод</span>
        @endif
    </p>
    <p style="font-size: 18px;"><strong>Position:</strong> {{ $position }}</p>
    <p style="font-size: 18px;"><strong>Address:</strong> {{ $address }}</p>
</div>
@stop