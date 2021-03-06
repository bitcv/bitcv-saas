@extends('layouts.saas')

@section('content')
<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <h2>登录后审核SaaS项目申请</h2>
        <form class="m-t">
            <div class="form-group">
                <input type="text" class="form-control" name="uname" placeholder="Username" required="">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required="">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
        </form>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
$('form').submit(function(event) {
    event.preventDefault();
    $.post("{{route('saas.login')}}", $('form').serialize(), function(ret) {
        if (ret.err > 0) {
            alert(ret.msg);
        } else {
            location.href = "{{ route('saas.admin') }}";
        }
    })
})
</script>
@stop