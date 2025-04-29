@extends('index')

@section('content')
    <style>
        .hidden {
            visibility: hidden;
        }

        #upload-frame {
            border: 1px solid gray;
        }

        .progress-bar {
            background: gainsboro;
            margin: 15px 50px;
            height: 25px;
        }

        .in-progress {
            width: 0%;
            height: 25px;
            background: #03a9f4;
        }

        .file-success {
            color: #03a9f4;
        }

        .file-failed {
            color: red;
        }

        #list-file {
            height: 400px;
            border: 1px solid gray;
            overflow: auto;
            margin: 0 auto;
            margin-bottom: 15px;
            border-radius: 15px;
        }

        #list-file ul {
            margin-top: 15px;
        }

        #list-file ul li {
            list-style: none;
        }

        #loading-icon {
            display: none;
            margin-left: 45%;
        }

        #lable-btn-choose-file {
            width: 280px;
            text-align: center;
        }
    </style>
    <div class="container">
        <div class="col-md-12">
            <h2>ユーザーのプロフィール画像を一括で取り込みます。</h2>
        </div>
        <br>
        <div class="col-md-12">
            <form action="#" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="_csrf_token" name="_token" value="{{ csrf_token() }}">
                <label id="lable-btn-choose-file" for="chooseFile" class="btn btn-success">
                    <span id="txt-btn-choose">ここで画像フォルダを選択してください。</span>
                    <i id="loading-icon" class="fas fa-spinner fa-spin"></i>
                </label>
                <input type="file" class="hidden" name="files" multiple id="chooseFile">
            </form>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" name="date" id="deadline" class="form-control"
                        value="{{ $dateNow->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <select name="priority" class="form-control" id="priority">
                        <option value="1">LOW</option>
                        <option value="2" selected>MEDIUM</option>
                        <option value="3">HIGH</option>
                    </select>
                </div>
            </div>
        </div>
        <br>
        <div class="col-md-12" id="upload-frame">
            <div class="col-md-11 progress-bar">
                <div class="in-progress"></div>
            </div>
            <br>
            <div class="col-md-11" id="list-file">
                <ul>
                    <li>
                        <p>filename1.png</p>
                    </li>
                    <li>
                        <p>filename2.png</p>
                    </li>
                    <li>
                        <p>filename3.png</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function () {
            // Handle file selection event
            $('#chooseFile').on('change', function (event) {
                $('#txt-btn-choose').css('display', 'none');
                $('#loading-icon').css('display', 'block');
                $('#chooseFile').attr('disabled', true);
                $('.in-progress').css('width', 0);
                // Get form data
                let deadline = $('#deadline').val();
                let priority = $('#priority').val();
                let _token = $('#_csrf_token').val();
                let files = event.target.files;
                let listFile = "";
                let uploadProgress = 0; // Init process
                let progressAnFile = 100 / files.length;

                // Iterate through selected files
                for (let i = 0; i < files.length; i++) {
                    let formData = new FormData();
                    formData.append("deadline", deadline);
                    formData.append("priority", priority);
                    formData.append("_token", _token);
                    formData.append("file", files[i]);
                    formData.append("user_id", {{ $userId }});

                    console.log(files[i]);

                    // Validate file type and size
                    if (files[i].type != 'image/jpeg' &&
                        files[i].type != 'image/png' &&
                        files[i].type != 'image/psd' ||
                        files[i].size >= 2097152) {
                        // Display error message for invalid files
                        listFile += '' +
                            '<li class="file-failed">' +
                            '<p>' + files[i].name + '</p>' +
                            '</li>';
                        $('#list-file ul').append(listFile);
                        uploadProgress += progressAnFile;
                        $('.in-progress').width(uploadProgress + "%");
                    } else {
                        // Upload file using AJAX
                        $.ajax({
                            url: "{{ url('/users/multiple-upload/upload') }}", // API endpoint
                            type: "POST",
                            processData: false,
                            contentType: false,
                            data: formData,

                            success: function (response) {
                                console.log(response);
                                if (response.code == 200) {
                                    uploadProgress += progressAnFile;
                                    // Update style for progress-bar
                                    $(".in-progress").width(uploadProgress + "%");
                                    listFile += '' +
                                        '<li class="file-success">' +
                                        '<p>' + files[i].name + '</p>' +
                                        '</li>';
                                    $('#list-file ul').append(listFile);
                                    if (i == files.length - 1) {
                                        // Clear all data
                                        $('#chooseFile').value = null;
                                        $('#txt-btn-choose').css('display', 'block');
                                        $('#loading-icon').css('display', 'none');
                                        $('#chooseFile').attr('disabled', false);
                                    }
                                }
                            }
                        });
                    }
                }
            });
        });
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>

@endsection