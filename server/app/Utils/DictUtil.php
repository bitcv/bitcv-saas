<?php

namespace App\Utils;

class DictUtil 
{
    // 命名规范 TableName_FieldName

    const Token_Protocol = [
        '1' => 'ERC20',
        '2' => 'BTC',
        '3' => 'NEO',
        '4' => 'DOGE',
    ];

    const UserAsset_Status = [
        '1' => '可提取',
        '2' => '转账中',
        '3' => '已提取',
    ];

    //英文版
    const UserAsset_EnStatus = [
        '1' => 'Extractable',
        '2' => 'Transfer',
        '3' => 'Extracted',
    ];

    const UserTransferRecord_Status = [
        '1' => '进行中',
        '2' => '交易成功',
        '3' => '交易失败',
    ];

    //英文版
    const UserTransferRecord_EnStatus = [
        '1' => 'Processing',
        '2' => 'Successful transaction',
        '3' => 'Transaction failed',
    ];

    const DepositBox_Status = [
        '0' => '待付款',
        '1' => '进行中',
        '2' => '已暂停',
        '3' => '已结束',
        '4' => '已清算',
    ];

    const DepositOrder_Status = [
        '0' => '待确认',
        '1' => '已完成',
        '2' => '已取消',
        '3' => '已过期',
    ];

    const DespositUserBox_Status = [
        '1' => '锁仓中',
        '2' => '可提取',
        '3' => '已提取',
    ];

    const DepositFinance_Type = [
        '1' => '购买',
        '2' => '收益',
        '3' => '提取',
    ];
    //钱包地址
    const WalletAddress = [
         '0' => '0xaed0363f76e4b906ef818b0f3199c580b5b01a43',
         '1' => '0x9eD38CAfc071f12f2a73c311f9F5c6D153A1A131'
    ];

    //钱包地址用途名称
    const WalletAddressName = [
      '0' => '奖金福利',
      '1' => '基金投资'
    ];

    //转账手续费
    const WalletFeeList = [
        // ERC20
        '1' => [
            ['level' => 1, 'amount' => '0.0004', 'amountStr' => '0.0004'],
            ['level' => 2, 'amount' => '0.0005', 'amountStr' => '0.0005'],
            ['level' => 3, 'amount' => '0.0006', 'amountStr' => '0.0006'],
        ],
        // BTC
        '2' => [
            ['level' => 1, 'amount' => '0.0001', 'amountStr' => '0.0001'],
            ['level' => 2, 'amount' => '0.0002', 'amountStr' => '0.0002'],
            ['level' => 3, 'amount' => '0.0003', 'amountStr' => '0.0003'],
        ],
        // DOGE
        '3' => [
            ['level' => 1, 'amount' => '1', 'amountStr' => '1'],
            ['level' => 2, 'amount' => '2', 'amountStr' => '2'],
            ['level' => 3, 'amount' => '3', 'amountStr' => '3'],
        ],
        // BSTK
        '4' => [
            ['level' => 1, 'amount' => '0.0001', 'amountStr' => '0.0001'],
            ['level' => 2, 'amount' => '0.0002', 'amountStr' => '0.0002'],
            ['level' => 3, 'amount' => '0.0003', 'amountStr' => '0.0003'],
        ],
    ];

    // 转账流水类型
    const UserFinance_Type = [
        '1' => '收入',
        '2' => '支出',
        '3' => '收入',
        '4' => '支出',
        '5' => '支出',
        '6' => '支出',
        '7' => '收入',
        '8' => '收入',
        '9' => '收入',
        '10' => '收入',
        '11' => '支出',
        '12' => '收入',
        '13' => '支出',
        '14' => '收入',
        '15' => '收入',
        '16' => '收入',
        '17' => '收入',
        '18' => '支出',
        '19' => '收入',

    ];

    const FinanceType_Icon = [
        '1' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '2' => 'https://www.bitcv.com/app_static/icon/finance_icon_out4.png',
        '3' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '4' => 'https://www.bitcv.com/app_static/icon/finance_icon_out4.png',
        '5' => 'https://www.bitcv.com/app_static/icon/finance_icon_out4.png',
        '6' => 'https://www.bitcv.com/app_static/icon/finance_icon_out4.png',
        '7' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '8' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '9' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '10' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '11' => 'https://www.bitcv.com/app_static/icon/finance_icon_out4.png',
        '12' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '13' => 'https://www.bitcv.com/app_static/icon/finance_icon_out4.png',
        '14' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '15' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '16' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '17' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
        '18' => 'https://www.bitcv.com/app_static/icon/finance_icon_out4.png',
        '19' => 'https://www.bitcv.com/app_static/icon/finance_icon_in4.png',
    ];

