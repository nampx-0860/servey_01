<ul class="clearfix form-wrapper page-section sortable {{ $typeSectionClass }}"
    id="section_{{ $section->id }}" data-section-id="{{ $section->id }}">
    <li class="p-0">
        <div class="form-header">
            <div class="section-badge section-option-menu">
                <span class="number-of-section">@lang('lang.section')
                    <span class="section-index"></span>
                </span>
                <div class="right-header-section">
                    <a href="" class="zoom-in-btn zoom-btn">
                        <span class="zoom-icon"></span>
                    </a>
                    <div class="option-menu-group">
                        <a href="#" class="fa fa-ellipsis-v option-menu"></a>
                        <ul class="option-menu-dropdown">
                            <li class="copy-section">
                               <span>@lang('lang.duplicate_section')</span>
                            </li>
                            <li class="move-section">
                                <span>@lang('lang.move_section')</span>
                            </li>
                            <li class="delete-section">
                                <span>@lang('lang.delete_section')</span>
                            </li>
                            <li class="merge-with-above">
                                <span>@lang('lang.merge_with_above')</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr/>
            {!! Form::textarea("title[section_$section->id]", $section->title, [
                'placeholder' => trans('lang.section-title'),
                'class' => 'form-control input-area auto-resize section-header-title',
                'data-autoresize',
                'rows' => 1,
            ]) !!}
            <br />
            {!! Form::textarea("description[section_$section->id]", $section->description, [
                'class' => 'form-control input-area auto-resize section-header-description',
                'data-autoresize',
                'placeholder' => trans('lang.description_section_placeholder'),
                'rows' => 1,
            ]) !!}
        </div>
    </li>
    @foreach ($section->questions as $question)
        @if ($question->type == config('settings.question_type.short_answer'))
            @include('clients.survey.edit.elements.short-answer', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.long_answer'))
            @include('clients.survey.edit.elements.long-answer', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.multiple_choice'))
            @include('clients.survey.edit.elements.multiple-choice', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.checkboxes'))
            @include('clients.survey.edit.elements.checkboxes', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.date'))
            @include('clients.survey.edit.elements.date', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.time'))
            @include('clients.survey.edit.elements.time', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.title'))
            @include('clients.survey.edit.elements.title-description', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.image'))
            @include('clients.survey.edit.elements.section-image', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.video'))
            @include('clients.survey.edit.elements.section-video', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.redirect'))
            @include('clients.survey.edit.elements.redirect', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.linear_scale'))
            @include('clients.survey.edit.elements.linear_scale', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.grid'))
            @include('clients.survey.edit.elements.grid', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @elseif ($question->type == config('settings.question_type.dropdown'))
            @include('clients.survey.edit.elements.dropdown', [
                'sectionId' => $section->id,
                'question' => $question,
                'type' => $question->type,
            ])
        @endif
    @endforeach
    <li class="end-section" style="display: none;">
        <span class="end-section-title">@lang('lang.after_section')</span>
        <div class="end-section-dropdown">
            <div class="section-select">
                <div class="section-select-styled">
                    <span>@lang('lang.continue_next_section')</span>
                </div>
                <ul class="section-select-options">
                    <li>
                        <span>@lang('lang.go_to_section', ['part' => 1])</span>
                    </li>
                    <li>
                        <span>@lang('lang.submit_form')</span>
                    </li>
                </ul>
            </div>
        </div>
    </li>
</ul>
