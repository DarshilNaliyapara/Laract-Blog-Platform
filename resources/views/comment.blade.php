@foreach ($posts as $post)
    <div id="post"
        class="flex flex-col justify-between space-x-4 p-4 mb-4 mt-4 rounded-lg shadow-md bg-white
               dark:bg-gray-900  dark:border-gray-700">

        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $post->user->name }}</span>
        <small class="ml-2 text-sm text-gray-400 dark:text-gray-500">{{ $post->created_at->format('j M Y, g:i a') }}</small>

        @unless ($post->created_at->eq($post->updated_at))
            <small class="text-sm text-gray-400 dark:text-gray-500"> &middot; {{ __('edited') }}</small>
        @endunless
        @php
            $postContent = json_decode($post->posts, true);
        @endphp

        <a href="{{ route('blogs.show', $post->id) }}" class="mt-1">
            <p class="text-xl font-bold text-gray-900 underline dark:text-gray-100">{{ $postContent['title'] }}</p>
        </a>
        <p class="text-base text-gray-700 mt-2 dark:text-gray-300">{{ $postContent['post'] }}</p>

        <div class="mt-4 p-4 rounded-lg w-auto postcomments bg-gray-100 dark:bg-gray-800 dark:border-gray-700" data-commentid="{{ $post->id }}">
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
            <x-text-input type="text" data-commentid="{{ $post->id }}" id="comment" name="comment"
                class="rounded-lg border-gray-200 p-2 mt-2 comment dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                placeholder="Comment"/>

            <div class="flex items-center space-x-2 mt-4">
                <x-primary-button type="submit" data-comment-btnid="{{ $post->id }}"
                    class="comment-btn  dark:hover:bg-blue-700 focus:ring-blue-500 dark:active:bg-blue-400 dark:bg-blue-400">
                    Comment
                </x-primary-button>
                <x-primary-button data-show-comment-btnid="{{ $post->id }}" type="button"
                    class="show-comment-btn dark:hover:bg-green-700 dark:bg-green-600 dark:text-gray-200 dark:text-gray-200">
                    Comments
                </x-primary-button>
            </form>
                @if ($post->user_id === auth()->id())
                    <form id="dlt-form">
                        <x-danger-button class="dark:bg-red-700 dark:hover:bg-red-600 dark:text-white dlt-btn"
                            data-dltbtnid="{{ $post->id }}">
                            Delete
                        </x-danger-button>
                    </form>

                    <a href="{{ route('blogs.edit', $post->id) }}">
                        <x-secondary-button class="dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            Edit
                        </x-secondary-button>
                    </a>
                @endif
            </div>

    </div>
@endforeach
