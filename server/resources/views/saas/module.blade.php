@extends('layouts.saas')

@section('css')
    <link href="/static/admin/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@endsection
@section('menu')
    @include("saas.menu")
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
    </div>
    <div class="col-lg-2">
        <button data-toggle="modal" data-target="#addModule" class="btn btn-sm btn-info add-module">开通模块</button>
    </div>
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
<div class="modal inmodal fade" id="addModule" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">开通模块</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="addModuleForm" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">项目名称：</label>
                        <div class="col-lg-10">
                            <input class="form-control" name="proj_name" readonly type="text" value="{{$proj['org']}}">
                            <input class="form-control" id="proj_id_form" name="proj_id" type="hidden" value="{{$proj_id}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">模块：</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="mod_id_form" name="mod_id">
                                @foreach (\App\Models\Constant::$mod_id_ch as $mod_id => $mod_id_v)
                                    <option value="{{$mod_id}}">{{$mod_id_v}}</option>
                                @endforeach;
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="addModuleBtn">提交</button>
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

<script type="text/javascript">
    $(function() {
        $('.module-pass').click(function() {
            proj_id = $(this).attr('proj_id');
            valid = {{\App\Models\Constant::mod_valid_use}}
            $.post(
                "{{route('saas.proj.mod')}}",
                {
                    proj_id : proj_id,
                    valid  : valid
                },

                function (data) {
                    if (data.retcode == 200) {
                        alert('修改成功！');
                    } else {
                        alert('修改失败！');
                    }

                    location.reload();
                }
            );
        });

        $('.module-refuse').click(function () {
            proj_id = $(this).attr('proj_id');
            valid = {{\App\Models\Constant::mod_valid_unuse}}
            $.post(
                "{{route('saas.proj.mod')}}",
                {
                    proj_id : proj_id,
                    valid   : valid
                },

                function (data) {
                    if (data.retcode == 200) {
                        alert('修改成功！');
                    } else {
                        alert('修改失败！');
                    }

                    location.reload();
                }
            );
        });

        $('#addModuleBtn').click(function () {
            proj_id = $('#proj_id_form').val();
            mod_id  = $('#mod_id_form').val();
            url     = "{{route('saas.proj.add')}}";
            $.post(
                url,

                {
                    proj_id : proj_id,
                    mod_id  : mod_id
                },

                function (data) {
                    if (data.retcode == 200) {
                        alert('添加模块成功！');
                    } else {
                        alert(data.msg);
                    }

                    console.log(data);
                }
            );
        });
    });
</script>
@stop
