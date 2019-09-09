<?php

namespace App\Repositories\Survey;

use DB;
use Exception;
use App\Repositories\Result\ResultInterface;
use App\Repositories\Question\QuestionInterface;
use App\Repositories\Like\LikeInterface;
use App\Repositories\Invite\InviteInterface;
use App\Repositories\Setting\SettingInterface;
use App\Repositories\User\UserInterface;
use App\Repositories\Section\SectionInterface;
use App\Repositories\BaseRepository;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Result;
use Carbon\Carbon;
use App\Models\Survey;
use Auth;
use App\Traits\SurveyProcesser;
use App\Mail\ManageSurvey;
use App\Mail\InviteSurvey;
use Mail;

class SurveyRepository extends BaseRepository implements SurveyInterface
{
    use SurveyProcesser;

    public function getModel()
    {
        return Survey::class;
    }

    public function getResutlSurvey($survey, $userRepo)
    {
        foreach ($survey->sections as $section) {
            if (!$section->redirect_id) {
                $resultsSurveys[] = [
                    'section' => $section,
                    'question_result' => $this->getResultOfEachSection($survey, $userRepo, $section),
                ];
            }
        }

        return $resultsSurveys;
    }

    public function createSurvey($userId, $data, $status, $userRepo)
    {
        $surveyInputs = [
            'title' => $data->get('title'),
            'description' => $data->get('description'),
            'start_time' => !empty($data->get('start_time')) ? $data->get('start_time') : Carbon::now(),
            'end_time' => $data->get('end_time'),
        ];
        $data = $data->all();

        $surveyInputs['feature'] = config('settings.survey.feature.default');
        $surveyInputs['token'] = md5(uniqid(rand(), true));
        $surveyInputs['token_manage'] = md5(uniqid(rand(), true));
        $surveyInputs['status'] = $status;

        $survey = parent::create($surveyInputs);

        if ($data['background']) {
            $survey->media()->create([
                'user_id' => $userId,
                'type' => config('settings.media_type.image'),
                'url' => $this->cutUrlImage($data['background'])
            ]);
        }

        if (!$survey) {
            throw new Exception('Error Processing Request', 1);
        }

        //create owner
        $owner = $survey->members()->attach($userId, [
            'role' => Survey::OWNER,
            'status' => Survey::APPROVE,
        ]);

        // create members
        foreach ($data['members'] as $member) {
            $memberId = $userRepo->where('email', $member['email'])->first()->id;

            if ($member['role'] == Survey::OWNER) {
                throw new Exception("Role not permited!", 1);
            }

            $survey->members()->attach($memberId, [
                'role' => $member['role'],
                'status' => Survey::APPROVE,
            ]);
        }

        // create invite email
        $inviteData = $data['invited_email'];

        if (count($inviteData['emails'])) {

            if ($data['setting']['privacy'] == config('settings.survey_setting.privacy.private')) {
                array_push($inviteData['emails'], Auth::user()->email);
            }
            $inviteMails = $this->formatInviteMailsString($inviteData['emails']);

            $survey->invite()->create([
                'invite_mails' => $inviteMails,
                'answer_mails' => '',
                'subject' => $inviteData['subject'],
                'message' => $inviteData['message'],
                'status' => config('settings.survey.invite_status.not_finish'),
                'number_invite' => count($inviteData['emails']),
                'number_answer' => config('settings.survey.number_answer_default'),
            ]);
        }

        // create settings of survey
        $settingsData = $this->createSettingDataArray($data['setting']);

        if (Auth::user()->checkLoginWsm()) {
            $settingMailToWsm = [
                'key' => config('settings.setting_type.send_mail_to_wsm.key'),
                'value' => $inviteData['send_mail_to_wsm'],
            ];
            array_push($settingsData, $settingMailToWsm);
        }

        $survey->settings()->createMany($settingsData);

        $orderSection = 0;
        $dataRedirectId = [];

        // create sections
        foreach ($data['sections'] as $section) {
            $sectionData['title'] = $section['title'];
            $sectionData['description'] = $section['description'];
            $sectionData['order'] = ++$orderSection;
            $sectionData['update'] = config('settings.survey.section_update.default');
            $sectionData['redirect_id'] = isset($section['redirect_id']) ? $dataRedirectId[$section['redirect_id']] : config('settings.section_redirect_id_default');

            $sectionCreated = $survey->sections()->create($sectionData);

            $orderQuestion = 0;

            // create questions
            if (isset($section['questions'])) {
                foreach ($section['questions'] as $question) {
                    $questionData['title'] = $question['title'];
                    $questionData['description'] = $question['description'];
                    $questionData['required'] = $question['require'];
                    $questionData['order'] = ++$orderQuestion;
                    $questionData['update'] = config('settings.survey.question_update.default');

                    $questionCreated = $sectionCreated->questions()->create($questionData);

                    // create type question in setting
                    $valueSetting = $question['type'] == config('settings.question_type.date') ? $question['date_format'] : '';

                    if ($question['type'] == config('settings.question_type.linear_scale')) {
                        $attributes = [
                            'min_value' => $question['min_value'],
                            'max_value' => $question['max_value'],
                            'min_content' => $question['min_content'],
                            'max_content' => $question['max_content'],
                        ];

                        $valueSetting = json_encode($attributes);
                    }

                    if ($question['type'] == config('settings.question_type.grid')) {
                        $valueGrids = $question['subQuestions'];

                        $questionCreated->update([
                            'sub_questions' => json_encode($valueGrids),
                        ]);
                        $valueSetting = json_encode($question['subOptions']);
                    }

                    if ($question['type'] == config('settings.question_type.dropdown')) {
                        $valueDropdown = [];
                        foreach ($question['answers'] as $option) {
                            array_push($valueDropdown, $option['content']);
                        }

                        $valueSetting = json_encode($valueDropdown);
                    }

                    $questionCreated->settings()->create([
                        'key' => $question['type'],
                        'value' => $valueSetting,
                    ]);

                    // create image or video (media) of question
                    if ($question['media']) {
                        $questionMedia['user_id'] = $userId;
                        $questionMedia['url'] = $this->cutUrlImage($question['media']);
                        $questionMedia['type'] = config('settings.media_type.image');

                        if ($question['type'] == config('settings.question_type.video')) {
                            $questionMedia['type'] = config('settings.media_type.video');
                        }

                        $questionCreated->media()->create($questionMedia);
                    }

                    // create answers
                    if (isset($question['answers'])) {
                        foreach ($question['answers'] as $answer) {
                            $answerData['content'] = $answer['content'];
                            $answerData['update'] = config('settings.survey.answer_update.default');

                            $answerCreated = $questionCreated->answers()->create($answerData);

                            // create type answer in setting
                            $answerCreated->settings()->create([
                                'key' => $answer['type'],
                            ]);

                            // create image (media) of answer
                            if ($answer['media']) {
                                $answerMedia['user_id'] = $userId;
                                $answerMedia['url'] = $this->cutUrlImage($answer['media']);
                                $answerMedia['type'] = config('settings.media_type.image');

                                $answerCreated->media()->create($answerMedia);
                            }

                            // save answer redirect id if question type is redirect
                            if ($question['type'] == config('settings.question_type.redirect')) {
                                $dataRedirectId[$answer['id']] = $answerCreated->id;
                            }
                        }
                    }
                }
            }
        }

        // if survey is create then send mail after created
        if ($status == config('settings.survey.status.open')) {
            $this->sendMailCreateSurvey($survey, Auth::user(), $userRepo, [
                'message' => $data['invited_email']['message'],
                'subject' => $data['invited_email']['subject'],
                'members' => $data['members'],
                'invite_mails' => $inviteData['emails'],
                'setting_mail_to_wsm' => !empty($settingMailToWsm['value']) ? $settingMailToWsm['value'] : '',
            ]);
        }

        return $survey;
    }

