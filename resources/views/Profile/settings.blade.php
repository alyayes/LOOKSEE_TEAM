@extends('layouts.main')

@section('title', 'Profile Settings | LOOKSEE')

@section('head_scripts')
    <link rel="stylesheet" href="{{ asset('assets/css/settingProfile.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection

@section('content')

    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">Account settings</h4>
        
        {{-- Simulasi Pesan Sesi --}}
        @if (session('success'))
            <div class="container mt-3"><div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>
        @endif
        @if (session('error'))
            <div class="container mt-3"><div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>
        @endif

        {{-- Form Action diatur ke route Laravel --}}
        <form action="{{ url('/update-profile') }}" method="POST" enctype="multipart/form-data">
            @csrf {{-- Token CSRF Laravel Wajib --}}
            <div class="card overflow-hidden">
                <div class="row no-gutters row-bordered row-border-light">
                    
                    {{-- Navigasi Sidebar --}}
                    <div class="col-md-3 pt-0">
                        <div class="list-group list-group-flush account-settings-links">
                            <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">General</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">Change password</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-info">Info</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-social-links">Social links</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-connections">Connections</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-notifications">Notifications</a>
                        </div>
                    </div>

                    {{-- Konten Tab --}}
                    <div class="col-md-9">
                        <div class="tab-content">
                            
                            <div class="tab-pane fade active show" id="account-general">
                                <div class="card-body media align-items-center">
                                    <img src="{{ asset('assets/images/profile/' . ($userData['profile_picture'] ?? '23d.jpeg')) }}" 
                                    alt="Profile Picture" 
                                    style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; display: block;">
                                    <div class="media-body ml-4">
                                        <label class="btn btn-outline-primary">
                                            Upload new photo
                                            <input type="file" class="account-settings-fileinput" name="profile_picture">
                                        </label>
                                        <button type="button" class="btn btn-default md-btn-flat" onclick="document.querySelector('.account-settings-fileinput').value = null;">Reset</button>
                                        <div class="text-light small mt-1">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                    </div>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control mb-1" value="{{ $user['username'] }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user['name'] }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">E-mail</label>
                                        <input type="email" name="email" class="form-control mb-1" value="{{ $user['email'] }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="account-change-password">
                                <div class="card-body pb-2">
                                    {{-- input password lama, baru, konfirmasi --}}
                                    <div class="form-group"><label class="form-label">Current password</label><input type="password" name="current_password" class="form-control"></div>
                                    <div class="form-group"><label class="form-label">New password</label><input type="password" name="new_password" class="form-control"></div>
                                    <div class="form-group"><label class="form-label">Repeat new password</label><input type="password" name="confirm_password" class="form-control"></div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="account-info">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Bio</label>
                                        <textarea name="bio" class="form-control" rows="5">{{ $user['bio'] }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Birthday</label>
                                        <input type="date" name="birthday" class="form-control" value="{{ $user['birthday'] }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <select class="custom-select" name="country">
                                            <option @if($user['country'] == 'Indonesia') selected @endif>Indonesia</option>
                                            <option @if($user['country'] == 'USA') selected @endif>USA</option>
                                            <option @if($user['country'] == 'Canada') selected @endif>Canada</option>
                                            <option @if($user['country'] == 'Germany') selected @endif>Germany</option>
                                            <option @if($user['country'] == 'France') selected @endif>France</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $user['phone'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="account-social-links">
                                <div class="card-body pb-2">
                                    <div class="form-group"><label class="form-label">Twitter</label><input type="text" name="twitter" class="form-control" value="{{ $user['twitter'] }}"></div>
                                    <div class="form-group"><label class="form-label">Facebook</label><input type="text" name="facebook" class="form-control" value="{{ $user['facebook'] }}"></div>
                                    <div class="form-group"><label class="form-label">Instagram</label><input type="text" name="instagram" class="form-control" value="{{ $user['instagram'] }}"></div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="account-connections">
                                <div class="card-body">
                                    {{-- ... Connections ... --}}
                                </div>
                                <hr class="border-light m-0">
                                {{-- ... Google dan Facebook Connection ... --}}
                            </div>
                            <div class="tab-pane fade" id="account-notifications">
                                <div class="card-body pb-2">
                                    {{-- ... Notifications ... --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary">Save changes</button>&nbsp;
                <button type="button" class="btn btn-default" onclick="window.location.href='{{ route('profile.settings') }}'">Cancel</button>
            </div>
        </form>
    </div>

@endsection

@section('footer_scripts')
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // JS untuk mereset foto
        function resetPhoto() {
            document.querySelector('.account-settings-fileinput').value = null;
            alert('Foto akan direset saat Anda menekan "Save changes".');
        }

    </script>
@endsection