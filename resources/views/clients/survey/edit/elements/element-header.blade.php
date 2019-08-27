<li class="form-line sort" id="question_{{ $question->id }}"
    data-question-id="{{ $question->id }}"
    data-question-type="{{ $type }}"
    data-number-answer="1">
    <div class="form-row draggable-area"></div>
    <div class="form-row answer-block">
        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-11 col-10">
            {!! Form::textarea("title[section_$sectionId][question_$question->id]", $question->title, [
                'class' => 'form-control input-area auto-resize question-input active',
                'data-autoresize',
                'placeholder' => trans('lang.question') . '(*)',
                'rows' => 1,
            ]) !!}
            {!! Form::hidden("media[section_$sectionId][question_$question->id]",
                $question->media->count() ? $question->media->first()->url : null,
                ['class' => 'image-question-hidden']) !!}
        </div>
        <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-2 question-image-block">
            {{ Html::link('#', '', [
                'class' => 'question-image-btn fa fa-image ' . ($question->media->count() ? 'hidden' : ''),
                'data-url' => route('ajax-fetch-image-question')
            ]) }}
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-8 question-dropdown-block">
            <div class="survey-select">
                <div class="survey-select-styled">
                    @if ($question->type == config('settings.question_type.short_answer'))
                        <span class="answer-icon short-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.short_answer')</span>
                    @elseif ($question->type == config('settings.question_type.long_answer'))
                        <span class="answer-icon paragraph-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.paragraph')</span>
                    @elseif ($question->type == config('settings.question_type.multiple_choice'))
                        <span class="answer-icon multi-choice-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.multiple_choice')</span>
                    @elseif ($question->type == config('settings.question_type.checkboxes'))
                        <span class="answer-icon checkboxes-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.checkboxes')</span>
                    @elseif ($question->type == config('settings.question_type.date'))
                        <span class="answer-icon date-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.date')</span>
                    @elseif ($question->type == config('settings.question_type.time'))
                        <span class="answer-icon time-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.time_answer')</span>
                    @elseif ($question->type == config('settings.question_type.redirect'))
                        <span class="fa fa-fw fa-sitemap redirect-icon"></span>
                        <span class="option-menu-content">@lang('lang.redirect')</span>
                    @elseif ($question->type == config('settings.question_type.linear_scale'))
                        <span>
                            {{ Html::image(asset(config('settings.linear_scale_icon')), null, ['class' => 'linear-scale']) }}
                        </span>
                        <span class="option-menu-content option-linear-scale">@lang('lang.linear_scale')</span>
                    @elseif ($question->type == config('settings.question_type.grid'))
                        <span class="fa fa-th grid-quetion-icon"></span>
                        <span class="option-menu-content">@lang('lang.grid')</span>
                    @elseif ($question->type == config('settings.question_type.dropdown'))
                        <span class="dropdown">{{ Html::image(asset(config('settings.dropdown_icon')), null, ['class' => '']) }}</span>
                        <span class="option-menu-content option-grid">@lang('lang.dropdown')</span>
                    @endif
                </div>
                <ul class="survey-select-options">
                    <li data-type="{{ config('settings.question_type.short_answer') }}" data-url="{{ route('ajax-fetch-short-answer') }}">
                        <span class="answer-icon short-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.short_answer')</span>
                    </li>
                    <li data-type="{{ config('settings.question_type.long_answer') }}" data-url="{{ route('ajax-fetch-long-answer') }}">
                        <span class="answer-icon paragraph-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.paragraph')</span>
                    </li>
                    <hr/>
                    <li data-type="{{ config('settings.question_type.multiple_choice') }}" data-url="{{ route('ajax-fetch-multiple-choice') }}">
                        <span class="answer-icon multi-choice-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.multiple_choice')</span>
                    </li>
                    <li data-type="{{ config('settings.question_type.checkboxes') }}" data-url="{{ route('ajax-fetch-checkboxes') }}">
                        <span class="answer-icon checkboxes-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.checkboxes')</span>
                    </li>
                    <li data-type="{{ config('settings.question_type.dropdown') }}" data-url="{{ route('ajax-fetch-dropdown-question') }}" class="clearfix">
                        <span class="dropdown">{{ Html::image(asset(config('settings.dropdown_icon')), null, ['class' => '']) }}</span>
                        <span class="option-menu-content option-grid">@lang('lang.dropdown')</span>
                    </li>
                    <hr/>
                    <li data-type="{{ config('settings.question_type.date') }}" data-url="{{ route('ajax-fetch-date') }}">
                        <span class="answer-icon date-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.date')</span>
                    </li>
                    <li data-type="{{ config('settings.question_type.time') }}" data-url="{{ route('ajax-fetch-time') }}">
                        <span class="answer-icon time-answer-icon"></span>
                        <span class="option-menu-content">@lang('lang.time_answer')</span>
                    </li>
                    <hr/>
                    <li data-type="{{ config('settings.question_type.redirect') }}" data-url="{{ route('ajax-fetch-redirect-question') }}" class="clearfix">
                        <span class="fa fa-fw fa-sitemap redirect-icon"></span>
                        <span class="option-menu-content">@lang('lang.redirect')</span>
                    </li>
                    <li data-type="{{ config('settings.question_type.linear_scale') }}"
                        data-url="{{ route('ajax-fetch-linear-scale-question') }}" class="clearfix">
                        <span>
                            {{ Html::image(asset(config('settings.linear_scale_icon')), null, ['class' => 'linear-scale']) }}
                        </span>
                        <span class="option-menu-content option-linear-scale">@lang('lang.linear_scale')</span>
                    </li>
                    <li data-type="{{ config('settings.question_type.grid') }}" data-url="{{ route('ajax-fetch-grid-question') }}" class="clearfix">
                        <span class="fa fa-th grid-quetion-icon"></span>
                        <span class="option-menu-content">@lang('lang.grid')</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="form-row description-input {{ $question->description ? 'active' : '' }}">
        <div class="col-12">
            {!! Form::textarea("description[section_$sectionId][question_$question->id]", $question->description, [
                'class' => 'form-control question-description-input input-area auto-resize',
                'data-autoresize',
                'placeholder' => trans('lang.description_section_placeholder'),
                'rows' => 1
            ]) !!}
        </div>
    </div>
    @if ($question->media->count())
        @include('clients.survey.edit.elements.image-question', [
            'imageURL' => $question->media->first()->url
        ])
    @endif
    <div class="form-row element-content">
