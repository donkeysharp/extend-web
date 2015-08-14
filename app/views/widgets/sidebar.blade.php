<div id="sidebar-wrapper">
  <div class="clearfix sidebar-user">
    {{-- <img id="navbar-profile" src="{{ViewHelper::avatar(Auth::user()->picture_url)}}" style="width:50px; height: 50px; border: 1px solid #7D7D7D;" class="img-circle center-block"> --}}
    <h4 style="margin: 5px auto 0 auto;">{{Auth::user()->name}}</h4>
  </div>
  <ul class="sidebar-nav">
    <li>
      <a href="{{url('dashboard')}}">
        <i class="fa fa-dashboard"></i>&nbsp;
        Dashboard
      </a>
    </li>
    <li>
      <a href="{{url('dashboard/news')}}">
        <i class="fa fa-newspaper-o"></i>&nbsp;
        Administrar Noticias
      </a>
    </li>
    <li>
      <a href="{{url('dashboard/clients')}}">
        <i class="fa fa-exchange"></i>&nbsp;
        Administrar Clientes
      </a>
    </li>
    <li>
      <a href="{{url('dashboard/media')}}">
        <i class="fa fa-video-camera"></i>&nbsp;
        Administrar Medios
      </a>
    </li>
    <li>
      <a href="{{url('dashboard/topics')}}">
        <i class="fa fa-folder-o"></i>&nbsp;
        Administrar Temas
      </a>
    </li>
  </ul>
</div>
