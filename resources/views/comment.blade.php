@foreach ($posts as $post)
    <div id="post"
        class="flex flex-col justify-between space-x-4 p-4 mb-4 mt-4 rounded-lg shadow-lg bg-white
               dark:bg-gray-900  dark:border-gray-700 post">

        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $post->user->name }}</span>
        <small
            class="ml-2 text-sm text-gray-400 dark:text-gray-500">{{ $post->created_at->format('j M Y, g:i a') }}</small>

        @unless ($post->created_at->eq($post->updated_at))
            <small class="text-sm text-gray-400 dark:text-gray-500"> &middot; {{ __('edited') }}</small>
        @endunless
        @php
            $postContent = json_decode($post->posts, true);
        @endphp

        <a href="{{ route('blogs.show', $post->slug) }}" class="mt-1">
            <p class="posttitle text-xl font-bold text-gray-900 underline dark:text-gray-100">{{ $postContent['title'] }}
            </p>
            @if ($post->photo_name)
                <div class="flex flex-wrap gap-4 mt-3">
                    <div class="relative w-full md:w-1/2 lg:w-1/3">
                        <img class="cursor-pointer rounded-lg shadow-lg object-cover w-full h-full"
                            src="{{ asset('/storage/' . $post->photo_name) }}" alt="Blog Image">
                    </div>

                </div>
            @endif
        </a>
        @auth
            <p id="postContent" class="postContent text-base text-gray-700 mt-2 dark:text-gray-300"
                data-postvalue="{{ Str::words($postContent['post'], 20, '<strong>...Read More</strong>') }}"></p>
        @else
            <p id="postContent" class="postContent text-base text-gray-700 mt-2 dark:text-gray-300"
                data-postvalue="{!! nl2br(e($postContent['post'])) !!}"></p>
        @endauth
        <div class="mt-4 p-4 rounded-lg w-auto postcomments bg-gray-100 dark:bg-gray-800 dark:border-gray-700"
            data-commentid="{{ $post->id }}">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Comments</h3>
            @if (count($post->comments) > 0)
                @foreach ($post->comments as $comment)
                    <div class="border-b border-gray-300 py-2 dark:border-gray-700">
                        <p class="text-gray-700 dark:text-gray-300">{{ $comment->comment }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">— {{ $comment->user->name }}</p>
                        @if ($comment->user_id === auth()->id())
                            <form id="commentdlt-form">
                                <button class="text-red-600 underline dark:text-red-400 cmtdltbtn"
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
            <p class="text-red-500 text-sm mt-1 err errcmt"></p>
            @auth

                <x-text-input type="text" data-commentid="{{ $post->id }}" id="comment" name="comment"
                    class="rounded-lg border-gray-200 p-2 mt-2 mr-1 comment dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                    placeholder="Comment" />
            @endauth
            <div class="mt-4 sm:flex-row inline-flex">
                @auth
                    <x-primary-button type="submit" data-comment-btnid="{{ $post->id }}"
                        class="comment-btn mr-2 dark:hover:bg-blue-700 focus:ring-blue-500 dark:active:bg-blue-400 dark:bg-blue-400">
                        Comment
                    </x-primary-button>

                @endauth
                <x-primary-button data-show-comment-btnid="{{ $post->id }}" type="button"
                    class="show-comment-btn dark:hover:bg-green-700 dark:bg-green-600 dark:text-gray-200 dark:text-gray-200">

                    Comments
                </x-primary-button>
            </div>
        </form>
        <div class="flex inline-flex">
            @if ($post->user_id === auth()->id())
                <form id="dlt-form">
                    <x-danger-button class="mr-2 mt-2 dlt-btn dark:text-gray-100" data-dltbtnid="{{ $post->slug }}">
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
