$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    autoScroll();

    $(document).on('click', '.zoom-btn-result', function (event) {
        event.preventDefault();
        var contentSection = $(this).closest('.wrapper-section-result').next('.content-section-result');

        if ($(contentSection).is(":visible")) {
            $(contentSection).hide('slide', { direction: 'up', }, 600);
            $(this).removeClass('zoom-in-btn');
            $(this).addClass('zoom-out-btn');
            $(this).closest('.wrapper-section-result').css('border-bottom', '1px solid #1987ad');
        } else {
            $(contentSection).show('slide', { direction: 'up', }, 600);
            $(this).removeClass('zoom-out-btn');
            $(this).addClass('zoom-in-btn');
            $(this).closest('.wrapper-section-result').css('border-bottom', 'none');
        }

        return false;
    });

    results();

    $(document).on('change', '.page-answer-detail', function (event) {
        event.preventDefault();
        var page = parseInt($(this).val());
        var countPage = parseInt($('.count-result').text());

        if (!Number.isInteger(page) || page > countPage || page < 1) {
            page = 1;
        }

        $('.page-answer-detail').val(page);
        getPageDetail(page);
    });

    $(document).on('click', '.preview-answer-detail', function (event) {
        event.preventDefault();
        var page = parseInt($('.page-answer-detail').val()) - 1;

        if (page < 1) {
            page = 1;
        }

        $('.page-answer-detail').val(page);
        getPageDetail(page);
    });

    $(document).on('click', '.next-answer-detail', function (event) {
        event.preventDefault();
        var countPage = parseInt($('.count-result').text());
        var page = parseInt($('.page-answer-detail').val()) + 1;

        if (page > countPage) {
            page = countPage;
        }

        $('.page-answer-detail').val(page);
        getPageDetail(page);
    });

    function getPageDetail(page) {
        var url = $('#btn-personal-result').data('url');
        var startDate = $('#chart-personal-start-date').val();
        var endDate = $('#chart-personal-end-date').val();

        $.ajax({
            url: url + '?page=' + page,
            dataType: 'json',
            data: {
                start_date: startDate,
                end_date: endDate,
            },
        })
            .done(function (data) {
                $('#div-management-survey').html(data.html);
                $('[data-toggle="tooltip"]').tooltip();
                location.hash = page;
                autoAlignChoiceAndCheckboxIcon();
            });
    }
});

function autoAlignChoiceAndCheckboxIcon() {
    // auto align center multi-choice icon
    $('.li-question-review .item-answer .checkmark-radio').each(function () {
        var height = $(this).parent().height();
        var top = 12.5 * (height / 25 - 1);
        $(this).css('top', top + 'px');
    });

    // auto align center checkboxes icon
    $('.li-question-review .item-answer .checkmark-setting-survey').each(function () {
        var height = $(this).parent().height();
        var top = 12.5 * (height / 25 - 1);
        $(this).css('top', top + 'px');
    });
}

function autoScroll() {
    if ($('.scroll-answer-result').height() >= 250) {
        $('.scroll-answer-result').css({
            'overflow-y': 'scroll',
            'max-height': '250px',
        });
    }
}

function results() {
    $('.checkboxes-result').each(function () {
        var text = createDataForChart($(this).attr('data'));
        var dataCheckboxes = $.parseJSON((text));

        createBarChart($(this).attr('id'), dataCheckboxes);
    });

    $('.multiple-choice-result').each(function () {
        var text = createDataForChart($(this).attr('data'));
        var dataMultipleChoice = $.parseJSON(text);

        createPieChart($(this).attr('id'), dataMultipleChoice);
    });

    $('.redirect-result').each(function () {
        var text = createDataForChart($(this).attr('data'));
        var dataRedirect = $.parseJSON(text);

        createPieChart($(this).attr('id'), dataRedirect, true);
    });

    $('.linearscale-result').each(function () {
        var value = $(this).data('linear');
        var text = createDataLinrearForChart($(this).attr('data'), value);
        var dataLinearScale = $.parseJSON(text);
        var arrLinear = [];

        for (var i = parseInt(value.min_value); i <= parseInt(value.max_value); i++) {
            arrLinear.push(i);
        }

        createBarChartColumn($(this).attr('id'), dataLinearScale, value, arrLinear);
    });

    $('.grid-result').each(function () {
        var text = $(this).attr('data');
        var dataChart = $.parseJSON(text);
        var subQuestions = $(this).data('sub-questions');
        var subOptions = $(this).data('sub-options');

        createCombineChart($(this).attr('id'), subQuestions, subOptions, dataChart);
    });

    // excel option menu
    $('.option-menu-group').on('click', function (e) {
        e.stopPropagation();
        $('.survey-select-options').hide();
        $('.option-menu-dropdown').hide();
        $(this).children('.option-menu').toggleClass('active').next('ul.option-menu-dropdown').toggle();

        return false;
    });

    $(document).click(function () {
        $('.option-menu').removeClass('active');
        $('.option-menu-dropdown').hide();
    });

    $(document).on('click', '.submit-export-excel', function (event) {
        event.preventDefault();
        $('.info-export').submit();
    });
}

