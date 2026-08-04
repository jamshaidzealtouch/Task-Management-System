@extends('layouts.app')

@section('content')

<h2>Project Details</h2>

<p>Title: {{ $project->title }}</p>

<p>Description: {{ $project->description }}</p>

<p>Status: {{ $project->status }}</p>

@endsection