<div class="item-answer item-dropdown">
    <div class="dropdown-question" data-option-select="active" >
        <select class="select-value-dropdown">
            <option value>@lang('lang.dropdown_choice')</option>
            @foreach ($question->answers as $key => $answer)
                <option value="{{ $answer->id }}">{{ $answer->content }}</option>
            @endforeach
        </select>
        <p class="selected-option"></p>
        <span class="icon-dropdown-question" >
            <svg viewBox="0 0 24 24" class="icon-dropdown">
                <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"/>
            </svg>
        </span>
        <div class="cont-list-dropdown-question">
            <ul class="cont-select-int"></ul> 
        </div>
    </div>
</div>
