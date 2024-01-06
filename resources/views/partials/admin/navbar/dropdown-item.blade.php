@inject('navbarItemHelper', 'JeroenNoten\LaravelAdminLte\Helpers\NavbarItemHelper')

@if ($navbarItemHelper->isSubmenu($item))

    {{-- Dropdown submenu --}}
    @include('partials.admin.navbar.dropdown-item-submenu')

@elseif ($navbarItemHelper->isLink($item))

    {{-- Dropdown link --}}
    @include('partials.admin.navbar.dropdown-item-link')

@endif
