<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/3
 * Time: 14:51
 */

namespace Console;


use Constant\JWTKey;
use Firebase\JWT\JWT;
use Symfony\Component\Console\Command\Command;
use FastD\Routing\Route;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateJWTConsole extends Command
{
    public function configure()
    {
        $this->setName('gen-jwt')
            ->setDescription('生成JWT')
            ->addOption(
                'id',
                'i',
                InputOption::VALUE_REQUIRED,
                '需要生成JWT的ID'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $id = $input->getOption('id');

        $output->writeln($this->generateJWT($id));
    }

    /**
     * 生成JWT
     * @param $uid
     * @return string
     */
    protected function generateJWT($uid)
    {
        $token = [
            'iss' => JWTKey::ISS,
            'aud' => (string)$uid,
            'iat' => time(),
            'exp' => time() + (3600 * 24 * 365), // 有效期一年
        ];

        return JWT::encode($token, JWTKey::KEY, JWTKey::ALG);
    }
}