function getDataCol(index, dataOfOneRow = []) {

    for (var i = 0; i < dataOfOneRow.length; i++) {

        if (dataOfOneRow[i].content == index) {

            return dataOfOneRow[i].count;
        }
    }

    return 0;
}

function createCombineChart(id, subQuestions, subOptions, dataChart) {
    var dataColumns = [];

    for (var i = 0; i < subOptions.length; i++) {
        var countEachRow = [];
        var index = i + 1;

        $.each(dataChart, function (key, dataOfOneRow) {
            countEachRow.push(getDataCol(index, dataOfOneRow));
        });

        dataColumns.push(countEachRow);
    }

    Highcharts.chart(id, {
        title: {
            text: ''
        },
        xAxis: {
            categories: subQuestions
        },
        yAxis: {
            title: {
                text: ''
            },
            allowDecimals: false
        },
        credits: {
            enabled: false,
        },

        series: (function () {
            var series = [];

            for (var i = 0; i < subOptions.length; i++) {
                series.push({
                    type: 'column',
                    name: subOptions[i],
                    data: dataColumns[i]
                });
            }

            return series;
        }())
    });
}

function createBarChartColumn(id, data, value, arr = []) {
    Highcharts.chart(id, {
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            categories: arr,
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },
        credits: {
            enabled: false,
        },
        series: [{
            name: 'Population',
            data: data,
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
}

function createBarChart(id, data) {
    Highcharts.chart(id, {
        chart: {
            type: 'bar',
            inverted: true,
        },
        exporting: {
            enabled: false,
        },
        title: {
            text: '',
        },
        subtitle: {
            text: '',
        },
        xAxis: {
            type: 'category',
        },
        yAxis: {
            title: {
                text: '',
            }
        },
        legend: {
            enabled: false,
        },
        credits: {
            enabled: false,
            position: {
                align: 'left',
            }
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}%',
                }
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>',
        },
        series: [
            {
                name: '',
                colorByPoint: true,
                data: data,
            }
        ],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500,
                },
                chartOptions: {
                    xAxis: {
                        labels: {
                            formatter: function () {
                                return this.value.charAt(0);
                            }
                        }
                    },
                    yAxis: {
                        labels: {
                            align: 'left',
                            x: 0,
                            y: -2,
                        },
                        title: {
                            text: '',
                        }
                    }
                }
            }]
        }
    });
}

function createPieChart(id, data, redirect = false) {
    var options3d = redirect ? { enabled: true, alpha: 45, beta: 0, } : { enabled: false, };
    Highcharts.chart(id, {
        chart: {
            type: 'pie',
            options3d: options3d,
        },
        exporting: {
            enabled: false,
        },
        title: {
            text: '',
        },
        credits: {
            enabled: false,
            position: {
                align: 'left',
            }
        },
        tooltip: {
            pointFormat: '<b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 40,
                dataLabels: {
                    enabled: true,
                    format: '{point.percentage:.1f} %',
                    colors: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                    distance: -50,
                },
                showInLegend: true,
            }
        },
        series: [
            {
                type: 'pie',
                name: '',
                data: data,
            }
        ],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500,
                },
                chartOptions: {
                    xAxis: {
                        labels: {
                            formatter: function () {
                                return this.value.charAt(0);
                            }
                        }
                    },
                    yAxis: {
                        labels: {
                            align: 'left',
                            x: 0,
                            y: -2,
                        },
                        title: {
                            text: '',
                        }
                    }
                }
            }]
        }
    });
}

function createDataLinrearForChart(data, value) {
    var newData = $.parseJSON(escapeSpecialChars(data));
    var text = '';

    $.each(newData, function (index, item) {
        text += `{"name": ${item['content']}, "y": ${item['percent']}}`;
        text += (index == newData.length - 1) ? '' : ',';
    });

    return '[' + text + ']';
}

function createDataForChart(data) {
    var newData = $.parseJSON(escapeSpecialChars(data));
    var text = '';

    $.each(newData, function (index, item) {
        var name = item['content'];

        if (name.length > 30) {
            name = name.substring(0, 30) + '...';
        }

        text += `{"name": "${name}", "y": ${item['percent']}}`;
        text += (index == newData.length - 1) ? '' : ',';
    });

    return '[' + text + ']';
}

