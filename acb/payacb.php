<?php
require_once __DIR__ . '/option.php';
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Detect module name from filename.
$gatewayModuleName = basename('acb');

// Fetch gateway configuration parameters.
$gatewayParams = getGatewayVariables($gatewayModuleName);
//include 'option.php';

if (!isset($_GET['orderId'])) {
    echo json_encode([
        'code' => 400,
        'status' => 'error',
        'msg' => 'Bad Request',
    ]);
    die();
}
$invoiceId = $_GET['orderId'];
$invoiceId2 = $gatewayParams['invoiceid'];

$array = array(
    '{{orderid}}' => $invoiceId,
);
$orderId = $invoiceId;
$noidung = strtr($gatewayParams['noidung'], $array);
$key = $gatewayParams['apikey'];
$amount = round($_GET['amount'], 0);
$ten = $gatewayParams['ten_tai_khoan'];
$sdt = $gatewayParams['sdt'];
$email = $gatewayParams['email'];
$stk = $gatewayParams['stk'];
$password = $gatewayParams['password'];
$cty = $gatewayParams['companyname'];
$logo = $gatewayParams['logo'];
$bank = $gatewayParams['nganhang'];
$qrcode = "https://api.vietqr.io/image/$bank-$stk-36cNbfw.jpg?amount=$amount&addInfo=$noidung&accountName=$ten";
$urldone = $gatewayParams['systemurl'] . "viewinvoice.php?id=" . $orderId . "&paymentSuccess=true";
$urlback = $gatewayParams['systemurl'] . "viewinvoice.php?id=" . $orderId . "&paymentfailed=true";
$url = $gatewayParams['systemurl'] . "modules/gateways/callback/request.php?&orderId=" . $orderId;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>Cổng thanh toán ACB</title>

    <meta property="og:locale" content="vi_VN" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="ACB Payment" />
    <meta property="og:image:width" content="800" />
    <meta property="og:image:height" content="366" />
    <script src="https://api.spayment.vn/Scripts/sweetalert/sweetalert.min.js"></script>
    <link
      href="https://api.spayment.vn/Scripts/sweetalert/sweetalert.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="./assets/css/bootstrap.min.css"
      integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
      crossorigin="anonymous"
    />
    <link media="screen" rel="stylesheet" href="./assets/css/core.css" />
    <link
      href="./assets/images/icons/acb.png"
      rel="SHORTCUT ICON"
    />
    <script
      crossorigin="anonymous"
      integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
      src="./assets/js/jquery-3.5.1.min.js"
    ></script>
    <script src="./assets/js/lazyload.js"></script>

    <meta content="width=device-width, initial-scale=1" name="viewport" />

    <meta name="_csrf" content="e48b6b60-26fd-4aaf-b284-16d1ad3bf246" />

    <!-- default header name is X-CSRF-TOKEN -->

    <meta name="_csrf_header" content="X-CSRF-TOKEN" />

    <link media="screen" rel="stylesheet" href="./assets/css/payment.css" />

    <link media="screen" rel="stylesheet" href="./assets/css/qr.css" />

    <link rel="stylesheet" href="./assets/css/style.css" />
  </head>
    <style>
        .box-detail {
            margin-bottom: 20px;
        }
        .popup {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 10px; /* Bo cong vien */
            padding: 16px;
            position: fixed;
            z-index: 1;
            right: 30px;
            bottom: 30px; /* Dat o phia tren */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); /* Hieu ung bong */
            animation: fadeIn 0.5s ease-in-out; /* Hieu ung hien thi */
        }
        .popup.show {
            visibility: visible;
            animation: fadeOut 2s ease-in-out forwards; /* Hieu ung an */
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); }
        }
        
.marquee {
  width: 100%;
  white-space: nowrap;
  overflow: hidden;
  box-sizing: border-box;
  padding: 10px; /* Thêm padding */
}

.marquee span {
  display: inline-block;
  top: 0;
  left: 100%;
  white-space: nowrap;
  animation: marquee 15s linear infinite;
  font-size: 1.5em; /* Chữ to hơn */
  font-weight: bold; /* Chữ đậm hơn */
}

