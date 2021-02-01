<footer>
    <div class="bg-johrh-gray w-screen lg:px-16 pr-8 py-8 flex flex-wrap justify-between items-center shadow-md sm:flex-row">
        <div class="w-full flex max-w-screen-xl m-auto justify-between md:flex-row flex-col">
            <div class="flex flex-col  w-4/12">
                <span class="mb-4">تحميل تطبيق الموبايل</span>
                <span>
                    <img src="{{ asset('img/download-app.svg') }}" alt="">
                </span>
                
            </div>
            <div class="flex flex-col  w-3/12">
                <span class="mb-4">نسعد في خدمتكم</span>
                <span class="md:flex-auto flex-row flex">
                    <a href="/#" class="w-8 ml-2"><img src="{{ asset('img/whatsapp.svg') }}" alt=""></a>
                    <a href="/#" class="w-8 ml-2"><img src="{{ asset('img/instagram.svg') }}" alt=""></a>
                    <a href="/#" class="w-4 ml-2"><img src="{{ asset('img/facebook.svg') }}" alt=""></a>
                    <a href="/#" class="w-8 ml-2"><img src="{{ asset('img/twitter.svg') }}" alt=""></a>
                </span>
                
            </div>
            <div class="w-4/12">
                <div class="w-full">
                    <h3 class="mb-2">
                        <strong>لمتابعة جديدنا</strong>
                        <span>اشتركي في القائمة البريدية</span>
                    </h3>
                    <form action="#">
                        <div class="max-w-sm flex items-center">
                            <input type="email" placeholder="البريد الإلكتروني"
                                class="flex-1 appearance-none rounded-r border-l-johrh-dark-header p-1.5 text-grey-dark focus:outline-none bg-transparent border border-johrh-dark-header border-opacity-20 focus:border-opacity-50">
                            <button type="submit"
                                class="bg-johrh-dark-header text-white text-base rounded-l-md shadow-md hover:bg-indigo-600 p-1.5 px-7 right-1 border border-johrh-dark-header">
                                اشتراك    
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white pt-2 w-screen lg:px-16 pr-8 py-8 flex flex-wrap justify-between items-center shadow-md sm:flex-row">
        <div class="w-full flex max-w-screen-xl m-auto justify-between">
            <div class="mt-2 flex-grow">
                <div class="flex lg:flex-grow items-center" id="example-navbar-info">
                    <ul class="flex flex-col lg:flex-row list-none ml-auto">
                        <li class="nav-item">
                            <a class="py-2 flex items-center pl-5 leading-snug hover:text-johrh-gold cursor-pointer">
                                سياسة الإرجاع  و الإستبدال
                            </a>
                            @livewire('flyout')
                        </li>
                        <li class="nav-item">
                            <a class="py-2 flex items-center pl-5 leading-snug hover:text-johrh-gold cursor-pointer">
                                التوصيل
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="py-2 flex items-center pl-5 leading-snug hover:text-johrh-gold cursor-pointer">
                                طرق الدفع
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="py-2 flex items-center pl-5 leading-snug hover:text-johrh-gold cursor-pointer">
                                سياسة الخصوصية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="py-2 flex items-center pl-5 leading-snug hover:text-johrh-gold cursor-pointer">
                                الأسئلة الشائعة
                            </a>
                        </li>
                    </ul>
                </div>
                <span>
                    Copyright © 2020 johrh.com All rights reserved
                </span>
            </div>
            <div class="md:flex-auto mt-2 flex-row flex justify-end items-baseline">
                <a href="/#" class="mx-1">
                    <img src="{{ asset('img/payment-methods/payment-1.svg') }}" alt="">
                </a>
                <a href="/#" class="mx-1">
                    <img src="{{ asset('img/payment-methods/payment-2.svg') }}" alt="">
                </a>
                <a href="/#" class="mx-1">
                    <img src="{{ asset('img/payment-methods/payment-3.svg') }}" alt="">
                </a>
                <a href="/#" class="mx-1">
                    <img src="{{ asset('img/payment-methods/payment-4.svg') }}" alt="">
                </a>
                <a href="/#" class="mx-1 h-7">
                    <img src="{{ asset('img/payment-methods/payment-5.svg') }}" alt="">
                </a>
                <a href="/#" class="mx-1">
                    <img src="{{ asset('img/payment-methods/payment-6.svg') }}" alt="">
                </a>
            </div>
        </div>
    </div>
</footer>