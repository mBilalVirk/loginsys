@extends('layouts.admin')
@section('title', 'Assistant')

@section('content')

    <div class="container-fluid">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fa-solid fa-robot text-primary"></i> AI Assistant Dashboard
            </h1>
        </div>

        <div class="row">

            <!-- AI Settings -->
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong>⚙️ AI Settings</strong>
                    </div>
                    <div class="card-body">

                        <form id="ai-settings-form" action="#" method="POST">
                            @csrf

                            <!-- Assistant Name -->
                            <div class="mb-3">
                                <label class="form-label">Assistant Name</label>
                                <input type="text" class="form-control" id="assistant_name" placeholder="Bilal AI">
                            </div>

                            <!-- Welcome Message -->
                            <div class="mb-3">
                                <label class="form-label">Welcome Message</label>
                                <textarea class="form-control" id="welcome_message" rows="2"></textarea>
                            </div>

                            <!-- System Prompt -->
                            <div class="mb-3">
                                <label class="form-label">System Prompt</label>
                                <textarea class="form-control" id="system_prompt" rows="4" placeholder="You are a helpful assistant..."></textarea>
                            </div>

                            <button type="button" id="save_ai_settings" class="btn btn-primary">
                                Save Settings
                            </button>
                        </form>

                    </div>
                </div>
            </div>
            <script>
                // Load settings from backend
                function loadAiSettings() {
                    $.ajax({
                        url: "{{ route('admin.ai-settings.load') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success && response.settings) {
                                $('#assistant_name').val(response.settings.assistant_name);
                                $('#welcome_message').val(response.settings.welcome_message);
                                $('#system_prompt').val(response.settings.system_prompt);
                            }
                        },
                        error: function(xhr) {
                            console.error('Failed to load AI settings', xhr.responseText);
                        }
                    });
                }

                // Save settings to backend
                function saveAiSettings() {
                    $.ajax({
                        url: "{{ route('admin.ai-settings.save') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            assistant_name: $('#assistant_name').val(),
                            welcome_message: $('#welcome_message').val(),
                            system_prompt: $('#system_prompt').val()
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                            } else {
                                alert('Failed to save AI settings');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error saving AI settings', xhr.responseText);
                            alert('An error occurred while saving AI settings');
                        }
                    });
                }
            </script>
            <!-- AI Controls -->
            <div class="col-md-6" style="pointer-events: none; opacity: 0.5;">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong>🤖 AI Controls</strong>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label>Response Length</label>
                            <select class="form-control">
                                <option>Short</option>
                                <option>Medium</option>
                                <option>Long</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Personality</label>
                            <select class="form-control">
                                <option>Friendly</option>
                                <option>Professional</option>
                                <option>Funny</option>
                            </select>
                        </div>

                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input">
                            <label class="form-check-label">Allow Emojis</label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input">
                            <label class="form-check-label">Enable Chatbot</label>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Chat Management -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between">
                <strong>💬 Chat History</strong>
                <button class="btn btn-danger btn-sm" onclick="clearAIHistory()">
                    Clear All
                </button>
            </div>
            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Role</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic data -->
                        <tr>

                        </tr>
                    </tbody>
                </table>
                <div class="mt-3">
                    <nav>
                        <ul id="pagination" class="pagination justify-content-center"></ul>
                    </nav>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                loadHistory();
                loadAiSettings();

                $('#save_ai_settings').on('click', function(e) {
                    e.preventDefault();
                    saveAiSettings();
                });
            });

            function loadHistory(page = 1) {
                $.ajax({
                    url: "{{ route('admin.ai-history') }}?page=" + page,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const messages = response.messages;
                            let html = '';
                            messages.forEach(msg => {
                                html += `
                        <tr>
                            <td>${msg.id}</td>
                            <td>${msg.user ? msg.user.name : 'Deleted User'}</td>
                            <td>${msg.role === 'user' 
                                ? `<span class="badge bg-primary">User</span>` 
                                : `<span class="badge bg-success">Assistant</span>`}</td>
                            <td>${msg.message}</td>
                        </tr>
                    `;
                            });
                            $('table tbody').html(html);

                            let pagHtml = '';

                            // Previous button
                            if (page > 1) {
                                pagHtml +=
                                    `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="loadHistory(${page - 1})">Previous</a></li>`;
                            } else {
                                pagHtml +=
                                    `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
                            }

                            // Page numbers
                            for (let i = 1; i <= response.pagination.last_page; i++) {
                                pagHtml += `<li class="page-item ${i === page ? 'active' : ''}">
                                    <a class="page-link" href="javascript:void(0)" onclick="loadHistory(${i})">${i}</a>
                                </li>`;
                            }

                            // Next button
                            if (page < response.pagination.last_page) {
                                pagHtml +=
                                    `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="loadHistory(${page + 1})">Next</a></li>`;
                            } else {
                                pagHtml +=
                                    `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
                            }

                            $('#pagination').html(pagHtml);
                        }
                    },
                    error: function() {
                        console.error('Failed to load AI history');
                    }
                });
            }

            function clearAIHistory() {
                if (confirm('Are you sure you want to clear all AI chat history? This action cannot be undone.')) {
                    $.ajax({
                        url: "{{ route('admin.clearAIHistory') }}",
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                loadHistory();
                            } else {
                                alert('Failed to clear AI history');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.status);
                            console.error(xhr.responseText); // ← paste what this says
                            alert('An error occurred while clearing AI history');
                        }
                    });
                }
            }
        </script>
    </div>

@endsection