function subResults() {
    $('.sub-checkboxes-result').each(function () {
        var text = createDataForChart($(this).attr('data'));
        var dataCheckboxes = $.parseJSON((text));

        createBarChart($(this).attr('id'), dataCheckboxes);
    });

    $('.sub-multiple-choice-result').each(function () {
        var text = createDataForChart($(this).attr('data'));
        var dataMultipleChoice = $.parseJSON(text);

        createPieChart($(this).attr('id'), dataMultipleChoice);
    });

    $('.sub-linearscale-result').each(function () {
        var text = createDataLinrearForChart($(this).attr('data'));
        var dataLinearScale = $.parseJSON(text);

        createBarChartColumn($(this).attr('id'), dataLinearScale);
    });

    $('.sub-grid-result').each(function () {
        var text = $(this).attr('data');
        var dataChart = $.parseJSON(text);
        var subQuestions = $(this).data('sub-questions');
        var subOptions = $(this).data('sub-options');

        createCombineChart($(this).attr('id'), subQuestions, subOptions, dataChart);
    });
}

function escapeSpecialChars(jsonString) {

    return jsonString.replace(/\\n/g, " ")
        .replace(/\\r/g, " ")
        .replace(/\\t/g, " ")
        .replace(/\\f/g, " ")
        .replace(/\\"/g, " ");
}

// Syns to Google Sheets
function createSheets(data, valueAddSheet) {
    var spreadsheetBody = {
        "properties": {
            "title": data['surveyInfo']['title'],
        },
        "sheets": valueAddSheet
    };
    var request = gapi.client.sheets.spreadsheets.create({}, spreadsheetBody);

    request.then(function (response) {
        updateGoogleToken(response.result['spreadsheetId'], data.surveyInfo.surveyId);

        if (data.surveyInfo.redirect.length == 0) {
            formatDataSheet(response.result['spreadsheetId']);
            putDataIntoSheets(data['valueSheets'], response.result['spreadsheetId'], data['surveyInfo']['title']);
        } else {
            for (var i = 0; i <= data.surveyInfo.redirect.length - 1; i++) {
                formatDataSheet(response.result['spreadsheetId'], i);
                putDataIntoSheets(data['valueSheets'], response.result['spreadsheetId'], data.surveyInfo.redirect[i]);
            }
        }
        $('.loading-hide').css('display', 'none');
        openInNewTab(response.result['spreadsheetUrl']);
    }, function (reason) {
        console.error('error: ' + reason.result.error.message);
    });
}

function updateGoogleToken(token, surveyId) {
    var url = $('#syns-to-sheets').data('google-token-url');

    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: {
            token: token,
            surveyId: surveyId,
        },
    })
        .done(function (data) {
            console.log(data);
        });
}

//update data google sheets
function putDataIntoSheets(data, ssID, surveyTitle) {
    var range = surveyTitle;
    var params = {
        spreadsheetId: ssID,
        range: range,
        valueInputOption: 'RAW',
    };
    var valueRangeBody = {
        "values": data[surveyTitle]
    };
    var request = gapi.client.sheets.spreadsheets.values.update(params, valueRangeBody);

    request.then(function (response) {
        console.log(response.result);
    }, function (reason) {
        console.error('error: ' + reason.result.error.message);
    });
}

function initClient() {
    var API_KEY = $('#syns-to-sheets').data('api-key');
    var CLIENT_ID = $('#syns-to-sheets').data('client-id');
    var SCOPE = $('#syns-to-sheets').data('google-scope');

    gapi.client.init({
        'apiKey': API_KEY,
        'clientId': CLIENT_ID,
        'scope': SCOPE,
        'discoveryDocs': ['https://sheets.googleapis.com/$discovery/rest?version=v4'],
    }).then(function () {
        gapi.auth2.getAuthInstance().isSignedIn.listen(updateSignInStatus);
        updateSignInStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
    });
}

function handleClientLoad() {
    gapi.load('client:auth2', initClient);
}

function updateSignInStatus(isSignedIn) {
    isSignedIn ? console.log('Signin Success') : console.log('Signin Fail');
}

