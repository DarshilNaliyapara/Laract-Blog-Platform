<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blogs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 shadow-lg">
                    <form id="postform" method="post" enctype="multipart/form-data">

                        <label for="title" class="block text-gray-300 text-sm font-medium mb-1">Title</label>
                        <x-text-input name="title" :value="old('title', isset($val['title']) ? $val['title'] : '')"
                            class="w-full dark:bg-gray-900 dark:text-gray-200" />

                        <p class="text-red-500 text-sm mt-1 err" id="errtitle"></p>

                        <label for="post" class="block text-gray-300 text-sm font-medium mt-4 mb-1">Post</label>

                        <textarea id="post" name="post"
                            class="post w-full h-32 border border-gray-700 bg-gray-800 text-gray-200 placeholder-gray-400 rounded-lg p-3 dark:focus:border-green-600 focus:ring-green-500 focus:outline-none  dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 leading-normal">{{ old('post', isset($val['post']) ? $val['post'] : '') }}</textarea>

                        <p class="text-red-500 text-sm mt-1 err" id="errpost"></p>

                        @if (request()->routeIs('blogs.index'))
                            <label for="file" 
                                class="block text-gray-300 text-sm font-medium mt-2 mb-2" >Image</label>
                            <input type="file" name="file" accept=".jpg,.png,.jpeg"  tabindex="0" data-toggle="tooltip" title="Choose Image file Less then 5Mb"
                                class="block file:mr-4 file:rounded-full file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-600 dark:file:text-violet-100 dark:hover:file:bg-violet-500 ..." />
                            <p class="text-red-500 text-sm mt-1 err" id="errfile"></p>
                        @endif
                        <x-primary-button class="mt-4 mr-2 mb-4 blogbtn"
                            data-blogid="{{ $blog->slug }}">{{ isset($blog->slug) ? 'Save' : 'post' }}
                        </x-primary-button>

                        @if (request()->routeIs('blogs.edit'))
                            <a href="{{ route('blogs.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Cancel
                            </a>
                        @endif
                    </form>
                    <hr>
                    @if (count($posts) > 0)

                        @foreach ($posts as $post)
                            <div class="flex flex-col space-x-4 p-4 mb-4 mt-4  rounded-lg shadow-lg bg-gray-900">


                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $post->user->name }}</span>

                                <small
                                    class="ml-2 text-sm text-gray-400 dark:text-gray-500">{{ $post->created_at->format('j M Y, g:i a') }}</small>

                                @unless ($post->created_at->eq($post->updated_at))
                                    <small class="text-sm text-gray-400 dark:text-gray-500"> &middot;
                                        {{ __('edited') }}</small>
                                @endunless

                                @php
                                    $postContent = json_decode($post->posts, true);
                                @endphp

                                <a href="{{ route('blogs.show', $post->slug) }}" class="mt-1">
                                    <p class="text-xl font-bold text-gray-200 underline">{{ $postContent['title'] }}
                                    </p>
                                    <div class="flex flex-wrap gap-4 mt-3">
                                       
                                            <div class="relative w-full md:w-1/2 lg:w-1/3">

                                                <img class="cursor-pointer rounded-lg shadow-lg object-cover w-full h-full"
                                                    src="{{ asset('/storage/' . $post->photo_name) }}"
                                                    alt="Blog Image">
                                            </div>
                                        
                                    </div>
                                </a>

                                <p class=" postContent text-base text-gray-200 mt-2"
                                    data-postvalue="{{ $postContent['post'] }}"></p>
                                <div class="mt-4 p-4 rounded-lg w-auto postcomments bg-gray-100 dark:bg-gray-800 dark:border-gray-700"
                                    data-commentid="{{ $post->id }}">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Comments
                                    </h3>
                                    @if (count($post->comments) > 0)
                                        @foreach ($post->comments as $comment)
                                            <div class="border-b border-gray-300 py-2 dark:border-gray-700">
                                                <p class="text-gray-700 dark:text-gray-300">{{ $comment->comment }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">—
                                                    {{ $comment->user->name }}</p>
                                                @if ($comment->user_id === auth()->id())
                                                    <form id="commentdlt-form">
                                                        <button
                                                            class="text-red-600 underline dark:text-red-400 cmtdltbtn"
                                                            data-cmtdltbtnid="{{ $comment->id }}">Delete</button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-gray-700 dark:text-gray-300">No Comments on This Post Now</p>
                                    @endif
                                </div>

                                <form id="comment-form">
                                    <p class="text-red-500 text-sm mt-1 err" id="errcmt"></p>
                                    <x-text-input type="text" data-commentid="{{ $post->id }}" id="comment"
                                        name="comment"
                                        class="rounded-lg border-gray-200 p-2 mt-2 mr-1 comment dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                        placeholder="Comment" />

                                    <div class="mt-4 sm:flex-row inline-flex">
                                        <x-primary-button type="submit" data-comment-btnid="{{ $post->id }}"
                                            class="comment-btn mr-2  dark:hover:bg-blue-700 focus:ring-blue-500 dark:active:bg-blue-400 dark:bg-blue-400">
                                            Comment
                                        </x-primary-button>
                                        <x-primary-button data-show-comment-btnid="{{ $post->id }}" type="button"
                                            class="show-comment-btn dark:hover:bg-green-700 dark:bg-green-600 dark:text-gray-200 dark:text-gray-200">
                                            Comments
                                        </x-primary-button>
                                    </div>
                                </form>
                                <div class="flex inline-flex">
                                    @if ($post->user_id === auth()->id())
                                        <form id="dlt-form">
                                            <x-danger-button class="mr-2 mt-2 dlt-btn dark:text-gray-100"
                                                data-dltbtnid="{{ $post->id }}">
                                                Delete
                                            </x-danger-button>
                                        </form>
                                        <a href="{{ route('blogs.edit', $post->slug) }}">
                                            <x-secondary-button class="mt-2 inline-block">
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
