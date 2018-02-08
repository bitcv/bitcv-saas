@extends('layouts.saas')

@section('css')
    <!--datatbale -->
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
                                {{--<th>编号</th>--}}
                                <th>机构</th>
                                <th>申请人</th>
                                <th>电话</th>
                                <th>邮箱</th>
                                <th>子域名</th>
                                <th>独立域名</th>
                                <th>申请时间</th>
                                <th>审核时间</th>
                                {{--<th>审核状态</th>--}}
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $proj)
                                <tr class="gradeX">
                                    {{--<td>{{$proj->proj_id}}</td>--}}
                                    <td>{{$proj->org}}</td>
                                    <td>{{$proj->username}}</td>
                                    <td>{{$proj->mobile}}</td>
                                    <td>{{$proj->email}}</td>
                                    <td>{{$proj->subname}}</td>
                                    <td>{{$proj->domain}}</td>
                                    <td class="center">{{$proj->ctime}}</td>
                                    <td class="center">{{$proj->atime}}</td>
                                    {{--<td>{{\App\Models\Constant::$proj_status_ch[$proj->status]}}</td>--}}
                                    <td>
                                    @if ($proj->status == \App\Models\Constant::proj_status_default)
                                            <button class="btn btn-sm btn-success proj-pass" proj_id="{{$proj->proj_id}}">通过</button>
                                            <button class="btn btn-sm btn-danger proj-refuse" proj_id="{{$proj->proj_id}}">驳回</button>
                                    @elseif ($proj->status == \App\Models\Constant::proj_status_pass)
                                            <button class="btn btn-sm btn-danger proj-refuse" proj_id="{{$proj->proj_id}}">驳回</button>
                                            <a href="{{route('saas.module', $proj->proj_id)}}">
                                                <button class="btn btn-sm btn-info">查看子模块</button>
                                            </a>
                                    @elseif ($proj->status == \App\Models\Constant::proj_status_refuse)
                                            <button class="btn btn-sm btn-success proj-pass" proj_id="{{$proj->proj_id}}">通过</button>
                                            <a href="{{route('saas.module', $proj->proj_id)}}">
                                                <button class="btn btn-sm btn-info">查看子模块</button>
                                            </a>
                                    @endif
                                        <button data-toggle="modal" data-target="#modifyInfo" proj_id="{{$proj->proj_id}}" class="btn btn-sm btn-info modifyInfo">详情</button>
                                    </td>
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
<script src="/js/libs/jquery.min.js"></script>
<script type="text/javascript">
    $(function() {
        //审核通过
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

        //审核拒绝
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