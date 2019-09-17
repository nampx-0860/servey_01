$(document).ready(function () {
    // datepicker setup
    if ($('#chart-start-date').length > 0) {
        $('.datepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            defaultDate: new Date(),
            dateFormat: 'dd-mm-yy',
        });
        document.getElementById("chart-start-date").onchange = function () {
            var start_date = document.getElementById("chart-start-date").value;
            $('#chart-end-date').datepicker('setStartDate', start_date);
        }
        document.getElementById("chart-personal-start-date").onchange = function () {
            var start_date = document.getElementById("chart-personal-start-date").value;
            $('#chart-personal-end-date').datepicker('setStartDate', start_date);
        }
        document.getElementById("chart-end-date").onchange = function () {
            var end_date = document.getElementById("chart-end-date").value;
            $('#chart-start-date').datepicker('setEndDate', end_date);
        }
        document.getElementById("chart-personal-end-date").onchange = function () {
            var end_date = document.getElementById("chart-personal-end-date").value;
            $('#chart-personal-start-date').datepicker('setEndDate', end_date);
        }
        $('#chart-start-date, #chart-end-date, #chart-personal-start-date, #chart-personal-end-date').on('click', function () {
            if ($(this).val() == '') {
                $(this).datepicker('setDate', new Date());
            }
        })
    }

    // get overview
    $(document).on('click', '#overview-survey', function () {
        $('#group-select-date-overview').show();
        $('#group-select-date-result').hide();
        var url = $(this).attr('data-url');
        handelManagement($(this));
        $.ajax({
            method: 'GET',
            url: url,
            dataType: 'json',
            success: function (data) {
                $('#div-management-survey').html(data.html).promise().done(function () {
                    getOverviewSurvey();
                });
            }
        });
    });

    // get result
    $(document).on('click', '#results-survey, #btn-summary-result, #btn-personal-result', function () {
        $('#chart-personal-start-date').val('');
        $('#chart-personal-end-date').val('');
        $('#group-select-date-overview').hide();
        $('#group-select-date-result').css('display', 'flex');
        var url = $(this).attr('data-url');
        handelManagement($(this));
        $('#results-survey').addClass('active');
        $.ajax({
            method: 'GET',
            url: url,
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $('#div-management-survey').html(data.html).promise().done(function () {
                        results();
                        $(this).find('.ul-result').addClass('ul-result-management section-resize');
                    });

                    $('[data-toggle="tooltip"]').tooltip();

                    autoScroll();
                    autoAlignChoiceAndCheckboxIcon();
                } else {
                    $('.content-section-preview').html(`<span class="message-result">${data.message}</span>`);
                }
            }
        });

        return false;
    });

    $(document).on('click', '.see-overview-result', function () {
        var url = $('#overview-survey').attr('data-url');
        $.ajax({
            method: 'GET',
            url: url,
            dataType: 'json',
            success: function (data) {
                getOverviewSurvey();
            }
        });
    });

    $(document).on('click', '.result-personal-by-date', function () {
        var startDate = $('#chart-personal-start-date').val();
        var endDate = $('#chart-personal-end-date').val();
        var url = $('#btn-personal-result').data('url');
        $.ajax({
            method: 'GET',
            url: url,
            data: {
                start_date: startDate,
                end_date: endDate,
            },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $('#div-management-survey').html(data.html).promise().done(function () {
                        results();
                        $(this).find('.ul-result').addClass('ul-result-management section-resize');
                    });

                    $('[data-toggle="tooltip"]').tooltip();

                    autoScroll();
                    autoAlignChoiceAndCheckboxIcon();
                } else {
                    $('.content-section-preview').html(`<span class="message-result">${data.message}</span>`);
                }
            }
        });
    });

    $(document).on('click', '.see-result-by-date', function (e) {
        e.preventDefault();
        var startDate = $('#chart-start-date').val();
        var endDate = $('#chart-end-date').val();
        var url = $(this).data('url');
        var surveyId = $(this).data('survey-id');
        if (startDate == '' && endDate == '') {
            var url = $('#overview-survey').attr('data-url');
            $.ajax({
                method: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    getOverviewSurvey();
                }
            });
        } else {
            $.ajax({
                method: 'GET',
                url: url,
                dataType: 'json',
                data: {
                    survey_id: surveyId,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function (data) {
                    if (data.success) {
                        Highcharts.chart('management-chart', {
                            title: {
                                text: Lang.get('result.activity_survey')
                            },

                            xAxis: {
                                categories: data.results['x']
                            },

                            yAxis: {
                                allowDecimals: false,
                                title: {
                                    text: Lang.get('result.number_survey_complete'),
                                }
                            },

                            tooltip: {
                                headerFormat: '<b>{series.name}</b><br />',
                                pointFormat: '{point.y}'
                            },

                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: true
                                    },
                                    enableMouseTracking: false
                                }
                            },

                            credits: {
                                enabled: false,
                            },

                            series: [{
                                data: data.results['y'],
                            }]
                        });
                    }
                }
            })
        }
    })

    // setting survey
    $(document).on('click', '#setting-survey', function () {
        $('#group-select-date-overview').hide();
        $('#group-select-date-result').hide();
        var url = $(this).attr('data-url');
        handelManagement($(this));
        $.ajax({
            method: 'GET',
            url: url,
            dataType: 'json',
            success: function (data) {
                $('#div-management-survey').html(data.html).promise().done(function () {
                    results();
                    $(this).find('.ul-result').addClass('ul-result-management');
                });

                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    });

    // delete survey
    $(document).on('click', '#delete-survey', function () {
        var deleteSurveyCondition = window.location.pathname == '/list-survey'
            ? $(this).data('survey-status') == 1
            : $('.btn.hide-div').attr('id') == 'open-survey';

        if (deleteSurveyCondition) {
            alertWarning(
                { message: Lang.get('lang.warning_close_survey_to_delete') }
            );
        } else {
            var url = $(this).attr('data-url');
            confirmDanger({ message: Lang.get('lang.comfirm_delete_survey') }, function () {
                $.ajax({
                    method: 'GET',
                    url: url,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            window.location.replace(data.url_redirect);

                            return;
                        }

                        alertDanger({ message: data.message });
                    }
                });
            });
        }
    });

    // close survey survey
    $(document).on('click', '#close-survey', function () {
        var url = $(this).attr('data-url');
        confirmDanger({ message: Lang.get('lang.comfirm_close_survey') }, function () {
            $.ajax({
                method: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        $('#close-survey').addClass('hide-div');
                        $('#open-survey').removeClass('hide-div');
                        alertSuccess({ message: data.message });
                    } else {
                        alertDanger({ message: data.message });
                    }
                }
            });
        });
    });

    // open survey survey
    $(document).on('click', '#open-survey', function () {
        var url = $(this).attr('data-url');
        confirmDanger({ message: Lang.get('lang.comfirm_open_survey') }, function () {
            $.ajax({
                method: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        $('#close-survey').removeClass('hide-div');
                        $('#open-survey').addClass('hide-div');
                        alertSuccess({ message: data.message });
                    } else {
                        alertDanger({ message: data.message });
                    }
                }
            });
        });
    });

    // clone survey survey
    $(document).on('click', '#clone-survey', function () {
        var url = $(this).attr('data-url');

        confirmDanger({ message: Lang.get('lang.comfirm_clone_survey') }, function () {
            $.ajax({
                method: 'GET',
                url: url,
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        alertSuccess(
                            { message: data.message },
                            function () {
                                var redirectWindow = window.open(data.redirect, '_blank');
                                redirectWindow.location;
                            }
                        );
                    } else {
                        alertDanger({ message: data.message });
                    }
                }
            });
        });
    });

    // show btn change token survey
    $(document).on('keyup', '.input-edit-token', function (e) {
        var element = $(this).closest('.form-group-custom').find('.input-edit-token');
        var oldToken = element.data('token');
        var newToken = toSlug($.trim(element.val()));

        if (oldToken != newToken && newToken) {
            $('.save-token-survey').show();
            $('.save-token-survey').addClass('show-save-btn');

            if (e.keyCode == 13) {

                if ($('#close-survey').is(':visible')) {
                    confirmWarning(
                        { message: Lang.get('lang.confirm_close_to_edit') },
                        function () {
                            closeSurvey();
                            changeToken(element);
                        }
                    );
                } else {
                    changeToken(element);
                }
            }
        } else {
            $('.save-token-survey').hide();
        }
    })

    $(document).on('click', '.edit-token-survey', function () {
        $('.input-edit-token').select();
    })

    $(document).on('click', '.save-token-survey', function () {
        var element = $(this);

        if ($('#close-survey').is(':visible')) {
            confirmWarning(
                { message: Lang.get('lang.confirm_close_to_edit') },
                function () {
                    closeSurvey();
                    changeToken(element.closest('.form-group-custom').find('.input-edit-token'));
                }
            );
        } else {
            changeToken(element.closest('.form-group-custom').find('.input-edit-token'));
        }
    })

    $(document).on('focusout', '.input-edit-token', function () {

        if (!$(this).val().trim()) {
            $(this).val($(this).data('token'));
            $(this).attr('data-original-title', $(this).data('token'));
        } else {
            $(this).attr('data-original-title', $(this).val());
        }
    });

    // show change token manage survey
    $(document).on('keyup', '.input-edit-token-manage', function () {
        var element = $(this).closest('.form-group-custom').find('.input-edit-token-manage');
        var oldTokenManage = element.data('token-manage');
        var newTokenManage = element.val().replace(' ', '_');

        if (oldTokenManage != newTokenManage && newTokenManage) {
            $('.edit-token-manage-survey').show();
        } else {
            $('.edit-token-manage-survey').hide();
        }
    })

    $(document).on('click', '.edit-token-manage-survey', function () {
        var element = $(this);

        if ($('#close-survey').is(':visible')) {
            confirmWarning(
                { message: Lang.get('lang.confirm_close_to_edit') },
                function () {
                    closeSurvey();
                    changeTokenManage(element.closest('.form-group-custom').find('.input-edit-token-manage'));
                }
            );
        } else {
            changeTokenManage(element.closest('.form-group-custom').find('.input-edit-token-manage'));
        }
    })

    $(document).on('click', '#edit-survey', function (event) {
        event.preventDefault();
        var redirect = $(this).attr('data-url');

        if ($('#close-survey').is(':visible')) {
            confirmWarning(
                { message: Lang.get('lang.confirm_close_to_edit') },
                function () {
                    closeSurvey(redirect);
                }
            );
        } else {
            window.location.href = redirect;
        }

        return false;
    });

    $(document).on('click', '.see-more-result', function (e) {
        e.preventDefault();
        var label = $(this).closest('.content-section-result').find('.container-radio-setting-survey');
        var redirectQuestionId = label.data('question-id');
        var url = label.data('url');
        var redirectSectionIds = [];
        var id;
        var detailResult = $('#detail-result-' + redirectQuestionId);
        var surveyToken = $(this).attr('survey-token');
        detailResult.hide('slow');
        label.each(function () {
            var result = $(this).children('input');
            if (result.prop('checked')) {
                redirectSectionIds.push(result.val());
                id = result.val();
            }
        });

        if (redirectSectionIds.length) {
            $.ajax({
                method: 'POST',
                url: url,
                dataType: 'json',
                data: {
                    id: id,
                    survey_token: surveyToken
                },
                success: function (data) {
                    if (data.success) {
                        detailResult.html(data.html).promise().done(function () {
                            subResults();
                            var ul = detailResult.find('ul');
                            ul.each(function () {
                                $(this).css('max-width', '100%');
                            });
                            detailResult.show('slow');
                            detailResult.append(
                                '<br><button class="btn btn-warning close-detail-result">' + Lang.get('lang.close') + '</button>'
                            );
                            detailResult.css('border', 'dashed');
                        });
                    }
                }
            });
        } else {
            alertDanger({ message: Lang.get('lang.select_option') });
        }
    });

    $(document).on('click', '.close-detail-result', function (e) {
        e.preventDefault();
        $(this).closest('div').hide('slow');
    });

    $(document).on('click', '.see-personal-result', function (e) {
        e.preventDefault();
        var label = $(this).closest('.li-question-review').find('.container-radio-setting-survey');
        var id;
        var url = $(this).data('url');
        var token = $(this).data('token');
        label.each(function () {
            var result = $(this).children('input');
            if (result.prop('checked')) {
                id = result.closest('.item-answer').data('id');
            }
        });
        var personResult = $('#personal-redirect-result-' + $(this).data('question-id'));
        personResult.hide('slow');
        $.ajax({
            method: 'POST',
            url: url,
            dataType: 'json',
            data: {
                id: id,
                token: token
            },
            success: function (data) {
                if (data.success) {
                    personResult.html(data.html).promise().done(function () {
                        var ul = personResult.find('ul');
                        ul.each(function () {
                            $(this).css('max-width', '100%');
                        });
                        personResult.show('slow');
                        personResult.append(
                            '<br><button class="btn btn-warning close-detail-result">' + Lang.get('lang.close') + '</button>'
                        );
                        personResult.css('border', 'dashed');
                    });
                }
            }
        });
    });
});

