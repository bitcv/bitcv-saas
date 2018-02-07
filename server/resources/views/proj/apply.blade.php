@extends('layouts.proj')

@section('content')
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">Bitcv</h1>
            </div>
            <form class="m-t">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="project name" required="">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="subname" placeholder="subname" required="">
                </div>
                <div class="form-group">
                    <input type="text" name="domain" class="form-control" placeholder="domain" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">apply</button>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript">
        $('form').submit(function(event) {
            event.preventDefault();
            $.post("{{route('proj.add')}}", $('form').serialize(), function(ret) {
                if (ret.retcode != 200) {
                    alert(ret.msg);
                } else {
                    alert('申请成功！');
                }
            })
        })
    </script>
@stop