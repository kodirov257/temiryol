@php( $locales = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalesOrder() )

<li class="nav-item dropdown localization-menu">
    {{-- Localization menu toggler --}}
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <span @if(config('adminlte.usermenu_image')) class="d-none d-md-inline" @endif>
            {{ $locales[App::getLocale()]['native'] }}
        </span>
    </a>

    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        @foreach($locales as $localeCode => $properties)
            <li>
                <a class="nav-link {{ $localeCode !== App::getLocale() ? : 'active' }}" hreflang="{{ $localeCode }}"
                   href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                    {{ $properties['native'] }}
                </a>
            </li>
        @endforeach
    </ul>
</li>
