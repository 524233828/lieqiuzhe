<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/20
 * Time: 14:50
 */

namespace Helper\Login\Provider;


use Overtrue\Socialite\AccessTokenInterface;

class QQProvider extends \Overtrue\Socialite\Providers\QQProvider
{

    protected function getMe(AccessTokenInterface $token)
    {
        $url = $this->baseUrl.'/oauth2.0/me?access_token='.$token->getToken();
        $this->withUnionId && $url .= '&unionid=1';

        $response = $this->getHttpClient()->get($url);

        return json_decode($this->removeCallback($response->getBody()->getContents()), true);
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param \Overtrue\Socialite\AccessTokenInterface $token
     *
     * @return array
     */
    protected function getUserByToken(AccessTokenInterface $token)
    {
        $me = $this->getMe($token);
        $this->openId = $me['openid'];
        $this->unionId = isset($me['unionid']) ? $me['unionid'] : '';

        $queries = [
            'access_token' => $token->getToken(),
            'openid' => $this->openId,
            'oauth_consumer_key' => $me['client_id'],
        ];

        $response = $this->getHttpClient()->get($this->baseUrl.'/user/get_user_info?'.http_build_query($queries));

        return json_decode($this->removeCallback($response->getBody()->getContents()), true);
    }
}