function handleSignInClick(data) {
    if (data.surveyInfo.googleToken != null) {
        gapi.auth2.getAuthInstance().signIn()
            .then(function (response) {
                updateDataToSheets(data);
            });
    } else {
        gapi.auth2.getAuthInstance().signIn()
            .then(function (response) {
                var valueAddSheet = []
                var formatSheet = [];
                if (data['surveyInfo']['redirect'].length == 0) {
                    valueAddSheet.push({
                        "properties": {
                            "sheetId": 0,
                            "title": data['surveyInfo']['title'],
                        }
                    })
                    for (var i = 0; i <= 3; i++) {
                        formatSheet.push({
                            "sheetId": 0,
                            "startRowIndex": i,
                            "endRowIndex": i + 1,
                        })
                    }
                    valueAddSheet.push({
                        "merges": formatSheet,
                    })
                } else {
                    for (var i = 0; i <= data.surveyInfo.redirect.length - 1; i++) {
                        valueAddSheet.push({
                            "properties": {
                                "sheetId": i,
                                "title": data.surveyInfo.redirect[i],
                            }
                        })
                    }
                    for (var i = 0; i <= data.surveyInfo.redirect.length - 1; i++) {
                        for (var j = 0; j <= 3; j++) {
                            formatSheet.push({
                                "sheetId": i,
                                "startRowIndex": j,
                                "endRowIndex": j + 1,
                            })
                        }
                    }
                    valueAddSheet.push({
                        "merges": formatSheet,
                    })
                }
                createSheets(data, valueAddSheet);
            });
    }
}

function handleSignOutClick() {
    gapi.auth2.getAuthInstance().signOut();
}

function getDataToSheets() {
    var url = $('#syns-to-sheets').data('url');
    var token = $('#syns-to-sheets').data('token');

    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: {
            token: token,
        },
    })
        .done(function (data) {
            handleSignInClick(data);
        });
}

function updateDataToSheets(data) {
    var googleToken = data.surveyInfo.googleToken;
    openInNewTab('https://docs.google.com/spreadsheets/d/' + googleToken);

    if (data.surveyInfo.redirect.length == 0) {
        clearDataSheets(googleToken, data.surveyInfo.title);
        setTimeout(() => {
            putDataIntoSheets(data.valueSheets, googleToken, data.surveyInfo.title);
        }, 1000);
    } else {
        Promise.all(promiseLoopClearData(data, googleToken)).then(function (value) {
            promiseLoopPutData(data, googleToken);
        });
    }
}

//xu ly bat dong bo
function promiseLoopClearData(data, googleToken) {
    let listPromises = [];

    for (var i = 0; i <= data.surveyInfo.redirect.length - 1; i++) {
        listPromises.push(loopClearData(data, googleToken, data.surveyInfo.redirect[i]));
    }

    return listPromises;
}

function promiseLoopPutData(data, googleToken) {
    for (var i = 0; i <= data.surveyInfo.redirect.length - 1; i++) {
        loopPutData(data, googleToken, data.surveyInfo.redirect[i]);
    }
}

function loopClearData(data, googleToken, indexTitle) {
    return new Promise((resolve, reject) => {
        setTimeout(function () {
            clearDataSheets(googleToken, indexTitle);
            resolve(true);
        }, 2000);
    })
}

function loopPutData(data, googleToken, indexTitle) {
    setTimeout(function () { putDataIntoSheets(data.valueSheets, googleToken, indexTitle); }, 1000);
}

//clear data google sheets
function clearDataSheets(googleToken, title) {
    var params = {
        spreadsheetId: googleToken,
        range: title,
    };

    var clearValuesRequestBody = {};

    var request = gapi.client.sheets.spreadsheets.values.clear(params, clearValuesRequestBody);
    request.then(function (response) {
        console.log(response.result);
    }, function (reason) {
        console.error('error: ' + reason.result.error.message);
    });
}

function openInNewTab(href) {
    Object.assign(document.createElement('a'), {
        target: '_blank',
        href,
    }).click();
}

$(document).on('click', '#syns-to-sheets', function (event) {
    $('#syns-to-sheets').css('display', 'none');
    $('.loading-hide').css('display', 'inline-block');
    setTimeout(function () {
        $('#syns-to-sheets').css('display', 'inline-block');
        $('.loading-hide').css('display', 'none');
    }, 5000);
    getDataToSheets();
});

//format cell google sheets
function formatDataSheet(spreadsheetId, sheetId) {
    var params = {
        spreadsheetId: spreadsheetId,
    };

    var batchUpdateSpreadsheetRequestBody = {
        requests: [{
            "repeatCell": {
                "range": {
                    "sheetId": sheetId,
                    "startRowIndex": 0,
                    "endRowIndex": 5
                },
                "cell": {
                    "userEnteredFormat": {
                        "horizontalAlignment": "LEFT",
                        "textFormat": {
                            "fontSize": 12,
                            "bold": true
                        }
                    }
                },
                "fields": "userEnteredFormat(horizontalAlignment,textFormat)"
            }
        }],
    };

    var request = gapi.client.sheets.spreadsheets.batchUpdate(params, batchUpdateSpreadsheetRequestBody);
    request.then(function (response) {
    }, function (reason) {
    });
}
