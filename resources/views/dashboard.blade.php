<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-black">
                    @if (Route::currentrouteName() === 'blogs.show')
                        <div id="post"
                            class="flex flex-col justify-between space-x-4 p-4 mb-4 mt-4 rounded-lg shadow-md bg-white
                               dark:bg-gray-900  dark:border-gray-700">

                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $blog->user->name }}</span>
                            <small
                                class="ml-2 text-sm text-gray-400 dark:text-gray-500">{{ $blog->created_at->format('j M Y, g:i a') }}</small>

                            @unless ($blog->created_at->eq($blog->updated_at))
                                <small class="text-sm text-gray-400 dark:text-gray-500"> &middot;
                                    {{ __('edited') }}</small>
                            @endunless
                            @php
                                $postContent = json_decode($blog->posts, true);
                            @endphp

                            <a href="{{ route('blogs.show', $blog->id) }}" class="mt-1">
                                <p class="text-xl font-bold text-gray-900 underline dark:text-gray-100">
                                    {{ $postContent['title'] }}</p>
                            </a>

                            @foreach ($blog->photos as $photo)
                                <img class=" mt-3 rounded-lg shadow-md h-80 w-96 object-cover ..." src="{{ asset('/storage/' . $photo->photo_name) }}"
                                    alt="">
                                {{-- <p>{{ ltrim($photo->photo_name, 'files/') }}</p> --}}
                            @endforeach

                            <p id="postContent" class="postContent text-base text-gray-700 mt-2 dark:text-gray-300"
                                data-postvalue="{{ $postContent['post'] }}"></p>
                        </div>
                    @else
                        @if (count($posts) > 0)
                            @include('comment')
                        @else
                            <div class="flex-grow">
                                <p class="text-white">No Data Avaliable</p>
                            </div>
                        @endif
                    @endif
                    @if (!(Route::currentrouteName() === 'blogs.show'))
                        <div class="mt-5">
                            {{ $posts->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script></script>