@keyframes marquee {
  0% {
    transform: translateX(100%);
  }
  100% {
    transform: translateX(-100%);
  }
}
    </style>

  <body>
      <header id="header">
      <div class="container">
        <div class="header-wrapper">
          <div class="logo-wrap">
            <a class="navbar-brand">
               <img src="./assets/images/icons/acb.png" style="width: 40px; height: auto; border-radius: 10%;">
            </a>
            <span class="logo-title"><b>Cổng Thanh Toán</b> <span style="color: #0000ff;font-weight: 700 !important;font-size: 18px !important;">ACB</span></span>
          </div>
        </div>
      </div>
            <div class="footer-wrap-info">
              <a class="momo-phone-icon" href="tel:<?php echo $sdt ?>"
                ><?php echo $sdt ?></a
              >
              <a class="momo-mail-icon" href="mailto:<?php echo $email ?>"
                ><?php echo $email ?></a
              >
            </div>
    </header>
    <div class="payment-container content">
      <div></div>
      <div class="container" id="contents">
        <div
          class="modal-background modal momoModal"
          id="paymentModal"
          role="dialog"
          tabindex="-1"
        >
          <div
            class="modal-instruction modal-dialog-centered w-75"
            role="document"
          >
          </div>
        </div>

        <div class="row qr-web d-flex justify-content-center" id="qr-web-ui">
          <div class="row payment-form" id="form-qr-code">
            <div class="col-lg-4 payment-form">
              <div class="ui-desktop">
                <div class="info-content">
                  <div class="payment-info payment-info-bottom">
                    <div class="payment-title">
                      <h1>Thông tin đơn hàng</h1>
                    </div>
                    <div class="payment-detail">
                      <div class="box-detail">
                        <h4>Nhà cung cấp</h4>
                        <table>
                          <tr>
                            <td>
                              <img class="merchant-logo" src="<?php echo $logo ?>" />
                            </td>
                            <td>
                              <p class="merchant-name"><?php echo $cty ?></p>
                            </td>
                          </tr>
                        </table>
                      </div>

                    <div class="line-detail"></div>
                    <div class="box-detail">
                        <h4 style="margin-bottom: 10px;">Nội dung chuyển tiền</h4>
                        <div style="display: flex; align-items: center;">
                            <p id="noidung" style="margin: 0 10px 0 0;"><?php echo $noidung; ?></p>
                            <button style="background-color: #33cc33; border-radius: 10px; font-weight: bold; color: white; border: none; padding: 5px 10px; margin-left: 70px;" onclick="copyContentND()"><small>Sao chép</small></button>
                        </div>
                    </div>

                    <div class="popup" id="popup1">Đã sao chép nội dung</div>

                    <script>
                        function copyContentND() {
                            var copyText = document.getElementById("noidung");
                            var range = document.createRange();
                            range.selectNode(copyText);
                            window.getSelection().removeAllRanges();
                            window.getSelection().addRange(range);
                            document.execCommand("copy");
                            window.getSelection().removeAllRanges();
                            showPopupND();
                        }

                        function showPopupND() {
                            var popup = document.getElementById("popup1");
                            popup.classList.add("show");
                            setTimeout(function() {
                                popup.classList.remove("show");
                            }, 900);
                        }
                    </script>


                      <div class="line-detail"></div>
                      <div class="box-detail">
                        <h4>Chủ tài khoản</h4>
                        <p><?php echo $ten ?></p>
                      </div>

                      <div class="line-detail"></div>
                      <div class="box-detail">
                          <h4 style="margin-bottom: 10px;">Số tài khoản</h4>
                          <div style="display: flex; align-items: center;">
                              <p id="stk" style="margin: 0 10px 0 0;"><?php echo $stk; ?></p>
                              <button style="background-color: #33cc33; border-radius: 10px; font-weight: bold; color: white; border: none; padding: 5px 10px; margin-left: 60px;" onclick="copyContentSTK()"><small>Sao chép</small></button>
                          </div>
                      </div>

                      <div class="popup" id="popup2">Đã sao chép STK</div>

                      <script>
                          function copyContentSTK() {
                              var copyText = document.getElementById("stk");
                              var range = document.createRange();
                              range.selectNode(copyText);
                              window.getSelection().removeAllRanges();
                              window.getSelection().addRange(range);
                              document.execCommand("copy");
                              window.getSelection().removeAllRanges();
                              showPopupSTK();
                          }
                      
                          function showPopupSTK() {
                              var popup = document.getElementById("popup2");
                              popup.classList.add("show");
                              setTimeout(function() {
                                  popup.classList.remove("show");
                              }, 900);
                          }
                      </script>

                      <div class="line-detail"></div>
                      <div class="box-detail">
                        <h4>Ngân hàng thụ hưởng</h4>
                        <p>Ngân hàng <?php echo $bank; ?></p>
                      </div>

                      <div class="line-detail"></div>
                      <div class="box-detail">
                        <h4>Số tiền</h4>
                        <h3><?php echo number_format($amount) ?>đ</h3>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="expire-content mb-3">
                  <div class="box-expire">
                    <div class="expire-text">
                      <p>
                           <div id="hide"><strong>
                        Đơn hàng sẽ hết hạn sau: </strong><br />
                        <span
                          class="font-weight-bold time-expire-text d-inline-flex"
                          id="expiredAt" name="expiredAt"
                        ></span>
                      </p>
                      </div>
                            <div id="thanhcong"></div>
                    </div>
                  </div>
                </div>
                
                <script>
                  $(".toggle-btn").click(function () {
                    if ($(".text-data-es").hasClass("d-none")) {
                      $(this).addClass("test");
                    } else {
                      $(this).addClass("test2");
                    }
                    $(this).toggleClass("active");
                    $(".text-data-es").toggleClass("d-none");
                  });
                </script>
              </div>
              <div class="text-center my-3 font-size-10 font-weight-bold-600">
                <button id="backButton" style="background-color: #1aa3ff; border: none; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; margin: 20px 2px 4px; cursor: pointer; border-radius: 5px; transition-duration: 0.4s;">
                  Quay về
                </button>
              </div>
            </div>
            <div class="qr-code col-lg-8">
              <div class="payment-content" id="body-payment-content">
                <div
                  class="payment-qr col-sm-7 mx-auto pb-0 pt-3 pb-3 text-white"
                >
                  <div class="payment-cta p-0">
                    <marquee class="marquee">
                     <span>Vui lòng đợi hệ thống duyệt thành công trước khi đóng tab trình duyệt!</span>
                    </marquee>       
                    <div>
                      <h1>Quét mã QR để thanh toán</h1>
                    </div>
                    <div id="momo-mark" class="mx-auto pt-3"></div>

                    <div class="qrcode_scan_container">
                      <div class="qrcode_scan">
                        <div class="qrcode_gradient" >
                          <img
                            alt=""
                            src="./assets/qrcode-gradient-ecfeda44ae2870718219b2046c4abe3e.png"
                            loading="lazy"
                            class="jsx-d22f6bd0771ae323 img-fluid"
                          />
                        </div>
                        <div class="qrcode_border">
                          <img
                            alt=""
                            src="./assets/border-qrcode-deab3eb55f9ef6d6a8d6d1b5b194b36c.svg"
                            class="jsx-d22f6bd0771ae323 img-fluid"
                          />
                        </div>
                        <div>
                         <img src="<?php echo $qrcode; ?>" class="mx-5 pt-10" style="width: 250px; height: 250px;">
                        </div>
                      </div>
                    </div>
                    <div class="mt-4 text-sm">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        class="jsx-d22f6bd0771ae323 mr-1 inline h-6 w-6"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"
                          class="jsx-d22f6bd0771ae323"
                        ></path>
                      </svg>

                      <a
                        >Sử dụng <b> App Ngân hàng </b> hoặc ứng dụng camera hỗ trợ
                        QR code để quét mã</a
                      >
                    </div>
                  </div>
                </div>
              </div>
                <marquee class="marquee">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/vietcombank.svg" alt="Vietcombank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/acb.svg" alt="ACB Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/bidv.svg" alt="BIDV Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/vietinbank.svg" alt="vietinbank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/agribank.svg" alt="agribank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/abbank.svg" alt="abbank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/baovietbank.svg" alt="baovietbank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/hdbank.svg" alt="HDBANK Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/oceanbank.svg" alt="OceanBank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/scb.svg" alt="SCB Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/bidc.svg" alt="BIDC Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/vietabank.svg" alt="VietABank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/eximbank.svg" alt="Eximbank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/coopbank.svg" alt="CoopBank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/vietbank.svg" alt="VietBank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/publicbank.svg" alt="Public Bank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/saigonbank.svg" alt="SaigonBank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/viettelmoney.svg" alt="Viettel Money Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/techcombank.svg" alt="Techcombank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/mbbank.svg" alt="MB Bank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/vpbank.svg" alt="VPBank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/vib.svg" alt="VIB Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/sacombank.svg" alt="Sacombank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/tpbank.svg" alt="TPBank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/msb.svg" alt="MSB Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/shb.svg" alt="SHB Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/ocb.svg" alt="OCB Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/bacabank.svg" alt="BacaBank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/kienlongbank.svg" alt="Kienlongbank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/pvcombank.svg" alt="PVcomBank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
                 <img src="https://sandbox.vnpayment.vn/paymentv2/images/img/logos/bank/big/cbbank.svg" alt="CBBank Logo" style="border: 1px solid #e6e6e6; padding: 5px; border-radius: 10px; max-height: 100px;" onmousedown="return false;" ondragstart="return false;" oncontextmenu="return false;">
             </marquee>  
            </div>
          </div>

          <form
            action="./assets/action"
            id="cancel-payment-form"
            method="post"
            novalidate=""
          >
            <input
              name="_csrf"
              value="e48b6b60-26fd-4aaf-b284-16d1ad3bf246"
              type="hidden"
            />
            <input
              name="sessionId"
              value="TU9NT1dYWUoyMDE5MDYxM3wxNjg5OTUzNDYwOjU4NjUzOQ"
              type="hidden"
            />
            <input
              name="mToken"
              value="BcbKzWCESfB+JcyIW6jaAwm4KoxcFEITyAA8Z5Jd9xHd34+fz17tQy0Qgx8eitNVFhSj0gKdDcY="
              type="hidden"
            />
            <div>
              <button
                class="btn btn-momo-cancel"
                id="btn-momo-cancel"
                name="action"
                style="display: none"
                type="submit"
                value="cancel"
              >
                Quay lại
              </button>
              <button
                id="btn-momo-expire"
                name="action"
                style="display: none"
                type="submit"
                value="expire"
              >
                Expire
              </button>
            </div>
          </form>
        </div>
        <script async="" src="./assets/js/js"></script>
        <script>
        var offset = 598626;
                var second = offset / 1000;
                var countdown = parseInt(second);
                var offset = 598626;
                var retry = 500;
          var isTablet = false;
          var device;
          if (false) {
            device = "mobile";
          } else if (false) {
            device = "tablet";
          } else device = "desktop";
          var trackingModel = {
            merchant_id: "MOMOWXYJ20190613",
            order_id: "1689953460:586539",
            service_name: "payment_momowallet",
            screen_name: "wallet_pgw",
            event_name: null,
            device: "desktop",
            api: null,
            button_name: null,
            status: null,
            component_name: null,
            category: null,
            field_name: null,
          };

