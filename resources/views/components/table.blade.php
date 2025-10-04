<!-- Table component -->
@props(['headers' => []])

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300 border border-gray-300">
                    <thead class="bg-gradient-to-br from-blue-50 to-white">
                        <tr>
                            @foreach($headers as $header)
                                <th scope="col" class="px-4 py-4 text-left text-sm font-semibold text-gray-900 border-x border-gray-200">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        {{ $slot }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>