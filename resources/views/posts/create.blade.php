@extends('layouts.app')

@section('title') {{ __('Create Post') }} @endsection

@section('content')

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Title (English)') }}</label>
                    <input name="title" type="text" class="form-control" value="{{ old('title') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Title (Arabic)') }}</label>
                    <input name="title_ar" type="text" class="form-control" value="{{ old('title_ar') }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Description (English)') }}</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Description (Arabic)') }}</label>
                    <textarea name="description_ar" class="form-control" rows="4">{{ old('description_ar') }}</textarea>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">{{ __('Post Creator') }}</label>
            <select name="post_creator" class="form-control">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('post_creator') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">{{ __('Post Image') }}</label>
            <input name="image" type="file" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">{{ __('Tags (comma separated)') }}</label>
            <input name="tags" type="text" class="form-control" value="{{ old('tags') }}" placeholder="e.g. laravel, coding, web">
        </div>

        <button class="btn btn-success">{{ __('Submit') }}</button>
    </form>


@endsection