$(document).ready(function () {
  // Thời gian offset ban đầu (10 phút = 600 giây)
  var offset = 600; // 10 phút

  // Lấy thời gian từ localStorage nếu có, nếu không sử dụng giá trị offset ban đầu
  var e = localStorage.getItem('remainingTime') ? parseInt(localStorage.getItem('remainingTime')) : offset;
  var t = parseInt(e);

  var n = setInterval(function () {
    var d = parseInt(e / 86400),
      b = parseInt((e - d * 86400) / 3600),
      a = parseInt((e - d * 86400 - b * 3600) / 60),
      c = parseInt(e - 60 * a - b * 3600 - d * 86400);
    e--,
    t--,
    a < 10 && (a = "0" + a),
    b < 10 && (b = "0" + b),
    c < 10 && (c = "0" + c);

    // Lưu thời gian còn lại vào localStorage
    localStorage.setItem('remainingTime', e);

    if (d > 0) {
      d < 10 && (d = "0" + d);
      $("span[name=expiredAt]").html(
        '<div class="time-box">' +
          d +
          "<br>" +
          "<p>" +
          "Ngày" +
          "</p>" +
          "</div>" +
          '<div class="time-box">' +
          b +
          "<br>" +
          "<p>" +
          "Giờ" +
          "</p>" +
          "</div>" +
          '<div class="time-box">' +
          a +
          "<br>" +
          "<p>" +
          "Phút" +
          "</p>" +
          "</div>" +
          '<div class="time-box">' +
          c +
          "<br>" +
          "<p>" +
          "Giây" +
          "</p>" +
          "</div>"
      ),
      $("b[name=expiredAt]").html(
        d +
          " Ngày : " +
          b +
          " Giờ : " +
          a +
          " Phút : " +
          c +
          " Giây "
      );
    } else if (d <= 0 && b > 0) {
      $("span[name=expiredAt]").html(
        '<div class="time-box">' +
          b +
          "<br>" +
          "<p>" +
          "Giờ" +
          "</p>" +
          "</div>" +
          '<div class="time-box">' +
          a +
          "<br>" +
          "<p>" +
          "Phút" +
          "</p>" +
          "</div>" +
          '<div class="time-box">' +
          c +
          "<br>" +
          "<p>" +
          "Giây" +
          "</p>" +
          "</div>"
      ),
      $("b[name=expiredAt]").html(
        b + " Giờ : " + a + " Phút : " + c + " Giây "
      );
    } else if (d <= 0 && b <= 0) {
      $("span[name=expiredAt]").html(
        '<div class="time-box">' +
          a +
          "<br>" +
          "<p>" +
          "Phút" +
          "</p>" +
          "</div>" +
          '<div class="time-box">' +
          c +
          "<br>" +
          "<p>" +
          "Giây" +
          "</p>" +
          "</div>"
      ),
      $("b[name=expiredAt]").html(
        a + " Phút : " + c + " Giây "
      );
    }

    // Dừng đếm ngược khi thời gian hết
    if (e <= 0) {
      clearInterval(n);
      localStorage.removeItem('remainingTime');
      swal({
        title: "Thanh toán không thành công!",
        text: "Vui lòng thanh toán lại hoặc liên hệ với Quản trị viên!",
        icon: "error",
        buttons: false,
        timer: 5000, // Tự động đóng sau 5 giây
        closeOnClickOutside: true,
        closeOnEsc: true,
      }),
      window.location = "<?php echo $urlback ?>";
    }
  }, 1000);

  $.ajaxSetup({ cache: !1 });
  setUpUI();
});

