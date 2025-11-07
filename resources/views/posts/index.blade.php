@extends('layouts.app')

@section('title') Posts @endsection

@section('content')
{{-- Create Post Button --}}

<div class="mt-4 text-center">
    <a href="{{ route('posts.create') }}" class="btn btn-success">Create Post</a>
</div>

{{-- Table --}}

<table class="table m-4">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Title</th>
      <th scope="col">Posted By</th>
      <th scope="col">Created At</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($posts as $post)
    {{-- @dd($posts, $post) --}}
    <tr>
      <th scope="row">{{ $post->id }}</th>
      <td>{{ $post->title }}</td>
      <td>{{ $post->description }}</td>
      <td>{{ $post->created_at }}</td>
      <td>
        <div>
            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info">View</a>
            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit</a>
            <form method="POST" action="{{ route('posts.destroy', $post->id) }}" class="delete-form" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger delete-btn" data-id="{{ $post->id }}">Delete</button>
            </form>

        </div>
    </td>
    </tr>
    @endforeach
  </tbody>
</table>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteConfirmLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this post?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let targetForm = null;

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            targetForm = this.closest('form');
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (targetForm) targetForm.submit();
    });
});
</script>
@endsection
