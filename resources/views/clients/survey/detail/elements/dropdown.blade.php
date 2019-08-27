<div class="item-answer item-dropdown">
    <div class="sel sel-question">
        <select class="select-profession" id="select-profession">
            <option value="" disabled>@lang('lang.dropdown_choice')</option>
            @foreach ($question->answers as $key => $answer)
                <option value="{{ $answer->id }}">{{ $answer->content }}</option>
            @endforeach
        </select>
    </div>
</div>
