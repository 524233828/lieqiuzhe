<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/18
 * Time: 13:31
 */

namespace Constant;

use FastD\Http\Response;

/**
 * 错误码，除1处理成功，负数的基本错误外，其余错误码均为4位数字，区别于3位的HTTP状态码
 * Class ErrorCode
 * @package Constant
 */

class ErrorCode
{
    const OK = 1;  //处理成功

    const ERR_SYSTEM = -1; //系统错误
    const ERR_OVERTIME = -2; // 请求超时
    const ERR_INVALID_PARAMETER = -4; //请求参数错误
    const ERR_CHECK_SIGN = -5; //签名验证错误
    const ERR_NO_PARAMETERS = -6; //参数缺失
    const ERR_UNKNOWN = -7; // 未知错误
    const OUT_OF_RANGE = -8; // 未知错误
    const ERR_PHONE_FORMAT = -9; // 未知错误


    /**
     * 10xx用户系统错误
     */
    const USER_NOT_LOGIN = 1000; // 未登录
    const USER_NOT_EXISTS = 1001; // 用户不存在
    const LOGIN_FAIL = 1002; //登录失败
    const SEND_CODE_FAIL = 1003; //登录失败
    const SEND_CODE_TOO_MUCH = 1004; //登录失败
    const CODE_INVALID = 1005; //登录失败
    const CODE_NOT_FOUND = 1006; //登录失败
    const PHONE_EXISTS = 1007; //登录失败
    const PASSWORD_NOT_CONFIRM = 1008; //登录失败
    const USER_LEVEL_EXISTS = 1009; //登录失败
    const TICKET_NOT_ENOUGH = 1010; //登录失败
    const PASSWORD_NOT_EQUAL = 1011; //两次密码不一致

    /**
     * 11xx分析师系统错误
     */
    const USER_NOT_ANALYST = 1100; // 用户不是分析师
    const ANALYST_LEVEL_TOO_LOW = 1101;
    const ANALYST_IS_USERSELF = 1102; //用户关注分析师自己
    const FOLLOW_ANALYST_FAIL = 1103; //关注失败
    const UNFOLLOW_ANALYST_FAIL = 1106; //取关失败
    const FOLLOW_ANALYST_ALREADY = 1104; //已经关注过了
    const UNFOLLOW_ANALYST_ALREADY = 1105; //已经取关过了
    const ANALYST_NOT_EXITST = 1107; // 分析师不存在
    const ANALYST_ALREADY = 1110; // 已是分析师
    const ANALYST_IN_REVIEW = 1111; // 已是分析师
    const APPLICATION_CHECKED = 1112; // 已是分析师

    /**
     * 12xx推荐系统错误
     */
    const RECOMMEND_FAIL = 1200;
    const RECOMMEND_EMPTY = 1201;

    /**
     * 13xx订单系统错误
     */
    const CREATE_ORDER_FAIL = 1300;


    /**
     * 14xx比赛系统错误
     */
    const MATCH_NOT_EXIST = 1400;

    /**
     * 15xx课程系统错误
     */
    const CLASS_NOT_FOUND = 1500; //课程不存在
    const CLASS_NO_CHAPTER = 1501;//课程没有章节
    const CHAPTER_DUPLICATE = 1502;//课程章节重复
    const LESSON_DUPLICATE = 1503;//章节的课时重复
    const CHAPTER_NO_LESSON = 1504;//章节没有课时
    const CLASS_NO_TRY = 1505;//课程没有试听列表
    const CLASS_EXPIRE = 1506;//课程已过期
    const CLASS_HAS_BOUGHT = 1507;//课程已过期

    /**
     * 16xx金币系统错误
     */
    const COIN_NOT_ENOUGH = 1600;

