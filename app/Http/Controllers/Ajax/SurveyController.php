<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Survey\SurveyInterface;
use App\Repositories\User\UserInterface;
use App\Traits\SurveyProcesser;

class SurveyController extends Controller
{
    use SurveyProcesser;

    protected $surveyRepository;
    protected $userRepository;

    public function __construct(
        SurveyInterface $surveyRepository,
        UserInterface $userRepository
    ) {
        $this->surveyRepository = $surveyRepository;
        $this->userRepository = $userRepository;
    }

    public function getListSurvey(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $flag = $request->flag;
        $data = $request->only('name', 'status', 'privacy');
        $surveys = $this->surveyRepository->getAuthSurveys($flag, $data);
        $html = '';

        if (
            $flag == config('settings.survey.members.owner') ||
            $flag == config('settings.survey.members.editor')
        ) {
            $html = view('clients.profile.survey.list_survey_owner', compact('surveys'))->render();
        } elseif ($flag == config('settings.survey.invited')) {
            $html = view('clients.profile.survey.list_survey_invite', compact('surveys'))->render();
        }

        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }

    public function getStatusInvite(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $tokenManage = $request->token_manage;
        $survey = $this->surveyRepository->getSurveyFromTokenManage($tokenManage);
        $results = $this->getResultsFollowOptionUpdate($survey, $survey->results(), $this->userRepository)->get();

        $invite = $survey->invite;
        $emailInviteds = $invite ? $invite->invite_mails_array : [];
        $emailAnswereds = $invite ? $invite->answer_mails_array : [];
        $emails = array_unique(array_merge($emailInviteds, $emailAnswereds));
        sort($emails);
        $data = [];

        foreach ($emails as $key => $email) {
            $item['email'] = $email;
            $userId = $this->userRepository->where('email', $email)->first()->id;
            $timesAnswer = $results->where('user_id', $userId)
                ->pluck('created_at')
                ->unique()->count();
            $item['count'] = $timesAnswer;
            array_push($data, $item);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function getSurveyManage(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $data = $request->only('name', 'status', 'privacy');
        $surveys = $this->surveyRepository->getAllSurvey($data);

        $html = view('clients.profile.survey.list_survey_manage', compact('surveys'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }
}
