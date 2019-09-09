@php
    $indexQuestion = 0;
@endphp

@foreach ($resultsSurveys as $results)
    <ul class="clearfix form-wrapper ul-result wrapper-section-result">
        <li class="p-0">
            <div class="form-header">
                <div class="section-badge section-option-menu">
                    <div class="right-header-section">
                        <a href="#" class="zoom-in-btn zoom-btn zoom-btn-result">
                            <span class="zoom-icon"></span>
                        </a>
                    </div>
                </div>
                <hr/>
                <h3 class="title-section" data-placement="bottom" data-toggle="tooltip"
                    title="{{ $results['section']->showTitleTooltip() }}">
                    {!! nl2br(e($results['section']->limit_title)) !!}
                </h3>
                <span class="description-section-result">
                    {!! nl2br(e($results['section']->custom_description)) !!}
                </span>
            </div>
        </li>
    </ul>
    <ul class="clearfix form-wrapper ul-result content-section-result">
        @foreach ($results['question_result'] as $result)
            <li class="li-question-review form-line li-question-result">
            @if ($result['question_type'] == config('settings.question_type.title'))
                <div class="title-question-preview">
                    <span>{!! nl2br(e($result['question']->title)) !!}</span>
                </div>
                <div class="form-group form-group-description-section">
                    <span class="description-section">
                        {!! nl2br(e($result['question']->description)) !!}
                    </span>
                </div>
            @else
                <span class="index-question">{{ ++ $indexQuestion }}</span>
                <h4 class="title-question">
                    {!! nl2br(e($result['question']->title)) !!}
                    @if ($result['question']->required)
                        <span class="notice-required-question"> *</span>
                    @endif
                </h4>
                @if ($result['question']->media->count())
                    <div class="img-preview-question-survey">
                        {!! Html::image($result['question']->url_media) !!}
                    </div>
                @endif
                <div class="form-group form-group-description-section">
                    <span class="number-result-answer">{{ $result['count_answer'] }} @lang('result.number_answer')</span>
                </div>
                @if ($result['answers'])
                    @if (in_array($result['question']->type, [
                        config('settings.question_type.short_answer'),
                        config('settings.question_type.long_answer'),
                        config('settings.question_type.date'),
                        config('settings.question_type.time'),
                    ]))
                        <div class="answer-result scroll-answer-result" id="style-scroll-3">
                            @foreach ($result['answers'] as $answer)
                                <p class="{{ ($loop->iteration % config('settings.checkEventOdd')) ?
                                    'item-answer-result-even' :
                                    'item-answer-result-odd' }}"
                                    data-placement="bottom" data-toggle="tooltip"
                                    title="{{ $answer['content'] }}">
                                    {{ str_limit($answer['content'], config('settings.limit_answer_content')) }}
                                    <span class="percent-answer">({{ $answer['percent'] }}%)</span>
                                </p>
                            @endforeach
                        </div>
                    @elseif ($result['question_type'] == config('settings.question_type.linear_scale'))
                        <div class="answer-result chart-result-answer sub-linearscale-result"
                            id="{{ $result['question']->id }}"
                            data="{{ json_encode($result['answers']) }}"
                            data-linear="{{ json_encode($result['question']->setting_value) }}">
                        </div>
                        <span class="linear-min">
                            {{ $result['question']->setting_value->min_value }} :
                            {{ $result['question']->setting_value->min_content }}
                        </span>
                        <span class="linear-max">
                            {{ $result['question']->setting_value->max_value }} :
                            {{ $result['question']->setting_value->max_content }}
                        </span>
                    @elseif ($result['question_type'] == config('settings.question_type.grid'))
                        <div class="answer-result chart-result-answer sub-grid-result"
                            id="{{ $result['question']->id }}"
                            data="{{ json_encode($result['answers']) }}"
                            data-sub-questions="{{ json_encode($result['question']->sub_questions) }}"
                            data-sub-options="{{ json_encode($result['question']->sub_options) }}">
                        </div>
                    @elseif ($result['question_type'] == config('settings.question_type.multiple_choice'))
                        @if ($result['question']->answers->count())
                            <div class="answer-result sub-multiple-choice-result"
                                id="{{ $result['question']->id }}"
                                data="{{ json_encode($result['answers']) }}"></div>
                        @endif
                    @elseif ($result['question_type'] == config('settings.question_type.checkboxes'))
                        @if ($result['question']->answers->count())
                            <div class="answer-result sub-checkboxes-result"
                                id="{{ $result['question']->id }}"
                                data="{{ json_encode($result['answers']) }}"></div>
                        @endif
                    @elseif ($result['question_type'] == config('settings.question_type.dropdown'))
                        @if ($result['question']->answers->count())
                            <div class="answer-result sub-multiple-choice-result"
                                id="{{ $result['question']->id }}"
                                data="{{ json_encode($result['answers']) }}"></div>
                        @endif
                    @endif
                @else
                    <span class="no-answer">@lang('result.there_is_no_result')</span>
                @endif
            @endif
        </li>
        @endforeach
    </ul>
@endforeach