    public function updateSettingSurvey($survey, $data, $userRepo)
    {
        $data = $data->all();

        // update members
        $membersData = [];
        $ownerId = $survey->members()->wherePivot('role', Survey::OWNER)->first()->id;

        // only owner can edit members
        if (Auth::user()->id == $ownerId) {
            foreach ($data['members'] as $member) {
                $memberId = $userRepo->where('email', $member['email'])->first()->id;

                if ($memberId == $ownerId || $member['role'] == Survey::OWNER) {
                    throw new Exception("Can not edit member", 1);
                }

                $membersData[$memberId] = [
                    'role' => $member['role'],
                    'status' => Survey::APPROVE,
                ];
            }

            $membersSyncData = $survey->members()->wherePivot('role', '!=', Survey::OWNER)->sync($membersData);

            // send mail to new member's survey (manage)
            $emailData = [
                'title' => $data['invited_email']['subject'],
                'messages' => $data['invited_email']['message'],
                'description' => $survey->description,
                'linkManage' => route('survey.management', $survey->token_manage),
                'link' => route('survey.create.do-survey', $survey->token),
            ];

            $members = $userRepo->whereIn('id', $membersSyncData['attached'])->get();

            // send mail manage to members
            foreach ($members as $member) {
                $emailData['name'] = $member->name;
                Mail::to($member->email)->queue(new ManageSurvey($emailData));
            }
        }

        // update invite mail
        $inviteData = $data['invited_email'];
        $inviter = $survey->invite;

        if (!empty($inviter) || !empty($inviteData['emails'])) {
            $answerMails = $answerOld = !empty($inviter) ? $inviter->answer_mails : '';

            $answerOld = collect(!empty($answerOld) ? explode('/', trim($answerOld, '/')) : []);
            $answerDelete = $answerOld->diff($inviteData['answer_emails'])->all();

            if (count($answerDelete)) {
                $userIds = $userRepo->whereIn('email', $answerDelete)->pluck('id')->all();
                $isDelete = $survey->results()->whereIn('user_id', $userIds)->forceDelete();

                if (!$isDelete) {
                    throw new Exception("Delete result answers of user failed !", 1);
                }

                $answerMails = $this->formatInviteMailsString($inviteData['answer_emails']);
            }

            $inviteMails = $this->formatInviteMailsString($inviteData['emails']);

            $updateInviteData = [
                'invite_mails' => $inviteMails,
                'answer_mails' => $answerMails,
                'subject' => $inviteData['subject'],
                'message' => $inviteData['message'],
                'status' => config('settings.survey.invite_status.not_finish'),
                'number_invite' => count($inviteData['emails']),
                'number_answer' => count($inviteData['answer_emails']),
            ];

            if (empty($inviter)) {
                $survey->invite()->create($updateInviteData);
            } else {
                if ($survey->isSendUpdateOption()) {
                    $sendUpdateMails = collect($inviter->send_update_mails_array);
                    $inviteMails = $inviteData['emails'];

                    $newSendUpdaateMails = $sendUpdateMails->reject(function ($mail) use ($inviteMails) {
                        return !in_array($mail, $inviteMails);
                    });

                    $sendUpdateMailsDeleted = $sendUpdateMails->diff($newSendUpdaateMails)->all();
                    $userIds = $userRepo->whereIn('email', $sendUpdateMailsDeleted)->pluck('id')->all();
                    $survey->results()->whereIn('user_id', $userIds)->forceDelete();

                    $updateInviteData['send_update_mails'] = join('/', $newSendUpdaateMails->all()) . '/';
                }

                $updateInviteData['number_invite'] = count($inviteData['emails']) + ($inviter->number_answer - count($answerDelete));
                $updateInviteData['number_answer'] = $inviter->number_answer - count($answerDelete);

                $survey->invite()->update($updateInviteData);
            }
        }

        // update settings of survey
        $survey->settings()->forceDelete();
        $settingsData = $this->createSettingDataArray($data['setting']);

        if (Auth::user()->checkLoginWsm()) {
            $settingMailToWsm = [
                'key' => config('settings.setting_type.send_mail_to_wsm.key'),
                'value' => $inviteData['send_mail_to_wsm'],
            ];
            array_push($settingsData, $settingMailToWsm);
        }

        $survey->settings()->createMany($settingsData);

        return true;
    }

    public function updateSurveyByObject($survey, $values)
    {
        return $survey->update($values);
    }

