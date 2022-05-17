

    <div class="flex min-h-screen bg-white">
        <div class="w-1/2 bg-cover md:block hidden overflow-hidden" style="background-image:  url(http://127.0.0.1:8000/storage/images/register/register.jpg)"></div>
        <div class="md:w-1/2 max-w-lg mx-auto my-10 px-4 py-5 shadow-none">
            <div class="text-left p-0 font-sans">
                <h1 class=" text-gray-800 text-3xl font-medium">Vytvorte si svoj účet</h1>
                <h3 class="p-1 text-gray-700">A stante sa členom myfood.</h3>
            </div>
            <form method="POST" action="{{ route('register') }}"class="p-0">
                @csrf
                <div class="mt-5">
                    @error('name')
                        <label for="name" class="text-xs text-red-500 font-semibold">{{ $message }}</label>
                    @enderror
                    <input name="name" type="text" class="block w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent " placeholder="Použivatelské meno" autofocus>
                </div>
                <div class="mt-5">
                    @error('email')
                        <label for="email" class="text-xs text-red-500 font-semibold">{{ $message }}</label>
                    @enderror
                    <input name="email" type="email" class="block w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent " placeholder="Email">
                </div>
                <div class="mt-5">
                    @error('password')
                        <label for="password" class="text-xs text-red-500 font-semibold">{{ $message }}</label>
                    @enderror
                    <input name="password" type="password" class="block w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent  " placeholder="Vaše heslo">
                </div>
                <div class="mt-5">
                    @error('password_confirmation')
                        <label for="password_confirmation" class="text-xs text-red-500 font-semibold">{{ $message }}</label>
                    @enderror
                    <input name="password_confirmation" type="password" class="block w-full p-2 border rounded border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-transparent  " placeholder="Zopakujte heslo">
                </div>
                <div class="mt-6 block p-5 text-sm md:font-sans  text-gray-800">
                    @error('checkbox')
                        <label for="checkbox" class="text-xs text-red-500 font-semibold">{{ $message }}</label>
                    @enderror
                    <input name="checkbox" type="checkbox" class="inline-block border-0 " required>
                    <span display="inline" class="">Vytvoreném účtu suhlasíte s 
                        <a class="" href="/s/terms">
                        <span class="underline ">podmienkamy použivania</span> </a> a
                    <a class="" href="/s/privacy" target="_blank" data-test="Link">
                        <span class="underline">spracovaním osobných udajov</span></a>
                    </span>
                </div>

                <div class="mt-10">
                    <input type="submit" value="Zaregistrovať" class="py-3 bg-green-500 text-white w-full rounded hover:bg-green-600">
                </div>
            </form>
            <a class="" href="/prihlasenie" data-test="Link"><span class="block  p-5 text-center text-gray-800  text-xs ">Už máte vytvorený účet ?</span></a>
        </div>
    </div>

