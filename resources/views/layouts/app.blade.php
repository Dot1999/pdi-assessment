<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/flowbite.min.css" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        <div id="common-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
        </div>
    </div>
    @if($errors->any())
    @foreach ($errors->all() as $error)
    <div id="toast-danger" class="fixed flex items-center w-full max-w-xs p-4 mb-4 top-5 right-5 text-gray-500 bg-white rounded-lg shadow light:text-gray-400 light:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg light:bg-red-800 light:text-red-200">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Error icon</span>
        </div>
        <div class="ml-3 text-sm font-normal">{{$error}}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 light:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-danger" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endforeach
    @endif
    @if(session('success'))
    <div id="toast-success" class="fixed flex items-center w-full max-w-xs p-4 top-5 right-5 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 light:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg light:bg-green-800 light:text-green-200">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Check icon</span>
        </div>
        <div class="ml-3 text-sm font-normal">{{session('success')}}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 light:text-gray-500 light:hover:text-white light:bg-gray-800 light:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/flowbite.min.js"></script>
    <script>
        const modalUrls = document.querySelectorAll('[data-modal-url]');
        for (let i = 0; i < modalUrls.length; i++) {
            const element = modalUrls[i];
            element.addEventListener('click', function(e) {
                const url = this.dataset.modalUrl;
                fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-Token": "{{ csrf_token() }}",
                        }
                    })
                    .then((response) => {
                        if (!response.ok) {
                            if (response.status == 403) {
                                alert('Permission Denied');
                            } else if (response.status == 404) {
                                alert('Page Not Found');
                            }
                        } else {
                            return response.text();
                        }
                    })
                    .then((data) => {
                        if (!data) return;
                        const modalEl = document.querySelector('#common-modal');
                        modalEl.innerHTML = data;
                        const modalHideEls = modalEl.querySelectorAll('[data-modal-hide]');
                        if (modalHideEls) {
                            for (let x = 0; x < modalHideEls.length; x++) {
                                modalHideEls[x].onclick = function() {
                                    modal.hide();
                                }
                            }
                        }
                        const modal = new Flowbite.default.Modal(modalEl, {
                            closable: element.dataset.modalBackdrop != "static"
                        })
                        modal.show();
                        if (element.dataset.script) {
                            window[element.dataset.script]();
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                    });
            })
        }

        function printData(html) {
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write(`<style>
                    thead th{
                        text-align:left;
                    }
                    tbody td, thead th{
                        padding: .5rem 1rem;
                    }
                    tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }
                    table{
                        width: 100%;
                        border-spacing:0;
                        border-collapse: collapse;
                    }
                </style>`);
            mywindow.document.write('</head><body >');
            // mywindow.document.write('<h1>' + document.title  + '</h1>');
            mywindow.document.write(html);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        }

        function print(url) {
            fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-Token": "{{ csrf_token() }}",
                    }
                })
                .then((response) => {
                    if (!response.ok) {
                        if (response.status == 403) {
                            alert('Permission Denied.');
                        } else if (response.status == 404) {
                            alert('Not Found.');
                        }
                    } else {
                        return response.text();
                    }
                })
                .then((data) => {
                    if (!data) return;
                    printData(data);
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        }
    </script>
    @stack('modals')

    @livewireScripts
</body>

</html>