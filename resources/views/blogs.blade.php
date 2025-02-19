<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blogs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 shadow-md">
                    <form id="postform" method="post">

                        <label for="title" class="block text-gray-300 text-sm font-medium mb-1">Title</label>
                        <x-text-input name="title" :value="old('title', isset($val['title']) ? $val['title'] : '')" class="w-full dark:bg-gray-900 dark:text-gray-200" />

                        <label for="post" class="block text-gray-300 text-sm font-medium mt-4 mb-1">Body</label>

                        <textarea id="post" name="post"
                            class="w-full h-32 border border-gray-700 bg-gray-800 text-gray-200 placeholder-gray-400 rounded-lg p-3 dark:focus:border-green-600 focus:ring-green-500 focus:outline-none  dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 leading-normal">{{ old('post', isset($val['post']) ? $val['post'] : '') }}</textarea>


                        <x-primary-button class="mt-2 mr-2 blogbtn"
                            data-blogid="{{ $blog->id }}">{{ isset($blog->id) ? 'Save' : 'post' }}
                        </x-primary-button>

                            @if (request()->routeIs('blogs.edit'))
                                <a href="{{ route('blogs.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">

                                        Cancel

                                </a>
                            @endif
                        </form>
                    @if (count($posts) > 0)

                        @foreach ($posts as $post)
                            <div
                                class="flex flex-col space-x-4 p-4 mb-4 mt-4  rounded-lg shadow-md bg-gray-900">


                                <span class="text-sm text-gray-200">{{ $post->user->name }}</span>

                                <small
                                    class="ml-2 text-sm text-gray-300">{{ $post->created_at->format('j M Y, g:i a') }}</small>

                                @unless ($post->created_at->eq($post->updated_at))
                                    <small class="text-sm text-gray-300"> &middot; {{ __('edited') }}</small>
                                @endunless

                                @php
                                    $postContent = json_decode($post->posts, true);
                                @endphp

                                <a href="{{ route('blogs.show', $post->id) }}" class="mt-1">
                                    <p class="text-xl font-bold text-gray-200 underline">{{ $postContent['title'] }}</p>
                                </a>

                                <p class="text-base text-gray-200 mt-2">{{ $postContent['post'] }}</p>

                                <div class="flex mt-4">
                                    @if ($post->user_id === auth()->id())
                                        <form id="dlt-form">
                                            <x-danger-button class="mr-2 dlt-btn dark:text-gray-100" data-dltbtnid="{{ $post->id }}">
                                                Delete
                                            </x-danger-button>
                                        </form>
                                        <a href="{{ route('blogs.edit', $post->id) }}">
                                            <x-secondary-button>
                                                Edit
                                            </x-secondary-button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="flex-grow">
                            <p class="text-white mt-4">No Data Avaliable</p>
                        </div>
                    @endif
                    <div class="mt-5">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
