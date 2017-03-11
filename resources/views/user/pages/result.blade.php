<div class="container-list-result">
    <div id="middle-wizard" class="div-show-result wizard-branch wizard-wrapper">
        <div class="tab-bootstrap">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#home">
                        <span class="glyphicon glyphicon-adjust"></span>
                        {{ trans('result.overview') }}
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#menu1">
                        <span class="glyphicon glyphicon-asterisk"></span>
                        {{ trans('result.see_detail') }}
                    </a>
                </li>
               @if (!empty($history))
                 <li><a data-toggle="tab" href="#menu2">{{ trans('survey.history') }}</a></li>
               @endif
            </ul>
            @include('user.component.tap-result')
        </div>
    </div>
    <div id="bottom-wizard bottom-wizard-anwser"></div>
</div>
