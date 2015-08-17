<?php
use Carbon\Carbon;

class ExportController extends BaseController
{
    public function index()
    {
        return View::make('exports.index');
    }

    public function export()
    {
        $data = [];
        if(Input::get('clients')) {
            $data['clients'] = Client::all();
        }
        if(Input::get('news')) {
            $data['news'] = News::all();
        }
        if(Input::get('topics')) {
            $data['topics'] = Topic::all();
        }
        if(Input::get('media')) {
            $data['media'] = Media::all();
        }
        if(Input::get('news_details')) {
            $data['news_details'] = NewsDetail::all();
        }

        return Excel::create(Carbon::now(), function($excel) use($data) {
            if(isset($data['clients'])) {
                $excel->sheet('Clientes', function($sheet) use($data) {
                    $sheet->fromArray($data['clients']);
                });
            }
            if(isset($data['news'])) {
                $excel->sheet('Noticias', function($sheet) use($data) {
                    $sheet->fromArray($data['news']);
                });
            }
            if(isset($data['topics'])) {
                $excel->sheet('Temas', function($sheet) use($data) {
                    $sheet->fromArray($data['topics']);
                });
            }
            if(isset($data['news_details'])) {
                $excel->sheet('Detalle de Noticias', function($sheet) use($data) {
                    $sheet->fromArray($data['news_details']);
                });
            }
            if(isset($data['media'])) {
                $excel->sheet('Medios', function($sheet) use($data) {
                    $sheet->fromArray($data['media']);
                });
            }

        })->download('xls');
    }
}
