<?php
class ReportController extends BaseController
{
    public function index()
    {
        return View::make('reports.index');
    }

    public function getReport()
    {
        $report = Input::get('report', false);
        if (!$report) {
            return Response::json([
                'status' => 'Report not found'
            ], 404);
        }

        $reportGenerator = new ReportGenerator();

        if ($report === 'report1') {
            $result = $reportGenerator->report1('2015-09-01', '2015-09-30');
            return Response::json($result, 200);
        } else if ($report === 'report2') {
            $result = $reportGenerator->report2('2015-09-01', '2015-09-30');
            return Response::json($result, 200);
        }

        return Input::all();
    }
    /**
     * Foobar
     * @param date $initDate
     * @param date $endDate
     * @return Response::json
     */
    public function mediaNewsReport($initDate, $endDate)
    {
        return '';
    }
}