    public function updateSurvey($survey, $data, $status, $questionRepo, $answerRepo, $userRepo = null)
    {
        $oldSectionIds = $survey->sections->pluck('id')->all();
        $oldQuestionIds = $questionRepo->withTrashed()->whereIn('section_id', $oldSectionIds)->pluck('id')->all();

        $surveyInputs = [
            'title' => $data->get('title'),
            'description' => $data->get('description'),
            'start_time' => $data->get('start_time'),
            'end_time' => $data->get('end_time'),
        ];

        $surveyInputs['status'] = $status;
        $deleteData = $data->get('delete');
        $updateData = $data->get('update');
        $createData = $data->get('create');
        $isDraftToOpen = false;
        $inviter = $survey->invite;
        $isDeleteClientResult = false;

        if (count($createData['sections']) || count($createData['questions']) || count($createData['answers'])) {
            $isDeleteClientResult = true;
        }

        if ($survey->isDraft() && $status == config('settings.survey.status.open')) {
            $isDraftToOpen = true;
        }

        // if option update is "send all question survey again" OR havent invite_list, then set option update of elements to default (no-update)
        if (($status == config('settings.survey.status.open')
                && $data->get('option') == config('settings.option_update.send_all_question_survey_again'))
            ||  empty($inviter) || (empty($inviter->answer_mails) && empty($inviter->send_update_mails))
        ) {
            $updateStatus = config('settings.survey.section_update.default');
        }

        // update base information of survey
        $survey->update($surveyInputs);

        //update background image survey
        $this->updateBackgroundSurvey($survey, $data->get('background'), Auth::user()->id);

        // delete sections, questions, answers has deleted
        $answerRepo->deleteAnswersById($deleteData['answers']);
        $questionRepo->deleteQuestionsById($deleteData['questions']);
        DB::table('sections')->whereIn('id', $deleteData['sections'])->delete();

        // update sections
        foreach ($updateData['sections'] as $key => $value) {
            if (isset($updateStatus)) {
                $value['update'] = $updateStatus;
            }

            $value['redirect_id'] = isset($value['redirect_id']) ? $value['redirect_id'] : config('settings.section_redirect_id_default');

            $survey->sections()->where('id', $key)->first()->update($value);
        }

        // update questions
        foreach ($updateData['questions'] as $key => $value) {
            if (
                !$isDeleteClientResult && !empty($value['update'])
                && $value['update'] == config('settings.survey.question_update.updated')
            ) {
                $isDeleteClientResult = true;
            }

            if (isset($updateStatus)) {
                $value['update'] = $updateStatus;
            }

            $question = $questionRepo->withTrashed()->where('id', $key)->first();
            $question->update($value);

            if ($question->type == config('settings.question_type.linear_scale')) {
                $valueSetting = json_encode([
                    'min_value' => $value['min_value'],
                    'max_value' => $value['max_value'],
                    'min_content' => $value['min_content'],
                    'max_content' => $value['max_content'],
                ]);
                $question->settings()->update([
                    'value' => $valueSetting,
                ]);
            }

            if ($question->type == config('settings.question_type.grid')) {
                $valueGrids = $value['subQuestions'];

                $question->update([
                    'sub_questions' => json_encode($valueGrids),
                ]);
                $valueSetting = json_encode($value['subOptions']);

                $question->settings()->update([
                    'value' => $valueSetting,
                ]);
            }

            if ($question->type == config('settings.question_type.date')) {
                $question->settings()->first()->update([
                    'value' => $value['date_format'],
                ]);
            }

            // update question media if has
            $this->updateQuestionMedia($question, $value, Auth::user()->id);
        }

        // update answers
        foreach ($updateData['answers'] as $key => $value) {
            if (isset($updateStatus)) {
                $value['update'] = $updateStatus;
            }

            $answer = $answerRepo->withTrashed()->where('id', $key)->first();
            $answer->update($value);

            // update answer media if has
            $this->updateAnswerMedia($answer, $value, Auth::user()->id);
        }

        $dataRedirectId = [];

        // create new answers in old questions when update
        $this->createNewAnswers('', $questionRepo, $createData['answers'], Auth::user()->id, $dataRedirectId);

        // create new questions in old sections when update
        $this->createNewQuestions('', $survey, $createData['questions'], Auth::user()->id, $dataRedirectId);

        // create new sections, questions, answers when update
        $this->createNewSections($survey, $createData['sections'], Auth::user()->id, $dataRedirectId);

        // if current survey is draft and saved to open
        if ($isDraftToOpen) {
            $inviteMails = [];
            $message = '';
            $subject = '';

            if (!empty($inviter)) {
                $message = $inviter->message;
                $subject = $inviter->subject;
                $inviteMails = $inviter->invite_mails_array;
            }

            $members = $survey->members()->wherePivot('role', '!=', Survey::OWNER)->get();
            $settingMailToWsm = $survey->settings()->where('key', config('settings.setting_type.send_mail_to_wsm.key'))->first();
            $settingMailToWsm = !empty($settingMailToWsm) ? $settingMailToWsm->value : '';

            $this->sendMailCreateSurvey($survey, Auth::user(), $userRepo, [
                'message' => $message,
                'subject' => $subject,
                'members' => $members,
                'invite_mails' => $inviteMails,
                'setting_mail_to_wsm' => $settingMailToWsm,
            ]);

            return;
        }

        // process follow option update
        if ($status == config('settings.survey.status.open')) {
            $optionUpdate = $data->get('option');
            $sectionIds = $survey->sections()->pluck('id')->all();
            $updatedQuestionIds = $questionRepo->withTrashed()->whereIn('section_id', $sectionIds)
                ->where('update', config('settings.survey.question_update.updated'))
                ->pluck('id')->all();
            $userAnsweredIds = [];

            // if option update is "send all question of survey again" then delete all old results
            if ($optionUpdate == config('settings.option_update.send_all_question_survey_again')) {
                $userAnsweredIds = $survey->results()->whereNotNull('user_id')
                    ->pluck('user_id')->unique()->all();
                $survey->results()->forceDelete();
            } elseif (count($updatedQuestionIds) || $isDeleteClientResult) {
                // if option update is "dont send survey again" OR is "send question has updated" then delete all result of these questions has updated and these resluts incognito

                // delete resluts incognito
                DB::table('results')->where('survey_id', $survey->id)
                    ->whereNull('user_id')
                    ->whereNotNull('client_ip')
                    ->delete();

                $createdQuestionIds = array_diff($updatedQuestionIds, $oldQuestionIds);

                // if have created new question
                if (count($createdQuestionIds)) {
                    $createdSectionIds = $questionRepo->withTrashed()->whereIn('id', $createdQuestionIds)->pluck('section_id')->unique()->all();
                    $createdRedirectIds = $survey->sections()->whereIn('id', $createdSectionIds)->pluck('redirect_id')->unique()->all();

                    // if question created in normal section => send all mails has answered (send_update_mails list)
                    if (in_array(config('settings.section_redirect_id_default'), $createdRedirectIds)) {
                        $userAnsweredIds = $survey->results()->pluck('user_id')->unique()->all();
                    } else {  // if question created in redirect section => just send mails which have answered that redirect (send_update_mails list)
                        $userAnsweredIds = $survey->results()
                            ->whereIn('answer_id', $createdRedirectIds)
                            ->pluck('user_id')->unique()->all();
                    }
                } else { // just have updated question
                    $userAnsweredIds = DB::table('results')->whereIn('question_id', $updatedQuestionIds)->pluck('user_id')->unique()->all();
                }

                DB::table('results')->whereIn('question_id', $updatedQuestionIds)->delete();
            } elseif (empty($createData['sections']) && empty($createData['questions']) && empty($createData['answers'])) {
                return;
            }

            $this->sendMailUpdateSurvey($optionUpdate, $userAnsweredIds, $survey, Auth::user(), $userRepo);
        }
    }

