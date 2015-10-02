<?php
class UserController extends BaseController
{
    public function index()
    {
        $limit = 10; $page = Input::get('page', 1);

        $users = User::orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $paginator = Paginator::make($users->all(), User::count(), $limit);
        return View::make('users.index')
            ->with('users', $paginator);
    }

    public function create()
    {
        $user = new User(Input::old());
        return View::make('users.edit')
            ->with('model', $user);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return View::make('users.edit')
            ->with('model', $user);
    }

    public function store()
    {
        $data = Input::all();
        if($data['password'] != $data['confirm']) {
            return Redirect::to('dashboard/users/create')
                ->with('error', 'Error al crear usuario')
                ->withInput(Input::except('_token', 'password', 'confirm'));
        }

        $user = new User();
        $user->username = $data['username'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return Redirect::to('dashboard/users/' . $user->id . '/edit')
            ->with('message', 'Usuario creado exitosamente');
    }

    public function update($id)
    {
        $data = Input::all();
        if($data['password'] != $data['confirm']) {
            return Redirect::to('dashboard/users/create')
                ->with('error', 'Error al actualizar usuario')
                ->withInput(Input::except('_token', 'password', 'confirm'));
        }

        $user = User::findOrFail($id);
        $user->username = $data['username'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return Redirect::to('dashboard/users/' . $user->id . '/edit')
            ->with('message', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return Response::json([
            'status' => 'ok'
        ], 200);
    }
}
