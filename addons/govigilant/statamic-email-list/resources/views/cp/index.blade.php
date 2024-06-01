@extends('statamic::layout')

@section('content')
    <div class="flex w-full gap-4">
        <div class="card flex-1 rounded-xl flex justify-between">
            <h1 class="font-bold">@lang('Confirmed E-Mail Addresses')</h1>
            <div class="flex flex-col text-right">{{ $confirmed }}</div>
        </div>

        <div class="card flex-1 rounded-xl flex justify-between">
            <h1 class="font-bold">@lang('Unconfirmed E-Mail Addresses')</h1>
            <div class="flex flex-col text-right">{{ $unconfirmed }}</div>
        </div>
    </div>

    <div class="card p-0 content mt-4">
        <div class="py-6 px-6 border-b">
            <h1 class="text-2xl font-bold">@lang('Submitted E-Mail Addresses')</h1>
        </div>
        <table class="min-w-full divide-y divide-gray-300 pb-2">
            <thead>
            <tr>
                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">@lang('List')</th>
                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">@lang('E-Mail')</th>
                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">@lang('Confirmation Mail Sent')</th>
                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">@lang('Confirmed')</th>
                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">@lang('Submitted At')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($entries as $entry)
                <tr class="even:bg-gray-50 last:rounded-full">
                    <td class="py-3.5 pl-4 pr-3 text-left text-sm text-gray-900">{{ $entry->list }}</td>
                    <td class="py-3.5 pl-4 pr-3 text-left text-sm text-gray-900 sm:pl-0">{{ $entry->email }}</td>
                    <td class="py-3.5 pl-4 pr-3 text-left text-sm text-gray-900 sm:pl-0">{{ $entry->mail_sent ? 'Yes' : 'No' }}</td>
                    <td class="py-3.5 pl-4 pr-3 text-left text-sm text-gray-900 sm:pl-0">{{ $entry->confirmed ? 'Yes' : 'No' }}</td>
                    <td class="py-3.5 pl-4 pr-3 text-left text-sm text-gray-900 sm:pl-0">{{ $entry->created_at->toDateTimeString() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $entries->links('emaillist::cp.components.paginator') }}
        </div>
    </div>
@endsection
