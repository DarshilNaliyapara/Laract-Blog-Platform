<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-black">
                    @if (Route::currentrouteName() === 'blogs.show')
                        <div id="post"
                            class="flex flex-col justify-between space-x-4 p-4 mb-4 mt-4 rounded-lg shadow-md bg-white dark:bg-gray-900 dark:border-gray-700">

                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $blog->user->name }}</span>
                            <small class="ml-2 text-sm text-gray-400 dark:text-gray-500">
                                {{ $blog->created_at->format('j M Y, g:i a') }}
                            </small>

                            @unless ($blog->created_at->eq($blog->updated_at))
                                <small class="text-sm text-gray-400 dark:text-gray-500"> &middot;
                                    {{ __('edited') }}</small>
                            @endunless

                            @php
                                $postContent = json_decode($blog->posts, true);
                            @endphp

                            <p class="text-xl font-bold text-gray-900  dark:text-gray-100">
                                {{ $postContent['title'] }}
                            </p>

                            <!-- Alpine.js Lightbox -->
                            <div x-data="{ open: false, imgSrc: '' }">
                                <div class="flex flex-wrap gap-4 mt-3">
                                   
                                        <div class="relative w-full md:w-1/2 lg:w-1/3">
                                            <!-- Clickable Image -->
                                            <img @click="open = true; imgSrc = '{{ asset('/storage/' . $blog->photo_name) }}'"
                                                class="cursor-pointer rounded-lg shadow object-cover w-full h-full"
                                                src="{{ asset('/storage/' . $blog->photo_name) }}" alt="Blog Image">
                                        </div>
                                    
                                </div>

                                <!-- Lightbox Modal -->
                                <div x-show="open" x-transition.opacity
                                    class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur flex items-center justify-center p-4 z-50"
                                    @click="open = false" @keydown.window.escape="open = false" style="display: none;">
                                    <div class="relative max-w-4xl w-full">
                                      
                                        <img :src="imgSrc"
                                            class="w-full max-h-screen object-contain rounded-lg shadow-lg"
                                            alt="Zoomed Image">
                                    </div>
                                </div>
                            </div>

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
</x-guest-layout>
