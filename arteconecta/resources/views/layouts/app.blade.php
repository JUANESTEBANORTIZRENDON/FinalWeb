<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ArteConecta') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/banter-theme.css') }}">
    </head>
    @php
        $themeClass = 'theme-default';

        if (auth()->check() && auth()->user()->isAdmin() && request()->routeIs('admin.*')) {
            $themeClass = 'admin-theme';
        } elseif (auth()->check() && auth()->user()->isArtist()) {
            $themeClass = 'theme-artist';
        } elseif (auth()->check() && auth()->user()->isVisitor()) {
            $themeClass = 'theme-visitor';
        }
    @endphp
    <body class="{{ $themeClass }}">
        @include('layouts.navigation')

        <main class="banter-page py-4">
            {{ $slot }}
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sidebar = document.getElementById('sidebar');
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                const openSidebarBtn = document.getElementById('openSidebar');
                const closeSidebarBtn = document.getElementById('closeSidebar');

                function openSidebar() {
                    if (!sidebar || !sidebarOverlay) {
                        return;
                    }
                    sidebar.classList.add('active');
                    sidebarOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }

                function closeSidebar() {
                    if (!sidebar || !sidebarOverlay) {
                        return;
                    }
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }

                if (openSidebarBtn) {
                    openSidebarBtn.addEventListener('click', openSidebar);
                }
                if (closeSidebarBtn) {
                    closeSidebarBtn.addEventListener('click', closeSidebar);
                }
                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', closeSidebar);
                }

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeSidebar();
                    }
                });

                const sections = document.querySelectorAll('.banter-section');
                if ('IntersectionObserver' in window && sections.length) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('is-visible');
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.12 });

                    sections.forEach((section) => observer.observe(section));
                } else {
                    sections.forEach((section) => section.classList.add('is-visible'));
                }

                document.querySelectorAll('[data-banter-slider]').forEach((sliderRoot) => {
                    const track = sliderRoot.querySelector('[data-banter-track]');
                    if (!track) {
                        return;
                    }

                    const prevBtn = sliderRoot.querySelector('[data-banter-prev]');
                    const nextBtn = sliderRoot.querySelector('[data-banter-next]');

                    const getStep = () => {
                        const firstCard = track.querySelector('[data-banter-slide]');
                        return firstCard ? firstCard.getBoundingClientRect().width + 16 : track.clientWidth * 0.9;
                    };

                    if (prevBtn) {
                        prevBtn.addEventListener('click', () => {
                            track.scrollBy({ left: -getStep(), behavior: 'smooth' });
                        });
                    }

                    if (nextBtn) {
                        nextBtn.addEventListener('click', () => {
                            track.scrollBy({ left: getStep(), behavior: 'smooth' });
                        });
                    }
                });

                document.querySelectorAll('[data-banter-tabs]').forEach((tabsRoot) => {
                    const tabButtons = tabsRoot.querySelectorAll('[data-banter-tab]');
                    const tabPanels = tabsRoot.querySelectorAll('[data-banter-tab-panel]');

                    const activateTab = (target) => {
                        tabButtons.forEach((btn) => {
                            btn.classList.toggle('active', btn.dataset.banterTab === target);
                            btn.setAttribute('aria-selected', String(btn.dataset.banterTab === target));
                        });
                        tabPanels.forEach((panel) => {
                            panel.classList.toggle('d-none', panel.dataset.banterTabPanel !== target);
                        });
                    };

                    tabButtons.forEach((btn) => {
                        btn.addEventListener('click', () => activateTab(btn.dataset.banterTab));
                    });

                    const firstTab = tabButtons[0];
                    if (firstTab) {
                        activateTab(firstTab.dataset.banterTab);
                    }
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>
