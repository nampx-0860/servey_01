@extends('clients.layout.master')
@push('styles')
    {!! Html::style(asset(config('settings.plugins') . 'js-offcanvas/js-offcanvas.css')) !!}
    {!! Html::style(asset(config('settings.plugins') . 'tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css')) !!}
    {!! Html::style(asset(config('settings.plugins') . 'metismenu/metisMenu.min.css')) !!}
    {!! Html::style(asset(config('settings.public_template') . 'css/fontsv2/fonts-v2.css')) !!}
    {!! Html::style(elixir(config('settings.public_template') . 'css/form-builder-custom.css')) !!}
@endpush
@section('content')
    @include('clients.profile.notice')
    <div class="background-user-profile"></div>
    <!-- .cd-main-header -->
    <main class="cd-main-content">
        <!-- Content Wrapper  -->
        <div class="content-wrapper">
            {!! Form::open([
                'class' => 'survey-form',
            ]) !!}
                <!-- Scroll buttons -->
                <div class="scroll-button-group-sidebar">
                    <div class="button-group-sidebar">
                        <div class="survey-action" data-placement="right"
                            data-trigger="hover"
                            data-toggle="tooltip" title="@lang('lang.add_question')">
                            <button type="button" class="btn btn-outline-light text-dark" id="add-question-btn"
                                data-url="{{ route('ajax-fetch-multiple-choice') }}">
                                <i class="fa fa-fw fa-plus-circle text-dark"></i>
                            </button>
                        </div>
                        <div class="survey-action" data-placement="right"
                            data-trigger="hover"
                            data-toggle="tooltip" title="@lang('lang.add_title')">
                           <button type="button" class="btn btn-outline-light text-dark" id="add-title-description-btn"
                                data-url="{{ route('ajax-fetch-title-description') }}">
                                <i class="fa fa-fw fa-header text-dark"></i>
                            </button>
                        </div>
                        <div class="survey-action" data-placement="right"
                            data-trigger="hover"
                            data-toggle="tooltip" title="@lang('lang.add_image')">
                            <button type="button" class="btn btn-outline-light text-dark"
                                id="add-image-section-btn" data-url="{{ route('ajax-fetch-image-section') }}">
                                <i class="fa fa-fw fa-picture-o text-dark"></i>
                            </button>
                        </div>
                        <div class="survey-action" data-placement="right"
                            data-trigger="hover"
                            data-toggle="tooltip" title="@lang('lang.add_video')">
                            <button type="button" class="btn btn-outline-light text-dark"
                                 id="add-video-section-btn" data-url="{{ route('ajax-fetch-video') }}">
                                <i class="fa fa-fw fa-video-camera text-dark"></i>
                            </button>
                        </div>
                        <div class="survey-action" data-placement="right"
                            data-trigger="hover"
                            data-toggle="tooltip" title="@lang('lang.add_section')">
                            <button type="button" class="btn btn-outline-light text-dark"
                                id="add-section-btn"
                                data-url="{{ route('ajax-fetch-section') }}"
                                data-redirect-section-url="{{ route('ajax-fetch-redirect-section') }}">
                                <i class="fa fa-fw fa-bars text-dark"></i>
                            </button>
                        </div>
                        <div class="survey-action"
                            id="setting-btn"
                            data-placement="right"
                            data-trigger="hover"
                            data-toggle="tooltip" title="@lang('lang.setting')">
                            <button type="button" class="btn btn-outline-light text-dark" data-toggle="modal" data-target="#setting-survey" id="survey-setting-btn">
                                <i class="fa fa-fw fa-cog text-dark"></i>
                            </button>
                        </div>
                        <div class="survey-action" data-placement="right"
                            data-trigger="hover"
                            data-toggle="tooltip" title="@lang('lang.preview')">
                            <button type="button" class="btn btn-outline-light text-dark" id="preview-survey-btn"
                                data-url="{{ route('survey.create.get-json') }}" url-location={{ route('survey.create.preview') }}>
                                <i class="fa fa-fw fa-eye text-dark"></i>
                            </button>
                        </div>
                        @if ($survey->isDraft())
                            <div class="survey-action" data-placement="right"
                                data-trigger="hover"
                                data-toggle="tooltip" title="@lang('lang.save_as_draft')">
                                <button type="button" class="btn btn-outline-light text-dark" id="update-survey-draft" data-url="{{ route('survey.update-draft', $survey->token_manage) }}">
                                    <i class="fa fa-fw fa-save text-dark"></i>
                                </button>
                            </div>
                            <div class="survey-action" data-placement="right"
                                data-trigger="hover"
                                data-toggle="tooltip" title="@lang('lang.send')">
                                <button type="button" class="btn btn-outline-light text-dark" id="update-survey-draft-to-open">
                                    <i class="fa fa-fw fa-paper-plane text-dark"></i>
                                </button>
                                <a href="#" id="edit-survey-btn" data-url="{{ route('surveys.update', $survey->token_manage) }}"></a>
                            </div>
                        @else
                            <div class="survey-action" data-placement="right"
                                data-trigger="hover"
                                data-toggle="tooltip" title="@lang('lang.send')">
                                <button type="button" class="btn btn-outline-light text-dark" id="open-send-option-modal">
                                    <i class="fa fa-fw fa-paper-plane text-dark"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- /Scroll buttons -->
                <ul class="clearfix form-wrapper page-section-header">
                    <li class="form-line p-0">
                        <div class="form-header form-header-create">
                            <h1>@lang('lang.edit_survey')</h1>
                        </div>
                    </li>
                    <li class="form-line">
                        <label>@lang('lang.title')(*)</label>
                        <div class="form-group">
                            {!! Form::textarea('title', $survey->title, [
                                'class' => 'form-control input-area auto-resize',
                                'data-autoresize',
                                'placeholder' => trans('lang.title_placeholder'),
                                'rows' => 1,
                                'id' => 'survey-title',
                            ]) !!}
                        </div>
                        <label>@lang('lang.time')</label>
                        <div class="form-group form-row">
                            <div class="col">
                                {!! Form::text('start_time', '', [
                                    'class' => 'form-control datetimepicker-input start-time',
                                    'id' => 'start-time',
                                    'data-toggle' => 'datetimepicker',
                                    'data-target' => '#start-time',
                                    'data-time' => $survey->start_time,
                                    'placeholder' => trans('lang.start_time__placeholder'),
                                ]) !!}
                                <span id="start-time-error"></span>
                            </div>
                            <div class="col">
                                {!! Form::text('end_time', '', [
                                    'class' => 'form-control datetimepicker-input end-time',
                                    'id' => 'end-time',
                                    'data-toggle' => 'datetimepicker',
                                    'data-target' => '#end-time',
                                    'data-time' => $survey->end_time,
                                    'placeholder' => trans('lang.end_time__placeholder'),
                                ]) !!}
                                <span id="end-time-error"></span>
                            </div>
                        </div>
                        <label>@lang('lang.description')</label>
                        <div class="form-group">
                            {!! Form::textarea('description', $survey->description, [
                                'class' => 'form-control auto-resize input-area',
                                'data-autoresize',
                                'placeholder' => trans('lang.description_placeholder'),
                                'rows' => 3,
                            ]) !!}
                        </div>
                        <div class="form-group">
                            @if (!$survey->media->isEmpty())
                                @foreach ($survey->media as $media)
                                    <label>@lang('survey.background')</label>
                                    <div class="row show-image-background-survey">
                                        @include('clients.survey.elements.background-survey', ['imageURL' => $media->url])
                                    </div>
                                    <input type="hidden" class="background-survey-hidden" value="{{$media->url}}" name="background-survey-hidden">
                                @endforeach
                            @else
                                @include('clients.survey.elements.insert-background-survey')
                            @endif
                        </div>
                    </li>
                </ul>
                @foreach ($survey->sections as $section)
                    @if (count($section->redirectSections))
                        <div class="redirect-question-block">
                            @include('clients.survey.edit.elements.section', [
                                'section' => $section,
                                'typeSectionClass' => 'normal-section',
                            ])
                            @foreach ($section->redirectSections as $redirectId => $redirectSection)
                                @include('clients.survey.edit.elements.section-redirect', [
                                    'answerRedirectId' => $redirectId,
                                    'redirectSection' => $redirectSection,
                                ])
                            @endforeach
                        </div>
                    @else
                        @include('clients.survey.edit.elements.section', [
                            'section' => $section,
                            'typeSectionClass' => 'normal-section',
                        ])
                    @endif
                @endforeach
            {!! Form::close() !!}
        </div>
        <!-- Content Wrapper  -->
    </main>
    <div id="survey-data" data-page="edit" data-number-section="{{ count($survey->sections) }}"  data-section-id="0" data-question-id="0" data-answer-id="0"></div>
    <div id="element-clone">
        <div class="form-row option choice other-choice-option">
            <div class="radio-choice-icon"><i class="fa fa-circle-thin"></i></div>
            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8 col-8 choice-input-block">
                {!! Form::textarea('name', trans('lang.other_option'), [
                    'class' => 'form-control answer-option-input auto-resize',
                    'readonly' => true,
                ]) !!}
            </div>
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3 answer-image-btn-group">
                {{ Html::link('#', '', ['class' => 'answer-image-btn fa fa-image invisible']) }}
                {{ Html::link('#', '', ['class' => 'answer-image-btn fa fa-times remove-other-choice-option']) }}
            </div>
        </div>

        <div class="form-row option checkbox other-checkbox-option">
            <div class="square-checkbox-icon"><i class="fa fa-square-o"></i></div>
            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8 col-8 checkbox-input-block">
                {!! Form::textarea('name', trans('lang.other_option'), [
                    'class' => 'form-control answer-option-input auto-resize',
                    'readonly' => true,
                ]) !!}
            </div>
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3 answer-image-btn-group">
                {{ Html::link('#', '', ['class' => 'answer-image-btn fa fa-image invisible']) }}
                {{ Html::link('#', '', ['class' => 'answer-image-btn fa fa-times remove-other-checkbox-option']) }}
            </div>
        </div>
    </div>
    @include('clients.survey.elements.insert-image')
    @include('clients.survey.elements.insert-video')
    @include('clients.survey.elements.reorder-section')
    <!-- pupup setting survey -->
    @include('clients.survey.edit.setting')
    <div class="modal fade" id="send-modal-loader">
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
    {!! Html::script(asset(config('settings.plugins') . 'collect/collect.min.js')) !!}
    <!-- Custom Script -->
    {!! Html::script(asset(config('settings.plugins') . 'metismenu/metisMenu.min.js')) !!}
    {!! Html::script(asset(config('settings.plugins') . 'jquery-menu-aim/jquery.menu-aim.js')) !!}
    {!! Html::script(asset(config('settings.plugins') . 'popper/popper.min.js')) !!}
    {!! Html::script(asset(config('settings.plugins') . 'bootstrap/dist/js/bootstrap.min.js')) !!}
    {!! Html::script(asset(config('settings.plugins') . 'jquery-validation/jquery.validate.min.js')) !!}
    {!! Html::script(elixir(config('settings.public_template') . 'js/builder-custom.js')) !!}
@endpush
