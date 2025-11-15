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
    <p class="card-text">Created At: {{ $post-> user ? $post->postCreator->created_at : 'not_found' }}.</p>
  </div>
</div>

@endsection
