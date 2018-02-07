@extends('layouts.saas')

@section('css')
    <link href="/static/admin/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@endsection
@section('menu')
    @include("saas.menu")
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>名称</th>
                                <th>域名</th>
                                <th>子域名</th>
                                <th>申请时间</th>
                                <th>审核时间</th>
                                <th>审核状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $proj)
                                <tr class="gradeX">
                                    <td>{{$proj->proj_id}}</td>
                                    <td>{{$proj->name}}</td>
                                    <td>{{$proj->domain}}</td>
                                    <td>{{$proj->subname}}</td>
                                    <td class="center">{{$proj->ctime}}</td>
                                    <td class="center">{{$proj->atime}}</td>
                                    <td>{{\App\Models\Constant::$proj_status_ch[$proj->status]}}</td>
                                    @if ($proj->status == \App\Models\Constant::proj_status_default)
                                        <td>
                                            <button class="btn btn-sm btn-success proj-pass" proj_id="{{$proj->proj_id}}">通过</button>
                                            <button class="btn btn-sm btn-danger proj-refuse" proj_id="{{$proj->proj_id}}">驳回</button>
                                        </td>
                                    @elseif ($proj->status == \App\Models\Constant::proj_status_pass)
                                        <td>
                                            <button class="btn btn-sm btn-danger proj-refuse" proj_id="{{$proj->proj_id}}">驳回</button>
                                            <a href="{{route('saas.module', $proj->proj_id)}}">
                                                <button class="btn btn-sm btn-info">查看子模块</button>
                                            </a>
                                        </td>
                                    @elseif ($proj->status == \App\Models\Constant::proj_status_refuse)
                                        <td>
                                            <button class="btn btn-sm btn-success proj-pass" proj_id="{{$proj->proj_id}}">通过</button>
                                            <a href="{{route('saas.module', $proj->proj_id)}}">
                                                <button class="btn btn-sm btn-info">查看子模块</button>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script src="/static/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/static/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/static/admin/js/plugins/dataTables/datatables.min.js"></script>
    <script src="/static/admin/js/inspinia.js"></script>
    <script src="/static/admin/js/plugins/pace/pace.min.js"></script>
@endsection
<!-- todo 莫名其妙报'$'不存在，查看框架加载规则是怎么样的-->
<script src="/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('.proj-pass').click(function() {
            proj_id = $(this).attr('proj_id');
            status = "{{\App\Models\Constant::proj_status_pass}}";
            $.post(
                "{{route('saas.proj.audit')}}",
                {
                    proj_id : proj_id,
                    status  : status
                },

                function (data) {
                    if (data.retcode == 200) {
                        alert('审核成功');
                    } else {
                        alert('审核失败');
                    }

                    location.reload();
                }
            );
        });

        $('.proj-refuse').click(function () {
            proj_id = $(this).attr('proj_id');
            status = "{{\App\Models\Constant::proj_status_refuse}}";
            $.post(
                "{{route('saas.proj.audit')}}",
                {
                    proj_id : proj_id,
                    status  : status
                },

                function (data) {
                    if (data.retcode == 200) {
                        alert('审核成功');
                    } else {
                        alert('审核失败');
                    }

                    location.reload();
                }
            );
        });
    });
</script>