function handelManagement(event) {
    $('.menu-management').removeClass('active');
    event.addClass('active');
}

function closeSurvey(redirect = '') {
    var url = $('#close-survey').attr('data-url');

    $.ajax({
        method: 'GET',
        url: url,
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                $('#close-survey').addClass('hide-div');
                $('#open-survey').removeClass('hide-div');

                if (redirect) {
                    window.location.href = redirect;
                }
            } else {
                alertDanger({ message: data.message });
            }
        }
    });
}

function changeToken(element) {
    var oldToken = element.attr('data-token');
    var newToken = toSlug($.trim(element.val()));

    if (oldToken != newToken && newToken) {
        confirmInfo(
            { message: Lang.get('lang.confirm_change_token', { token: oldToken, new_token: newToken }) },
            function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    method: 'POST',
                    url: element.data('url'),
                    dataType: 'json',
                    data: {
                        'survey_id': element.data('survey-id'),
                        'token': newToken,
                        'old_token': oldToken,
                    }
                })
                    .done(function (data) {
                        if (data.success) {
                            alertSuccess({ message: Lang.get('lang.change_success') });
                            $('.next-section-survey').attr('data-url', data.next_section_url);
                            $('.save-token-survey').hide();
                            $('.input-edit-token').attr('data-token', data.new_token);
                            $('.input-edit-token').attr('data-original-title', data.new_token);
                            $('.input-edit-token').attr('value', data.new_token);
                            $('#setting-survey').attr('data-url', data.setting_url);
                            $('.link-survey').attr('href', data.link_doing);
                            element.val(data.new_token);
                        } else {
                            alertDanger({ message: data.message });
                        }
                    })
                    .fail(function (data) {
                        alertWarning({ message: data.responseJSON.token[0] });
                    });
            }
        );
    } else {
        element.val(oldToken);
    }
}

