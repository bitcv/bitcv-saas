<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{csrf_token()}}" />
    <title>BitCV</title>
    <meta name=viewport content="width=device-width,minimum-scale=1,maximum-scale=1,user-scalable=no">
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
                    <img src="{{$proj['logo_url']}}">
                </a>
            </h1>
            <div class="intro">
                <div class="join"></div>
                <p>每个手机号只能申请一次</p>
            </div>
            <fieldset id="info">
                <div id="verifyCode">
                    <input type="text" class="ipt-txt ipt-address" id="mobile" placeholder="输入你的手机号"/>
                    <input type="text" class="ipt-txt ipt-address" style="width:69%" id="vcode" placeholder="输入验证码"/>
                    <input type="button" class='ipt-btn' style="width:30%" id="btnvcode" value="获取验证码">
                    <input type="submit" value="提 交" class='ipt-btn' id="code-btn"/>
                </div>

                <div style="display:none;" id="addAddress">
                    <input type="text" class="ipt-txt ipt-address" id="address" placeholder="输入你的以太坊钱包地址"/>
                    <input type="submit" value="提 交" class='ipt-btn' id="address-btn"/>
                </div>


            </fieldset>

            <div class="intro" style="display:none" id="result">
                <div class="join"></div>
                <p id="tips">您已申请成功，邀请朋友成功参与，可获取额外奖励</p>
                <input type="text" id="inviteurl" class="ipt-txt ipt-address" style="width:69%">
                <input type="button" id="btncopy" class='ipt-btn' style="width:30%" data-clipboard-target='address' value="复制邀请地址">
            </div>
        </div>
        <div class="footer">
            <div class="" style="text-align: left;padding-top: 20px;font-size: 14px;padding-bottom: 40px; color: rgba(255,255,255,0.5)">
                <p style="margin-bottom:20px">{{$proj['name_cn']}}</p>
            </div>

        </div>
    </div>
</div>

<script src="/js/libs/jquery.min.js"></script>
<script src="/static/js/jquery.zclip.js"></script>
<script>
    $.ajaxSetup(
        {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }
    );
    // 提交表单
    $(function() {
        $('#btnvcode').click(function() {
            var mobile = $('#mobile').val();
            var pat = /^(((13[0-9]{1})|(17[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if (!pat.test(mobile)) {
                alert('请输入正确的手机号');
                return false;
            }
            $.post('/invite/vcode/'+mobile, '', function(ret) {
                if (ret.retcode == 200) {
                    alert('验证码已发送，请在5分钟内输入');
                } else {
                    alert(ret.msg);
                }
            })
        });

        //注册手机号码
        $('#code-btn').click(function () {
            var mobile = $('#mobile').val();
            var pat = /^(((13[0-9]{1})|(17[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if (!pat.test(mobile)) {
                alert('请输入正确的手机号');
                return false;
            }

            var vcode = $('#vcode').val();
            if (vcode.length != 6) {
                alert('请输入正确的验证码');
                return false;
            }

            $.post(
                '/invite/verifyCode',
                {
                    'mobile': mobile,
                    'vcode': vcode
                },

                function(ret) {
                    if (ret.retcode == 200) {
                        $('#verifyCode').hide();
                        $('#addAddress').show();
                    } else if (ret.retcode == 202) {
                        $('#inviteurl').val(ret.data);
                        $('#tips').html('您的手机号已申请，邀请好友参与可获更多奖励');
                        $('#verifyCode').hide();
                        $('#result').show();
                    } else {
                        alert(ret.msg);
                    }
                }
            );
        });

        $('#address-btn').click(function() {
            var address = $('#address').val();
            var pattern = /[0-9a-zA-Z]{30,50}/;
            //验证长度，字母数字，长度30-50
            if (!pattern.test(address)) {
                alert('请输入正确格式的钱包地址！');
                return false;
            }

            $.post(
                '/invite/add',
                {
                    'address' : address,
                    'code': '{{$code}}'
                },
                function(ret) {
                    if (ret.retcode == 200 || ret.retcode == 201) {
                        if (ret.retcode == 201) {
                            $('#tips').html('您的手机号或钱包地址已申请，邀请好友参与可获更多奖励');
                        }
                        $('#inviteurl').val(ret.data);
                        $('#addAddress').hide();
                        $('#result').show();
                    } else {
                        alert(ret.msg);
                    }
                }
            );
        });

        $('#btncopy').zclip({
            path: "/static/js/ZeroClipboard.swf",
            copy: function(){
                return $('#inviteurl').val();
            }
        });
    });
</script>
</body>

</html>