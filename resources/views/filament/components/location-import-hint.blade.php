<div class="space-y-4 text-sm">
    <p>{{ __('common.location_import_hint_download') }}
        <a
            href="{{ asset('import-samples/locations-sample.csv') }}"
            download
            class="font-medium underline text-primary-600 dark:text-primary-400"
        >
            {{ __('common.location_import_sample_download') }}
        </a>
    </p>

    <div class="space-y-1 text-gray-600 dark:text-gray-300">
        <p>{{ __('common.location_import_hint_structure') }}</p>
        <ul class="pl-5 space-y-1 list-disc">
            <li>{{ __('common.location_import_hint_step_country') }}</li>
            <li>{{ __('common.location_import_hint_step_city') }}</li>
            <li>{{ __('common.location_import_hint_step_district') }}</li>
            <li>{{ __('common.location_import_hint_step_neighborhood') }}</li>
        </ul>
    </div>

    <p class="text-xs font-semibold text-gray-500 uppercase">{{ __('common.location_import_hint_examples') }}</p>

    <div class="w-full mx-auto overflow-hidden border border-gray-200 rounded-md dark:border-gray-700 lg:w-11/12">
        <table class="w-full text-xs text-left">
            <thead class="text-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white">
                <tr class="divide-x divide-gray-200 dark:divide-gray-800">
                    <th class="px-3 py-2 font-semibold text-gray-700 dark:text-white">{{ __('common.location_import_column_country') }}</th>
                    <th class="px-3 py-2 font-semibold text-gray-700 dark:text-white">{{ __('common.location_import_column_city') }}</th>
                    <th class="px-3 py-2 font-semibold text-gray-700 dark:text-white">{{ __('common.location_import_column_district') }}</th>
                    <th class="px-3 py-2 font-semibold text-gray-700 dark:text-white">{{ __('common.location_import_column_neighborhood') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900">
                <tr class="bg-white divide-x divide-gray-100 dark:bg-gray-900 dark:divide-gray-800">
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Türkiye</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">İstanbul</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Sultangazi</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">50. Yıl Mahallesi</td>
                </tr>
                <tr class="divide-x divide-gray-100 bg-gray-50 dark:bg-gray-950 dark:divide-gray-800">
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Türkiye</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">İstanbul</td>
                    <td class="px-3 py-2 text-gray-400 dark:text-gray-500">—</td>
                    <td class="px-3 py-2 text-gray-400 dark:text-gray-500">—</td>
                </tr>
                <tr class="bg-white divide-x divide-gray-100 dark:bg-gray-900 dark:divide-gray-800">
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Türkiye</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">İstanbul</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Gaziosmanpaşa</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Barbaros Hayrettin Paşa Mahallesi</td>
                </tr>
                <tr class="divide-x divide-gray-100 bg-gray-50 dark:bg-gray-950 dark:divide-gray-800">
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Türkiye</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Ankara</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Çankaya</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Kızılay Mahallesi</td>
                </tr>
                <tr class="bg-white divide-x divide-gray-100 dark:bg-gray-900 dark:divide-gray-800">
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Türkiye</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">İzmir</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Konak</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Alsancak Mahallesi</td>
                </tr>
                <tr class="divide-x divide-gray-100 bg-gray-50 dark:bg-gray-950 dark:divide-gray-800">
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Türkiye</td>
                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">Antalya</td>
                    <td class="px-3 py-2 text-gray-400 dark:text-gray-500">—</td>
                    <td class="px-3 py-2 text-gray-400 dark:text-gray-500">—</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