    const FinanceType_Title = [
        '1' => '收款',
        '2' => '转账',
        '3' => '收款',
        '4' => '转账',
        '5' => '服务费',
        '6' => '发糖包',
        '7' => '领糖包',
        '8' => '糖包退回',
        '9' => '平台赠送',
        '10' => '空投',
        '11' => '购买余币宝',
        '12' => '余币宝提取',
        '13' => '第三方支付',
        '14' => '第三方收入',
        '15' => '菠菜庄园',
        '16' => '系统补发',
        '17' => '第三方平台收款',
        '18' => '第三方平台付款',
        '19' => '市场营销费用',
    ];

    const FinanceType_Status = [
        '1' => '收款成功',
        '2' => '转账成功',
        '3' => '收款成功',
        '4' => '转账成功',
        '5' => '支付成功',
        '6' => '发送成功',
        '7' => '领取成功',
        '8' => '退回成功',
        '9' => '已入账',
        '10' => '已入账',
        '11' => '购买成功',
        '12' => '提取成功',
        '13' => '支付成功',
        '14' => '领取成功',
        '15' => '收取成功',
        '16' => '收取成功',
        '17' => '收取成功',
        '18' => '付款成功',
        '19' => '市场营销费用',
    ];

    const FinanceType_Tag = [
        '1' => '平台外收款',
        '2' => '平台外转账',
        '3' => '平台内收款',
        '4' => '平台内转账',
        '5' => '服务费',
        '6' => '发糖包',
        '7' => '领糖包',
        '8' => '糖包退回',
        '9' => '平台赠送',
        '10' => '空投',
        '11' => '购买余币宝',
        '12' => '余币宝提取',
        '13' => '第三方支付',
        '14' => '第三方收入',
        '15' => '菠菜庄园',
        '16' => '系统补发',
        '17' => '第三方平台收款',
        '18' => '第三方平台付款',
        '19' => '市场营销费用',
    ];

    //费用性质
    const TokenUsed = [
        '1' => '收到投资款',
        '2' => '收回投资币种',
        '3' => '收到余币宝投资',
        '4' => '币种兑换',
        '5' => '员工工资',
        '6' => '团队激励',
        '7' => '市场推广费',
        '8' => '币种投资',
        '9' => '币种出售',
        '10' => '币种兑换',
        '11' => '退回投资款',
        '12' => '手续费',
        '13' => '账户互转',
        '14' => '退回顾问费',//新增收入item
        '15' => '退回产品测试费',
        '16' => '退回推广费',
        '17' => '发行BCV',
        '18' => '顾问费',
        '19' => '产品测试费',
        '20' => '支付投资人BCV'
    ];
    // 代币符号
    const CoinType = [
        'BTC' => 'BTC',
        'BCV' => 'BCV',
        'ETH' => 'ETH',
        'LEND' => 'LEND',
        'CPU' => 'CPU',
        'TAC' => 'TAC',
        'EOS' => 'EOS',
        'BAI' => 'BAI',
        'INSUR' => 'INSUR',
        'LXT' => 'LXT',
        'ICST' => 'ICST',
        'PXC' => 'PXC',
        'AAC' => 'AAC'
    ];

    //类型
    const TokenType = [
      '1' => '收入',
      '2' => '支出',
      '3' => '账户互转',
    ];

    //项目主体
    const TokenSubject = [
      '1' => '员工',
      '2' => '投资人',
      '3' => 'A 类',
    ];

    const Dispense = [
        '0' => '',
        '1' => '进行中',
        '2' => '已完成',
    ];

    const DispenseStatus = [
        '1' => '不发',
        '2' => '可发',
        '3' => '已提交',
        '4' => '发送成功',
        '5' => '发送成功数量不匹配',
        '6' => '少发已补发',
        '7' => '多发'
    ];

    //费用性质
    const ITEMS = [
        '999' => '期初余额',
        '99' => '收入项目',
        '1' => '收到投资款',
        '2' => '收回投资币种',
        '3' => '收到余币宝投资',
        '4' => '币种兑换',
        '14' => '退回顾问费',//新增收入item
        '15' => '退回产品测试费',
        '16' => '退回推广费',
        '17' => '发行BCV',
        '199' => '支出项目',
        '5' => '员工工资',
        '6' => '团队激励',
        '7' => '市场推广费',
        '8' => '币种投资',
        '9' => '币种出售',
        '10' => '币种兑换',
        '11' => '退回投资款',
        '12' => '手续费',
        '18' => '顾问费',
        '19' => '产品测试费',
        '20' => '支付投资人BCV',
        '13' => '账户互转',
        '1999' => '期末余额',
        '2999' => '投资收益-期末折算损益'
    ];
}
