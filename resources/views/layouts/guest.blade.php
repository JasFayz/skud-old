<!DOCTYPE html>
<html lang="ru" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
          content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('/assets/images/favicon.png') }}">
    <!-- Page Title  -->
    <title>Login | Skud</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('/assets/css/dashlite.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('/assets/css/theme.css') }}">
</head>

<body class="nk-body ui-rounder npc-default pg-auth">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main ">
        <!-- wrap @s -->
        <div class="nk-wrap nk-wrap-nosidebar">
            <!-- content @s -->
            <div class="nk-content ">
                <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
                    <div class="brand-logo pb-4 text-center">
                        <a href="html/index.html" class="logo-link">
                            <img class="w-50" src="{{ asset('/assets/images/Skudlogo.svg') }}" alt="logo">
                        </a>
                    </div>
                    <div class="card card-bordered">
                        <div class="card-inner card-inner-lg">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h4 class="nk-block-title text-center">Вход</h4>
                                    @if(session()->has('error_message') )
                                        <p class="bold text-center mt-2" style="color: red">{{session()->get('error_message')}}</p>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="default-01">Почта</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control form-control-lg" id="default-01"
                                               name="email"
                                               placeholder="Введите адрес электронной почты">
                                        @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="password">Пароль</label>

                                    </div>
                                    <div class="form-control-wrap">
                                        <a href="#" class="form-icon form-icon-right passcode-switch lg toggle_password"
                                           data-target="password">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input type="password" class="form-control form-control-lg" id="password"
                                               name="password"
                                               placeholder="Введите пароль">
                                        @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                        <span class="ml-2 text-sm text-gray-600">Запомнить</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-lg btn-primary btn-block">Войти</button>
                                </div>
                            </form>

                            @if(config('oauth.egov.enabled'))
                                <div class="text-center pt-4 pb-3">
                                    <h6 class="overline-title overline-title-sap"><span>OR</span></h6>
                                </div>
                                <ul class="nav justify-center gx-4 mb-2">
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-primary text-white"
                                           href="{{ route('login.egov') }}">OneID</a>
                                    </li>
                                </ul>
                                @error('oauth')
                                <small class="d-block text-danger text-center">{{ $message }}</small>
                                @enderror
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/assets/js/bundle.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.toggle_password').click(function () {
            const inputType = $(this).siblings('input').attr('type')
            inputType === 'password'
                ? $(this).siblings('input.form-control').attr('type', 'text')
                : $(this).siblings('input.form-control').attr('type', 'password')
        })
    })
</script>
</body>
</html>
