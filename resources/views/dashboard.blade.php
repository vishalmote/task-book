<!doctype html>
<html>

<head>
    <title>My Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script>
        var header = {
            "Authorization": "Bearer " + localStorage.getItem("token")
        };

        function checkSession(messageList) {
            if (messageList.unauthorized != undefined) {
                window.location.href = "{{ route('login')}}";
                return;
            }
        }

        function checkLogin() {
            console.log('>>>>>>checkLogin<<<<<<');
            $.ajax({
                url: "{{ route('api.me')}}",
                type: "POST",
                headers: header,
                success: function(data) {
                    console.log('checkLogin', data);
                    if (data.status == 'error') {
                        if (data.data.messageList) {
                            checkSession(data.data.messageList);
                            if (data.data.messageList[0])
                                alert(data.data.messageList[0]);
                        }
                    }
                }
            });
        }
        checkLogin();

        function logout() {
            console.log('>>>>>>logout<<<<<<');
            $.ajax({
                url: "{{ route('api.logout')}}",
                type: "POST",
                headers: header,
                success: function(data) {
                    console.log('logout', data);
                    alert(data.data.messageList[0]);
                    if (data.status == 'success') {
                        window.location.href = "{{ route('login')}}";
                    }
                }
            });
        }

        function addTask() {
            console.log('>>>>>>addTask<<<<<<');
            $(document).find('span.error-text').text('');
            toggleModal();
        }

        function toggleModal() {
            $('#addTaskModal').modal('toggle');
        }

        function addNote() {
            console.log('>>>>>>addNote<<<<<<');
            var note = `<div class="card col-6">
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="note_subject[]" placeholder="note subject...">
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="note_note[]" placeholder="Detail note..." rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="file" name="note_attachment[]" multiple>
                                    </div>
                                </div>
                            </div>`;
            $("#noteList").append(note);
        }

        function clearNote() {
            $("#noteList").html('');
        }

        function deleteNote() {
            if ($('#noteList').children().length == 0)
                return;
            $('#noteList').children().eq($('#noteList').children().length - 1).remove();
        }

        function submitTask() {
            console.log('>>>>>>submitTask<<<<<<');
            let formData = $("#task_form").serializeArray();
            let finalData = new FormData();
            for (let i = 0; i < formData.length; i++) {
                finalData.append(formData[i].name, formData[i].value);
            }
            var files = $('input[type=file]');
            for (let i = 0; i < files.length; i++) {
                finalData.append('form_attachment_inner_length_' + i, files[i].files.length);
                for (let j = 0; j < files[i].files.length; j++) {
                    finalData.append('form_attachment_' + i + '_' + j, files[i].files[j]);
                }
            }
            finalData.append('form_attachment_length', files.length);
            // console.log('formData', $("#task_form").serialize(), finalData);
            $.ajax({
                url: "{{ route('api.createTask')}}",
                type: "POST",
                headers: header,
                contentType: false,
                processData: false,
                enctype: 'multipart/form-data',
                data: finalData,
                beforeSend: function() {
                    $(document).find('span.error-text').text('');
                },
                success: function(data) {
                    toggleModal
                    console.log('submitTask', data);
                    if (data.status == 'error') {
                        if (data.data.validationErrorList) {
                            $.each(data.data.validationErrorList, function(prefix, val) {
                                $('.' + prefix + '_error').text(val[0]);
                            });
                        } else if (data.data.messageList) {
                            checkSession(data.data.messageList);
                            if (data.data.messageList[0])
                                $('#show_error').text(data.data.messageList[0]);
                        }
                    }
                    if (data.status == 'success') {
                        listTask();
                        toggleModal();
                        clearNote();
                        $(document).find('span.error-text').text('');
                    }
                }
            });
        }

        function listTask() {
            console.log('>>>>>>listTask<<<<<<');
            let formData = $("#filter_form").serializeArray();
            let finalData = new FormData();
            for (let i = 0; i < formData.length; i++) {
                finalData.append(formData[i].name, formData[i].value);
            }
            $.ajax({
                url: "{{ route('api.listTask')}}",
                type: "POST",
                contentType: false,
                processData: false,
                headers: header,
                data: finalData,
                success: function(data) {
                    console.log('listTask', data);
                    if (data.status == 'error') {
                        if (data.data.messageList)
                            checkSession(data.data.messageList);
                        if (data.data.messageList[0])
                            alert(data.data.messageList[0]);
                        return;
                    }
                    let innerHtml = '';
                    if (data.data.taskList) {
                        $.each(data.data.taskList, function(key, val) {
                            innerHtml += `<tr>
                                            <th scope="row">${val.id}</th>
                                            <td>${val.subject}</td>
                                            <td>${val.due_date}</td>
                                            <td>${val.status}</td>
                                            <td>${val.priority}</td>
                                            <td>${val.notes.length}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6">
                                            Notes : ${JSON.stringify(val.notes, null, 1)}
                                            </td>
                                        </tr>
                                        `;
                        });
                    }
                    $('#listTaskGrid').html(innerHtml);
                }
            });
        }
        listTask();
    </script>
