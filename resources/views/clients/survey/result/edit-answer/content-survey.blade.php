<ul class="clearfix form-wrapper ul-preview ul-content-preview"
    id="section-{{ $data['section']->order }}"
    data-redirect-id="{{ $data['section']->redirect_id }}"
    data-current-id="{{ $data['section']->id }}">
    <li class="form-line content-title-section">
        <div class="form-group">
            <h3 class="title-section {{ $data['section']->order }}" id="section-id-preview" data-order="{{ $data['section']->order }}">
                {!! nl2br(e($data['section']->title)) !!}
            </h3>
        </div>
        <div class="form-group form-group-description-section">
            <span class="description-section">
                {!! nl2br(e($data['section']->description)) !!}
            </span>
        </div>
    </li>
    @php
        $indexQuestion = config('settings.number_0');
    @endphp
    @foreach ($data['section']->questions as $question)
        @php
            $questionSetting = $question->type;
            $countQuestionMedia = $question->media->count();
            $result = $questionSetting == config('settings.question_type.checkboxes')
                ? $results->where('question_id', $question->id)
                : $results->where('question_id', $question->id)->first();
        @endphp
        <li class="li-question-review form-line">
            <!-- tittle -->
            @if ($questionSetting == config('settings.question_type.title'))
                @include ('clients.survey.result.edit-answer.elements.title')
            <!-- video -->
            @elseif ($questionSetting == config('settings.question_type.video'))
                @include ('clients.survey.result.edit-answer.elements.video')
            <!-- image -->
            @elseif ($questionSetting == config('settings.question_type.image'))
                @include ('clients.survey.result.edit-answer.elements.image')
            @else
                <span class="index-question">{{ ++ $indexQuestion }}</span>
                <h4 class="title-question question-survey
                    {{ $question->required ? 'required-question' : '' }}
                    {{ $questionSetting == config('settings.question_type.redirect') ? 'redirect-question' : ''  }}"
                    data-type="{{ $questionSetting }}" data-id="{{ $question->id }}"
                    data-required="{{ $question->required }}">
                    {!! nl2br(e($question->title)) !!}
                    @if ($question->required)
                        <span class="notice-required-question"> *</span>
                    @endif
                </h4>
                <div class="form-group form-description">
                    <span class="description-question">{!! nl2br(e($question->description)) !!}</span>
                </div>
                @if ($countQuestionMedia)
                    <div class="img-preview-question-survey">
                        {!! Html::image($question->url_media) !!}
                    </div>
                @endif
                <!-- short answer -->
                @if ($questionSetting == config('settings.question_type.short_answer'))
                    @include ('clients.survey.result.edit-answer.elements.short-question')
                <!-- long answer -->
                @elseif ($questionSetting == config('settings.question_type.long_answer'))
                    @include ('clients.survey.result.edit-answer.elements.long-question')
                <!-- multi choice -->
                @elseif ($questionSetting == config('settings.question_type.multiple_choice')
                    || $questionSetting == config('settings.question_type.redirect'))
                    @include ('clients.survey.result.edit-answer.elements.multiple-choice')
                <!-- check boxes -->
                @elseif ($questionSetting == config('settings.question_type.checkboxes'))
                    @include ('clients.survey.result.edit-answer.elements.checkboxes')
                <!-- date -->
                @elseif ($questionSetting == config('settings.question_type.date'))
                    @include ('clients.survey.result.edit-answer.elements.date')
                <!-- time -->
                @elseif ($questionSetting == config('settings.question_type.time'))
                    @include ('clients.survey.result.edit-answer.elements.time')
                <!-- linear scale -->
                @elseif ($questionSetting == config('settings.question_type.linear_scale'))
                    @include ('clients.survey.result.edit-answer.elements.linear_scale')
                @elseif ($questionSetting == config('settings.question_type.grid'))
                    @include ('clients.survey.result.edit-answer.elements.grid')
                @endif
            @endif
            @if ($question->required)
                <div class="notice-required">@lang('lang.question_required')</div>
            @endif
        </li>
    @endforeach

    <li class="li-question-review form-line">
        @if ($data['survey']->sections->count() > 1)
            @if ($data['index_section'] != config('settings.index_section.start'))
                <a href="javascript:void(0)" class="btn-action-preview previous-section-survey">@lang('lang.previous')</a>
            @endif
            @if ($data['index_section'] != config('settings.index_section.end'))
                <a href="javascript:void(0)" data-url="{{ route('survey.create.edit-answer', $data['survey']->token) }}"
                    class="btn-action-preview next-section-survey">@lang('lang.next')</a>
            @endif
        @endif

        @php
            if (!Session::has('url_current')) {
                $currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                Session::put('url_current', $currentUrl);
            } else {
                $currentUrl = Session::get('url_current');
            }
        @endphp

        @if ($data['index_section'] == config('settings.index_section.end') || $data['survey']->sections->count() == 1)
            {!! Form::button(trans('profile.send'), ['class' => 'btn-action-preview btn-action-preview-submit',
                'data-url' => route('survey.create.store-edit-answer', $data['survey']->token),
                'data-redirect' => route('show-complete-answer', $data['survey']->token)]) !!}
        @endif
    </li>
</ul>