    public function checkCloseSurvey($inviteIds, $surveyIds)
    {
        $ids = array_merge(
            $inviteIds->lists('survey_id')->toArray(),
            $surveyIds->lists('id')->toArray()
        );

        return app(SettingInterface::class)
            ->whereIn('survey_id', $ids)
            ->where([
                'key' => config('settings.key.limitAnswer'),
                'value' => 0,
            ])
            ->lists('survey_id')
            ->toArray();
    }

    public function listsSurvey($userId, $email = null)
    {
        $invites = $inviteIds = app(InviteInterface::class)
            ->where('recevier_id', $userId)
            ->orWhere('mail', $email);
        $surveys = $surveyIds = $this->where('user_id', $userId)->orWhere('mail', $email);
        $settings = $this->checkCloseSurvey($inviteIds, $surveyIds);
        $invites = $invites
            ->orderBy('id', 'desc')
            ->paginate(config('settings.paginate'));
        $surveys = $surveys
            ->orderBy('id', 'desc')
            ->paginate(config('settings.paginate'));

        return compact('surveys', 'surveys', 'settings');
    }

    public function checkSurveyCanAnswer(array $inputs)
    {
        $date = ($inputs['deadline']) ? Carbon::parse($inputs['deadline'])->gt(Carbon::now()) : true;
        $invite = true;
        $email = $inputs['email'];
        $surveyId = $inputs['surveyId'];

        if (!$date || !$inputs['status']) {
            return false;
        } elseif (!$inputs['type']) {
            $invite = app(InviteInterface::class)
                ->where('recevier_id', $inputs['userId'])
                ->where('survey_id', $inputs['surveyId'])
                ->orWhere(function ($query) use ($email, $surveyId) {
                    $query->where('mail', $email)->where('survey_id', $surveyId);
                })
                ->exists();
        }

        return $invite;
    }

    public function getSettings($surveyId)
    {
        if (!$surveyId) {
            return [];
        }

        return app(SettingInterface::class)
            ->where('survey_id', $surveyId)
            ->lists('value', 'key')
            ->toArray();
    }

    public function getHistory($userId, $clientIp, $surveyId, array $options)
    {
        if (!$surveyId) {
            return [
                'history' => [],
                'results' => [],
            ];
        }

        if ($userId) {
            if ($options['type'] == 'history') {
                $results = app(QuestionInterface::class)
                    ->getResultByQuestionIds($surveyId)
                    ->where('sender_id', $userId)
                    ->get();
            } else {
                $email = $options['email'];
                $name = $options['name'];
                $results = app(QuestionInterface::class)
                    ->getResultByQuestionIds($surveyId)
                    ->where(
                        function ($query) use ($userId, $clientIp, $email) {
                            $query->where('sender_id', $userId)
                                ->orWhere(
                                    function ($query) use ($clientIp, $email) {
                                        $query->where('email', $email)
                                            ->where('client_ip', $clientIp);
                                    }
                                );
                        }
                    )
                    ->get()
                    ->toArray();

                if (empty($email) && $name) {
                    $results = app(QuestionInterface::class)
                        ->getResultByQuestionIds($surveyId)
                        ->where(
                            function ($query) use ($userId, $clientIp, $name) {
                                $query->where('sender_id', $userId)
                                    ->orWhere(
                                        function ($query) use ($clientIp, $name) {
                                            $query->where('name', $name)
                                                ->where('client_ip', $clientIp);
                                        }
                                    );
                            }
                        )
                        ->get()
                        ->toArray();
                }

                $collection = collect($results);

                return $collection->groupBy('created_at')->toArray();
            }
        } else {
            if ($options['type'] == 'history') {
                $results = app(QuestionInterface::class)
                    ->getResultByQuestionIds($surveyId)
                    ->where('sender_id', null)
                    ->where('client_ip', $clientIp)
                    ->get();
            }
        }

        if (!$results) {
            return [];
        }

        $history = [];
        $maxCreate = $results->max('created_at');

        foreach ($results as $key => $value) {
            if ($options['type'] == 'history' && $value->created_at == $maxCreate) {
                $history[$value->answer_id] = $value->content;
            }
        }

        return [
            'history' => $history,
            'results' => $results,
        ];
    }

