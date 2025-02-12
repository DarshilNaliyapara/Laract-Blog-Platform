@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'text-md border-gray-300 text-White dark:border-gray-700 dark:bg-gray-200 dark:text-black focus:border-green-500 dark:focus:border-green-600 focus:ring-green-500 dark:focus:ring-green-600 rounded-md shadow-sm']) }}>