function changeTokenManage(element) {
    var oldTokenManage = element.attr('data-token-manage');
    var newTokenManage = element.val().replace(' ', '_');

    if (oldTokenManage != newTokenManage && newTokenManage) {
        confirmInfo(
            { message: Lang.get('lang.confirm_change_token_manage', { token_manage: oldTokenManage, new_token_manage: newTokenManage }) },
            function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    method: 'POST',
                    url: element.data('url'),
                    dataType: 'json',
                    data: {
                        'survey_id': element.data('survey-id'),
                        'token_manage': newTokenManage,
                        'old_token_manage': oldTokenManage,
                    }
                })
                    .done(function (data) {
                        if (data.success) {
                            alertSuccess({ message: Lang.get('lang.change_success') });
                            $('#overview-survey').attr('data-url', data.overview_url);
                            $('#results-survey').attr('data-url', data.result_url);
                            $('#delete-survey').attr('data-url', data.delete_survey_url);
                            $('#close-survey').attr('data-url', data.close_survey_url);
                            $('#open-survey').attr('data-url', data.open_survey_url);
                            $('#edit-survey').attr('data-url', data.edit_survey_url);
                            $('#clone-survey').attr('data-url', data.clone_survey_url);
                            $('.input-edit-token-manage').attr('data-token-manage', data.new_token_manage);
                            $('.input-edit-token-manage').attr('data-original-title', data.new_token_manage);
                            $('.link-manage').attr('href', data.link_manage);

                            if (typeof (history.pushState) != 'undefined') {
                                var obj = { Page: 'update-url', Url: data.new_token_manage };
                                history.pushState(obj, obj.Page, obj.Url);
                            }

                            $('.edit-token-manage-survey').hide();
                            element.val(data.new_token_manage);
                        } else {
                            alertDanger({ message: data.message });
                        }
                    })
                    .fail(function (data) {
                        alertWarning({ message: data.responseJSON.token_manage[0] });
                    });
            }
        );
    } else {
        element.val(oldTokenManage);
    }
}

function toSlug(str) {
    str = str.toLowerCase();
    str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
    str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
    str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
    str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
    str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
    str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
    str = str.replace(/(đ)/g, 'd');
    str = str.replace(/([^0-9a-z-\s])/g, '-');
    str = str.replace(/(\s+)/g, '-');
    str = str.replace(/^-+/g, '');
    str = str.replace(/-+$/g, '');

    return str;
}
