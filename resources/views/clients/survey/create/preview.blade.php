@extends('clients.layout.master')
@push('styles')
    {!! Html::style(asset(config('settings.plugins') . 'js-offcanvas/js-offcanvas.css')) !!}
    {!! Html::style(asset(config('settings.plugins') . 'tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css')) !!}
    {!! Html::style(asset(config('settings.plugins') . 'metismenu/metisMenu.min.css')) !!}
    {!! Html::style(asset(config('settings.public_template') . 'css/fontsv2/fonts-v2.css')) !!}
    {!! Html::style(elixir(config('settings.public_template') . 'css/form-builder-custom.css')) !!}
    {!! Html::style(elixir(config('settings.public_template') . 'css/preview.css')) !!}
@endpush
@section('content')
    <div class="background-user-profile"></div>
    <!-- .cd-main-header -->
    <main class="cd-main-content">
        <div class="show-background">
            @if ($survey->background)
                {{ Html::image(asset($survey->background), '', ['class' => 'image-header']) }}
            @else
                {{ Html::image(asset(config('settings.background_survey')), '', ['class' => 'image-header']) }}
            @endif
        </div>
        <!-- Content Wrapper  -->
        <div class="content-wrapper">
            <!-- /Scroll buttons -->
            {!! Form::open(['class' => 'form-preview']) !!}
                <ul class="clearfix form-wrapper content-margin-top-preview ul-preview">
                    <li class="form-line">
                        <div class="form-group">
                            <h2 class="title-survey-preview">{!! nl2br(e($survey->title)) !!}</h2>
                        </div>
                        <div class="form-group">
                            <span class="description-survey">{!! nl2br(e($survey->description)) !!}</span>
                        </div>
                    </li>
                    <li class="form-line content-title-section">
                        <div class="form-group">
                            <h3 class="title-section">
                                {!! nl2br(e($section->title)) !!}
                            </h3>
                        </div>
                        <div class="form-group form-group-description-section">
                            <span class="description-section">
                                {!! nl2br(e($section->description)) !!}
                            </span>
                        </div>
                    </li>
                </ul>
                <div class="content-section-preview">
                    <ul class="clearfix form-wrapper ul-preview">
                        @php
                            $indexQuestion = config('settings.number_0');
                        @endphp
                        @foreach ($section->questions as $question)
                            <li class="li-question-review form-line">
                                <!-- tittle -->
                                @if ($question->type === config('settings.question_type.title'))
                                    @include ('clients.survey.create.elements-preview.title')
                                <!-- video -->
                                @elseif ($question->type === config('settings.question_type.video'))
                                    @include ('clients.survey.create.elements-preview.video')
                                <!-- image -->
                                @elseif ($question->type === config('settings.question_type.image'))
                                    @include ('clients.survey.create.elements-preview.image')
                                @else
                                    <span class="index-question">{{ ++ $indexQuestion}}</span>
                                    <h4 class="title-question {{ $question->type == config('settings.question_type.redirect')? 'question-redirect' : ''}}">
                                        {!! nl2br(e($question->title)) !!}
                                        @if ($question->require)
                                            <span class="notice-required-question"> *</span>
                                        @endif
                                    </h4>
                                    <div class="form-group form-description">
                                        <span class="description-question">{!! nl2br(e($question->description)) !!}</span>
                                    </div>
                                    @if ($question->media)
                                        <div class="img-preview-question-survey">
                                            {!! Html::image($question->media) !!}
                                        </div>
                                    @endif
                                    <!-- short answer -->
                                    @if ($question->type === config('settings.question_type.short_answer'))
                                        @include ('clients.survey.create.elements-preview.short')
                                    <!-- long answer -->
                                    @elseif ($question->type === config('settings.question_type.long_answer'))
                                        @include ('clients.survey.create.elements-preview.long')
                                    <!-- multi choice -->
                                    @elseif ($question->type === config('settings.question_type.multiple_choice')
                                        || $question->type == config('settings.question_type.redirect'))
                                        @include ('clients.survey.create.elements-preview.multichoice')
                                    <!-- check boxes -->
                                    @elseif ($question->type === config('settings.question_type.checkboxes'))
                                        @include ('clients.survey.create.elements-preview.checkboxes')
                                    <!-- date -->
                                    @elseif ($question->type === config('settings.question_type.date'))
                                        @include ('clients.survey.create.elements-preview.date')
                                    <!-- time -->
                                    @elseif ($question->type === config('settings.question_type.time'))
                                        @include ('clients.survey.create.elements-preview.time')
                                    <!-- linear scale -->
                                    @elseif ($question->type === config('settings.question_type.linear_scale'))
                                        @include ('clients.survey.create.elements-preview.linear')
                                    <!-- multiple choice grid -->
                                    @elseif ($question->type === config('settings.question_type.grid'))
                                        @include ('clients.survey.create.elements-preview.grid')
                                    <!-- dropdown -->
                                    @elseif ($question->type === config('settings.question_type.dropdown'))
                                        @include ('clients.survey.create.elements-preview.dropdown')
                                    @endif
                                    @if ($question->type == config('settings.question_type.redirect'))
                                        <div class="notice-required">@lang('lang.question_required')</div>
                                    @endif
                                @endif
                            </li>
                        @endforeach

                        {{ Form::hidden('redirect_id', $section->redirect_id) }}

                        <li class="li-question-review form-line">
                            @if (count($survey->sections) > 1)
                                @if ($checkIndexSection != config('settings.index_section.start'))
                                    <a href="{{ route('survey.create.preview.previous', $section->id) }}"
                                        class="btn-action-preview btn-action-preview-survey">@lang('lang.previous')</a>
                                @endif
                                @if ($checkIndexSection != config('settings.index_section.end'))
                                    <a href="{{ route('survey.create.preview.next', $section->id) }}"
                                        class="btn-action-preview btn-action-preview-survey btn-action-next">@lang('lang.next')</a>
                                @endif
                            @endif
                        </li>
                    </ul>
                </div>
            {!! Form::close() !!}
        </div>
        <!-- Content Wrapper  -->
    </main>
    <div class="modal fade" id="loader-section-survey-doing">
        <section>
            <div class="loader-spin">
                <div class="loader-outter-spin"></div>
                <div class="loader-inner-spin"></div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <!-- Plugins -->
    {!! Html::script(asset(config('settings.plugins') . 'jquery-ui/jquery-ui.min.js')) !!}
    {!! Html::script(asset(config('settings.public_template') . 'js/popper.min.js')) !!}
    {!! Html::script(asset(config('settings.public_template') . 'js/modernizr.js')) !!}
    {!! Html::script(asset(config('settings.plugins') . 'tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.js')) !!}
    <!-- Custom Script -->
    {!! Html::script(asset(config('settings.plugins') . 'metismenu/metisMenu.min.js')) !!}
    {!! Html::script(asset(config('settings.plugins') . 'jquery-menu-aim/jquery.menu-aim.js')) !!}
    {!! Html::script(asset(config('settings.plugins') . 'linkifyjs/dist/linkify.min.js')) !!}
    {!! Html::script(asset(config('settings.plugins') . 'linkifyjs/dist/linkify-jquery.min.js')) !!}
    {!! Html::script(elixir(config('settings.public_template') . 'js/preview-doing.js')) !!}
@endpush
