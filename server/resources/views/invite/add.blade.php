
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{csrf_token()}}" />
    <title>BitCV</title>
    <meta name=viewport content="width=device-width,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <link rel="stylesheet" href="/static/css/reset.css" />
    <link rel="stylesheet" href="/static/css/style.css" />
</head>

<body>
<div id="doc">

    <div class="header">
    </div>

    <div class="lang-wrap" data-lang="ZH">
        <div class="container">
            <h1 class="logo">
                <a href>
                    <img src="/static/image/logo.png">
                </a>
            </h1>
            <div class="intro">
                <div class="join"></div>
                <p></p>
            </div>
                <fieldset>
                    <input type="text" class="ipt-txt ipt-address" id="address" placeholder="输入你的以太坊钱包地址"/>
                    <input type="submit" value="提 交" class='ipt-btn' id="address-btn"/>
                </fieldset>
        </div>
        <div class="footer">
            <div class="" style="text-align: left;padding-top: 20px;font-size: 14px;padding-bottom: 40px; color: rgba(255,255,255,0.5)">
                <p style="margin-bottom:20px"></p>
                <p style="margin-bottom:20px"></p>
            </div>

        </div>
    </div>
</div>

<script src="/static/jquery/dist/jquery.min.js"></script>
<script>
    // 提交表单
    $(function() {
        $('#address-btn').click(function() {
            address = $('#address').val();
            var pattern = /[0-9a-zA-Z]{30,50}/;

            //验证长度，字母数字，长度30-50
            if (!pattern.test(address)) {
                //alert('请输入正确格式的以太坊钱包地址！');
            }

            $.ajaxSetup(
                {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }
            );

            $.post(
                '/invite/add',
                {
                    'address' : address
                },
                function(data) {
                    if (data.retcode == 200) {
                        alert('添加成功！');
                    } else {
                        alert(data.msg);
                    }
                    console.log(data);
                }
            );
        });
    });
</script>
</body>

</html>