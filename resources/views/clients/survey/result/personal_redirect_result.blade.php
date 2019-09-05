<ul class="clearfix form-wrapper ul-result wrapper-section-result unset-max-with section-resize">
    <li class="p-0">
        <div class="form-header">
            <div class="section-badge section-option-menu">
                @if (!$section->redirect_id)
                    <span class="number-of-section">@lang('lang.section')
                        <span class="section-index">{{ $index }}</span>
                    </span>
                @endif
                <div class="right-header-section">
                    <a href="#" class="zoom-in-btn zoom-btn zoom-btn-result">
                        <span class="zoom-icon"></span>
                    </a>
                </div>
            </div>
            <hr/>
            <h3 class="title-section" data-placement="bottom" data-toggle="tooltip"
                title="{{ $section->showTitleTooltip() }}">
                {!! nl2br(e($section->limit_title)) !!}
            </h3>
            <span class="description-section-result">
                {!! nl2br(e($section->custom_description)) !!}
            </span>
        </div>
    </li>
</ul>
<ul class="clearfix form-wrapper ul-result unset-max-with content-section-result section-resize">
    @php
        $indexQuestion = config('settings.number_0');
    @endphp
    @foreach ($section->questions as $question)
        @php
            $questionSetting = $question->type;
            $countQuestionMedia = $question->media->count();
            $detailResult = $questionSetting != config('settings.question_type.checkboxes')
                ? $details->first()->where('question_id', $question->id)->first()
                : $details->first()->where('question_id', $question->id);
        @endphp
        <li class="li-question-review form-line">
            <!-- tittle -->
            @if ($questionSetting == config('settings.question_type.title'))
                @include ('clients.survey.result.elements.title')
            <!-- video -->
            @elseif ($questionSetting == config('settings.question_type.video'))
                @include ('clients.survey.result.elements.video')
            <!-- image -->
            @elseif ($questionSetting == config('settings.question_type.image'))
                @include ('clients.survey.result.elements.image')
            @else
                <span class="index-question">{{ ++ $indexQuestion }}</span>
                <h4 class="title-question question-survey {{ $question->required ? 'required-question' : '' }}"
                    data-type="{{ $questionSetting }}" data-id="{{ $question->id }}"
                    data-required="{{ $question->required }}">
                    {!! nl2br(e($question->title)) !!}
                    @if ($question->required)
                        <span class="notice-required-question"> *</span>
                    @endif
                </h4>
                <div class="form-group form-description">
                    <span class="description-question">
                        {!! nl2br(e($question->description)) !!}
                    </span>
                </div>
                @if ($countQuestionMedia)
                    <div class="img-preview-question-survey">
                        {!! Html::image($question->url_media) !!}
                    </div>
                @endif

                @switch ($questionSetting)
                    @firstcase (config('settings.question_type.short_answer'))
                        @include ('clients.survey.result.elements.short-question')
                        @break
                    @case (config('settings.question_type.long_answer'))
                        @include ('clients.survey.result.elements.long-question')
                        @break
                    @case (config('settings.question_type.linear_scale'))
                        @include ('clients.survey.result.elements.linear-scale')
                        @break
                    @case (config('settings.question_type.grid'))
                        @include ('clients.survey.result.elements.grid')
                        @break
                    @case (config('settings.question_type.multiple_choice'))
                        @include ('clients.survey.result.elements.multiple-choice')
                        @break
                    @case (config('settings.question_type.dropdown'))
                        @include ('clients.survey.result.elements.dropdown')
                        @break
                    @case (config('settings.question_type.redirect'))
                        @include ('clients.survey.result.elements.multiple-choice')
                        <div class="item-answer">
                            {!! Form::button(trans('lang.see_more'), [
                                'class' => 'btn btn-info see-personal-result',
                                'data-token' => $detailResult->token,
                                'data-question-id' => $question->id,
                                'data-url' => route('survey.personal.result'),
                            ]) !!}
                        </div>
                        @break
                    @case (config('settings.question_type.checkboxes'))
                        @include ('clients.survey.result.elements.checkboxes')
                        @break
                    @case (config('settings.question_type.date'))
                        @include ('clients.survey.result.elements.date')
                        @break
                    @case (config('settings.question_type.time'))
                        @include ('clients.survey.result.elements.time')
                        @break
                @endswitch
            @endif
            @if ($question->required)
                <div class="notice-required">@lang('lang.question_required')</div>
            @endif
        </li>
        @if ($questionSetting == config('settings.question_type.redirect'))
            <div id="personal-redirect-result-{{ $question->id }}"></div>
        @endif
    @endforeach
</ul>
