@extends('base')

@section('title')
    Page d'acceuil
@endsection

@section('body')
    @include('navbar')

    @forelse ($posts as $post)
        <div class="flex items-center justify-center p-6">
            <div class="w-1/2">
                <article class="flex bg-white">
                    <div class="hidden sm:block sm:basis-56">
                        <img alt=""
                            src="{{ $post->image }}"
                            class="aspect-square h-full w-full object-cover" />
                    </div>

                    <div class="flex flex-1 flex-col justify-between">
                        <div class="border-s border-gray-900/10 p-4 sm:border-l-transparent sm:p-6">
                            <span class="font-bold underline text-orange-500 capitalize">{{ $post->category->name }}</span>
                            <a href="#">
                                <h3 class="font-bold uppercase text-gray-900">
                                    {{ $post->title }}
                                </h3>
                            </a>

                            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-700">
                                {{$post->content }}
                            </p>
                        </div>

                        <div class="sm:flex sm:items-end sm:justify-end">
                            <a href="#"
                                class="block bg-green-500 px-5 py-3 text-center text-xs font-bold uppercase text-gray-900 transition hover:bg-green-700">
                                Lire l'article
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    @empty
        <p>Aucune poste pour le moment</p>
    @endforelse

    <div class="mt-5">
        {{$posts->links('pagination::tailwind')}}
    </div>
@endsection
