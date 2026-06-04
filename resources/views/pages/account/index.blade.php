@extends('layout.master')

@section('title', 'Your Account')



@section('content')
  <section class="py-5">
    <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
      <div class="card profile-card border">
        <div class="profile-header text-center">
        <h2 class="profile-title">Account Details</h2>
        </div>

        <div class="profile-body text-uppercase">
        <div class="mb-3">
          <div class="field-label">Title</div>
          <div class="field-value">{{ $user['title'] ?? 'N/A' }}</div>
        </div>

        <div class="divider"></div>

        <div class="mb-3">
          <div class="field-label">Name</div>
          <div class="field-value">{{ $user['name'] ?? 'N/A' }}</div>
        </div>

        <div class="divider"></div>

        <div class="mb-3">
          <div class="field-label">Email</div>
          <div class="field-value">{{ $user['email'] ?? 'N/A' }}</div>
        </div>

        <div class="divider"></div>

        <div class="mb-0">
          <div class="field-label">Mobile</div>
          <div class="field-value">+968 {{ $user['mobile'] ?? 'N/A' }}</div>
        </div>

        <div class="divider"></div>

        <div class="mb-0">
          <div class="field-label">Contact Preference</div>
          <div class="field-value"> {{ $user['contact_method'] ?? 'N/A' }}</div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
  </section>
@endsection