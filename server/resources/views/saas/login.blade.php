@extends('layouts.saas')

@section('content')
<form>
    <input type="text" name="uname" placeholder="user name" required><br>
    <input type="password" name="password" placeholder="password" required><br>
    <input type="submit" value="login">
</form>
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