</head>

<body>
    <hr />
    <div class="row">
        <div class="col-10">
            Dashboard
        </div>
        <div class="col-2">
            <div class="d-flex justify-content-end">
                <input type="button" onclick="logout()" value="Logout" class="btn btn-danger">
            </div>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-3">
            &nbsp;
        </div>
        <div class="col-3">
            Task List
        </div>
        <div class="col-2">
            <div class="d-flex justify-content-end">
                <input type="button" onclick="addTask()" value="Add Task" class="btn btn-primary">
            </div>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-10">
            <form id="filter_form">
                <div class="row">
                    <div class="col">
                        <select class="form-control" name="status">
                            <option value="">--Status--</option>
                            <option value="New">New</option>
                            <option value="Incomplete">Incomplete</option>
                            <option value="Complete">Complete</option>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-control" name="priority">
                            <option value="">--Priority--</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <div class="col">
                        <input type="date" class="form-control" name="due_date" placeholder="Due Date">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="note" placeholder="Notes">
                    </div>
                </div>
            </form>
        </div>
        <div class="col-2">
            <div class="d-flex justify-content-end">
                <input type="button" onclick="listTask()" value="Search" class="btn btn-success">
            </div>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Due Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Note Count</th>
                    </tr>
                </thead>
                <tbody id="listTaskGrid">
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade " id="addTaskModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Task</h5>
                    <button type="button" class="close" onclick="toggleModal()" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="show_error" style="color: red"> </div>
                    <form id="task_form">
                        <div class="form-group">
                            <label for="Subject">Subject</label>
                            <input type="text" class="form-control" id="Subject" name="subject" placeholder="Travel the world...">
                            <span class="text-danger error-text subject_error" style="color: red"></span>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                            <span class="text-danger error-text start_date_error" style="color: red"></span>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date">
                            <span class="text-danger error-text due_date_error" style="color: red"></span>
                        </div>
                        <div class="form-group">
                            <label for="Status">Status</label>
                            <select class="form-control" id="Status" name="status">
                                <option value="New">New</option>
                                <option value="Incomplete">Incomplete</option>
                                <option value="Complete">Complete</option>
                            </select>
                            <span class="text-danger error-text status_error" style="color: red"></span>
                        </div>
                        <div class="form-group">
                            <label for="Priority">Priority</label>
                            <select class="form-control" id="Priority" name="priority">
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                            <span class="text-danger error-text priority_error" style="color: red"></span>
                        </div>
                        <div class="form-group">
                            <label for="Description">Description</label>
                            <textarea class="form-control" id="Description" name="description" rows="3"></textarea>
                            <span class="text-danger error-text description_error" style="color: red"></span>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-6">
                                <button type="button" onclick="addNote()" class="btn btn-secondary">Add Note</button>
                            </div>
                            <div class="col-6">
                                <button type="button" onclick="deleteNote()" class="btn btn-danger">Delete Note</button>
                            </div>
                        </div>
                        <div class="row" id="noteList">
                            <div class="card col-6">
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="note_subject[]" placeholder="note subject...">
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="note_note[]" placeholder="Detail note..." rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="file" name="note_attachment[]" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="toggleModal()" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="submitTask()" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>