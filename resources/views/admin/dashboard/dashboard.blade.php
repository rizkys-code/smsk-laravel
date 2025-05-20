@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-0">Dashboard</h1>
    <p class="text-muted">Welcome to your dashboard overview.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card">
            <div class="d-flex justify-content-between">
                <p class="mb-0 fw-medium">Total Surat Masuk</p>
            </div>
            <div class="mt-3">
                <p class="h2 fw-bold mb-0">24</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card">
            <div class="d-flex justify-content-between">
                <p class="mb-0 fw-medium">Total Surat Keluar</p>
            </div>
            <div class="mt-3">
                <p class="h2 fw-bold mb-0">18</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card">
            <div class="d-flex justify-content-between">
                <p class="mb-0 fw-medium">Revisi Pending</p>
            </div>
            <div class="mt-3">
                <p class="h2 fw-bold mb-0">5</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card">
            <div class="d-flex justify-content-between">
                <p class="mb-0 fw-medium">Berhasil Di Setujui</p>
            </div>
            <div class="mt-3">
                <p class="h2 fw-bold mb-0">42</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h3 class="card-title">Recent Activity</h3>
        <div class="mt-4">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="activity-content">
                    <p class="mb-0 fw-medium">New Surat Masuk</p>
                    <p class="mb-0 small">Received from Department A</p>
                </div>
                <div class="activity-time">2 hours ago</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="bi bi-send"></i>
                </div>
                <div class="activity-content">
                    <p class="mb-0 fw-medium">Surat Keluar Sent</p>
                    <p class="mb-0 small">Sent to Department B</p>
                </div>
                <div class="activity-time">Yesterday</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div class="activity-content">
                    <p class="mb-0 fw-medium">Revisi Completed</p>
                    <p class="mb-0 small">Document #1234 revised</p>
                </div>
                <div class="activity-time">3 days ago</div>
            </div>
        </div>
    </div>
</div>
@endsection
