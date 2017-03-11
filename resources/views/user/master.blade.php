<!DOCTYPE html>
<html lang="en" class=" js no-touch csstransforms csstransitions">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>{{ trans('info.fsurvey') }}</title>
        <meta name="description" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @include('library.css-file')
        @include('library.js-file')
    </head>
    <body>
        @include('user.blocks.popup-send-mail')
        {!! Form::hidden('', [
            'data-number' => config('temp.data_number'),
            'data-question' => config('temp.data_question',
            'data-error' => trans('home.error'),
            'data-confirm' => trans('temp.confirm'),
            'data-email-invalid' => trans('temp.email_invalid'),
            'data-link' => action('SurveyController@autocomplete'),
        ]) !!}
        <section id="top-area">
            @include('user.blocks.menu')
            <div class="container animated slideInDown">
                 <div class="row">
                     <div class="col-md-12 main-title">
                        <h1>{{ trans('view.title_web') }}</h1>
                        <p>{{ trans('view.body.intro.slogan') }}</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="container" id="main">
            @yield('content')
            @yield('content-info-web')
        </section>
        @include('user.blocks.footer')
    </body>
</html>