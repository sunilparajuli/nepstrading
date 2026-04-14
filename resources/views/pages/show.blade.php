@extends('layouts.app')

@section('meta_title', $page->meta_title ?: $page->title . ' - ' . config('app.name'))
@section('meta_description', $page->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($page->content), 155))
@section('meta_keywords', $page->meta_keywords ?: $page->title . ', information')
@section('og_type', 'website')
@section('og_title', $page->og_title ?: $page->meta_title ?: $page->title)
@section('og_description', $page->og_description ?: $page->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($page->content), 155))
@section('og_image', $page->og_image ? asset($page->og_image) : asset('images/og-default.jpg'))

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 48px 16px 64px;">
    <h1 style="font-family: 'DM Serif Display', serif; font-size: 36px; font-weight: 400; color: hsl(var(--fg)); margin: 0 0 32px 0; line-height: 1.2;">{{ $page->title }}</h1>
    
    <div style="font-size: 16px; line-height: 1.8; color: hsl(var(--muted-fg));">
        {!! $page->content !!}
    </div>
</div>
@endsection
