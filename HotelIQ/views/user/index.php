<?php
?>

<html>
<head>
    <style>
        .container {
            padding: 10px 20px;
        }
        table {
            border: 1px solid;
            border-collapse: collapse;
        }
        td, th {
            padding: 10px;
            border: 1px solid;
            border-collapse: collapse;
        }
    </style>
</head>
<div class="container">
    <div class="usersList">
        <h1>
            Show Users Statistics
        </h1>

        <h2>Users</h2>
        <ol>
            <?php foreach($result as $user) {  ?>
                <?= "<li class='showUserStatistics' data-id='" . $user['id'] ."' style='cursor:pointer'>" . $user['name'] ."</li>"?>
            <?php }?>
        </ol>
    </div>

    <table id="table">
        <thead>

        </thead>
        <tbody>

        </tbody>
    </table>
    <p class="total"></p>
</div>
<script
        src="https://code.jquery.com/jquery-3.7.0.js"
        integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>
<script>

    $(document).on('click', '.getByDay', function(e) {
        e.preventDefault()
        let id = $(this).attr('data-id')
        let date = $(this).attr('data-date')
        $('.total').empty()
        $.ajax({
            type: 'GET',
            url: '/user-get-statistic-by-day?id=' + id + '&date=' + date,
            dataType: 'json',
            success: function( response ) {
                let data = JSON.parse(JSON.stringify(response))
                if (data.data.length) {
                    $("#table thead").empty()
                    $("#table tbody").empty()
                    data.headers.forEach((i) => {
                        $("#table thead").append(" <th>" + i + "</th>")
                    })
                    data.data.forEach(i => {
                        $("#table tbody").append(`<tr>
                            <td>${i.room}</td>
                            <td>${i.type}</td>
                            <td>${i.work_type}</td>
                            <td>${i.start}</td>
                            <td>${i.end}</td>
                            <td>${i.price}</td>
                        </tr>`)
                    })
                    $('.total').append('<span> total: ' + data.total)
                } else {
                    $('.total').append('<span> total: ' + 0)

                }
            },
            error: function(data) {
                console.log(data)
            }
        })
    })
    $(document).on('click', '.showUserStatistics', function() {
        let id = $(this).attr('data-id')
        $.ajax({
            type: 'GET',
            url: '/user-get-statistic?id=' + id,
            dataType: 'json',
            success: function( response ) {
                $("#table thead").empty()
                $("#table tbody").empty()
                $('.total').empty()
                let data = JSON.parse(JSON.stringify(response))
                if (Object.keys(data.days).length) {
                    data.headers.forEach((i) => {
                        $("#table thead").append(" <th>" + i + "</th>")
                    })
                    for (const item in data.days) {
                        $("#table tbody").append(`<tr>
                            <td><a href="" class="getByDay" data-id="${id}" data-date="${item.split(" ")[0]}">${item}</a></td>
                            <td>${data.days[item].start}</td>
                            <td>${data.days[item].end}</td>
                            <td>${data.days[item].general}</td>
                            <td>${data.days[item].ingoing}</td>
                            <td>${data.days[item].income}</td>
                            <td>${data.days[item].price}</td>
                        </tr>`)
                    }
                    $('.total').append('<span> total: ' + data.total)
                } else {
                    $('.total').append('<span> total: ' + 0)

                }

            },
            error: function(data) {
            console.log(data)
            }
        })
    })


</script>
</html>