<?php

class MediaController extends BaseController
{
    public function index()
    {
        $limit = 10; $page = Input::get('page', 1);

        $media = Media::orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($media->all(), Media::count(), $limit);
        return View::make('media.index')
            ->with('media', $paginator);
    }

    public function create()
    {
        $model = new Media();
        $types = [
            '' => '--- Seleccione un tipo ---',
            '1' => 'Impreso',
            '2' => 'Digital',
            '3' => 'Radio',
            '4' => 'TV',
            '5' => 'Fuente'
        ];
        $cities = [
            '' => '-- Seleccione una ciudad --',
            'Beni' => 'Beni',
            'Cochabamba' => 'Cochabamba',
            'La Paz' => 'La Paz',
            'Oruro' => 'Oruro',
            'Pando' => 'Pando',
            'Potosi' => 'Potosí',
            'Santa Cruz' => 'Santa Cruz',
            'Sucre' => 'Sucre',
            'Tarija' => 'Tarija'
        ];
        return View::make('media.edit')
            ->with('model', $model)
            ->with('types', $types)
            ->with('cities', $cities);
    }

    public function edit($id)
    {
        $model = Media::findOrFail($id);
        $types = [
            '' => '--- Seleccione un tipo ---',
            '1' => 'Impreso',
            '2' => 'Digital',
            '3' => 'Radio',
            '4' => 'TV',
            '5' => 'Fuente'
        ];
        $cities = [
            '' => '-- Seleccione una ciudad --',
            'Beni' => 'Beni',
            'Cochabamba' => 'Cochabamba',
            'La Paz' => 'La Paz',
            'Oruro' => 'Oruro',
            'Pando' => 'Pando',
            'Potosi' => 'Potosí',
            'Santa Cruz' => 'Santa Cruz',
            'Sucre' => 'Sucre',
            'Tarija' => 'Tarija'
        ];
        return View::make('media.edit')
            ->with('model', $model)
            ->with('types', $types)
            ->with('cities', $cities);
    }

    public function store()
    {
        $data = Input::all();
        $media = new Media();
        $media->name = $data['name'];
        $media->type = $data['type'];
        $media->description = $data['description'];
        $media->city = $data['city'];
        $media->website = $data['website'];
        $media->save();

        if (Request::wantsJson()) {
            return Response::json($media, 200);
        }

        return Redirect::to('dashboard/media/' . $media->id . '/edit')
            ->with('message', 'Medio creado exitosamente');
    }

    public function update($id)
    {
        $data = Input::all();
        $media = Media::findOrFail($id);
        $media->name = $data['name'];
        $media->type = $data['type'];
        $media->description = $data['description'];
        $media->city = $data['city'];
        $media->website = $data['website'];
        $media->save();

        return Redirect::to('dashboard/media/' . $media->id . '/edit')
            ->with('message', 'Medio actualizado exitosamente');
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();

        return Response::json([
            'status' => 'ok'
        ], 200);
    }
}
