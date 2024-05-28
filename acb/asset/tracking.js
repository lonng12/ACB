/**
 * HaiNguyen -- 2017-12-27
 * Wrapper Ajax of fw7
 * Follow Convenient SDK
 */

var EV = {
    "SIGN_UP_PAGE": "SIGN_UP_PAGE",
    "LOGIN_PAGE": "LOGIN_PAGE",
    "QR_CODE_PAGE": "QR_CODE_PAGE",
    "OPEN_JUMPAGE": "OPEN_JUMPAGE",
    "CF_SIGN_UP_OTP_PAGE": "CF_SIGN_UP_OTP_PAGE",
    "MAP_BANK_PAGE": "MAP_BANK_PAGE",
    "ATM_MAP_BANK_PAGE": "ATM_MAP_BANK_PAGE",
    "CF_ATM_MAP_BANK_PAGE": "CF_ATM_MAP_BANK_PAGE",
    "CASH_IN_PAGE": "CASH_IN_PAGE",
    "PAYMENT_PAGE": "PAYMENT_PAGE",
    "RESULT_PAYMENT": "RESULT_PAYMENT",
    "CLICK_LOGIN_ON_QR_PAGE": "CLICK_LOGIN_ON_QR_PAGE",
    "CLICK_SIGN_UP_ON_QR_PAGE": "CLICK_SIGN_UP_ON_QR_PAGE",
    "CANCEL_ORDER_ON_QR_PAGE": "CANCEL_ORDER_ON_QR_PAGE",
    "CLICK_BTN_SIGN_UP": "CLICK_BTN_SIGN_UP",
    "CLICK_BTN_CF_SIGN_UP": "CLICK_BTN_CF_SIGN_UP",
    "CLICK_BTN_MAP_ATM": "CLICK_BTN_MAP_ATM",
    "CLICK_BTN_CF_MAP_ATM": "CLICK_BTN_CF_MAP_ATM",
    "CLICK_BTN_CF_CASHIN": "CLICK_BTN_CF_CASHIN",
    "CLICK_BTN_CF_PAYMENT": "CLICK_BTN_CF_PAYMENT",
    "CLICK_BTN_CF_LOGIN": "CLICK_BTN_CF_LOGIN",
    "CANCEL_ORDER_JUMPAGE": "CANCEL_ORDER_JUMPAGE",
    "CLICK_SIGN_UP_ON_JUMPAGE": "CLICK_SIGN_UP_ON_JUMPAGE",
    "OPEN_MOMO_APP_ON_JUMPAGE": "OPEN_MOMO_APP_ON_JUMPAGE",
    "CLICK_RESEND_OTP": "CLICK_RESEND_OTP",
    "CF_OTP_SIGN_UP_SUCCESS": "CF_OTP_SIGN_UP_SUCESS",
    "CF_OTP_SIGN_UP_FAIL": "CF_OTP_SIGN_UP_FAIL",
    "CF_OTP_MAP_ATM_FAIL": "CF_OTP_MAP_ATM_FAIL",
    "CF_OTP_MAP_ATM_SUCCESS": "CF_OTP_MAP_ATM_SUCESS",
    "CF_PAYMENT_OTP_PAGE": "CF_PAYMENT_OTP_PAGE",
    "RESEND_OTP_SUCCESS": "RESEND_OTP_SUCCESS",
    "RESEND_OTP_FAIL": "RESEND_OTP_FAIL",
    "PREPARED_DATA_SIGN_UP_SUCCESS": "PREPARED_DATA_SIGN_UP_SUCCESS",
    "PREPARED_DATA_SIGN_UP_FAIL": "PREPARED_DATA_SIGN_UP_FAIL",
    "PREPARED_DATA_ATM_SUCCESS": "PREPARED_DATA_ATM_SUCCESS",
    "PREPARED_DATA_ATM_FAIL": "PREPARED_DATA_ATM_FAIL",
    "CASHIN_SUCCESS": "CASHIN_SUCCESS",
    "CASHIN_FAIL": "CASHIN_FAIL",
    "CF_MAP_ATM_SUCCESS": "CF_MAP_ATM_SUCCESS",
    "CF_MAP_ATM_FAIL": "CF_MAP_ATM_FAIL",
    "ORDER_EXPRIED": "ORDER_EXPRIED",
    "USER_CLOSE_PAGE_RESULT_PAYMENT": "USER_CLOSE_PAGE_RESULT_PAYMENT",
    "USER_REDIRECTED_PAGE_RESULT_PAYMENT": "USER_REDIRECTED_PAGE_RESULT_PAYMENT",
    "RESULT_PAY_ATM": "RESULT_PAY_ATM",
    "PAYMENT_SUCCESS": "PAYMENT_SUCCESS",
    "PAYMENT_FAIL": "PAYMENT_FAIL",
    "CLICK_CLOSE_BROWSER_REGISTER_PAGE": "CLICK_CLOSE_BROWSER_REGISTER_PAGE",
    "CLICK_CLOSE_BROWSER_OTP_PAGE": "CLICK_CLOSE_BROWSER_OTP_PAGE",
    "CLICK_CLOSE_BROWSER_QR_PAGE": "CLICK_CLOSE_BROWSER_QR_PAGE",
    "CLICK_CLOSE_BROWSER_LOGIN_PAGE": "CLICK_CLOSE_BROWSER_LOGIN_PAGE",
    "CLICK_CLOSE_BROWSER_MAPBANK_PAGE": "CLICK_CLOSE_BROWSER_MAPBANK_PAGE",
    "CLICK_CLOSE_BROWSER_CF_PAYMENT_PAGE": "CLICK_CLOSE_BROWSER_CF_PAYMENT_PAGE",
    "BACK_TO_MERCHANT_RESULT_PAGE": "BACK_TO_MERCHANT_RESULT_PAGE",
    "JUMP_PAGE_SIGNUP": "JUMP_PAGE_SIGNUP",
    "JUMP_PAGE_BACK_TO_MERCHANT_RESULT_PAGE": "JUMP_PAGE_BACK_TO_MERCHANT_RESULT_PAGE",
    "JUMP_PAGE_LOGIN_PAGE": "JUMP_PAGE_LOGIN_PAGE",
    "INSTALL_APP_ANDROID": "INSTALL_APP_ANDROID",
    "INSTALL_APP_IOS": "INSTALL_APP_IOS",
    OPEN_APP_LINKS_PAGE: "OPEN_APP_LINKS_PAGE",
    OPEN_STORE_FROM_ANDROID: "OPEN_STORE_FROM_ANDROID",
    OPEN_STORE_FROM_IOS: "OPEN_STORE_FROM_IOS",
    CLICK_OPEN_STORE: "CLICK_OPEN_STORE",
    "CLICK_LOGIN_AUTHEN_PAGE": "CLICK_LOGIN_AUTHEN_PAGE",
    "CLICK_DOWNLOAD_APP_AUTHEN_PAGE":"CLICK_DOWNLOAD_APP_AUTHEN_PAGE",
    "LOGIN_AUTHEN_PAGE": "LOGIN_AUTHEN_PAGE",
    "OTP_AUTHEN_PAGE":"OTP_AUTHEN_PAGE",
    "CONFIRM_AUTHEN_PAGE":"CONFIRM_AUTHEN_PAGE",
    "PAGE_CONFIRM_PAYMENT_AUTHEN": "PAGE_CONFIRM_PAYMENT_AUTHEN",
    "PAGE_AUTHEN_WEB_MOBILE": "PAGE_AUTHEN_WEB_MOBILE",
    "DONT_APP_MOMO_AUTHEN": "DONT_APP_MOMO_AUTHEN",
    "OPEN_APP_AUTHEN_WEB_MOBILE": "OPEN_APP_AUTHEN_WEB_MOBILE",
    "QR_CODE_AUTHEN": "QR_CODE_AUTHEN",
    "RESULT_LINKED": "RESULT_LINKED",
    "CANCEL_TRANSACTION_LINK": "CANCEL_TRANSACTION_LINK",
    "CLICK_CANCEL_TRANSACTION_LINK_AUTHEN_PAGE": "CLICK_CANCEL_TRANSACTION_LINK_AUTHEN_PAGE",
    "CLICK_RESEND_OTP_AUTHEN_PAGE": "CLICK_RESEND_OTP_AUTHEN_PAGE",
    "CLICK_CONFIRM_OTP_AUTHEN_PAGE": "CLICK_CONFIRM_OTP_AUTHEN_PAGE",
    "CLICK_CONFIRM_AUTHEN_PAGE": "CLICK_CONFIRM_AUTHEN_PAGE",
    "CLICK_CONFIRM_PAYMENT_AUTHEN_PAGE": "CLICK_CONFIRM_PAYMENT_AUTHEN_PAGE"

}

