@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 48px 16px 64px;">
    <h1 style="font-family: 'DM Serif Display', serif; font-size: 36px; font-weight: 400; color: hsl(var(--fg)); margin: 0 0 32px 0; line-height: 1.2;">{{ $page->title }}</h1>
    
    <div style="font-size: 16px; line-height: 1.8; color: hsl(var(--muted-fg));">
        {!! nl2br(e($page->content)) !!}
    </div>
</div>
@endsection
