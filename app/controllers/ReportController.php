<?php
class ReportController extends BaseController
{
    public function index()
    {
        return View::make('reports.index');
    }

    public function mediaNewsReport($initDate, $endDate)
    {
        return '';
    }
}