    public function getUserAnswer($token)
    {
        $survey = $this->where('token', $token)->orWhere('token_manage', $token)->first();

        if (!$survey) {
            return false;
        }

        $results = app(QuestionInterface::class)
            ->getResultByQuestionIds($survey->id)
            ->with('sender');
        $results = $results->distinct('created_at')->get([
            'created_at',
            'name',
            'email',
            'sender_id',
            'client_ip',
        ])
            ->toArray();

        if (!$results) {
            return [];
        }
        /*
            Get all user answer survey and group by user id.
            Sender_id can be null.
        */
        $collection = collect($results)->groupBy('sender_id')->toArray();
        //  Get users login when anwser survey with key = user id.
        $userLogin = collect($collection)->except([''])->toArray();
        /*
            Get users not login when answer survey and group by email.
            Email can be set default because user don't need enter email.
        */
        $settings = $this->getSettings($survey->id);

        $userNotLogin = in_array('', array_keys($collection))
            ? collect(collect($collection['']))->groupBy('client_ip')->toArray()
            : [];

        return array_merge($userLogin, $userNotLogin);
    }

    private function chart(array $inputs)
    {
        $results = [];

        foreach ($inputs as $key => $value) {
            $results[] = [
                'answer' => $value['content'],
                'percent' => $value['percent'],
            ];
        }

        return $results;
    }

    public function viewChart($token)
    {
        $results = $this->getResutlSurvey($token);
        $charts = [];

        if (!$results) {
            return [
                'charts' => null,
                'status' => false,
            ];
        }

        foreach ($results as $key => $value) {
            $charts[] = [
                'question' => $value['question'],
                'chart' => ($this->chart($value['answers'])) ?: null,
            ];
        }

        return [
            'charts' => $charts,
            'status' => true,
        ];
    }

    /* new */
    public function getResultsWithMonth($survey, $month, $questions)
    {
        if ($month) {
            $dateMonthArray = explode('-', $month);
            $startMonth = Carbon::createFromDate($dateMonthArray[1], $dateMonthArray[0])->startOfMonth();
            $endMonth = Carbon::createFromDate($dateMonthArray[1], $dateMonthArray[0])->endOfMonth();
            $results = $survey->results()->whereIn('question_id', $questions->pluck('id')->all())
                ->where('created_at', '>=', $startMonth)->where('created_at', '<', $endMonth);
        } else {
            $results = $survey->results()->whereIn('question_id', $questions->pluck('id')->all());
        }

        return $results;
    }

    public function getResultWithRedirectSection($questionsRedirect, $questionsWithoutRedirect, $survey, $month, $requiredSurvey, $count = 0, $caseResult = [], $title = '')
    {
        $dataRedirect = [];
        $titleTmp = $title;
        $answers = Answer::where('question_id', $questionsRedirect[$count]->id)->get();

        foreach ($answers as $answer) {
            array_push($caseResult, $answer->id);
            $tokensResult = $survey->results()->whereIn('answer_id', $caseResult)->get()->groupBy('token');
            $dataToken = [];

            //get Tokens do by case
            foreach ($tokensResult as $key => $value) {
                if (count($value) == count($caseResult)) {
                    $dataToken[] = $key;
                }
            }

            //get section by redirect_id
            $sections = $survey->sections->where('redirect_id', $answer->id);
            $section_id = [];
            foreach ($sections as $section) {
                array_push($section_id, $section->id);
            }

            //get question by case
            $questionsInsideRedirectSection = app(QuestionInterface::class)->withTrashed()
                ->whereIn('section_id', $section_id)
                ->with('settings', 'section')->get();
            $questions = $questionsWithoutRedirect->merge($questionsInsideRedirectSection)->sortBy('order')->sortBy('section_order');

            //get Result by case
            $results = $this->getResultsWithMonth($survey, $month, $questions)->whereIn('token', $dataToken);
            $results = $this->getResultsFollowOptionUpdate($survey, $results, app(UserInterface::class));
            $results = $results->with('question.section', 'answer.settings', 'user')->get()->groupBy('token');

            //title referred to case
            $getTitle = str_limit(Answer::find($answer->id)->content, config('settings.limit_title_excel'));
            $title = ($title == '') ? $getTitle : $title . '-' . $getTitle;

            if ($count >= count($questionsRedirect) - 1) {
                $dataRedirect[] = [
                    'questions' => $questions,
                    'results' => $results,
                    'requiredSurvey' => $requiredSurvey,
                    'title' => $title,
                ];
            } else {
                $dataRedirect = array_merge(
                    $dataRedirect,
                    $this->getResultWithRedirectSection($questionsRedirect, $questions, $survey, $month, $requiredSurvey, $count + 1, $caseResult, $title)
                );
            }

            $caseResult = array_splice($caseResult, 0, -1);
            $title = $titleTmp;
        }

        return $dataRedirect;
    }

    public function getResultExport($survey, $month = '')
    {
        $requiredSurvey = $survey->required;

        //result of question belongs to sections which not section redirect
        $sectionsWithoutRedirect = $survey->sections->where('redirect_id', 0)->pluck('id')->all();
        $questionsWithoutRedirect = app(QuestionInterface::class)->withTrashed()
            ->whereIn('section_id', $sectionsWithoutRedirect)
            ->with('settings', 'section')->get()->sortBy('order')->sortBy('section_order');
        $resultsWithoutRedirect = $this->getResultsWithMonth($survey, $month, $questionsWithoutRedirect);
        $resultsWithoutRedirect = $this->getResultsFollowOptionUpdate($survey, $resultsWithoutRedirect, app(UserInterface::class));
        $resultsWithoutRedirect = $resultsWithoutRedirect->with('question.section', 'answer.settings', 'user')->get()->groupBy('token');

        //get question Redirect
        $questionsRedirect = Question::whereHas('settings', function ($query) {
            $query->whereIn('key', [
                config('settings.question_type.redirect'),
            ]);
        })->whereIn('section_id', $sectionsWithoutRedirect)->with('settings')->get();

        if (count($questionsRedirect)) {
            return $this->getResultWithRedirectSection($questionsRedirect, $questionsWithoutRedirect, $survey, $month, $requiredSurvey);
        }

        return [
            'questions' => $questionsWithoutRedirect,
            'results' => $resultsWithoutRedirect,
            'requiredSurvey' => $requiredSurvey,
        ];
    }

    public function duplicateSurvey($survey)
    {
        $survey->load('questions', 'settings');
        $newSurvey = $survey->replicate();

        $token = md5(uniqid(rand(), true));
        $tokenManage = md5(uniqid(rand(), true));

        $newSurvey->token = $token;
        $newSurvey->token_manage = $tokenManage;

        $newSurvey->deadline = null;
        $newSurvey->status = config('survey.status.block');

        $newSurvey->push();

        foreach ($survey->getRelations() as $relation => $items) {
            foreach ($items as $item) {
                $newItemRelation = $newSurvey->{$relation}()->create($item->toArray());

                if ($newItemRelation instanceof Question) {
                    $item->duplicate($newItemRelation);
                }
            }
        }

        return $newSurvey;
    }

