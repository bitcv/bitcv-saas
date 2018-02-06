@extends('layouts.saas')

@section('css')
    <link href="/static/admin/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@endsection
@section('menu')
    @include("saas.menu")
@endsection
@section('content')
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Welcome to Bitcv.</span>
                        </li>

                        <li>
                            <a href="#">
                                <i class="fa fa-sign-out"></i>退出
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
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
                                            <th>模块id</th>
                                            <th>审核时间</th>
                                            <th>审核状态</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data as $module)
                                            <tr class="gradeX">
                                                <td>{{$module->proj_id}}</td>
                                                <td>{{$module->mod_id}}</td>
                                                <td>{{$module->ctime}}</td>
                                                <td>{{\App\Models\Constant::$mode_valid_ch[$module->valid]}}</td>
                                                <td>
                                                @if ($module->valid == \App\Models\Constant::mod_valid_use)
                                                        <button class="btn btn-sm btn-danger module-refuse" proj_id="{{$module->proj_id}}">禁止使用</button>
                                                @else
                                                        <button class="btn btn-sm btn-success module-pass" proj_id="{{$module->proj_id}}">允许使用</button>
                                                @endif
                                                    <button class="btn btn-sm btn-info add-module" proj_id="{{$module->proj_id}}">开通模块</button>
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
        $('.module-pass').click(function() {
            proj_id = $(this).attr('proj_id');
            $.post(
                "{{route('saas.proj.mod')}}",
                {
                    proj_id : proj_id,
                    status  : 1
                },

                function (data) {
                    console.log(data);
                }
            );
        });

        $('.module-refuse').click(function () {
            proj_id = $(this).attr('proj_id');
            $.post(
                "{{route('saas.proj.mod')}}",
                {
                    proj_id : proj_id,
                    status  : 2
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