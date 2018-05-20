<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/28
 * Time: 14:28
 */

namespace Console;


use Model\OddModel;
use Model\OptionModel;
use PHPHtmlParser\Dom;
use Qiutan\League;
use Qiutan\Lottery;
use Qiutan\Match;
use Qiutan\Odds;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestConsole extends Command
{

    public function configure()
    {
        $this->setName('test')
            ->setDescription('测试专用');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $match_id = 1395350;
        Match::$redis = redis();
        $res = Match::matchInfo($match_id);

        if(!isset($res['match']) || !is_array($res['match']))
        {
            return false;
        }

        if(!isset($res['match'][0])){
            $res['match'] = [ 0 => $res['match'] ];
        }

        foreach ($res['match'] as $info)
        {
            $match_id = $info['ID'];

            $html = $info['Briefing'];

//            $player_suspend = $info['PlayerSuspend'];

            $dom = new Dom();

            $dom->load($html);

            $red_t1 = $dom->find('.red_t1')[0]->getParent()->find('tr');

            unset($red_t1[0]);

            $home_info = [];

            foreach ($red_t1 as $red)
            {

                $home_info[] = [
                    "match_id" => $match_id,
                    "team_type" => 0,
                    "desc" => $red->find('td')[0]->innerhtml,
                    "create_time" => time(),
                ];
//                var_dump($red->find('td')[0]->innerhtml);
            }

            $blue_t1 = $dom->find('.blue_t1')[0]->getParent()->find('tr');

            unset($blue_t1[0]);

            foreach ($blue_t1 as $blue)
            {

                $away_info[] = [
                    "match_id" => $match_id,
                    "team_type" => 1,
                    "desc" => $blue->find('td')[0]->innerhtml,
                    "create_time" => time(),
                ];
            }

            unset($dom);

            $info = array_merge($home_info, $away_info);

            var_dump($info);
//            var_dump($html);
//            var_dump($player_suspend);
        }

        $html = "<table cellspacing=0 cellpadding=0 width='100%' border=0>
	<tr>
		<td width='50%' style='vertical - align: top;'>
			<table width=100% border=0 align=left cellPadding=3 cellSpacing=1 bgcolor=#CECECE>
				<tr align=middle class=red_t1> 
					<td height=28 bgcolor=\"#FDEFD2\">
						<font class=vander16 style=\"color:#000\">
							<b>{hometeam}</b>
						</font>
					</td>
				</tr>
				<tr align=left height=\"25\" bgcolor=#feefed> 
					<td style='padding-left:20px;text-align:left;height:25px;line-height: 20px;'>【状态】阿森纳本赛季11次因防线失误而导致丢球，为联赛最多。主帅温格表示俱乐部的目标仍然是争取第四名。据《太阳报》报道，温格日前召集了球员和教练组成员，他表示如果球队拿不到下赛季的欧冠席位，今夏就会大换血。</td>
				</tr>
				<tr align=left height=\"25\" bgcolor=#feefed> 
					<td style='padding-left:20px;text-align:left;height:25px;line-height: 20px;'>【阵容】本轮比赛将是阿森纳在一周之内连续第二次与曼城交手，卡索拉、拉卡泽特还在养伤，主力边后卫蒙雷亚尔出战成疑，科拉西纳茨有望出现在左翼卫的位置上。</td>
				</tr>
			</table>
		</td>
		<td width='50%' style='vertical - align: top;'>
			<table width=100% border=0 align=left cellPadding=3 cellSpacing=1 borderColorLight=#ffffff borderColorDark=#666666 bgcolor=#CECECE>
				<tr align=middle class=blue_t1> 
					<td height=28 bgcolor=\"#DCE8ED\"><font class=vander16 style=\"color:#000\"><b>{guestteam}</b></font></td>
				</tr>
				<tr align=left height=\"25\" bgcolor=#f2f9fd> 
					<td style='padding-left:20px;text-align:left;height:25px;line-height: 20px;'>【声音】主帅瓜迪奥拉表示：“阿森纳在酋长球场是一支强硬的球队。也许本赛季他们的表现不够稳定，但是在主场他们总是很强势。他们主场很少丢分，我执教巴萨，拜仁和曼城都来过阿森纳主场，比赛一直都是不容易。”</td>
				</tr>
				<tr align=left height=\"25\" bgcolor=#f2f9fd> 
					<td style='padding-left:20px;text-align:left;height:25px;line-height: 20px;'>【阵容】德尔夫解禁复出，预计将回到主力左后卫的位置上，球队也有足够的轮换空间。斯特林腿筋伤势恢复情况良好，但是瓜迪奥拉表示无法保证本轮比赛是否可以上场。前锋阿圭罗在过去4场比赛攻入6球，本轮比赛有望首发出场领衔锋线。</td>
				</tr>
			</table>
		</td>
	</tr>
</table>";

        $dom = new Dom();

        $dom->load($html);

        $red_t1 = $dom->find('.red_t1')[0]->getParent()->find('tr');

        unset($red_t1[0]);

        foreach ($red_t1 as $red)
        {
            var_dump($red->find('td')[0]->innerhtml);
        }

        $blue_t1 = $dom->find('.blue_t1')[0]->getParent()->find('tr');

        unset($blue_t1[0]);

        foreach ($blue_t1 as $blue)
        {
            var_dump($blue->find('td')[0]->innerhtml);
        }
        echo count($red_t1);exit;

    }
}