<?php use Illuminate\Support\Facades\Route;

?>
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="{{ route('admin.dashboard') }}">
            <img alt="Logo"
                src="{{ asset(!empty($mainData->image) ? $mainData->image : 'assets/media/logos/default-dark.svg') }}"
                class="h-50px app-sidebar-logo-default" />
        </a>
        <!--end::Logo image-->
        <!--begin::Sidebar toggle-->
        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-sm h-30px w-30px rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
            <span class="svg-icon svg-icon-2 rotate-180">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5"
                        d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                        fill="currentColor" />
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="currentColor" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold px-3" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">

                @can('view_dashboard')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.dashboard']) ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-speedometer2 text-primary fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.dashboard') }}</span>
                        </a>
                    </div>
                @endcan
                @canany('view_subscriptions', 'view_sarf_band')
                    <hr class="w-100 border border-success">

                    <div class="menu-item ">
                        <div class="menu-content">
                            <span
                                class="fw-bold text-uppercase fs-7 text-success">{{ trans('sidebar.settings_management') }}</span>
                        </div>

                    </div>
                @endcan
                @php
                    $defaultSettingsLink = null;

                    if (auth()->user()->can('view_subscriptions')) {
                        $defaultSettingsLink = route('admin.subscriptions');
                    } elseif (auth()->user()->can('view_sarf_band')) {
                        $defaultSettingsLink = route('admin.sarf_bands');
                    }
                @endphp
                @if ($defaultSettingsLink)
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.subscriptions') || request()->routeIs('admin.sarf_bands') ? 'active' : '' }}"
                            href="{{ $defaultSettingsLink }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-sliders text-primary fs-2x"></i> <!-- Changed to "sliders" -->
                            </span>
                            <span class="menu-title">{{ trans('sidebar.general_settings') }}</span>
                        </a>
                    </div>
                @endif

                @canany(['list_roles', 'list_users', 'view_employees'])
                    <hr class="w-100 border border-success">

                    <div class="menu-item">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="fw-bold text-uppercase fs-7 text-success">
                                {{ trans('sidebar.user&employees_management') }}
                            </span>
                        </div>
                    </div>
                @endcanany

                @can('view_employees')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.employee_data', 'jobs', 'admin.archive_shelf_settings', 'shelf', 'admin.archive_settings', 'desk']) ? 'active' : '' }}"
                            href="{{ route('admin.employee_data') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-person-lines-fill text-success fs-2x"></i>
                                <!-- Changed to "person-lines-fill" -->
                            </span>
                            <span class="menu-title">{{ trans('sidebar.employee_data') }}</span>
                        </a>
                    </div>

                @endcan

                @can('list_roles')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.roles.index']) ? 'active' : '' }}"
                            href="{{ route('admin.roles.index') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-shield-lock text-primary fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.roles') }}</span>
                        </a>
                    </div>
                @endcan

                @can('list_users')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.users.index']) ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-clipboard-check text-success fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.users') }}</span>
                        </a>
                    </div>
                @endcan

                @can('list_clients')
                    <hr class="w-100 border border-success">

                    <div class="menu-item ">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span
                                class="fw-bold text-uppercase fs-7 text-success">{{ trans('sidebar.clients_management') }}</span>
                        </div>

                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.clients.index']) ? 'active' : '' }}"
                            href="{{ route('admin.clients.index') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-people text-danger fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.clients') }}</span>
                        </a>
                    </div>
                @endcan
                @can('list_clients')

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.clients.import.show']) ? 'active' : '' }}"
                            href="{{ route('admin.clients.import.show') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-people text-danger fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.import_clients') }}</span>
                        </a>
                    </div>
                @endcan

                {{-- <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs(['admin.invoices.index']) ? 'active' : '' }}"
                       href="{{ route('admin.invoices.index') }}">
        <span class="svg-icon svg-icon-2" style="margin-left: 5px">
            <i class="bi bi-receipt text-primary fs-2x"></i>
        </span>
                        <span class="menu-title">{{ trans('sidebar.invoices') }}</span>
                    </a>
                </div> --}}

                {{-- <div class="menu-item menu-accordion {{ request()->routeIs(['admin.invoices.index', 'admin.due_monthly_invoices', 'admin.new_paid_invoices']) ? 'here show' : '' }}">
                    <a class="menu-link menu-toggle" href="#">
                        <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                            <i class="bi bi-file-earmark-text text-primary fs-2x"></i>
                        </span>
                        <span class="menu-title">{{ trans('sidebar.invoices') }}</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="menu-sub menu-sub-accordion">

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.invoices.index') ? 'active' : '' }}" href="{{ route('admin.invoices.index') }}">
                                <span class="menu-title">{{ trans('sidebar.all_invoices') }}</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.due_monthly_invoices') ? 'active' : '' }}" href="{{ route('admin.due_monthly_invoices') }}">
                                <span class="menu-title">{{ trans('sidebar.due_monthly_invoices') }}</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.new_paid_invoices') ? 'active' : '' }}" href="{{ route('admin.new_paid_invoices') }}">
                                <span class="menu-title">{{ trans('sidebar.new_paid_invoices') }}</span>
                            </a>
                        </div>
                    </div>
                </div> --}}
                @canany(['list_invoices', 'view_reports'])
                    <hr class="w-100 border border-success">

                    <div class="menu-item">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="fw-bold text-uppercase fs-7 text-success">
                                {{ trans('sidebar.invoices&reports_management') }}
                            </span>
                        </div>
                    </div>
                @endcanany
                @can('list_invoices')
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ request()->routeIs(['admin.invoices.index', 'admin.due_monthly_invoices', 'admin.new_paid_invoices']) ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="bi bi-file-earmark-text fs-2x"></i>
                                </span>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.invoices') }}</span>
                            <span class="menu-arrow"></span>
                        </span>

                        <div
                            class="menu-sub menu-sub-accordion {{ request()->routeIs(['admin.invoices.index', 'admin.due_monthly_invoices', 'admin.new_paid_invoices']) ? 'show' : '' }}">
                            <div class="menu-item">

                                <a class="menu-link {{ request()->routeIs('admin.invoices.index') ? 'active' : '' }}"
                                    href="{{ route('admin.invoices.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ trans('sidebar.all_invoices') }}</span>
                                </a>

                                <a class="menu-link {{ request()->routeIs('admin.due_monthly_invoices') ? 'active' : '' }}"
                                    href="{{ route('admin.due_monthly_invoices') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ trans('sidebar.due_monthly_invoices') }}</span>
                                </a>

                                <a class="menu-link {{ request()->routeIs('admin.new_paid_invoices') ? 'active' : '' }}"
                                    href="{{ route('admin.new_paid_invoices') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ trans('sidebar.new_paid_invoices') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('view_reports')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.reports.reports']) ? 'active' : '' }}"
                            href="{{ route('admin.reports.reports') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-wallet2 text-primary fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.reports') }}</span>
                        </a>
                    </div>
                @endcan

                @canany(['list_eradat', 'list_masrofat', 'view_accounts', 'view_account_settings', 'view_financial_transactions',
                    'view_account_transfers'])
                    <hr class="w-100 border border-success">

                    <div class="menu-item">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="fw-bold text-uppercase fs-7 text-success">
                                {{ trans('sidebar.finance_management') }}
                            </span>
                        </div>
                    </div>
                @endcanany

                @can('view_accounts')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.accounts']) ? 'active' : '' }}"
                            href="{{ route('admin.accounts') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-wallet text-primary fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.accounts') }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_account_settings')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.account_settings']) ? 'active' : '' }}"
                            href="{{ route('admin.account_settings') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-gear text-primary fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.account_settings') }}</span>
                        </a>
                    </div>
                @endcan
                @can('view_financial_transactions')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.financial_transactions.index']) ? 'active' : '' }}"
                            href="{{ route('admin.financial_transactions.index') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-cash-coin text-primary fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.financial_transactions') }}</span>
                        </a>
                    </div>
                @endcan

                @can('view_account_transfers')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.account_transfers']) ? 'active' : '' }}"
                            href="{{ route('admin.account_transfers') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-arrow-left-right text-primary fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.account_transfers') }}</span>
                        </a>
                    </div>
                @endcan

                @can('list_eradat')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.revenues.index']) ? 'active' : '' }}"
                            href="{{ route('admin.revenues.index') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-wallet2 text-primary fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.revenues') }}</span>
                        </a>
                    </div>
                @endcan
                @can('list_masrofat')
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs(['admin.masrofat.index']) ? 'active' : '' }}"
                            href="{{ route('admin.masrofat.index') }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-cash-coin text-success fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.masrofat') }}</span>
                        </a>
                    </div>
                @endcan

                @canany(['view_new_clients_notifications', 'view_unpaid_invoices_notifications'])
                    <hr class="w-100 border border-success">

                    <div class="menu-item">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="fw-bold text-uppercase fs-7 text-success">
                                {{ trans('sidebar.notifications_management') }}
                            </span>
                        </div>
                    </div>
                @endcanany

                @php
                    $defaultNotificationLink = null;

                    if (auth()->user()->can('view_new_clients_notifications')) {
                        $defaultNotificationLink = route('admin.new_clients_notifications');
                    } elseif (auth()->user()->can('view_unpaid_invoices_notifications')) {
                        $defaultNotificationLink = route('admin.unpaid_invoices_notifications');
                    } else {
                        $defaultNotificationLink = route('admin.invoices_notifications');
                    }
                    // echo $defaultNotificationLink;
                @endphp
                @if ($defaultNotificationLink)
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('admin.new_clients_notifications') || request()->routeIs('admin.unpaid_invoices_notifications') || request()->routeIs('admin.invoices_notifications') || request()->routeIs('admin.transfers_notifications') ? 'active' : '' }}"
                            href="{{ $defaultNotificationLink }}">
                            <span class="svg-icon svg-icon-2" style="margin-left: 5px">
                                <i class="bi bi-bell text-warning fs-2x"></i>
                            </span>
                            <span class="menu-title">{{ trans('sidebar.notifications') }}</span>

                            @if (count_all_notifications_clients() > 0)
                                <span class="badge bg-danger blinking" style="order: 1; margin-left: 5px;">
                                    {{ count_all_notifications_clients() }}
                                </span>
                            @endif

                            <style>
                                @keyframes blink {
                                    0% {
                                        opacity: 1;
                                    }

                                    50% {
                                        opacity: 0.3;
                                    }

                                    100% {
                                        opacity: 1;
                                    }
                                }

                                .blinking {
                                    animation: blink 1s infinite;
                                }
                            </style>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!--end::sidebar menu-->

</div>
