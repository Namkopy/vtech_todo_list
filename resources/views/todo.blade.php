<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>

<body>
    <div>
        <h2> TODO LIST</h2>

        <input type="text" id='todo'><br />
        <label id="message"> This item is Already Exists</label>
        <label id="empty"> Please to Input Item</label>

        <br />

        <table border="1" id='tbl' align="center">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class='size'>Action</th>
                </tr>
            </thead>
            <tbody class="hover">

            </tbody>
        </table>
    </div>
</body>

</html>

<style>
    div {
        text-align: center;
        weight: 100px
    }

    button {
        display: none;
    }

    tr:hover button {
        display: inline;
    }

    label {
        color: red
    }

    .size {
        width: 500px;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {

        //call function get data
        getData();
        // hide label
        $('#message').hide();
        $('#empty').hide();
    });


    // add and update todo
    $('#todo').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);

        if (keycode == '13') {
            var text = $('#todo').val();
            var id = $('#todo').attr('data-id');
            $(".hover").empty();
            // hide label
            $('#message').hide();
            $('#empty').hide();
            // for edit data
            if (id != null) {
                $.ajax({
                    type: "PUT",
                    url: 'api/todo/' + id,
                    data: {
                        todo: text
                    },
                    dataType: "JSON",
                    success: function(data) {
                        var data = data.Response;
                        if (data == 'success') {
                            $('#todo').val('');
                            $("#todo").removeAttr("data-id");
                            getData();
                        }
                    },
                    error: function(data) {
                        var data = data.responseJSON.Response;
                        if (data == 'Already Exists') {
                            // show label
                            $('#message').show();
                        }
                        if (data == 'Empty') {
                            // show label
                            $('#empty').show();
                        }
                    }

                });
            } else {
                // for add data
                $.ajax({
                    type: "POST",
                    url: 'api/todo',
                    data: {
                        todo: text
                    },
                    success: function(data) {
                        var data = data.Response;
                        if (data == 'success') {
                            $('#todo').val('');
                            $(".hover").empty();
                            getData();
                        }
                    },
                    error: function(data) {
                        var data = data.responseJSON.Response;
                        if (data == 'Already Exists') {
                            // show label
                            $('#message').show();
                        }
                        if (data == 'Empty') {
                            // show label
                            $('#empty').show();
                        }
                    }

                });
            }
        }

    })

    // filter
    $('#todo').keyup(function(event) {
        var text = $('#todo').val()
        $(".hover").empty();
        var keycode = (event.keyCode ? event.keyCode : event.which);

        if (keycode != '13') {
            $.ajax({
                type: "get",
                url: 'api/todo?search=' + text,
                dataType: "JSON",
                success: function(data) {
                    var data = data.Response;
                    var tr = '';
                    $(".hover").empty();
                    if (data == 'No Data') {
                        tr = '<tr><td colspan="2">No Result. Create a new one Instead </td></tr>';
                        $('.hover').append(tr);
                    } else {
                        $.each(data, function(i, data) {
                            var str = '';
                            if (data.isCompleted == true) {
                                str = 'line-through;'
                            } else {
                                str = 'none;'
                            }
                            tr += "<tr>" +
                                "<td class='" + data.id + "' data-key='" +
                                data.id + "' style='text-decoration:" + str + "'''>" + (data
                                    .todo) + "</td>" +
                                "<td>" +
                                "<button  value='" + data.id +
                                "'  onclick='remove(this)' >Remove</button>" +
                                "<button value='" + data.id +
                                "' data-todo='" + data.todo +
                                "'onclick='edit(this)'>Edit</button>" +
                                "<button  value='" + data.id +
                                "'  onclick='complete(this)' >Mask as Complete</button>" +
                                "<button  value='" + data.id +
                                "'  onclick='incomplete(this)' >Mask as Incomplete</button>" +
                                "</td>" +
                                "</tr>";

                        })
                        $('.hover').append(tr);
                    }
                },
                error: function(data) {
                    var data = data.responseJSON.Response;
                    $(".hover").empty();
                    if (data == 'No Data') {
                        tr = '<tr><td colspan="2">No Result. Create a new one Instead </td></tr>';
                        $('.hover').append(tr);
                    }
                }

            });
        }

    })

    // get data
    function getData() {
        $(".hover").empty();
        $.ajax({
            type: "get",
            url: 'api/todo',
            dataType: "JSON",
            success: function(data) {
                var data = data.Response;
                var tr = '';
                $(".hover").empty();
                if (data == 'No Data') {
                    tr = '<tr><td colspan="2">No Result. Create a new one Instead </td></tr>';
                    $('.hover').append(tr);
                } else {
                    $.each(data, function(i, data) {
                        var str = '';
                        if (data.isCompleted == true) {
                            str = 'line-through;'
                        } else {
                            str = 'none;'
                        }
                        tr += "<tr>" +
                            "<td class='" + data.id + "' data-key='" +
                            data.id + "' style='text-decoration:" + str + "'''>" + (data.todo) +
                            "</td>" +
                            "<td>" +
                            "<button  value='" + data.id +
                            "'  onclick='remove(this)' >Remove</button>" +
                            "<button value='" + data.id +
                            "' data-todo='" + data.todo +
                            "'onclick='edit(this)'>Edit</button>" +
                            "<button  value='" + data.id +
                            "'  onclick='complete(this)' >Mask as Complete</button>" +
                            "<button  value='" + data.id +
                            "'  onclick='incomplete(this)' >Mask as Incomplete</button>" +
                            "</td>" +
                            "</tr>";

                    })
                    $('.hover').append(tr);
                }
            },
            error: function(data) {
                var data = data.responseJSON.Response;
                if (data == 'No Data') {
                    tr = '<tr><td colspan="2">No Result. Create a new one Instead </td></tr>';
                    $('.hover').append(tr);
                }
            }

        });
    }

    // function remove item
    function remove(e) {
        // console.log("remove", e.value);
        $.ajax({
            type: "delete",
            url: 'api/todo/' + e.value,
            success: function(data) {
                var data = data.Response;
                console.log(data);
                if (data == 'success') {
                    $('#todo').val('');
                    getData();
                }
            }

        });
    };

    // function edit item
    function edit(e) {
        var id = e.value;
        var txt = e.dataset.todo;

        $('#todo').val(txt);
        $('#todo').attr("data-id", id);

        console.log("edit", e.dataset.todo);
    };

    // function mask as complete
    function complete(e) {
        var id = e.value;
        $.ajax({
            type: "PUT",
            url: 'api/todoComplete/' + id,
            data: {
                isCompleted: true
            },
            dataType: "JSON",
            success: function(data) {
                var data = data.Response;
                if (data == 'success') {
                    getData();
                }
            },
            error: function(data) {
                alert("error");
            }

        });
    }

    // function mask as inomplete
    function incomplete(e) {
        var id = e.value;
        $.ajax({
            type: "PUT",
            url: 'api/todoComplete/' + id,
            data: {
                isCompleted: false
            },
            dataType: "JSON",
            success: function(data) {
                var data = data.Response;
                if (data == 'success') {
                    getData();
                }
            },
            error: function(data) {
                alert("error");
            }

        });
    }
</script>
