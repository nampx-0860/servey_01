@extends('clients.profile.layout')

@section('content-profile')
    @include('clients.profile.notice')
    @if(Request::path() == config('settings.path_list_survey'))
    <div class="container padding-profile">
        <div class="row">
            <div class="col-xl-12 push-xl-12 col-lg-12 push-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button type="button"
                    class="btn btn-info col-xl-3 push-xl-3 col-lg-3 push-lg-3 col-md-3 col-sm-3 col-xs-3 btn-list-survey list-survey-ajax active"
                    data-url="{{ route('ajax-list-survey', config('settings.survey.members.owner')) }}"
                    id="list-survey-owner">@lang('survey.survey_owner')</button>
                <button type="button"
                    class="btn btn-info col-xl-3 push-xl-3 col-lg-3 push-lg-3 col-md-3 col-sm-3 col-xs-3 list-survey-ajax btn-list-survey"
                    data-url="{{ route('ajax-list-survey', config('settings.survey.members.editor')) }}"
                    id="list-survey-editor">@lang('survey.survey_editor')</button>
                <button type="button"
                    class="btn btn-info col-xl-3 push-xl-3 col-lg-3 push-lg-3 col-md-3 col-sm-3 col-xs-3 list-survey-ajax btn-list-survey"
                    data-url="{{ route('ajax-list-survey', config('settings.survey.invited')) }}"
                    id="list-survey-invited">@lang('survey.survey_invited')</button>
            </div>
        </div>
    </div>
    @endif

    <!-- show list survey -->
    <div class="container ui-block padding-profile div-list-survey">
        <div class="ui-block-content">
            @include('clients.profile.survey.element.search')

            <div class="table-responsive" id="show-list-surveys">
                @include('clients.profile.survey.list_survey_manage')
            </div>
        </div>
    </div>
    <!-- modal -->
    @include('clients.profile.survey.invite_mail_modal')
@endsection

@push('scripts')
    {!! Html::script(elixir(config('settings.public_template') . 'js/survey.js')) !!}
    {!! Html::script(elixir(config('settings.public_template') . 'js/management.js')) !!}
@endpush
