<?php

namespace App\Repositories\Survey;

interface SurveyInterface
{
    public function getResutlSurvey($survey, $userRepo);

    public function createSurvey($userId, $data, $status, $userRepo);

    public function updateSettingSurvey($survey, $data, $userRepo);

    public function updateSurveyByObject($survey, $values);

    public function updateSurvey($survey, $data, $status, $questionRepo, $answerRepo, $userRepo = null);

    public function checkCloseSurvey($inviteIds, $surveyIds);

    public function listsSurvey($userId, $email = null);

    public function getSettings($surveyId);

    public function getHistory($userId, $clientIp, $surveyId, array $options);

    public function getUserAnswer($token);

    public function getResultExport($survey, $month = '');

    public function duplicateSurvey($survey);

    public function checkExist($token);

    public function getSurveyByTokenManage($token);

    public function getSurveysByStatus($status);

    //get survey by token
    public function getSurvey($token);

    //get section current
    public function getSectionCurrent($survey, $sectionId);

    public function deleteSurvey($survey);

    public function getAuthSurveys($role);

    public function countSurveyDraftOfUser($userId);

    public function closeSurvey($survey);

    public function openSurvey($survey);

    public function getOverviewSurvey($survey);

    public function getSurveyFromTokenManage($token_manage);

    public function getSurveyForResult($tokenManage);

    public function getSurveyFromToken($token);

    public function getSurveyForClone($tokenManage);

    public function cloneSurvey($survey);

    public function getRedirectQuestionIds($survey);

    public function getResultOfQuestions($questions, $survey, $userRepo);

    public function getResultOfEachSection($survey, $userRepo, $section);

    public function getResultFromRedirectSection($sections, $userRepo);

    public function getPublicResults($survey);

    public function getAllSurvey($data);

    public function getQuestionToSheet($groupQuestion, $questions);

    public function getResultToSheet($data, $survey, $newVals, $orderQuestion);

    public function getNormalDataToSheet($data, $survey, $title, $orderQuestion);

    public function getRedirectDataToSheet($data, $survey, $title);

    public function updateGoogleToken($token, $surveyId);
}
