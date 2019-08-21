<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ElementFetchingController extends Controller
{
    public function fetchSection(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $answerId = $request->answerId;
        $optionId = config('settings.survey.option.first');

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.section', compact(
                'sectionId',
                'questionId',
                'answerId',
                'optionId'
            ))->render(),
        ]);
    }

    public function fetchTitleDescription(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.title-description', compact(
                'sectionId',
                'questionId'
            ))->render(),
        ]);
    }

    public function fetchShortAnswer(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $imageURL = $request->imageURL;

        $image = $imageURL ? view('clients.survey.elements.image-question', compact('imageURL'))->render() : '';

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.short-answer', compact(
                'sectionId',
                'questionId',
                'imageURL'
            ))->render(),
            'image' => $image,
        ]);
    }

    public function fetchLongAnswer(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $imageURL = $request->imageURL;

        $image = $imageURL ? view('clients.survey.elements.image-question', compact('imageURL'))->render() : '';

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.long-answer', compact(
                'sectionId',
                'questionId',
                'imageURL'
            ))->render(),
            'image' => $image,
        ]);
    }

    public function fetchMultipleChoice(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $answerId = $request->answerId;
        $optionId = config('settings.survey.option.first');
        $imageURL = $request->imageURL;

        $image = $imageURL ? view('clients.survey.elements.image-question', compact('imageURL'))->render() : '';

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.multiple-choice', compact(
                'sectionId',
                'questionId',
                'answerId',
                'optionId',
                'imageURL'
            ))->render(),
            'image' => $image,
        ]);
    }

    public function fetchCheckboxes(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $answerId = $request->answerId;
        $optionId = config('settings.survey.option.first');
        $imageURL = $request->imageURL;

        $image = $imageURL ? view('clients.survey.elements.image-question', compact('imageURL'))->render() : '';

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.checkboxes', compact(
                'sectionId',
                'questionId',
                'answerId',
                'optionId',
                'imageURL'
            ))->render(),
            'image' => $image,
        ]);
    }

    public function fetchDate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $imageURL = $request->imageURL;

        $image = $imageURL ? view('clients.survey.elements.image-question', compact('imageURL'))->render() : '';

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.date', compact(
                'sectionId',
                'questionId',
                'imageURL'
            ))->render(),
            'image' => $image,
        ]);
    }

    public function fetchTime(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $imageURL = $request->imageURL;

        $image = $imageURL ? view('clients.survey.elements.image-question', compact('imageURL'))->render() : '';

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.time', compact(
                'sectionId',
                'questionId',
                'imageURL'
            ))->render(),
            'image' => $image,
        ]);
    }

    public function fetchImage(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $imageURL = $request->imageURL;

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.section-image', compact(
                'sectionId',
                'questionId',
                'imageURL'
            ))->render(),
        ]);
    }

    public function fetchVideo(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $thumbnailVideo = $request->thumbnailVideo;
        $urlEmbed = $request->urlEmbed;

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.section-video', compact(
                'sectionId',
                'questionId',
                'thumbnailVideo',
                'urlEmbed'
            ))->render(),
        ]);
    }

    public function fetchImageQuestion(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $imageURL = $request->imageURL;
        $backgroudCover = $request->backgroudCover;
        $backgroudUrl = route('home') . '/' . $backgroudCover;

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.image-question', compact('imageURL', 'backgroudUrl'))->render(),
            'imageURL' => $imageURL,
            'backgroudCover' => $backgroudCover,
        ]);
    }

    public function fetchImageAnswer(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $imageURL = $request->imageURL;
        $backgroudCover = $request->backgroudCover;
        $backgroudUrl = route('home') . '/' . $backgroudCover;

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.image-answer', compact('imageURL', 'backgroudUrl'))->render(),
            'imageURL' => $imageURL,
            'backgroudCover' => $backgroudCover,
        ]);
    }

    public function fecthBackgroundImageSurvey(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $imageURL = $request->imageURL;
        $backgroudCover = $request->backgroudCover;
        $backgroudUrl = route('home') . '/' . $backgroudCover;

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.background-survey', compact('imageURL', 'backgroudUrl'))->render(),
            'imageURL' => $imageURL,
            'backgroudCover' => $backgroudCover,
        ]);
    }

    public function fetchRedirectQuestion(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $data = $request->only(
            'sectionId',
            'questionId',
            'redirectSectionData',
            'imageURL'
        );

        $data['answerIds'] = array_pluck($data['redirectSectionData'], 'answerRedirectId');
        $data['isRedirectQuestion'] = true;
        $viewRedirectQuestion = view('clients.survey.elements.redirect')->with($data)->render();
        $viewRedirectSections = [];

        foreach ($data['redirectSectionData'] as $key => $redirectSection) {
            $viewRedirectSections[] = view('clients.survey.elements.section-redirect')->with([
                'answerRedirectId' => $redirectSection['answerRedirectId'],
                'answerRedirectContent' => trans('lang.redirect_option_content', ['index' => $key + 1]),
                'sectionId' => $redirectSection['sectionId'],
                'questionId' => $redirectSection['questionId'],
                'answerId' => $redirectSection['answerId'],
                'optionId' => config('settings.survey.option.first'),
            ])->render();
        }

        $image = $data['imageURL']
            ? view('clients.survey.elements.image-question')->with(['imageURL' => $data['imageURL']])->render()
            : null;

        return response()->json([
            'success' => true,
            'view_question' => $viewRedirectQuestion,
            'view_sections' => $viewRedirectSections,
            'image' => $image,
        ]);
    }

    public function fetchLinearScaleQuestion(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $imageURL = $request->imageURL;

        $image = $imageURL ? view('clients.survey.elements.image-question', compact('imageURL'))->render() : '';

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.linear_scale', compact(
                'sectionId',
                'questionId',
                'imageURL'
            ))->render(),
            'image' => $image,
        ]);
    }

    public function fetchRedirectSection(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $data = $request->only(
            'answerRedirectId',
            'answerRedirectContent',
            'sectionId',
            'questionId',
            'answerId'
        );

        $data['optionId'] = config('settings.survey.option.first');

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.section-redirect')->with($data)->render(),
        ]);
    }

    public function fetchGridQuestion(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $imageURL = $request->imageURL;

        $image = $imageURL ? view('clients.survey.elements.image-question', compact('imageURL'))->render() : '';

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.grid', compact(
                'sectionId',
                'questionId',
                'imageURL'
            ))->render(),
            'image' => $image,
        ]);
    }

    public function fetchDropdownQuestion(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $sectionId = $request->sectionId;
        $questionId = $request->questionId;
        $answerId = $request->answerId;
        $optionId = config('settings.survey.option.first');
        $imageURL = $request->imageURL;

        $image = $imageURL ? view('clients.survey.elements.image-question', compact('imageURL'))->render() : '';

        return response()->json([
            'success' => true,
            'html' => view('clients.survey.elements.dropdown', compact(
                'sectionId',
                'questionId',
                'answerId',
                'optionId',
                'imageURL'
            ))->render(),
            'image' => $image,
        ]);
    }
}
