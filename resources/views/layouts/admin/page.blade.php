<x-admin-master-layout>
    @inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

    @section('adminlte_css')
        @stack('css')
        @yield('css')
    @stop

    @section('classes_body', $layoutHelper->makeBodyClasses())

    @section('body_data', $layoutHelper->makeBodyData())

    @section('body')
        <div class="wrapper">

            {{-- Preloader Animation --}}
            @if($layoutHelper->isPreloaderEnabled())
                @include('partials.admin.common.preloader')
            @endif

            {{-- Top Navbar --}}
            @if($layoutHelper->isLayoutTopnavEnabled())
                @include('partials.admin.navbar.navbar-layout-topnav')
            @else
                @include('partials.admin.navbar.navbar')
            @endif

            {{-- Left Main Sidebar --}}
            @if(!$layoutHelper->isLayoutTopnavEnabled())
                @include('partials.admin.sidebar.left-sidebar')
            @endif

            {{-- Content Wrapper --}}
            @empty($iFrameEnabled)
                @include('partials.admin.cwrapper.cwrapper-default')
            @else
                @include('partials.admin.cwrapper.cwrapper-iframe')
            @endempty

            {{-- Footer --}}
            @hasSection('footer')
                @include('partials.admin.footer.footer')
            @endif

            {{-- Right Control Sidebar --}}
            @if(config('adminlte.right_sidebar'))
                @include('partials.admin.sidebar.right-sidebar')
            @endif

        </div>
    @stop

    @section('adminlte_js')
        @stack('js')
        @yield('js')
    @stop
</x-admin-master-layout>
