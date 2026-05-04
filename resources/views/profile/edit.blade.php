@extends('layouts.app')

@section('content')

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6f9;
        margin: 20px;
    }

    .profile-container {
        width: 50%;
        margin: 40px auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .profile-container h2 {
        font-size: 18px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 4px;
    }

    .profile-container p.subtitle {
        font-size: 13px;
        color: #888;
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #555;
        margin-bottom: 6px;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 13px;
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: #007bff;
    }

    .avatar-preview {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ccc;
        margin-top: 10px;
        display: block;
    }

    .divider {
        border: none;
        border-top: 1px solid #eee;
        margin: 24px 0;
    }

    .btn-save {
        background: #007bff;
        color: white;
        padding: 8px 24px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 10px;
    }

    .btn-save:hover {
        background: #0056b3;
    }

    .btn-password {
        background: #343a40;
        color: white;
        padding: 8px 24px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 10px;
    }

    .btn-password:hover {
        background: #1a1f24;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 10px 14px;
        border-radius: 6px;
        margin-bottom: 16px;
        font-size: 13px;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 10px 14px;
        border-radius: 6px;
        margin-bottom: 16px;
        font-size: 13px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 4px;
    }

    .section-sub {
        font-size: 12px;
        color: #888;
        margin-bottom: 16px;
    }
</style>

<div class="profile-container">

    <h2>Profile</h2>

    {{-- Success / Error messages --}}
    @if(session('status') === 'profile-updated')
        <div class="alert-success">Profile updated successfully!</div>
    @endif

    @if(session('status') === 'password-updated')
        <div class="alert-success">Password updated successfully!</div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Update Name, Email & Avatar --}}
    <p class="section-title">Profile Information</p>
    <p class="section-sub">Update your account's profile information and email address.</p>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label>Name</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', auth()->user()->name) }}"
                   required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', auth()->user()->email) }}"
                   required>
        </div>

        <div class="form-group">
            <label>Avatar</label>
            <input type="file"
                   name="avatar"
                   accept="image/*"
                   onchange="previewAvatar(event)">

            {{-- Avatar Preview --}}
            <img id="avatar-preview"
                 class="avatar-preview"
                 src="{{ auth()->user()->avatar
                     ? asset('storage/' . auth()->user()->avatar)
                     : asset('avatars/defaultprofile.png') }}"
                 alt="Avatar Preview">
        </div>

        <button type="submit" class="btn-save">SAVE</button>
    </form>

    <hr class="divider">

    {{-- Update Password --}}
    <p class="section-title">Update Password</p>
    <p class="section-sub">Ensure your account is using a long, random password to stay secure.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password">
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password">
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation">
        </div>

        <button type="submit" class="btn-password">Update Password</button>
    </form>

</div>

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection