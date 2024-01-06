<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body class="antialiased">
    <div class="container mt-3">
        <button type="button" class="btn btn-primary" id="add_todo">
            Add Todo
        </button>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="list_todo">
            @foreach ($todos as $todo)
                <tr id="row_todo_{{ $todo->id }}">
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->name }}</td>
                    <td><button type="button" id="edit_todo" data-id="{{ $todo->id }}"
                            class="btn btn-sm btn-info ml-1">
                            Edit</button>
                        <button type="button" id="delete_todo" data-id="{{ $todo->id }}"
                            class="btn btn-sm btn-danger ml-1">
                            Delete
                        </button>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- The Modal -->
    <div class="modal" id="modal_todo">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form_todo">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title"></h4>

                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <input type="text" name="name" class="form-control" id="name_todo"
                            placeholder="Enter Todo......">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Submit</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        });
        $("#add_todo").on('click', function() {
            $("#form_todo").trigger('reset');
            $("#modal_title").html('Add Todo');
            $('#modal_todo').modal('show');
        });
        //In this time we can access data through id base so when we click on edit button we can pass parameters of body to clcik on edit button
        //Show existing specific data through ajax request
        $("body").on('click', '#edit_todo', function() {
            var id = $(this).data("id");
            $.get('todos/' + id + '/edit', function(res) {
                $('#modal_title').html('Edit Todo');
                $('#id').val(res.id);
                $('#name_todo').val(res.name);
                $('#modal_todo').modal('show');
            })
        });

        //Delete Todo by id base
        $("body").on('click', '#delete_todo', function() {
            var id = $(this).data("id");
            confirm('Are You Sure want to Delete Todo !');
            $.ajax({
                type: "DELETE",
                url: "todo/destroy/" + id
            }).done(function(res) {
                $('#row_todo' + id).remove();
            })

        });

        //Save Data
        $('form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "todo/Store",
                data: $('#form_todo').serialize(),
                type: "POST",

            }).done(function(res) {
                var row = '<tr id="row_todo_' + res.id + '">';
                row += '<td>' + res.id + '</td>';
                row += '<td>' + res.name + '</td>';
                row += '<td>' + '<button type="button" id="edit_todo" data-id="' + res.id +
                    '" class="btn btn-info btn-sm ml-1">Edit</button>' +
                    '<button type="button" id="delete_todo" data-id="' + res.id +
                    '" class="btn btn-danger btn-sm ml-1">Delete</button>' + '</td>';


                if ($("#id").val()) {
                    $('#row_todo_' + res.id).replaceWith(row);
                } else {
                    $('#list_todo').prepend(row);
                }

                $('#form_todo').trigger('reset');
                $('#modal_todo').modal('hide');
            })
        });
    </script>
</body>

</html>
