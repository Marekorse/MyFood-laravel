@extends('master')
@section('content')
<navigation-panel
    :user="{{auth()->user() ?? 0}}"
></navigation-panel>
<div class=" flex  pt-16">
    <div class="w-full  border-t border-b border-gray-200 px-2 py-16 md:py-24 text-gray-800 ">
        <div class="w-full max-w-6xl mx-auto">
            <div class="text-center max-w-xl mx-auto">
            <h1 class=" text-gray-600 Alex-Brush text-8xl md:text-9xl font-bold mb-5 ">Myfood</h1>
                <h3 class="text-xl mb-5 font-light">Každý z vás sa počíta.</h3>
                <div class="text-center mb-10">
                    <span class="inline-block w-1 h-1 rounded-full bg-green-500 ml-1"></span>
                    <span class="inline-block w-3 h-1 rounded-full bg-green-500 ml-1"></span>
                    <span class="inline-block w-40 h-1 rounded-full bg-green-500"></span>
                    <span class="inline-block w-3 h-1 rounded-full bg-green-500 ml-1"></span>
                    <span class="inline-block w-1 h-1 rounded-full bg-green-500 ml-1"></span>
                </div>
            </div>
            <div class="px-0.5 sm:px-6 text-sm sm:text-lg mb-14 font-semibold text-gray-500">
                <p>
                    Vestibulum fringilla, nibh tempor suscipit faucibus, orci nunc sodales odio, at ultrices dolor nibh sed dui. Phasellus id laoreet ligula, at condimentum lacus. Curabitur laoreet eros pulvinar sapien cursus sagittis. Maecenas posuere felis eget sapien blandit pulvinar. Nullam mauris diam, rhoncus a tellus non, eleifend efficitur justo. Proin ex sem, maximus at lacus ac, mollis suscipit orci. In vehicula sollicitudin massa, ac vestibulum mi. Nunc faucibus sagittis condimentum. Integer in ante imperdiet, fermentum felis et, cursus enim. Sed posuere lectus nec posuere porttitor. Aenean vitae orci efficitur odio posuere fringilla non tincidunt massa. Morbi pellentesque accumsan orci, at tristique lorem tempus eu. Maecenas tellus enim, fermentum ut hendrerit quis, euismod vel dui. Nunc auctor justo at luctus vehicula. Donec non lacinia diam. Nullam eu malesuada velit. 
                </p>
            </div>
            <div class="-mx-3 md:flex items-start">
                <div class="px-3 md:w-1/3">
                    {{--card--}}
                    
                </div> 
            </div>
        </div>
    </div>
</div>

{{--contact us --}}
<div class="flex items-top justify-center  sm:items-center sm:pt-0">
    <div class="w-full sm:w-3/4 mx-auto sm:px-6 lg:px-8">
        <div class="mt-8 ">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="p-6 mr-2 bg-gray-100 dark:bg-gray-800 sm:rounded-lg">
                    <h1 class="text-4xl sm:text-5xl text-gray-600 dark:text-white font-extrabold tracking-tight">
                        Napíš nám
                    </h1>
                    <p class="text-normal text-lg sm:text-2xl font-medium text-gray-600 dark:text-gray-400 mt-2">
                        každy može zanechať správu
                    </p>

                

                    <div class="flex items-center mt-4 text-gray-600 dark:text-gray-400">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <div class="ml-4 text-md tracking-wide font-semibold w-40">
                            +421 xxx xxx xxx
                        </div>
                    </div>

                    <div class="flex items-center mt-2 text-gray-600 dark:text-gray-400">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div class="ml-4 text-md tracking-wide font-semibold w-40">
                            xxxxxxxxx@gmail.com
                        </div>
                    </div>
                </div>

                <form class="p-2  flex flex-col justify-center" action="/contact" method="POST">
                    @csrf
                    <div class="flex flex-col ">
                        <label for="name" class="hidden">Tvoje meno</label>
                        <input type="name" name="name" id="name" placeholder="Meno" class="w-100 mt-2 py-3 px-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-400 dark:border-gray-700 text-gray-800 font-semibold focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="flex flex-col mt-2">
                        <label for="email" class="hidden">Email</label>
                        <input type="email" name="email" id="email" placeholder="Email" class="w-100 mt-2 py-3 px-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-400 dark:border-gray-700 text-gray-800 font-semibold focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="flex flex-col mt-2">
                        <label for="tel" class="hidden">Správa</label>
                        <textarea name="text" id="text" cols="30" rows="10" class="w-100 mt-2 py-3 px-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-400 dark:border-gray-700 text-gray-800 font-semibold focus:border-indigo-500 focus:outline-none" placeholder="Tvoja správa"></textarea>
                    </div>

                    <button type="submit" class="md:w-32 bg-green-600 hover:bg-blue-dark text-white font-bold py-3 px-6 rounded-lg mt-3 hover:bg-green-500 transition ease-in-out duration-300">
                        Odoslať
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>



    
@endsection