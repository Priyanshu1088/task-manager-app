@extends('manager.layout')

@section('content')

<h2 class="mb-4">{{ isset($editProject) ? 'Update Project' : 'Create Project' }}</h2>

<div class="card p-4">

<form action="{{ isset($editProject) 
    ? route('manager.projects.update',$editProject->id) 
    : route('manager.projects.store') }}"
method="POST">

@csrf

@if(isset($editProject))
@method('PUT')
@endif

<div class="mb-3">
    <label>Project Name</label>

    <input type="text"
    name="name"
    class="form-control"
    value="{{ isset($editProject) ? $editProject->name : '' }}"
    required>

    </div>

    <button class="btn btn-success">

    {{ isset($editProject) ? 'Update Project' : 'Create Project' }}

    </button>

    </form>

</div>

@endsection