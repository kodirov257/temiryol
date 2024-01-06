@inject('navbarItemHelper', 'JeroenNoten\LaravelAdminLte\Helpers\NavbarItemHelper')

@if ($navbarItemHelper->isSearch($item))

    {{-- Search form --}}
    @include('partials.admin.navbar.menu-item-search-form')

@elseif ($navbarItemHelper->isNotification($item))

    {{-- Notification link --}}
    <x-layout.navbar-notification
        :id="$item['id']"
        :href="$item['href']"
        :icon="$item['icon']"
        :icon-color="$item['icon_color'] ?? null"
        :badge-label="$item['label'] ?? null"
        :badge-color="$item['label_color'] ?? null"
        :update-cfg="$item['update_cfg'] ?? null"
        :enable-dropdown-mode="$item['dropdown_mode'] ?? null"
        :dropdown-footer-label="$item['dropdown_flabel'] ?? null"
    />

@elseif ($navbarItemHelper->isFullscreen($item))

    {{-- Fullscreen toggle widget --}}
    @include('partials.admin.navbar.menu-item-fullscreen-widget')

@elseif ($navbarItemHelper->isDarkmode($item))

    {{-- Darkmode toggle widget --}}
    <x-layout.navbar-darkmode-widget
        :icon-enabled="$item['icon_enabled'] ?? null"
        :color-enabled="$item['color_enabled'] ?? null"
        :icon-disabled="$item['icon_disabled'] ?? null"
        :color-disabled="$item['color_disabled'] ?? null"
    />

@elseif ($navbarItemHelper->isSubmenu($item))

    {{-- Dropdown menu --}}
    @include('partials.admin.navbar.menu-item-dropdown-menu')

@elseif ($navbarItemHelper->isLink($item))

    {{-- Link --}}
    @include('partials.admin.navbar.menu-item-link')

@endif
