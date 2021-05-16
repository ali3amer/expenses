<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Blank Page</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{--<!-- Bootstrap 3.3.7 -->--}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/lStyle.css') }}">
</head>

<body>
<div class="wrapper">
    <div class="container">
        <h1>نظام المصارف</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" placeholder="إسم المستخدم" class=" @error('name') is-invalid @enderror" name="name"
                   value="{{ old('name') }}" required autocomplete="off">
            <input type="password" placeholder="كلمة المرور" class=" @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password">
            <button type="submit" id="login-button">تسجيل دخول</button>
        </form>
    </div>

    <ul class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>

</body>
</html>
