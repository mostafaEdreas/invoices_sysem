<nav class="navbar navbar-expand-lg bg-body-tertiary shadow" >
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/qeematechlogo.png') }}" alt="Bootstrap" width="30" height="24">
            {{ auth()->user()->name ?? 'Guest' }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                @can('view_all_invoices')
                     <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('home') }}">Home</a>
                </li>
                @endcan
               @can('view_all_products')
                   <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                </li>
               @endcan
                 @can('view_all_invoices')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('invoices.index') }}">invoices</a>
                </li>
                @endcan
                @can('view_all_users')
                 <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                </li>
                @endcan
            </ul>
            @auth
               <form class="d-flex" action="{{ route('logout') }}" method="post">
                  @csrf
                  <button class="btn btn-outline-success" type="submit">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                          class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                          <path fill-rule="evenodd"
                              d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                          <path fill-rule="evenodd"
                              d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                      </svg> Logout
                  </button>
              </form>
              @else
              <a class="btn btn-outline-primary" href="{{ route('login') }}">Login</a>
            @endauth
           
        </div>
    </div>
</nav>
