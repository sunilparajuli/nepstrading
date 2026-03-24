@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
<div class="bg-white rounded-sm shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-50 flex items-center gap-4">
        <a href="{{ route('admin.reviews.index') }}" class="text-sm font-bold {{ !request('status') ? 'text-blue-600 underline' : 'text-gray-500' }}">All</a>
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="text-sm font-bold {{ request('status') === 'pending' ? 'text-blue-600 underline' : 'text-gray-500' }}">Pending</a>
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="text-sm font-bold {{ request('status') === 'approved' ? 'text-blue-600 underline' : 'text-gray-500' }}">Approved</a>
        <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" class="text-sm font-bold {{ request('status') === 'rejected' ? 'text-blue-600 underline' : 'text-gray-500' }}">Rejected</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Rating</th>
                    <th class="px-6 py-4">Review</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($reviews as $review)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold">{{ $review->product->name ?? 'Deleted' }}</td>
                    <td class="px-6 py-4">
                        {{ $review->user->name ?? 'Unknown' }}
                        @if($review->verified_purchase)
                            <span class="text-[10px] bg-green-100 text-green-700 px-1 rounded ml-1">Verified</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    </td>
                    <td class="px-6 py-4 max-w-xs truncate">
                        @if($review->title) <strong>{{ $review->title }}</strong> - @endif
                        {{ $review->content }}
                    </td>
                    <td class="px-6 py-4">
                        @if($review->status === 'approved')
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Approved</span>
                        @elseif($review->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Pending</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            @if($review->status !== 'approved')
                            <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button class="text-green-600 text-[11px] hover:underline">Approve</button>
                            </form>
                            @endif
                            @if($review->status !== 'rejected')
                            <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button class="text-orange-600 text-[11px] hover:underline">Reject</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-500 text-[11px] hover:underline" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $reviews->appends(request()->query())->links() }}</div>
</div>
@endsection
