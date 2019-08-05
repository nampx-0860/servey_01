<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <title>@lang('lang.web_title')</title>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google-signin-client_id" content="{{ config('settings.clientID') }}">

        {{ Html::favicon(asset('templates/survey/images/icon/favicon.ico')) }}

        <!-- Plugins CSS -->
        {!! Html::style(asset(config('settings.plugins') . 'bootstrap/dist/css/bootstrap.min.css')) !!}
        {!! Html::style(asset(config('settings.plugins') . 'font-awesome/css/font-awesome.min.css')) !!}
        {!! Html::style(asset(config('settings.plugins') . 'ionicons/css/ionicons.min.css')) !!}
        <!-- Theme CSS -->
        {!! Html::style(elixir(config('settings.public_template') . 'css/home.css')) !!}
        {!! Html::style(elixir(config('settings.public_template') . 'css/modal-auth.min.css')) !!}
        {!! Html::style(elixir(config('settings.public_template') . 'js/datepicker/bootstrap-datepicker.min.css')) !!}
        {!! Html::style(elixir(config('settings.public_template') . 'css/style.css')) !!}
        @stack('styles')
    </head>
    <body class="home-page">
        <section class="create-survey-btn @yield('btn-create-survey')" title="@lang('lang.create_survey')">
            <a href="{{ route('surveys.create') }}">
                <span class="fa fa-plus"></span>
            </a>
        </section>
        <div class="site">
            
        </div>
        <div class="header-wrapper header-wrapper-home">
            <!-- ******HEADER****** -->
            <header id="header" class="header fixed-top mobile-header @yield('header-change')">
                <div class="container">
                    <h1 class="logo">
                        <a href="{{ route('home') }}">{!! config('settings.logo_content') !!}</a>
                    </h1>
                    <nav class="main-nav navbar navbar-expand-md navbar-dark" role="navigation">
                        {{ Form::button('<span class="navbar-toggler-icon"></span>', ['class' => 'navbar-toggler',
                            'data-toggle' => 'collapse', 'data-target' => '#navbar-collapse', 'aria-controls' => 'navbar-collapse',
                            'aria-expanded' => 'false', 'aria-label' => 'Toggle navigation']) }}
                        <div id="navbar-collapse" class="navbar-collapse collapse justify-content-end">
                            <ul class="nav navbar-nav">
                                <li class="active nav-item">
                                    {{ Html::link(route('home'), trans('lang.home'), ['class' => 'nav-link']) }}
                                </li>
                                @if(!Auth::guard()->check() || !Auth::user()->isAdmin())
                                    <li class="nav-item">
                                        {{ Html::link(route('home'), trans('lang.feedback'), [
                                            'class' => 'nav-link',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#modal-feedback',
                                        ]) }}
                                    </li>
                                @endif
                                @if (!Auth::guard()->check())
                                    <li class="nav-item">
                                        {{ Html::link(route('home'), trans('lang.login'), [
                                            'class' => 'nav-link',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#modalLogin',
                                        ]) }}
                                    </li>
                                    <li class="nav-item">
                                        {{ Html::link(route('home'), trans('lang.register'), [
                                            'class' => 'nav-link',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#modalRegister'
                                        ]) }}
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <div class=" select">
                                        <div class="select-styled">
                                            {{ Html::image(config('settings.blank_icon'), '', ['class' => 'flag ' . Session::get('locale') . ' fnone']) }}
                                            <span>@lang('lang.' . Session::get('locale'))</span>
                                        </div>
                                        <ul class="select-options select-language" data-url="{{ route('set-language') }}">
                                            <li rel="vn">
                                                {{ Html::image(config('settings.blank_icon'), '', ['class' => 'flag vn fnone']) }}
                                                <span>@lang('lang.vn')</span>
                                            </li>
                                            <li rel="en">
                                                {{ Html::image(config('settings.blank_icon'), '', ['class' => 'flag en fnone']) }}
                                                <span>@lang('lang.en')</span>
                                            </li>
                                            {{-- <li rel="jp">
                                                {{ Html::image(config('settings.blank_icon'), '', ['class' => 'flag jp fnone']) }}
                                                <span>@lang('lang.jp')</span>
                                            </li> --}}
                                        </ul>
                                    </div>
                                </li>
                                @if (Auth::guard()->check())
                                    <li class="nav-item notifications" style="display: none;">
                                        <a class="nav-link dropdown-toggle" href="#" id="navNotifications"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="notify fa fa-bell-o"></span>
                                            <span class="round fa fa-cirlce"></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right header-menu" aria-labelledby="navNotifications">
                                            <li class="notify-header">
                                                @lang('lang.notifications')
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <span class="text-success">@lang('lang.congrats') </span> <!-- notification message here-->
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <span class="text-danger">@lang('lang.failure')</span> <!-- notification message here-->
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <span class="text-warning">@lang('lang.notifications')</span> <!-- notification message here-->
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <span class="text-danger">@lang('lang.warning')</span> <!-- notification message here-->
                                                </a>
                                            </li>
                                            <li class="notify-footer"></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item user-dropdown last">
                                        <a class="nav-link dropdown-toggle user-nav-show" href="{{ route('home') }}" id="navbarDropdownProfile"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="user-profile">
                                                {{ Html::image(Auth::user()->image_path, '', ['class' => 'user-images']) }}
                                                <span class="user-name d-none-992">
                                                    {{ Auth::user()->name }}
                                                </span>
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right header-menu" aria-labelledby="navbarDropdownProfile">
                                            <li>
                                                {!! html_entity_decode(Html::link( route('survey.survey.show-surveys'), '<i class="fa fa-list"></i> ' . trans('lang.my_surveys'), ['class' => 'dropdown-item'])) !!}
                                            </li>
                                            @if (Auth::user()->isAdmin())
                                                <li>
                                                    {!! html_entity_decode(Html::link(route('feedbacks.index'), '<i class="fa fa-comments"></i> ' . trans('lang.list_feedback'), ['class' => 'dropdown-item'])) !!}
                                                </li>
                                                <li>
                                                    {!! html_entity_decode(Html::link(route('management-user.index'), '<i class="fa fa-list"></i> ' . trans('lang.list_user'), ['class' => 'dropdown-item'])) !!}
                                                </li>
                                                <li>
                                                    {!! html_entity_decode(Html::link(route('list-survey-management'), '<i class="fa fa-list"></i> ' . trans('lang.management_survey'), ['class' => 'dropdown-item'])) !!}
                                                </li>
                                            @endif
                                            <li>
                                                {!! html_entity_decode(Html::link(route('survey.profile.index'), '<i class="fa fa-user"></i> ' . trans('profile.profile'), ['class' => 'dropdown-item'])) !!}
                                            </li>
                                            <li>
                                                {!! html_entity_decode(Html::link(route('logout'), '<i class="fa fa-power-off"></i> ' . trans('lang.logout'), ['class' => 'dropdown-item'])) !!}

                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </nav>
                </div>
            </header>
        </div>
