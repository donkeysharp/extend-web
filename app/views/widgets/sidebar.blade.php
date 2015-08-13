<div id="sidebar-wrapper">
  <div style="width: 100%; color: #eee; background-color: #2B3E3E; padding: 15px" class="clearfix">
    {{-- <img id="navbar-profile" src="{{ViewHelper::avatar(Auth::user()->picture_url)}}" style="width:50px; height: 50px; border: 1px solid #7D7D7D;" class="img-circle center-block"> --}}
    <h4 style="margin: 5px auto 0 auto;">{{Auth::user()->name}}</h4>
  </div>
  <ul class="sidebar-nav">
    <li>
      <a href="{{url('dashboard')}}">Dashboard</a>
    </li>
    <li>
      <a href="{{url('news')}}">Administrar Noticias</a>
    </li>
    <li>
      <a href="{{url('clients')}}">Administrar Clientes</a>
    </li>
    <li>
      <a href="{{url('media')}}">Administrar Medios</a>
    </li>
    <li>
      <a href="{{url('topics')}}">Administrar Temas</a>
    </li>
  </ul>
</div>
