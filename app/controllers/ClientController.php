<?php

class ClientController extends BaseController
{
    public function index()
    {
        $limit = 10; $page = Input::get('page', 1);

        $clients = Client::orderBy('id')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($clients->all(), Client::count(), $limit);
        return View::make('clients.index')
            ->with('clients', $paginator);
    }

    public function create()
    {
        $model = new Client();
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
        return View::make('clients.edit')
            ->with('model', $model)
            ->with('cities', $cities);
    }

    public function edit($id)
    {
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
        $model = Client::with('contacts')->findOrFail($id);
        return View::make('clients.edit')
            ->with('model', $model)
            ->with('cities', $cities);
    }

    public function store()
    {
        $data = Input::all();
        $client = new Client();
        $client->name = $data['name'];
        $client->phone = $data['phone'];
        $client->description = $data['description'];
        $client->address = $data['address'];
        $client->city = $data['city'];
        $client->website = $data['website'];
        $client->save();

        return Redirect::to('/dashboard/clients/' . $client->id . '/edit')
            ->with('message', 'Cliente creado exitosamente.');
    }

    public function update($id)
    {
        $data = Input::all();
        $client = Client::findOrFail($id);
        $client->name = $data['name'];
        $client->phone = $data['phone'];
        $client->description = $data['description'];
        $client->address = $data['address'];
        $client->city = $data['city'];
        $client->website = $data['website'];
        $client->save();

        return Redirect::to('/dashboard/clients/' . $client->id . '/edit')
            ->with('message', 'Cliente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return Response::json([
            'status' => 'ok'
        ], 200);
    }

    public function storeContact($id)
    {
        $data = Input::all();
        $client = Client::findOrFail($id);
        $contact = new Contact();
        $contact->name = $data['name'];
        $contact->email = $data['email'];
        $contact->position = $data['position'];
        $contact->phone = $data['phone'];
        $contact->client_id = $id;
        $contact->save();

        return Redirect::to('/dashboard/clients/' . $client->id . '/edit')
            ->with('message', 'Contacto adicionado exitosamente.');
    }

}
