@extends('clients.survey.elements.master')
@section('element-type', config('settings.question_type.dropdown'))
@section('element-content')
    <div class="col-12 multiple-choice-block">
        <div class="form-row option choice choice-sortable"
            id="answer_{{ $answerId }}"
            data-answer-id="{{ $answerId }}"
            data-question-id="{{ $questionId }}"
            data-option-id="{{ $optionId }}">
            <div class="radio-choice-icon">{{ config('settings.number_1') }}.</i></div>
            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8 col-8 choice-input-block">
                {!! Form::textarea("answer[question_$questionId][answer_$answerId][option_$optionId]", trans('lang.option_1'), [
                    'class' => 'form-control auto-resize answer-option-input',
                    'data-autoresize',
                    'rows' => config('settings.number_1'),
                ]) !!}
            </div>
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3 answer-image-btn-group">
                {{ Html::link('#', '', ['class' => 'answer-image-btn fa fa-times remove-choice-option hidden']) }}
            </div>
        </div>
        <div class="form-row other-choice">
            <div class="radio-choice-icon">{{ config('settings.number_2') }}.</i></div>
            <div class="other-choice-block">
                <span class="add-choice-dropdown">@lang('lang.add_option')</span>
            </div>
        </div>
    </div>
@endsection
