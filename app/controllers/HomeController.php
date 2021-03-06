<?php

class HomeController extends BaseController
{
	public function index()
	{
		if (Auth::check()) {
			return Redirect::to('dashboard/news');
		}
		return Redirect::to('login');
	}

	public function login()
	{
		if (Auth::check()) {
			return Redirect::to('dashboard');
		}
		return View::make('auth.login');
	}

	public function doLogin()
	{
		if (Auth::attempt([
			'username'=>Input::get('username'),
			'password' =>Input::get('password')])
			) {
			return Redirect::intended('/');
		}
		return Redirect::to('login')
			->with('error', 'Invalid credentials');
	}

	public function doLogout()
	{
		Auth::logout();

		return Redirect::to('login');
	}
}
