function acb_config()
{
    return array(
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'Cổng thanh toán ACB',
            'Description' => 'Thực hiện Thanh toán qua cổng thanh toán ACB',
        ),
        'nganhang' => array(
            'FriendlyName' => 'Hãy chọn ngân hàng',
            'Type' => 'dropdown',
            'Options' => array(
                'ACB' => 'ACB',
                'VCB' => 'VCB',
                'TPBANK' => 'TPBANK',
                'MBBANK' => 'MBBANK',
            ),
            'Description' => 'Vui lòng chọn ngân hàng từ danh sách. Vui lòng chọn đúng ngân hàng để tạo được mã QR.',
        ),
        'ten_tai_khoan' => array(
            'FriendlyName' => 'Tên tài khoản ACB',
            'Type' => 'text',
            'Default' => '',
            'Description' => 'Vui lòng nhập tên chủ tài khoản ACB',
        ),
        'stk' => array(
            'FriendlyName' => 'Sô tài khoản ACB',
            'Type' => 'text',
            'Default' => '',
            'Description' => 'Cung cấp số tài khoản ACB',
        ),
        'token' => array(
            'FriendlyName' => 'Token sử dụng ACB',
            'Type' => 'text',
            'Default' => '',
            'Description' => 'Token sử dụng ACB',
        ),
        'logo' => array(
            'FriendlyName' => 'Nhập Url Logo',
            'Type' => 'text',
            'Default' => 'https://' . $_SERVER['SERVER_NAME'] . '/assets/img/logo.png',
            'Description' => 'Cung cấp link logo Website của bạn',
        ),
        'sdt' => array(
            'FriendlyName' => 'Số điện thoại hỗ trợ',
            'Type' => 'text',
            'Default' => '',
            'Description' => 'Cung cấp số điện thoại hỗ trợ sự cố',
        ),
        'email' => array(
            'FriendlyName' => 'Email hỗ trợ',
            'Type' => 'text',
            'Default' => '',
            'Description' => 'Cung cấp Email hỗ trợ sự cố',
        ),
        'noidung' => array(
            'FriendlyName' => 'Nhập nội dung đơn',
            'Type' => 'text',
            'Default' => 'DH{{orderid}}',
            'Description' => 'Nội dung thanh toán giúp chủ shop nhận biết được thanh toán cho đơn hàng nào. Với {{orderid}} là mã đơn hàng.',
        ),
        'license_key' => array(
            'FriendlyName' => 'License Key',
            'Type' => 'text',
            'Default' => '',
            'Description' => 'Nhập key kích hoạt của bạn',
        ),
        'url_callback' => array(
            'FriendlyName' => 'Link CallBack',
            'Description' => 'https://' . $_SERVER['SERVER_NAME'] . '/client/modules/gateways/callback/ipn_acb.php',
        ),
    );
}
