<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date and after start time',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field is required.',
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values is present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'phone' => 'phone',
        'name' => 'name',
        'image' => 'image',
        'birthday' => 'birthday',
        'address' => 'address',
        'gender' => 'gender',
    ],
    'msg' => [
        'required' => 'This field is required.',
        'remote' => 'Please fix this field.',
        'email' => 'Please enter a valid email address.',
        'name' => 'The name is incorrect or has invalid characters.',
        're_password' => 'Your new password and confirmation password do not match ',
        'url' => 'Please enter a valid URL.',
        'date' => 'Please enter a valid date.',
        'dateISO' => 'Please enter a valid date (ISO).',
        'number' => 'Please enter a valid number.',
        'digits' => 'Please enter only digits.',
        'creditcard' => 'Please enter a valid credit card number.',
        'equalTo' => 'Please enter the same value again.',
        'maxlength' => 'Please enter no more than {0} characters.',
        'minlength' => 'Please enter at least {0} characters.',
        'rangelength' => 'Please enter a value between {0} and {1} characters long.',
        'range' => 'Please enter a value between {0} and {1}.',
        'max' => 'Please enter a value less than or equal to {0}.',
        'min' => 'Please enter a value greater than or equal to {0}.',
        'invalid_mail' => 'Please enter vali tail email.',
        'file' => 'The file upload must be type in jpeg|jpg|png||gif|bmp and at least 2MB',
        'tailmail' => 'You must enter at least one type of email',
        'start_time_after_now' => 'The start of survey must be after time now.',
        'after_start_time' => 'The end of the survey must be at least 30 minutes after the start time.',
        'end_time_after_now' => 'The end of the survey must be after time now.',
        'more_than_30_minutes' => 'The closing time must be at least 30 minutes longer than the current time.',
        'duplicate_section_title' => 'The title of the section is identical',
        'duplicate_question_title' => 'There is a question that coincides with this question',
        'duplicate_answer_title' => 'There was an answer that matched this answer',
    ],
    'password_without_spaces_and_require_letter_number_special_character' => 'Your password can not be started and ends with a space and required has letters, numbers, special characters.',
];
