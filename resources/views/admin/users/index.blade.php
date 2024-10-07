@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Users List</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr id="user-{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-user" data-id="{{ $user->id }}">Edit</button>
                            @if (auth()->user()->id !== $user->id)
                                <button class="btn btn-danger delete-user" data-id="{{ $user->id }}">Delete</button>
                            @else
                                <button class="btn btn-danger delete-user" data-id="{{ $user->id }}" disabled>Delete
                                    (Self)</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editUserForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button> --}}
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-user-id">
                        <div class="form-group">
                            <label for="edit-user-name">Name</label>
                            <input type="text" id="edit-user-name" class="form-control">
                            <span id="name-error" class="text-danger"></span> <!-- Error message for name -->
                        </div>
                        <div class="form-group">
                            <label for="edit-user-email">Email</label>
                            <input type="email" id="edit-user-email" class="form-control">
                            <span id="email-error" class="text-danger"></span> <!-- Error message for email -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Handle Edit Button Click
            $('.edit-user').on('click', function() {
                let id = $(this).data('id');
                $.get('/admin/users/' + id + '/edit', function(data) {
                    $('#edit-user-id').val(data.id);
                    $('#edit-user-name').val(data.name);
                    $('#edit-user-email').val(data.email);
                    $('#editUserModal').modal('show');
                });
            });

            // Handle Edit Form Submission
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#edit-user-id').val();
                let name = $('#edit-user-name').val();
                let email = $('#edit-user-email').val();

                // Clear previous error messages
                $('#edit-user-name').removeClass('is-invalid');
                $('#edit-user-email').removeClass('is-invalid');
                $('#name-error').text('');
                $('#email-error').text('');

                $.ajax({
                    url: '/admin/users/' + id,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        email: email,
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#editUserModal').modal('hide');
                            $('#user-' + id + ' td:nth-child(2)').text(name);
                            $('#user-' + id + ' td:nth-child(3)').text(email);
                            alert(response.success);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validation failed, display errors
                            var errors = xhr.responseJSON.errors;

                            if (errors.name) {
                                $('#edit-user-name').addClass('is-invalid');
                                $('#name-error').text(errors.name[0]);
                            }
                            if (errors.email) {
                                $('#edit-user-email').addClass('is-invalid');
                                $('#email-error').text(errors.email[0]);
                            }
                        } else {
                            alert('An error occurred while updating the user.');
                        }
                    }
                });
            });

            // Handle Delete Button Click
            $('.delete-user').on('click', function() {
                if (confirm('Are you sure you want to delete this user?')) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: '/admin/users/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#user-' + id).remove();
                            alert(response.success);
                        }
                    });
                }
            });
        });
    </script>
@endsection
