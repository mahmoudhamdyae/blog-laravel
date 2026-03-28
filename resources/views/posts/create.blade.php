@extends('layouts.app')

@section('title') Create @endsection

@section('content')

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" type="text" class="form-control" value="{{ old('title') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Post Creator</label>
            <select name="post_creator" class="form-control">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('post_creator') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Post Image</label>
            <input name="image" type="file" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Tags (comma separated)</label>
            <input name="tags" type="text" class="form-control" value="{{ old('tags') }}" placeholder="e.g. laravel, coding, web">
        </div>

        <button class="btn btn-success">Submit</button>
    </form>


@endsection
