<?php
class ReportController extends BaseController
{
    public function index()
    {
        return View::make('reports.index');
    }

    public function getReport()
    {
        $client = Client::findOrFail(Input::get('client_id'));
        $clientId = $client->id;
        $date = ReportGenerator::getDates(Input::get('month', 1), Input::get('year', 2015));
        $from = $date[0];
        $to = $date[1];

        $reportGenerator = new ReportGenerator();

        $result = [];
        $result['press'] = [];
        $result['radio'] = [];
        $result['tv'] = [];

        $result['press']['Report1'] = $reportGenerator->report1($from, $to, $client->id, ReportGenerator::PRESS);
        $result['press']['Report2'] = $reportGenerator->report2($from, $to, $client->id, ReportGenerator::PRESS);
        $result['press']['Report3'] = $reportGenerator->report3($from, $to, $client->id, ReportGenerator::PRESS);
        $result['press']['Report4'] = $reportGenerator->report4($from, $to, $client->id, ReportGenerator::PRESS);
        // $result['press']['report5'] = $reportGenerator->report5($from, $to, $client->id);
        $result['press']['Report5'] = null;
        $result['press']['Report6'] = $reportGenerator->report6($from, $to, $client->id, ReportGenerator::PRESS);
        $result['press']['Report7'] = $reportGenerator->report7($from, $to, $client->id, ReportGenerator::PRESS);

        $result['radio']['Report1'] = $reportGenerator->report1($from, $to, $client->id, ReportGenerator::RADIO);
        $result['radio']['Report2'] = $reportGenerator->report2($from, $to, $client->id, ReportGenerator::RADIO);
        $result['radio']['Report3'] = $reportGenerator->report3($from, $to, $client->id, ReportGenerator::RADIO);
        $result['radio']['Report6'] = $reportGenerator->report6($from, $to, $client->id, ReportGenerator::RADIO);
        $result['radio']['Report7'] = $reportGenerator->report7($from, $to, $client->id, ReportGenerator::RADIO);

        $result['tv']['Report1'] = $reportGenerator->report1($from, $to, $client->id, ReportGenerator::TV);
        $result['tv']['Report2'] = $reportGenerator->report2($from, $to, $client->id, ReportGenerator::TV);
        $result['tv']['Report3'] = $reportGenerator->report3($from, $to, $client->id, ReportGenerator::TV);
        $result['tv']['Report6'] = $reportGenerator->report6($from, $to, $client->id, ReportGenerator::TV);
        $result['tv']['Report7'] = $reportGenerator->report7($from, $to, $client->id, ReportGenerator::TV);

        // TODO: General Reports (reports 8, 9, 10 for byMediaTypeGeneral, byMediaTypeSpecific, trimistral)

        return $result;
    }
}
