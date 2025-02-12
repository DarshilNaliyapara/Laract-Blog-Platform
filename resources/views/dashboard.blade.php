<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-black">
                    @if (Route::currentrouteName() === 'blogs.show')
                        <div class="flex-shrink-0">
                            <span class="text-sm text-white">{{ $blog->user->name }}</span>
                            <small
                                class="ml-2 text-sm text-white">{{ $blog->created_at->format('j M Y, g:i a') }}</small>

                            @unless ($blog->created_at->eq($blog->updated_at))
                                <small class="text-sm text-white"> &middot; {{ __('edited') }}</small>
                            @endunless
                        </div>

                        <div class="flex-grow">
                            @php
                                $postContent = json_decode($blog->posts, true);
                            @endphp
                            <p class="text-xl font-bold text-gray-100">{{ $postContent['title'] }}</p>
                            <p class="text-base text-gray-300 mt-2">{{ $postContent['post'] }}</p>

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

<script>

</script>
