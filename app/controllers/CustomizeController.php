<?php

class CustomizeController extends BaseController
{
    public function subtitles()
    {
        $clients = Client::all()->lists('name', 'id');
        $clients[''] = 'Seleccioar un cliente';

        return View::make('customization.subtitles')
            ->with('clients', $clients);
    }

    public function getSubtitlesByClient($clientId)
    {
        $client = Client::findOrFail($clientId);
        $subtitles = Subtitle::orderBy('id')->get();
        $customSubtitles = $client->customSubtitles()->get();

        if (count($customSubtitles) === 0) {
            $customSubtitles = $subtitles;
        } else if(count($subtitles) != count($customSubtitles)) {
            $subtitleDiff = [];
            foreach ($subtitles as $sub1) {
                $subtitleExists = false;
                foreach ($customSubtitles as $sub2) {
                    if (strcmp($sub1->subtitle, $sub2->subtitle) === 0) {
                        $subtitleExists = true;
                        break;
                    }
                }
                if (!$subtitleExists) {
                    $subtitleDiff[] = $sub1;
                }
            }
            $result = [];
            for ($i = 0; $i < count($customSubtitles) - 1; $i++) {
                $result[] = $customSubtitles[$i];
            }
            for ($i = 0; $i < count($subtitleDiff); $i++) {
                $result[] = $subtitleDiff[$i];
            }
            $result[] = $customSubtitles[count($customSubtitles) - 1];
            $ids = array_pluck($result, 'id');

            DB::table('custom_subtitles')->where('client_id', '=', $clientId)->delete();
            $client->customSubtitles()->attach($ids);

            return Response::json($result, 200);
        }

        return Response::json($customSubtitles, 200);
    }

    public function saveSubtitles($clientId)
    {
        $ids = Input::get('subtitles');
        $client = Client::findOrFail($clientId);
        DB::table('custom_subtitles')->where('client_id', '=', $clientId)->delete();
        $client->customSubtitles()->attach($ids);

        return Response::json([
            'status' => 'ok'
        ], 200);
    }
}
