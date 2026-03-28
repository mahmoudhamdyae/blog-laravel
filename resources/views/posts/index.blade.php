@extends('layouts.app')

@section('title')
    Posts
@endsection

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
                    {{-- @dd($post->user, $post->post()->where('id', $post->user_id)->first()) --}}
                    <td>{{ $post->user ? $post->user->name : 'not_found' }}</td>
                    {{-- <td>{{ $post->created_at->format('Y-m-d') }}</td> --}}
                    <td>{{ $post->humanReadableDate }}</td>
                    <td>
                        <div>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info">View</a>
                            <button type="button" class="btn btn-secondary view-ajax-btn"
                                data-id="{{ $post->id }}">View Data</button>
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit</a>
                            <form method="POST" action="{{ route('posts.destroy', $post->id) }}" class="delete-form"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger delete-btn"
                                    data-id="{{ $post->id }}">Delete</button>
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

    {{-- View Ajax Modal --}}
    <div class="modal fade" id="ajaxViewModal" tabindex="-1" aria-labelledby="ajaxViewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="ajaxViewLabel">Post Details (Ajax)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalLoading" class="text-center py-3">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="modalContent" style="display: none;">
                        <p><strong>Title:</strong> <span id="viewTitle"></span></p>
                        <p><strong>Description:</strong> <span id="viewDescription"></span></p>
                        <hr>
                        <p><strong>Username:</strong> <span id="viewUsername"></span></p>
                        <p><strong>Email:</strong> <span id="viewEmail"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    {{-- <div class="d-flex justify-content-center mt-3">
    {{ $posts->links('pagination::bootstrap-5') }}
</div> --}}

    <div class="d-flex justify-content-center mt-3">
        {{ $posts->links() }}
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let targetForm = null;

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    targetForm = this.closest('form');
                    const modal = new bootstrap.Modal(document.getElementById(
                    'deleteConfirmModal'));
                    modal.show();
                });
            });

            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (targetForm) targetForm.submit();
            });

            // View Ajax logic
            const ajaxModal = new bootstrap.Modal(document.getElementById('ajaxViewModal'));
            const modalLoading = document.getElementById('modalLoading');
            const modalContent = document.getElementById('modalContent');

            document.querySelectorAll('.view-ajax-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const postId = this.getAttribute('data-id');

                    // Reset modal state
                    modalLoading.style.display = 'block';
                    modalContent.style.display = 'none';
                    ajaxModal.show();

                    // Fetch post data
                    fetch(`/posts/${postId}/json`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('viewTitle').innerText = data.title;
                            document.getElementById('viewDescription').innerText = data
                                .description;
                            document.getElementById('viewUsername').innerText = data.user_name;
                            document.getElementById('viewEmail').innerText = data.user_email;

                            modalLoading.style.display = 'none';
                            modalContent.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error fetching post data:', error);
                            alert('Failed to load post data');
                            ajaxModal.hide();
                        });
                });
            });
        });
    </script>
@endsection