function fbCommit(eventName, params) {
    // Add this to a button's onclick handler
    try {
    if (FB) {
        if (!params)
            FB.AppEvents.logEvent(eventName);
        else {
            FB.AppEvents.logEvent(eventName, null, params);
        }
    }}
    catch(e){
        console.warn("Error send request tracking", e);
    }

}

function fbPurchase(pCode, requestId, amount, success) {
    // Add this to a button's onclick handler
    if (success === "true") {
        success = 1;
    } else {
        success = 0;
    }
    var info = {
        pCode: pCode,
        requestId: requestId,
        success: success
    }
    if (FB) {
        FB.AppEvents.logPurchase(amount, 'VND', info);
    }
}

function fbFlow(step, action, success) {
    var params = {};
    if (FB) {
        params[FB.AppEvents.ParameterNames.CONTENT_ID] = step;
        params["action"] = action;
        params[FB.AppEvents.ParameterNames.SUCCESS] = success;
        FB.AppEvents.logEvent(step, 1, params);
    }
}

function fbTrack(eventName, pCode, requestId) {
    // Add this to a button's onclick handler
    try {
        var info = {
            pCode: pCode,
            requestId: requestId
        };
        if (FB) {
            FB.AppEvents.logEvent(eventName, 1, info);
        }
    } catch (e) {
        console.warn("Error send request tracking", e);
    }
}

/**
 * Tracking event per page
 * @param pageId : LOGIN_PAGE
 * @param eventId : PAGE_VIEW, CLICK_BTN,
 * @param pCode
 */
function fbTrackOnPage(pageId, eventId, pCode){
    // Add this to a button's onclick handler
    try {
        var info = {
            pCode: pCode,
            requestId: requestId
        };
        if (FB) {
            FB.AppEvents.logEvent(eventName, 1, info);
        }
    } catch (e) {
        console.warn("Error send request tracking", e);
    }
}
