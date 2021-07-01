<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">Rüzgar CRM</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">RC</a>
        </div>
        <ul class="sidebar-menu">
            @foreach ($admin->sideNav() as $nav)
                @if (isset($nav['can']) && $nav['can'] == true)
                    @if (isset($nav['header']) && $nav['header'] !== null)
                        <li class="menu-header">{{ $nav['header'] }}</li>
                    @else
                        @isset($nav['submenu'])
                            <li class="nav-item dropdown @if ($nav['active']==true) active @endif">
                                <a href="{{ isset($nav['route']) ? route($nav['route']) : '#' }}"
                                    class="nav-link has-dropdown">
                                    <i class="{{ $nav['icon'] }}"></i>
                                    <span>{{ $nav['title'] }}</span>
                                </a>

                                <ul class="dropdown-menu">
                                    @foreach ($nav['submenu'] as $subnav)
                                        @if (isset($subnav['can']) && $subnav['can'] == true)
                                            <li @if ($subnav['active'] == true) class="active" @endif>
                                                <a class="nav-link" href="{{ route($subnav['route']) }}">
                                                    {{ $subnav['title'] }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li @if ($nav['active'] == true) class="active" @endif>
                                <a href="{{ route($nav['route']) }}" class="nav-link">
                                    <i class="{{ $nav['icon'] }}"></i>
                                    <span>{{ $nav['title'] }}</span>
                                </a>
                            </li>
                        @endisset
                    @endif
                @endif
            @endforeach
        </ul>
    </aside>
</div>
