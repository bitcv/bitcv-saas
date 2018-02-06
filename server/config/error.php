<?php
//错误信息统一记录，不会出现重复的retcode，以及集中便于管理，user_msg提供用户友好的错误信息展示
return array(
    'add_project_error'     => array('retcode'   => '500001', 'msg'    => 'saas_proj添加失败！', 'user_msg' => '申请失败，请重新尝试！'),
    'add_mod_error'         => array('retcode'   => '500002', 'msg'    => 'proj_mod添加失败！', 'user_msg' => '申请失败，请重新尝试！'),
);