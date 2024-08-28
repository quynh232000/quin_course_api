<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $data['title'] }}</title>
    <style>
        .btn-wrapper {
            display: flex;
            gap: 10px
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            color: white !important;
        }

        .btn.success {
            background-color: green
        }

        .btn.error {
            background-color: red
        }
    </style>
</head>

<body>
    <h2 style="text-align: center; padding:10px 0;color:green">{{ $data['title'] }}</h2>
    <div>
        <div class="display:flex;gap:5px; padding:5px 0">
            <span>User Email: </span> <strong>{{ $data['order']['email'] }}</strong>
        </div>
        <div class="display:flex;gap:5px; padding:5px 0">
            <span>Order code: </span> <strong>{{ $data['order']['order_code'] }}</strong>
        </div>
        <div class="display:flex;gap:5px; padding:5px 0">
            <span>Total: </span> <strong>{{ $data['order']['total'] }}</strong>
        </div>
        <hr>
        <div>Order <strong>{{ $data['order']['order_code'] }}</strong> had already paid, please confirm this infomation</div>
        <span>Confirm for this transaction</span>
        <div class="btn-wrapper">
            <a class="btn btn-confirm success" href="{{$data['APP_URL']}}/order/confirm/{{$data['order']['id']}}?token={{$data['token_success']}}">Payment confirmed</a>
            <a class="btn btn-confirm error" href="{{$data['APP_URL']}}/order/confirm/{{$data['order']['id']}}?token={{$data['token_error']}}">Unpaid confirmation</a>
        </div>
    </div>
</body>

</html>
