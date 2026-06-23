@extends('layouts.admin')

@section('title', 'Modifier un événement')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Modifier l'événement</h1>
        <div>
            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="{{ route('admin.events.calendar') }}" class="btn btn-outline-secondary">
                <i class="fas fa-calendar-alt"></i> Calendrier
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $event->type) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date début</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', optional($event->start_date)->format('Y-m-d\TH:i')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date fin</label>
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', optional($event->end_date)->format('Y-m-d\TH:i')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Lieu</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $event->location) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Lien lieu (URL)</label>
                        <input type="url" name="location_url" class="form-control" value="{{ old('location_url', $event->location_url) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Responsable</label>
                        <input type="text" class="form-control" value="{{ $event->responsible?->name ?? ($event->responsible_id ?? '') }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select" required>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $event->status) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Budget</label>
                        <input type="number" name="budget" min="0" step="0.01" class="form-control" value="{{ old('budget', $event->budget) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Coût réel</label>
                        <input type="number" name="actual_cost" min="0" step="0.01" class="form-control" value="{{ old('actual_cost', $event->actual_cost) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Poster</label>
                        <input type="file" name="poster" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $event->notes) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

