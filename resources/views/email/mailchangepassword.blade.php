<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create Tour</title>




    <style>
        * {
            box-sizing: inherit;
        }

        html,
        body {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
    </style>
    <style>
        #mail{
            display: flex;
            flex-direction: column;
        }
        #mail .content {
            flex: 1;
            padding-top: 20px;
            text-align: center

        }
        #mail .btn{
            margin-top: 20px;
            text-decoration: none;
            background-color: blue;
            color: white;
            padding: 10px 30px;
            border-radius: 6px
        }
        #mail #btn-wrapper{
            padding: 20px;
        }
        .quynh{
            height: 100vh;
        }
    </style>
</head>

<body id="mail" style="height:100vh;">

    <div class="quynh">
        <main style="display: flex;flex-direction:column;padding:10px">
            <div style="display: flex;justify-content: center;">
                <img src="https://quin.mr-quynh.com/assest/images/UNIDI_LOGO-FINAL%202.svg" alt="" width="96">
            </div>
            
            <div class="content" >
                <div>
                    <div><h1>Welcome to Quin Course</h1></div>
                    <div>Xin chào <strong>{{$data['user']['full_name']}}</strong> - Vui lòng nhấn đường link dưới đây để xác nhận thay đổi mật khẩu của bạn</div>
                </div>
                <div  id="btn-wrapper">
                    <a href="{{$data['url']}}" class="btn" href="">Đổi mật khẩu</a>
                </div>
            </div>
            <div>
                <div style="text-align: center;padding:5px;color: gray; background-color:rgba(22,22,24,.12);padding: 6px;">
                    Copyright © 2024 Mr Quynh
                </div>
            </div>
        </main>
    </div>
    


</body>

</html>