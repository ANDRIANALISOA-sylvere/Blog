@extends('base')

@section('title')
    {{ $post->title }}
@endsection

@section('body')
    @include('navbar')

    <div class="flex items-center justify-center p-6">
        <div class="w-1/2 h-96">
            <div class="w-full h-full">
                <img alt="{{ $post->title }}" src="{{ $post->image }}"
                    class="aspect-square h-full w-full object-cover" />
            </div>
            <div class="mt-5">
                <span class="font-bold text-black capitalize">{{ $post->category->name }}</span>
                <h3 class="font-bold text-2xl text-gray-900">
                    {{ $post->title }}
                </h3>
                @foreach ($post->tags as $tag)
                    <span
                        class="whitespace-nowrap rounded-full bg-blue-100 px-2.5 py-0.5 text-sm text-blue-700">{{ $tag->name }}</span>
                @endforeach
                <p class="mt-5 mb-5 text-sm/relaxed text-gray-700">
                    {!! nl2br(e($post->content)) !!}
                </p>
            </div>
        </div>
    </div>
@endsection
