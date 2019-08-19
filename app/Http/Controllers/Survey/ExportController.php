<?php

namespace App\Http\Controllers\Survey;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Survey\SurveyInterface;
use PHPExcel_Style_Alignment;
use Excel;
use Exception;

class ExportController extends Controller
{
    protected $surveyRepository;

    public function __construct(SurveyInterface $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

    public function export(Request $request)
    {
        try {
            $survey = $this->surveyRepository->getSurveyFromToken($request->token);

            if (!$survey) {
                throw new Exception('Survey not found', 1);
            }

            $data = $this->surveyRepository->getResultExport($survey, $request->month);
            $title = $request->name ? str_limit($request->name, config('settings.limit_title_excel')) : str_limit($survey->title, config('settings.limit_title_excel'));
            $orderQuestion = [];
    
            if (!count($survey->sections->where('redirect_id', '!=', 0))) {
                foreach ($data['questions']->groupBy('section_id') as $questionOfSection) {
                    $questionOfSection = $questionOfSection->where('type', '!=', config('settings.question_type.title'))
                        ->where('type', '!=', config('settings.question_type.image'))
                        ->where('type', '!=', config('settings.question_type.video'));
                    $orderQuestion = array_merge($orderQuestion, $questionOfSection->sortBy('order')->pluck('id')->toArray());
                }
            }

            return Excel::create($title, function ($excel) use ($title, $data, $survey, $orderQuestion) {
                if (isset($data['questions'])) {
                    $excel->sheet($title, function ($sheet) use ($title, $data, $survey, $orderQuestion) {
                        $data['title'] = $title;
                        $data['questions'] = $data['questions']->groupBy('section_id');
                        $sheet->loadView('clients.export.excel', compact('data', 'survey', 'orderQuestion'));
                        $sheet->setOrientation('landscape')
                            ->getDefaultStyle()
                            ->getAlignment()
                            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    });
                } else {
                    foreach ($data as $dataRedirect) {

                        foreach ($dataRedirect['questions']->groupBy('section_id') as $questionOfSection) {
                            $questionOfSection = $questionOfSection->where('type', '!=', config('settings.question_type.title'))
                                ->where('type', '!=', config('settings.question_type.image'))
                                ->where('type', '!=', config('settings.question_type.video'));
                            $orderQuestion = array_merge($orderQuestion, $questionOfSection->sortBy('order')->pluck('id')->toArray());
                        }
                        
                        $excel->sheet($dataRedirect['title'], function ($sheet) use ($dataRedirect, $survey, $orderQuestion) {
                            $dataRedirect['questions'] = $dataRedirect['questions']->groupBy('section_id');
                            $sheet->loadView('clients.export.excel', ['data' => $dataRedirect, 'survey' => $survey, 'orderQuestion' => $orderQuestion]);
                            $sheet->setOrientation('landscape')
                                ->getDefaultStyle()
                                ->getAlignment()
                                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        });
                        $orderQuestion = [];
                    }
                }
            })->export($request->type);
        } catch (Exception $e) {
            return redirect()->back()->with('error', trans('lang.export_error'));
        }
    }
}
