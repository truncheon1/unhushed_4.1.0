<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Site info -->
        <title>{{ config('app.name', 'UN|HUSHED') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}?version={{ config('app.version') }}" rel="stylesheet">
        @stack('styles')
        <!-- Font Awesome Pro 6 -->
        <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
        <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/sharp-solid.css">
        <!-- web-awesome (fallback) -->
        <link rel="stylesheet" href="https://early.webawesome.com/webawesome@3.0.0-alpha.2/dist/themes/default.css" />
        <script type="module" src="https://early.webawesome.com/webawesome@3.0.0-alpha.2/dist/webawesome.loader.js"></script>
        <!-- googleapis -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- bootstrap -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
        <!-- Bootstrap Bundle (includes Popper 2.11.8) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Bootstrap Select for Bootstrap 5 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
        <!-- flatpickr -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <!-- dropzone -->
        <script src='{{url("js/dropzone/dist/dropzone.js")}}'></script>
    <!-- tinymce 7 -->
        <script src='{{url("js/tinymce/tinymce.min.js")}}'></script>
        <script>
            // Disable TinyMCE auto-initialization - only init manually in modals
            if(window.tinymce){ 
                tinymce.settings = tinymce.settings || {}; 
                tinymce.settings.auto_focus = false;
            }
        </script>
        <!-- js -->    
        <link rel="stylesheet" href="{{url('js/jquery-ui/jquery-ui.min.css')}}">
        <script src="{{url('js/jquery-ui/jquery-ui.min.js')}}"></script>
        <script type="text/javascript">
            const base_url = '{{url('')}}';
            window.path = window.path || "{{ $path ?? 'educators' }}"; // global audience path
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Global toast helper
            window.showToast = function(message, type = 'info'){
                const container = document.getElementById('toastContainer');
                if(!container) return alert(message); // fallback
                const id = 'toast_'+Date.now();
                const bg = type === 'success' ? 'bg-success' : (type === 'error' ? 'bg-danger' : 'bg-secondary');
                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-white ${bg} border-0`;
                toast.setAttribute('role','alert');
                toast.setAttribute('aria-live','assertive');
                toast.setAttribute('aria-atomic','true');
                toast.id = id;
                toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>`;
                container.appendChild(toast);
                if(window.bootstrap && bootstrap.Toast){ 
                    const bsToast = new bootstrap.Toast(toast, {delay: 3500});
                    bsToast.show();
                    toast.addEventListener('hidden.bs.toast', function(){ toast.remove(); });
                }
            };
            
            // Ensure Bootstrap 5 alerts work properly on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize all alerts with close buttons
                const alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(function(alert) {
                    // Ensure proper Bootstrap 5 structure
                    if (!alert.classList.contains('fade')) {
                        alert.classList.add('fade', 'show');
                    }
                    if (!alert.hasAttribute('role')) {
                        alert.setAttribute('role', 'alert');
                    }
                    
                    // Fix old-style close buttons
                    const oldClose = alert.querySelector('a.close');
                    if (oldClose) {
                        const newClose = document.createElement('button');
                        newClose.type = 'button';
                        newClose.className = 'btn-close';
                        newClose.setAttribute('data-bs-dismiss', 'alert');
                        newClose.setAttribute('aria-label', 'Close');
                        oldClose.parentNode.replaceChild(newClose, oldClose);
                    }
                });
                
                // Prevent Stripe forms from being submitted multiple times
                const stripeForms = document.querySelectorAll('form[action*="stripe"], form[action*="subscription"]');
                stripeForms.forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn && !submitBtn.disabled) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
                            // Re-enable after 3 seconds as fallback
                            setTimeout(function() {
                                if (submitBtn.disabled) {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = submitBtn.getAttribute('data-original-text') || 'Submit';
                                }
                            }, 3000);
                        }
                    });
                    
                    // Store original button text
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.setAttribute('data-original-text', submitBtn.textContent.trim());
                    }
                });
            });
        </script>
        @include('layouts.analytics')
    </head>
    <body>
        <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WGPDNST"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <!-- Header -->
        <div id="site-header">
            @include('layouts.header')
        </div>
        <!-- Main -->
        <main id="site-main">
            @include('auth.errors')
            @yield('content')
        </main>
        <!-- Back to Top button -->
        <div id="site-back">
            @include('layouts.back-to-top')
        </div>
        <!-- Toast Container (Global) -->
        <div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index:1080">
            <div id="toastContainer"></div>
        </div>
        <!-- Footer -->
        <footer>
            <div id="site-footer">
                @include('layouts.footer')
            </div>
        </footer>
        <!-- Script include -->
        @if(env('APP_ENV') == 'nyk1')
            <script src="{{ asset('js/app.js') }}?version={{ config('app.version') }}" defer></script>
        @endif
        @stack('scripts')
        <style>
            /* Global alert styles to prevent overlap and ensure proper display */
            .alert {
                position: relative;
                margin-bottom: 1rem;
                z-index: 1050;
            }
            
            /* Bootstrap 5 dismissible alert structure */
            .alert-dismissible {
                padding-right: 3rem;
            }
            
            .alert-dismissible .btn-close {
                position: absolute;
                top: 0.5rem;
                right: 0.5rem;
                z-index: 2;
                padding: 0.5rem;
            }
            
            /* Toast container z-index */
            #toastContainer {
                z-index: 1090 !important;
            }
            
            .toast {
                z-index: 1091 !important;
            }
            
            /* Ensure messages don't stack on top of each other */
            .alert + .alert {
                margin-top: -0.5rem;
            }
            
            /* Stripe form button loading state */
            button[type="submit"]:disabled {
                opacity: 0.65;
                cursor: not-allowed;
            }
            
            @media screen and (max-width: 600px) {
                #site-support {
                    visibility: hidden;
                    display: none;
                }
                
                /* Stack alerts on mobile */
                .alert {
                    font-size: 0.9rem;
                }
            }
        </style>
    </body>
</html>






