<div class="w-screen lg:px-16 pr-8 bg-johrh-dark-header flex flex-wrap items-center shadow-md h-16">
    <div class="w-full flex max-w-screen-xl m-auto justify-between">
        <div class="flex-none flex justify-between items-center">
            <label for="menu-toggle" class="pointer-cursor md:hidden block pl-6">
                <img src="{{ asset('img/Burger.svg') }}" alt="">
            </label>
            <a href="#" class="text-xl text-white">
                <img src="{{ asset('img/Logo.svg') }}"/>
            </a>
        </div>
        <div class="hidden md:flex flex-grow flex justify-between items-center mr-8 xl:ml-48 md:ml-16">
            <input type="text" placeholder="ما الذي تبحث عنه" class="w-full rounded h-10 px-8">
            <span class="absolute p-2">
                <img src="{{ asset('img/Search.svg') }}"/>
            </span>
        </div>
        <div class="flex-none flex justify-between items-center">
            <input class="hidden" type="checkbox" id="menu-toggle"/>
            <div class="items-center w-auto w-full" id="menu">
                <nav>
                    <ul class="flex items-center justify-between text-base text-white h-12">
                        <li class="hidden md:flex border-opacity-20 border-l-2 border-johrh-gold hover:opacity-75">
                            <a class="p-4 py-1.5 block flex" href="#">
                                <span>المفضلة</span>
                                <img src="{{ asset('img/Favorite.svg') }}" alt="" class="pr-2">
                            </a>
                        </li>
                        <li class="border-opacity-20 md:border-l-2 border-johrh-gold hover:opacity-75">
                            <a class="md:p-4 md:py-1.5 block flex" href="#">
                                <span class="hidden md:flex">حسابي</span>
                                <img src="{{ asset('img/Profile.svg') }}" alt="" class="pr-2">
                            </a>
                        </li>
                        <li class="border-l-1 border-johrh-gold hover:opacity-75">
                            <a class="p-4 py-1.5 block flex" href="#">
                                <span class="hidden md:flex">عربة التسوق</span>
                                <img src="{{ asset('img/Cart.svg') }}" alt="" class="pr-2">
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
