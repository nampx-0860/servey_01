@if ($feedbacks->isNotEmpty())
    <table class="table table-bordered table-list-survey">
        <thead>
            <tr>
                <th class="text-center width-5">@lang('profile.index')</th>
                <th class="text-center">@lang('lang.name')</th>
                <th class="text-center">@lang('lang.email')</th>
                <th class="text-center width-30">@lang('lang.feedback_content')</th>
                <th class="text-center width-30">@lang('lang.date_create')</th>
                <th class="width-16"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($feedbacks as $feedback)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <span data-toggle="tooltip" title="{{ $feedback->name }}" class="feedback-name" val="{{ $feedback->name }}">
                            {{ str_limit($feedback->name, config('settings.limit_feedback_name')) }}
                        </span>
                    </td>
                    <td>
                        <span data-toggle="tooltip" title="{{ $feedback->email }}" class="feedback-email" val="{{ $feedback->email }}">
                            {{ str_limit($feedback->email, config('settings.limit_feedback_name')) }}
                        </span>
                    </td>
                    <td>
                        <span class="feedback-content" val="{{ $feedback->content }}">{{ str_limit($feedback->content, config('settings.limit_feedback_content')) }}</span>
                    </td>
                    <td>
                        <span class="feedback-content" val="{{ $feedback->created_at }}">{{ date(config('settings.date_time_format'), strtotime($feedback->created_at)) }}</span>
                    </td>
                    <td class="feedback-option">
                        <a href="#" class="btn btn-info feedback-detail-btn" data-toggle="tooltip" title="@lang('lang.detail')">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="btn btn-danger delete-feedback-btn" data-toggle="tooltip" title="@lang('lang.remove')">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                        {{ Form::open(['route' => ['feedbacks.destroy', $feedback->id], 'method' => 'DELETE', 'class' => 'delete-feedback-form']) }}
                        {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <input type="hidden" name="url_onwer" value="{{ route('ajax-list-feedback') }}" class="url_onwer">

    {{ $feedbacks->links('clients.layout.pagination') }}
@else
    @include('clients.layout.empty_data')
@endif
