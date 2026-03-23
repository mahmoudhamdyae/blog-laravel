@extends('layouts.app')

@section('title') Post Details @endsection

@section('content')

<div class="card m-4">
  <div class="card-header">
    Post Info
  </div>
  <div class="card-body">
    <h5 class="card-title">Title: {{ $post->title }}</h5>
    <p class="card-text">Description: {{ $post->description }}</p>
  </div>
</div>

<div class="card m-4">
  <div class="card-header">
    Post Creator Info
  </div>
  <div class="card-body">
    <h5 class="card-title">Name: {{ $post-> user ? $post->user->name : 'not_found' }}</h5>
    <p class="card-text">Email: {{ $post-> user ? $post->user->email : 'not_found' }}</p>
    <p class="card-text">Created At: {{ $post-> user ? $post->user->created_at->diffForHumans() : 'not_found' }}.</p>
  </div>
</div>

<div class="card m-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>
      Comments
      <span class="badge bg-secondary ms-1">{{ $post->comments()->count() }}</span>
    </span>
    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addCommentModal">
      Add Comment
    </button>
  </div>
  <div class="card-body">
    @forelse ($comments as $comment)
      <div class="d-flex justify-content-between align-items-start border-bottom py-2">
        <p class="mb-0">{{ $comment->body }}</p>
        <button
          class="btn btn-sm btn-danger ms-3"
          data-bs-toggle="modal"
          data-bs-target="#deleteCommentModal"
          data-comment-id="{{ $comment->id }}"
        >
          Delete
        </button>
      </div>
    @empty
      <p class="text-muted mb-0">No comments yet.</p>
    @endforelse

    <div class="mt-3">
        {{ $comments->links() }}
    </div>
  </div>
</div>

{{-- Add Comment Modal --}}
<div class="modal fade" id="addCommentModal" tabindex="-1" aria-labelledby="addCommentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCommentLabel">Add Comment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('comments.store', $post->id) }}">
        @csrf
        <div class="modal-body">
          <textarea name="body" class="form-control" rows="4" placeholder="Write your comment..."></textarea>
          @error('body')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Delete Comment Modal --}}
<div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteCommentLabel">Delete Comment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this comment?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteCommentForm" method="POST" action="">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteCommentModal = document.getElementById('deleteCommentModal');
    deleteCommentModal.addEventListener('show.bs.modal', function (event) {
        const commentId = event.relatedTarget.getAttribute('data-comment-id');
        document.getElementById('deleteCommentForm').action = '/comments/' + commentId;
    });
});
</script>
@endsection
