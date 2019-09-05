<div class="item-answer item-dropdown" data-question-id={{ $question->id }}>
    <div class="sel sel-question">
        <select class="select-profession" id="select-profession-{{ $question->id }}">
            <option value="{{ config('settings.survey.option.default') }}" selected disabled>@lang('lang.dropdown_choice')</option>
            @foreach ($question->answers as $key => $answer)
                <option value="{{ $answer->id }}">{{ $answer->content }}</option>
            @endforeach
        </select>
    </div>
</div>
