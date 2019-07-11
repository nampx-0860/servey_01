<div class="modal" id="modal-insert-image" tabindex="-1" role="dialog" aria-hidden="true" data-home-url="{{ route('home') }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('lang.insert_image')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <nav>
                        <div class="nav nav-tabs nav-tab-insert-image" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-img-tab" data-toggle="tab" href="#nav-choose-image" role="tab" aria-controls="nav-img" aria-selected="false">
                                @lang('lang.choose_image')
                            </a>
                            <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-upload-image" role="tab" aria-controls="nav-home" aria-selected="true">
                                @lang('lang.upload_or_by_url')
                            </a>
                            <!-- <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-search-image" role="tab" aria-controls="nav-contact" aria-selected="false">
                                @lang('lang.search_by_google')
                            </a> -->
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-choose-image" role="tabpanel" aria-labelledby="nav-img-tab">
                            <div class="tabpanel-form">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="home" role="tabpanel" aria-expanded="true">
                                        <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                            <div class="radio-image">
                                                <label class="custom-radio">
                                                    {!! Html::image(asset(config('settings.choose-background-image.1')), '') !!}
                                                    {!! Form::radio('background_cover', config('settings.choose-background-image.1')) !!}
                                                    <span class="circle"></span>
                                                    <span class="check"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                            <div class="radio-image">
                                                <label class="custom-radio">
                                                    {!! Html::image(asset(config('settings.choose-background-image.2')), '') !!}
                                                    {!! Form::radio('background_cover', config('settings.choose-background-image.2')) !!}
                                                    <span class="circle"></span>
                                                    <span class="check"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                            <div class="radio-image">
                                                <label class="custom-radio">
                                                    {!! Html::image(asset(config('settings.choose-background-image.3')), '') !!}
                                                    {!! Form::radio('background_cover', config('settings.choose-background-image.3')) !!}
                                                    <span class="circle"></span>
                                                    <span class="check"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                            <div class="radio-image">
                                                <label class="custom-radio">
                                                    {!! Html::image(asset(config('settings.choose-background-image.default')), '') !!}
                                                    {!! Form::radio('background_cover', config('settings.choose-background-image.default')) !!}
                                                    <span class="circle"></span>
                                                    <span class="check"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                            <div class="radio-image">
                                                <label class="custom-radio">
                                                    {!! Html::image(asset(config('settings.choose-background-image.5')), '') !!}
                                                    {!! Form::radio('background_cover', config('settings.choose-background-image.5')) !!}
                                                    <span class="circle"></span>
                                                    <span class="check"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                            <div class="radio-image">
                                                <label class="custom-radio">
                                                    {!! Html::image(asset(config('settings.choose-background-image.6')), '') !!}
                                                    {!! Form::radio('background_cover', config('settings.choose-background-image.6')) !!}
                                                    <span class="circle"></span>
                                                    <span class="check"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                            <div class="radio-image">
                                                <label class="custom-radio">
                                                    {!! Html::image(asset(config('settings.choose-background-image.7')), '') !!}
                                                    {!! Form::radio('background_cover', config('settings.choose-background-image.7')) !!}
                                                    <span class="circle"></span>
                                                    <span class="check"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                            <div class="radio-image">
                                                <label class="custom-radio">
                                                    {!! Html::image(asset(config('settings.choose-background-image.8')), '') !!}
                                                    {!! Form::radio('background_cover', config('settings.choose-background-image.8')) !!}
                                                    <span class="circle"></span>
                                                    <span class="check"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                            <div class="radio-image">
                                                <label class="custom-radio">
                                                    {!! Html::image(asset(config('settings.choose-background-image.9')), '') !!}
                                                    {!! Form::radio('background_cover', config('settings.choose-background-image.9')) !!}
                                                    <span class="circle"></span>
                                                    <span class="check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-upload-image" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="tabpanel-form">
                                <div class="form-upload-image">
                                    <div class="row col-md-10 offset-md-1 row-btn-upload">
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-no-radius input-url-image" id="input-url-image" name="url-image" placeholder="@lang('lang.paste_a_url_or_upload_image')">
                                                <div class="input-group-append">
                                                    <button class="btn btn-default btn-insert-url-image"><i class="fa fa-link"></i></button>
                                                </div>
                                            </div>
                                            <div class="messages-validate-image hidden">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="btn-upload-image">
                                                <span class="fa fa-cloud-upload fa-2x"></span>
                                            </div>
                                            <input type="file" accept="image/*" name="input-upload-image" data-url="{{ route('ajax-upload-image') }}" class="hidden input-upload-image">
                                        </div>
                                    </div>
                                    <div class="row col-md-10 offset-md-1">
                                        <div class="col-md-12 show-image">
                                            <img class="img-fluid img-preview-in-modal" src="" alt="" srcset="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-search-image" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <div class="tabpanel-form">
                                <div class="input-group col-md-10 offset-md-1">
                                    <input type="text" class="form-control input-no-radius" placeholder="@lang('lang.search_an_image')">
                                    <div class="input-group-append">
                                    <button class="btn btn-default btn-search-image" ><i class="fa fa-google"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer btn-group-image-modal">
                <button class="btn btn-default btn-cancel-insert-image" data-dismiss="modal">@lang('lang.cancel')</button>
                <button class="btn btn-default btn-insert-image" data-dismiss="modal">@lang('lang.insert_image')</button>
            </div>
        </div>
    </div>
</div>
