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
        $pastDates = ReportGenerator::getPastDates(Input::get('month', 1), Input::get('year', 2015));
        $reportGenerator = new ReportGenerator();

        $result = [];
        $result['press'] = [];
        $result['radio'] = [];
        $result['tv'] = [];

        $result['press']['Report1'] = $reportGenerator->report1($from, $to, $client->id, ReportGenerator::PRESS);
        $result['press']['Report2'] = $reportGenerator->report2($from, $to, $client->id, ReportGenerator::PRESS);
        $result['press']['Report3'] = $reportGenerator->report3($from, $to, $client->id, ReportGenerator::PRESS);
        $result['press']['Report4'] = $reportGenerator->report4($from, $to, $client->id, ReportGenerator::PRESS);
        $result['press']['Report5'] = $reportGenerator->report5($from, $to, $client->id, ReportGenerator::PRESS);
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

        $result['general']['GeneralReportA'] = $reportGenerator->generalReportA(
                                                            $result['press']['Report1'],
                                                            $result['radio']['Report1'],
                                                            $result['tv']['Report1']);
        $result['general']['GeneralReportB'] = $reportGenerator->generalReportB(
                                                            $result['press']['Report3'],
                                                            $result['radio']['Report3'],
                                                            $result['tv']['Report3']);
        $result['general']['GeneralReportC'] = $reportGenerator->generalReportC($pastDates, $client->id);

        return $result;
    }

    public function exportReport()
    {
        $data = Input::all();
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $imageCounter = 0;
        foreach ($data as $key => $value) {
            $table = $value['table'];
            $image = $value['image'];
            $subtitle = isset($value['subtitle']) ? $value['subtitle'] : null;
            if ($subtitle) {
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, "<h1>$subtitle</h1>");
            }

            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            file_put_contents(public_path() . '/image' . $imageCounter . '.png', $image);
            $wordTable = $section->addTable();
            for ($i = 0; $i < count($table); ++$i) {
                $wordTable->addRow();
                for ($j = 0; $j < count($table[$i]); ++$j) {
                    $wordTable->addCell(1750)->addText($table[$i][$j]);
                }
            }
            $section->addImage(public_path() . '/image' . $imageCounter . '.png');
            $imageCounter++;
        }
        $imageCounter--;
        for ($i = $imageCounter; $i >= 0; $i--) {
            unlink(public_path() . '/image' . $i . '.png');
        }
        $phpWord->save(public_path() . '/reporte.docx');
        return Response::json([
            'filename' => 'reporte.docx'
        ]);
    }
}