    /**
     * 错误代码与消息的对应数组
     *
     * @var array
     */
    static public $msg = [
        self::OK                    => ['处理成功', Response::HTTP_OK],
        self::ERR_SYSTEM            => ['系统错误', Response::HTTP_INTERNAL_SERVER_ERROR],
        self::ERR_INVALID_PARAMETER => ['请求参数错误', Response::HTTP_BAD_REQUEST],
        self::ERR_CHECK_SIGN        => ['签名错误', Response::HTTP_FORBIDDEN],
        self::ERR_NO_PARAMETERS     => ['参数缺失', Response::HTTP_BAD_REQUEST],
        self::ERR_OVERTIME          => ['请求超时', Response::HTTP_BAD_REQUEST],
        self::OUT_OF_RANGE          => ['字数超出限制', Response::HTTP_BAD_REQUEST],
        self::ERR_PHONE_FORMAT      => ['请输入正确的手机号', Response::HTTP_BAD_REQUEST],

        //用户系统错误
        self::USER_NOT_LOGIN        => ['未登录', Response::HTTP_FORBIDDEN],
        self::USER_NOT_EXISTS       => ['用户名或密码错误', Response::HTTP_FORBIDDEN],
        self::LOGIN_FAIL            => ['登录失败', Response::HTTP_INTERNAL_SERVER_ERROR],
        self::SEND_CODE_FAIL        => ['发送验证码失败', Response::HTTP_INTERNAL_SERVER_ERROR],
        self::SEND_CODE_TOO_MUCH    => ['请求太频繁', Response::HTTP_FORBIDDEN],
        self::CODE_INVALID          => ['验证码错误', Response::HTTP_BAD_REQUEST],
        self::CODE_NOT_FOUND        => ['验证码不存在或已过期', Response::HTTP_NOT_FOUND],
        self::PHONE_EXISTS          => ['手机号码已存在', Response::HTTP_BAD_REQUEST],
        self::PASSWORD_NOT_CONFIRM  => ['两次密码不相同', Response::HTTP_BAD_REQUEST],
        self::USER_LEVEL_EXISTS     => ['您已经购买该等级', Response::HTTP_BAD_REQUEST],
        self::TICKET_NOT_ENOUGH     => ['球票不足', Response::HTTP_FORBIDDEN],
        self::PASSWORD_NOT_EQUAL     => ['两次密码不一致', Response::HTTP_FORBIDDEN],

        //分析师系统错误
        self::USER_NOT_ANALYST      => ['用户不是分析师', Response::HTTP_FORBIDDEN],
        self::ANALYST_LEVEL_TOO_LOW => ['分析师等级太低', Response::HTTP_FORBIDDEN],
        self::ANALYST_IS_USERSELF => ['用户不能关注自己', Response::HTTP_FORBIDDEN],
        self::FOLLOW_ANALYST_FAIL => ['关注失败', Response::HTTP_FORBIDDEN],
        self::UNFOLLOW_ANALYST_FAIL => ['取关失败', Response::HTTP_FORBIDDEN],
        self::FOLLOW_ANALYST_ALREADY => ['已经关注', Response::HTTP_FORBIDDEN],
        self::UNFOLLOW_ANALYST_ALREADY => ['尚未关注，不能取关', Response::HTTP_FORBIDDEN],
        self::ANALYST_NOT_EXITST => ['分析师不存在', Response::HTTP_FORBIDDEN],
        self::ANALYST_ALREADY =>    ['您已是分析师', Response::HTTP_BAD_REQUEST],
        self::ANALYST_IN_REVIEW =>    ['您的申请审核中', Response::HTTP_BAD_REQUEST],
        self::APPLICATION_CHECKED =>    ['已经审核过了', Response::HTTP_BAD_REQUEST],

        //分析师 提示

        //比赛相关提示
        self::MATCH_NOT_EXIST => ['找不到该比赛', Response::HTTP_FORBIDDEN],


        //
        self::RECOMMEND_FAIL        => ['推荐失败', Response::HTTP_INTERNAL_SERVER_ERROR],
        self::RECOMMEND_EMPTY        => ['没有更多推荐数据', Response::HTTP_FORBIDDEN],

        self::CREATE_ORDER_FAIL     => ['创建订单失败', Response::HTTP_INTERNAL_SERVER_ERROR],

        //课程系统错误
        self::CLASS_NOT_FOUND       => ['课程不存在', Response::HTTP_NOT_FOUND],
        self::CLASS_NO_CHAPTER      => ['该课程没有章节', Response::HTTP_NOT_FOUND],
        self::CHAPTER_DUPLICATE      => ['课程章节不能重复', Response::HTTP_BAD_GATEWAY],
        self::LESSON_DUPLICATE      => ['章节课时不能重复', Response::HTTP_BAD_GATEWAY],
        self::CHAPTER_NO_LESSON      => ['该章节没有课时', Response::HTTP_NOT_FOUND],
        self::CLASS_NO_TRY          =>  ['该章节没有试听', Response::HTTP_NOT_FOUND],
        self::CLASS_EXPIRE          =>  ['您购买的课程已过期，请重新购买', Response::HTTP_FORBIDDEN],
        self::CLASS_HAS_BOUGHT      =>  ['您已购买此课程', Response::HTTP_OK],

        //金币系统错误
        self::COIN_NOT_ENOUGH      =>  ['金币余额不足', Response::HTTP_OK],
    ];

    /**
     * 返回错误代码的描述信息
     *
     * @param int    $code        错误代码
     * @param string $otherErrMsg 其他错误时的错误描述
     * @return string 错误代码的描述信息
     */
    public static function msg($code, $otherErrMsg = '')
    {
        if ($code == self::ERR_UNKNOWN) {
            return $otherErrMsg;
        }

        if (isset(self::$msg[$code][0])) {
            return self::$msg[$code][0];
        }

        return $otherErrMsg;
    }

    /**
     * 返回错误代码的Http状态码
     * @param int $code
     * @param int $default
     * @return int
     */
    public static function status($code, $default = 200)
    {
        if ($code == self::ERR_UNKNOWN) {
            return $default;
        }

        if (isset(self::$msg[$code][1])) {
            return self::$msg[$code][1];
        }

        return $default;
    }
}
