@extends('layouts.app')

@section('title')
    {{ __('Update Post') }}
@endsection

@section('content')

    <form method="POST" action="{{ route('posts.update', $post['id']) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Title (English)') }}</label>
                    <input name="title" type="text" class="form-control" value="{{ old('title', $post->getRawOriginal('title')) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Title (Arabic)') }}</label>
                    <input name="title_ar" type="text" class="form-control" value="{{ old('title_ar', $post->title_ar) }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Description (English)') }}</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $post->getRawOriginal('description')) }}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Description (Arabic)') }}</label>
                    <textarea name="description_ar" class="form-control" rows="4">{{ old('description_ar', $post->description_ar) }}</textarea>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">{{ __('Post Creator') }}</label>
            <select name="post_creator" class="form-control">
                @foreach ($users as $user)
                    <option {{-- @if ($user->id == $post->user_id)
                        selected
                    @endIf --}} @selected($user->id == $post->user_id) value="{{ $user->id }}">
                        {{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">{{ __('Post Image') }}</label>
            <input name="image" type="file" class="form-control">
            @if ($post->image)
                <div class="mt-2 text-center">
                    <p>{{ __('Current Image') }}:</p>

                    @if (Str::startsWith($post->image, ['http://', 'https://']))
                        <img src="{{ $post->image }}" alt="Post Image" style="width: 200px; height: auto;">
                    @else
                        <img src="{{ Storage::url($post->image) }}" alt="Post Image" style="width: 200px; height: auto;">
                    @endif
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">{{ __('Tags (comma separated)') }}</label>
            <input name="tags" type="text" class="form-control" value="{{ old('tags', $post->tags->pluck('name')->implode(', ')) }}" placeholder="e.g. laravel, coding, web">
        </div>

        <button class="btn btn-primary">{{ __('Submit') }}</button>
    </form>


@endsection