function back() {
  if (trackingModel) {
    const params = trackingModel;
    params.component_name = "cancel_confirmation";
    params.category = "popup";
    params.button_name = "confirm";
  }
  window.location = "<?php echo $urlback ?>";
}

function closeCommonModalAndSendEvent() {
  if (trackingModel) {
    const params = trackingModel;
    params.component_name = "cancel_confirm";
    params.category = "popup";
    params.button_name = "close";
  }
  $(".common-modal").remove();
}

(document.onreadystatechange = function () {
  if ("interactive" === document.readyState && trackingModel) {
    const params = trackingModel;
    params.api = "payUrl";
    params.status = "success";
  }
  "complete" == document.readyState &&
    setTimeout(function () {
      document.getElementById("interactive"),
      document.getElementById("load").remove(),
      document
        .getElementById("contents")
        .classList.remove("d-none");
      if (trackingModel) {
        const params = trackingModel;
        params.api = "qr";
        params.status = "success";
      }
    }, 1e3);
});

function redirect(data) {
  if (trackingModel) {
    const params = trackingModel;
    params.api = "redirectUrl";
    params.status = "success";
  }
  if (data.return_url) {
    window.location.replace(data.return_url);
  } else if (data.returnUrl) {
    window.location.replace(data.returnUrl);
  }
}


          function executeXML() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
              if (this.readyState == 4 && this.status == 200) {
                // Typical action to be performed when the document is ready:
                var data = JSON.parse(xhttp.responseText);
                if (data.code == "400") {
                  setTimeout(executeXML, 1000);
                  retry--;
                } else if (data.code == "200") {
                  $("#hide").html("");
                  $("#thanhcong").html(
                    '<div class="expire-text">Thanh toán thành công.<br/>Vui lòng chờ trong giây lát.</div>'
                  );
                  setTimeout(function () {
                    window.location = "<?php echo $urldone ?>";
                  }, 3000);
                }
              }
            };
            xhttp.open(
              "POST",
              "<?php echo $url ?>" + "&_=" + new Date().getTime(),
              true
            );
            xhttp.send();
          }

          $(document).ready(function () {
            $.ajaxSetup({
              cache: false,
            });
            setTimeout(executeXML, 4000);
          });

          function showModal() {
            if (trackingModel) {
              // const params = Object.fromEntries(Object.entries(trackingModel).filter(([_, v]) => v != null));
              const params = trackingModel;
              params.button_name = "user_guide";
            }
            $(".modal-background").css("display", "block");
          }

          function closeModal() {
            $(".modal-background").css("display", "none");
          }

          function setUpUI() {
            if ("152.900" == 0) {
              var expireText = $(
                "<div class='expire-content mb-3'> <div class='box-expire' style='width: 350px; margin: auto'> <div class='expire-text'><p>Đơn hàng sẽ hết hạn sau: <span class='font-weight-bold time-expire-text d-inline-flex' name='expiredAt'></span></p></div></div></div>"
              );
              var instruction = $(
                "<div class='text-center my-3 font-size-14'><a class='black12-color mr-1'>Gặp khó khăn khi thanh toán?</a><a onclick='return showModal()' class='blue-color font-weight-bold-600' href='#'>Xem Hướng dẫn</a></div>"
              );
              var backButtonObjectRender = $(
                "<div class='text-center my-3 font-size-14 font-weight-bold-600'><a class='momo-color backButton' href='#'>Quay về</a></div>"
              );

              $(".payment-qr").css("padding", "30px 0px 30px 0px");

              $("#orderInfo").remove();
              $("#form-qr-code").removeClass("col-lg-8");

              isTablet
                ? $("#form-qr-code").addClass("col-lg-12")
                : $("#form-qr-code").addClass("col-lg-12");

              $(".payment-qr")
                .append(expireText)
                .append(instruction)
                .append(backButtonObjectRender);
            }

            $("#backButton").click(function () {
              if (
                "captureWallet" === "payWithMethod" &&
                (null === null || null === false)
              ) {
                location.reload();
                return false;
              }
              if (trackingModel) {
                // const params = Object.fromEntries(Object.entries(trackingModel).filter(([_, v]) => v != null));
                const params = trackingModel;
                params.button_name = "back";
              }
              stringPartner =
                "Bạn chắc chắn muốn hủy giao dịch thanh toán với " +
                "<strong>" +
                "<?php echo $cty ?>" +
                "</strong>" +
                "?";
              showCommonModal(
                "Huỷ giao dịch thanh toán",
                stringPartner,
                "HUỶ GIAO DỊCH",
                "return back()",
                "ĐÓNG",
                "return closeCommonModalAndSendEvent()"
              );
              return false;
            });
          }
        </script>
        <script src="./assets/js/core.js"></script>
      </div>
    </div>
    <div>
      <footer id="footer">
        <div class="container">
          <div class="footer-wrapper">
            <div class="footer-wrap-version">
            <span>© 2024 - Cổng thanh toán ACB v4.2.0</span>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <script>
      if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
      }
    </script>
  </body>
</html>