    public function checkExist($token)
    {
        return $this->model->where('token', $token)->exists();
    }

    public function getSurveyByTokenManage($token)
    {
        $survey = $this->model->withTrashed()->with([
            'settings',
            'invite',
            'members',
            'media',
            'sections' => function ($query) {
                $query->with([
                    'questions' => function ($query) {
                        $query->with([
                            'answers' => function ($query) {
                                $query->with('media', 'settings');
                            },
                            'settings',
                            'media',
                        ]);
                    }
                ]);
            }
        ])->where('token_manage', $token)->first();

        $normalSections = $survey->sections->where('redirect_id', config('settings.section_redirect_id_default'));

        foreach ($normalSections as $section) {
            $questionRedirect = $section->questions
                ->where('type', config('settings.question_type.redirect'))
                ->first();

            if (!isset($questionRedirect)) {
                continue;
            }

            $answerRedirectIds = $questionRedirect->answers->pluck('id')->all();
            $section->redirectSections = $survey->sections->whereIn('redirect_id', $answerRedirectIds)->groupBy('redirect_id');
        };

        $survey->sections = $normalSections;

        if (!$survey) {
            throw new Exception("Error Processing Request", 1);
        }

        return $survey;
    }

    public function getSurveysByStatus($status)
    {
        return $this->model->where('status', $status);
    }

    public function getAuthSurveys($flag, $data = [])
    {
        $userId = Auth::user()->id;

        $survey = $this->model->withTrashed();

        //check flag
        if (
            $flag == config('settings.survey.members.owner') ||
            $flag == config('settings.survey.members.editor')
        ) {
            $survey = $survey->whereHas('members', function ($query) use ($flag, $userId) {
                $query->where('role', $flag)
                    ->where('user_id', $userId);
            });
        }

        if ($flag == config('settings.survey.invited')) {
            $survey = $survey->whereHas('invite', function ($query) {
                $query->where('invite_mails', 'LIKE', '%/' . Auth::user()->email . '%')
                    ->orWhere('invite_mails', 'LIKE', Auth::user()->email . '%')
                    ->orWhere('answer_mails', 'LIKE', '%/' . Auth::user()->email . '%')
                    ->orWhere('answer_mails', 'LIKE', Auth::user()->email . '%');
            });
        }

        //check paramater
        if (isset($data['name']) && $data['name']) {
            $survey = $survey->where('title', 'like', '%' . $data['name'] . '%');
        }

        if (isset($data['privacy']) && $data['privacy']) {
            $privacy = $data['privacy'];
            $survey = $survey->whereHas('settings', function ($query) use ($privacy) {
                $query->where('key', config('settings.setting_type.privacy.key'))
                    ->where('value', $privacy);
            });
        }


        if (isset($data['status']) && $data['status']) {
            $survey = $survey->where('status', $data['status']);
        }

        return $survey->with(['settings' => function ($query) {
            $query->where('key', config('settings.setting_type.privacy.key'));
        }])->orderBy('created_at', 'desc')->paginate(config('settings.survey.paginate'));
    }

    //get survey by token
    public function getSurvey($token)
    {
        $survey = $this->model->withTrashed()->where('token', $token)->first();
        $inviter = $survey->invite;
        $sendUpdateMails = collect(!empty($inviter) ? $inviter->send_update_mails_array : []);
        $userMails = Auth::check() ? Auth::user()->email : '';

        if ($survey->isSendUpdateOption() && $sendUpdateMails->contains($userMails)) {
            $results = $survey->load([
                'results' => function ($query) {
                    $query->where('user_id', Auth::user()->id)->with([
                        'question' => function ($query) {
                            $query->whereHas('settings', function ($query) {
                                $query->where('key', config('settings.question_type.redirect'));
                            })->with('answers');
                        }
                    ]);
                }
            ]);

            $redirectIds = [];
            $redirectAnsweredIds = [];

            foreach ($results->results as $result) {
                if (isset($result->question)) {
                    $redirectAnsweredIds[] = $result->answer_id;
                    $redirectIds = array_merge($result->question->answers->pluck('id')->all(), $redirectIds);
                }
            }

            // load updated section flow answer of user
            $survey = $survey->load([
                'settings',
                'sections' => function ($query) use ($redirectIds, $redirectAnsweredIds) {
                    $query->where('update', config('settings.survey.section_update.updated'))
                        ->where(function ($query) use ($redirectIds, $redirectAnsweredIds) {
                            $query->orWhereNotIn('redirect_id', $redirectIds)
                                ->orWhereIn('redirect_id', $redirectAnsweredIds);
                        })->with([
                            'questions' => function ($query) {
                                $query->where('update', config('settings.survey.question_update.updated'))->with([
                                    'settings',
                                    'media',
                                    'answers' => function ($queryAnswer) {
                                        $queryAnswer->with('settings', 'media');
                                    }
                                ]);
                            }
                        ]);
                },
            ]);
        } else {
            $survey =  $survey->load([
                'settings',
                'sections.questions' => function ($query) {
                    $query->with(['settings', 'media', 'answers' => function ($queryAnswer) {
                        $queryAnswer->with('settings', 'media');
                    }]);
                }
            ]);
        }

        if (!$survey) {
            throw new Exception("Error Processing Request", 1);
        }

        return $survey;
    }

    //get survey by token
    public function getSurveyFromTokenManage($token_manage)
    {
        $survey = $this->model->withTrashed()->where('token_manage', $token_manage)->first();

        if (!$survey) {
            throw new Exception("Error Processing Request", 1);
        }

        return $survey;
    }

    //get survey by token
    public function getSurveyFromToken($token)
    {
        $survey = $this->model->withTrashed()->where('token', $token)->first();

        if (!$survey) {
            throw new Exception("Error Processing Request", 1);
        }

        return $survey;
    }

    //get section current
    public function getSectionCurrent($survey, $sectionId)
    {
        return $survey->sections->where('id', $sectionId)->first();
    }

