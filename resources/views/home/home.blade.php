@extends('base')

@section('title')
    Page d'acceuil
@endsection

@section('body')
    @include('navbar')

    @forelse ($posts as $post)
        <div class="flex items-center justify-center p-6">
            <div class="w-1/2">
                <article class="flex bg-white transition hover:shadow-xl">
                    <div class="hidden sm:block sm:basis-56">
                        <img alt=""
                            src="https://images.unsplash.com/photo-1609557927087-f9cf8e88de18?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80"
                            class="aspect-square h-full w-full object-cover" />
                    </div>

                    <div class="flex flex-1 flex-col justify-between">
                        <div class="border-s border-gray-900/10 p-4 sm:border-l-transparent sm:p-6">
                            <a href="#">
                                <h3 class="font-bold uppercase text-gray-900">
                                    Finding the right guitar for your style - 5 tips
                                </h3>
                            </a>

                            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-700">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae dolores, possimus
                                pariatur animi temporibus nesciunt praesentium dolore sed nulla ipsum eveniet corporis
                                quidem, mollitia itaque minus soluta, voluptates neque explicabo tempora nisi culpa eius
                                atque dignissimos. Molestias explicabo corporis voluptatem?
                            </p>
                        </div>

                        <div class="sm:flex sm:items-end sm:justify-end">
                            <a href="#"
                                class="block bg-yellow-300 px-5 py-3 text-center text-xs font-bold uppercase text-gray-900 transition hover:bg-yellow-400">
                                Read Blog
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    @empty
        <p>Aucune poste pour le moment</p>
    @endforelse
@endsection
