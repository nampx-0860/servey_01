<div class="item-answer item-dropdown">
    <div class="sel sel-question">
        @foreach ($question->answers->sortBy('type') as $answer)
            @if($detailResult->answer_id ==  $answer->id)
            <span class="sel__placeholder sel__placeholder-question">{!! nl2br(e($answer->content)) !!}</span>
            @endif
        @endforeach
        @if(!$detailResult->answer_id)
            <span class="sel__placeholder sel__placeholder-question">@lang('lang.dropdown_choice')</span>
        @endif
    </div>
</div>