    public function deleteSurvey($survey)
    {
        $survey->members()->detach();
        $survey->settings()->withTrashed()->forceDelete();
        $survey->invite()->withTrashed()->forceDelete();

        return $survey->forceDelete();
    }

    public function countSurveyDraftOfUser($userId)
    {
        return $this->model->where('status', config('settings.survey.status.draft'))
            ->whereHas('members', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('role', Survey::OWNER);
            })->count();
    }

    public function closeSurvey($survey)
    {
        $survey->settings()->delete();
        $survey->invite()->delete();

        return $survey->delete();
    }

    public function openSurvey($survey)
    {
        $survey->settings()->onlyTrashed()->restore();
        $survey->invite()->onlyTrashed()->restore();

        return $survey->restore();
    }

    public function getOverviewSurvey($survey)
    {
        $results = $survey->results();
        $results = $this->getResultsFollowOptionUpdate($survey, $results, app(UserInterface::class))->get();
        $results = $results->groupBy(
            function ($date) {
                return Carbon::parse($date->created_at)->format('d-m-Y');
            }
        );

        $data = [];

        foreach ($results as $date => $result) {
            $group = $result->groupBy('token');
            $item = collect();
            $item->date = $date;
            $item->number = $group->count();
            array_push($data, $item);
        }

        return collect($data);
    }

    public function getSurveyForResult($tokenManage)
    {
        $result = $this->model->withTrashed()->with([
            'sections.questions' => function ($queryQuestion) {
                $queryQuestion->with([
                    'settings',
                    'media',
                    'answers' => function ($queryAnswer) {
                        $queryAnswer->with([
                            'settings',
                            'media',
                        ]);
                    },
                ]);
            },
            'results',
        ])->where('token_manage', $tokenManage)->first();

        if (empty($result)) {
            throw new Exception("Survey not found", 1);
        }

        return $result;
    }

    public function getSurveyForClone($tokenManage)
    {
        $survey = $this->model->withTrashed()->with([
            'settings',
            'invite',
            'members',
            'sections' => function ($query) {
                $query->with([
                    'questions' => function ($queryQuestion) {
                        $queryQuestion->with([
                            'settings',
                            'media',
                            'answers' => function ($queryAnswer) {
                                $queryAnswer->with([
                                    'media',
                                    'settings',
                                ]);
                            },
                        ]);
                    },
                ]);
            },
        ])->where('token_manage', $tokenManage)->first();

        if (!$survey) {
            throw new Exception("Error Processing Request", 1);
        }

        return $survey;
    }

    public function cloneSurvey($survey)
    {
        // clone survey
        $newSurvey = $survey->replicate();
        $stringCopy = ' -' . config('settings.title_copy');
        $arrTitle = explode(' ', $newSurvey->title);
        $countCopy = substr_count($newSurvey->title, $stringCopy);

        if ($countCopy) {
            $newTitle = str_replace(end($arrTitle), end($arrTitle) + config('settings.number_1'), $newSurvey->title);
            $newSurvey->title = $newTitle;
        } else {
            $newSurvey->title = $survey->title . $stringCopy . ' ' . config('settings.number_1');
        }

        $newSurvey->token = md5(uniqid(rand(), true));
        $newSurvey->token_manage = md5(uniqid(rand(), true));
        $newSurvey->status = config('settings.survey.status.open');
        $newSurvey = $this->model->create($newSurvey->toArray());

        // clone members
        $newSurvey->members()->attach(Auth::user()->id, [
            'role' => Survey::OWNER,
            'status' => Survey::APPROVE,
        ]);

        // clone settings
        $dataSettings = $survey->settings->toArray();
        $newSurvey->settings()->createMany($dataSettings);

        return $newSurvey;
    }

    public function getResultOfEachSection($survey, $userRepo, $section)
    {
        $questions = $section->questions;

        return $this->getResultOfQuestions($questions, $survey, $userRepo);
    }

    public function getResultFromRedirectSection($sections, $userRepo)
    {
        $results = [];
        foreach ($sections as $key => $section) {
            $result[] = [
                'section' => $section,
                'question_result' => $this->getResultOfEachSection($section->survey, $userRepo, $section),
            ];
        }

        return $result;
    }

    public function getResultOfQuestions($questions, $survey, $userRepo)
    {
        foreach ($questions as $question) {
            if (!in_array($question->type, [
                config('settings.question_type.image'),
                config('settings.question_type.video'),
            ])) {
                $totalAnswerResults = config('settings.number_0');
                $resultQuestion = [];

                if (in_array($question->type, [
                    config('settings.question_type.short_answer'),
                    config('settings.question_type.long_answer'),
                    config('settings.question_type.date'),
                    config('settings.question_type.time'),
                    config('settings.question_type.linear_scale'),
                ])) {
                    $resultQuestion = $this->getTextQuestionResult($question, $survey, $userRepo);
                } elseif ($question->type == config('settings.question_type.grid')) {
                    $resultQuestion = $this->getResultGridQuestion($question, $survey, $userRepo);
                } else {
                    if ($question->answers->count()) {
                        $resultQuestion = $this->getResultChoiceQuestion($question, $survey, $userRepo, app(ResultInterface::class));
                    } else { // title
                        $resultQuestion['temp'] = $question->title;
                        $resultQuestion['total_answer_results'] = $totalAnswerResults;
                    }
                }

                $temp = $resultQuestion['temp'];
                $totalAnswerResults = $resultQuestion['total_answer_results'];
                $questionResult[] = [
                    'question' => $question,
                    'question_type' => $question->type,
                    'count_answer' => $totalAnswerResults,
                    'answers' => $temp,
                ];
                $temp = [];
            }
        }

        return $questionResult;
    }

    public function getRedirectQuestionIds($survey)
    {
        $redirectQuestionIds = $survey->questions
            ->where('type', config('settings.question_type.redirect'))->pluck('id')->all();

        return $redirectQuestionIds;
    }

    public function getPublicResults($survey)
    {
        $publicResults = [];
        foreach ($survey->sections as $section) {
            $publicResults[] = [
                'section' => $section,
                'question_result' => $this->getResultOfEachSection($survey, app(UserInterface::class), $section),
            ];
        }

        return $publicResults;
    }

    public function getAllSurvey($data)
    {
        $survey = $this->model->withTrashed();

        //check paramater
        if (isset($data['name']) && $data['name']) {
            $survey = $survey->where('title', 'like', '%' . $data['name'] . '%');
        }

        if (isset($data['privacy']) && $data['privacy']) {
            $privacy = $data['privacy'];
            $survey = $survey->whereHas('settings', function ($query) use ($privacy) {
                $query->where('key', config('settings.setting_type.privacy.key'))
                    ->where('value', $privacy);
            });
        }

        if (isset($data['status']) && $data['status']) {
            $survey = $survey->where('status', $data['status']);
        }

        return $survey->orderBy('created_at', 'desc')->with(['settings' => function ($query) {
            $query->where('key', config('settings.setting_type.privacy.key'));
        }])->paginate(config('settings.survey.paginate'));
    }

    public function getQuestionToSheet($groupQuestion, $questions)
    {
        foreach ($groupQuestion as $question) {
            if (!in_array($question->type, [
                config('settings.question_type.title'),
                config('settings.question_type.image'),
                config('settings.question_type.video'),
            ])) {
                if ($question->settings->first()->key == config('settings.question_type.linear_scale')) {
                    $settingValue = $question->setting_value;
                    $linearQuestion = $question['title'] . ' ( ' . $settingValue->min_value . ' = ' . $settingValue->min_content . ' , ' . $settingValue->max_value . ' = ' . $settingValue->max_content . ' )';

                    array_push($questions, $linearQuestion);
                } elseif ($question->settings->first()->key == config('settings.question_type.grid')) {
                    $options = json_decode($question->settings->first()->value, true);
                    $strOption = '';

                    foreach ($options as $option) {
                        $strOption = $strOption . ' ( ' . $option . ' )';
                    }

                    array_push($questions, $question->title . $strOption);
                } else {

                    array_push($questions, $question['title']);
                }
            }
        }

        return $questions;
    }

    public function getResultToSheet($data, $survey, $newVals, $orderQuestion = '')
    {
        if (count($data['results'])) {
            foreach ($data['results'] as $result) {
                $results = [];
                $result = $result->sortBy('order')->sortBy('section_order');

                if ($orderQuestion != '') {
                    $groupByResults = $result->groupBy('question_id');
                    $finalResults = [];
                    foreach ($orderQuestion as $value) {
                        $finalResults[$value] = $groupByResults[$value];
                    }
                } else $finalResults = $result->groupBy('question_id');

                array_push($results, $result->first()->created_at->toDateTimeString());

                if ($data['requiredSurvey'] != config('settings.survey_setting.answer_required.none')) {
                    array_push($results, $result->first()->user ? $result->first()->user->email : trans('lang.incognito'));
                }

                foreach ($finalResults as $answers) {
                    if ($answers->count() == 1) {
                        if ($answers->first()->question->type == config('settings.question_type.grid')) {
                            $subQuestions = $answers->first()->question->sub_questions;
                            $subOptions = json_decode($answers->first()->question->settings->first()->value);
                            $gridResult = json_decode($answers->first()->content, true);
                            $strGrid = '';

                            foreach ($subQuestions as $key => $subQuestion) {
                                $indexQuestion = $key + 1;
                                foreach ($subOptions as $key => $subOption) {
                                    if ($gridResult[$indexQuestion] == ($key + 1)) {
                                        $gridAnswer = ' [ ' . $subQuestion .  ':' . $subOption . ' ] ';
                                        $strGrid = $strGrid . $gridAnswer;
                                    }
                                }
                            }

                            array_push($results, $strGrid);
                        } else {
                            array_push($results, $answers->first()->content_answer);
                        }
                    } else {
                        array_push($results, $answers->implode('content_answer', ', '));
                    }
                }
                $orderQuestion != ''
                    ? array_push($newVals[str_limit($survey->title, config('settings.limit_title_excel'))], $results)
                    : array_push($newVals[str_limit($data['title'], config('settings.limit_title_excel'))], $results);
            }
        }

        return $newVals;
    }

    public function getNormalDataToSheet($data, $survey, $title, $orderQuestion)
    {
        $data['questions'] = $data['questions']->groupBy('section_id');
        $questions = [trans('lang.timestamps')];

        if ($data['requiredSurvey'] != config('settings.survey_setting.answer_required.none')) {
            array_push($questions, trans('lang.email'));
        }

        $newVals = [
            str_limit($survey->title, config('settings.limit_title_excel')) => [
                [trans('lang.sun_asterisk_vn')],
                [$title],
                [trans('lang.date_create') . $survey->created_at],
                [],
            ]
        ];

        foreach ($data['questions'] as $groupQuestion) {
            $groupQuestion = $groupQuestion->sortBy('order');
            $questions = $this->getQuestionToSheet($groupQuestion, $questions);
        }

        array_push($newVals[str_limit($survey->title, config('settings.limit_title_excel'))], $questions);
        $newVals = $this->getResultToSheet($data, $survey, $newVals, $orderQuestion);

        return $newVals;
    }

    public function getRedirectDataToSheet($data, $survey, $title)
    {
        foreach ($data as $dataRedirect) {
            $questions = [trans('lang.timestamps')];

            if ($dataRedirect['requiredSurvey'] != config('settings.survey_setting.answer_required.none')) {
                array_push($questions, trans('lang.email'));
            }

            $newVals[str_limit($dataRedirect['title'], config('settings.limit_title_excel'))] = [
                [trans('lang.sun_asterisk_vn')],
                [$title],
                [trans('lang.date_create') . $survey->created_at],
                [],
            ];
            $questions = $this->getQuestionToSheet($dataRedirect['questions'], $questions);

            array_push($newVals[str_limit($dataRedirect['title'], config('settings.limit_title_excel'))], $questions);
        }

        foreach ($data as $dataRedirect) {
            $newVals = $this->getResultToSheet($dataRedirect, $survey, $newVals);
        }

        return $newVals;
    }

    public function updateGoogleToken($token, $surveyId)
    {
        $updateGoogleToken = DB::table('surveys')->where('id', $surveyId)->update([
            'google_token' => $token,
        ]);

        return $updateGoogleToken;
    }
}
