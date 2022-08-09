<?php

use App\Models\PlayList;
use App\Models\Resource;
use App\Models\Video;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('addVideos', function () {
    $names = [
        "雨中寺庙的沉浸式冥想引导", "专注音乐增加脑力增强专注力", "5分钟冥想练习坚持提高专注力", "呼吸放松稳定自律神经", "1小时深度睡眠引导", "无法入睡的夜晚恢复疲惫的身心", "夏日的风铃声", "神秘的瀑布和河水潺潺的声音", "11分钟大颂钵音乐", "睡眠指导必收藏", "70 分钟冥想音乐", "治愈BGM放松身心", "神秘喷泉的音乐", "河水潺潺的治愈之声", "萤火虫悠然飞舞", "热带鱼水族馆", "昆虫和青蛙的自然之声", "要休息一下吗", "从黄昏到晚上的铃虫和蟋蟀", "篝火静静燃烧的声音", "被阳光透过树木的喃喃声治愈", "穿越日本最美的白桦林", "河水潺潺声", "篝火“破裂的声音", "芭莎诺瓦放松音乐", "竹灯笼在秋天的声音", "梦幻般的开月舞", "在一个令人耳目一新的早晨醒来", " 鸟鸣治愈心灵", "来自山中湖的治愈之声", "月圆之夜的秋虫声", "超赞夜景超赞", "夏天的河流咝咝声", "京都夜间避雨", "在晚上睡不着时", "治愈风铃音", "木炭柔和的火焰让你感到温暖", "进入深度睡眠的引导", "1分钟学会冥想",
    ];

    $ori_names = ["引導冥想 雨中寺廟沈浸式中文超真實情境深度睡眠放鬆冥想音樂-1", "Increase Brain Power, Enhance Intelligence, Iq To Improve, Binaural Beats, Impro-1", "正念冥想引導 (女聲) 每天5分鐘冥想入門練習 堅持21天提升專注力 5分鐘暫停讓生活充滿能量-1", "呼吸放松_-穩定自律神經 幫助睡眠-1", "睡眠引导_1小時中文深度放鬆入睡催眠海浪療癒聲音 -1", "眠れない夜にどうぞ。／灯のある風景「4K高画質夜景」疲れた心身の回復・リラックス効果・睡", "環境音・夏の音『風鈴・日暮ゼミ・川のせせらぎ音』Asmr", "【環境音・Asmr】神秘の滝(元滝伏流水)と川のせせらぎ音癒し3時間・疲れた心身の回復・リラッ", "11分鐘大頌鉢音樂-1", "睡眠引导_睡眠不好的必收藏！-1", "70 Minute 7 Chakra Continuous Meditation With 21 Antique Tibetan Singing Bowls-1.", "【4K】癒しBGMと爽やかな新緑（鳥のさえずり・川のせせらぎ）／疲れた心身の回復・リラックス", "【4K】神秘の泉・丸池様 ＆ 日本初の世界遺産『白神山地』十二湖・青池／勉強中や作業用、ま", "【4K】癒しの音／川のせせらぎ／睡眠・集中力・リラックス効果α波（3時間）", "【4K】ホタルの乱舞と自然音ASMR／癒し系3時間", "【4K】熱帯魚アクアリュウム3時間観賞用（ASMR・自然音）", "【ASMR】 美しい星空（天の川）と自然音【虫の鳴声・カエルの鳴声】作業用・勉強用・睡眠時に", "【ASMR・環境音】ホッと一息つきませんか？（コーヒー編", "【ASMR・環境音】美しい日本庭園（昼は日暮ゼミや風鈴、夕暮れから夜は鈴虫、コオロギの音色", "【ASMR・癒し音】焚火環境音でリラックス効果、瞑想、疲労回復、ストレス軽減に。また作業用", "【自然の音と4K 映像】円原川 美しい水景色 木漏れ日の中でせせらぎの音に癒される３時間", "【環境音・ASMR】日本一美しいとされている白樺群生地（八千穂高原）を散策／勉強中や作業用", "【環境音・ASMR】神秘の滝(元滝伏流水)と川のせせらぎ音癒し3時間・疲れた心身の回復・リラッ", "【癒し系】カエルの焚火『パチパチ音とたまにカエルの鳴き声』1_fゆらぎ効果でリラックス！", "Relax Music - Tropical Night Bossa Nova - Smooth Bossa Nova Guitar Instrumental", "ゆらぎ効果でリラックス／竹灯籠1時間（環境音・秋の音）α波で自律神経の乱れを整え心身の", "幻想的な海月の舞と癒しBGM／ゆらぎ効果で疲れた心身の回復、リラックス、勉強中や作業用、", "爽やかな朝の目覚めに！鳥のさえずり「自然音・ASMR」疲れた心身の回復・リラックス効果・癒", "爽やかな朝の目覚めにどうぞ。『鳥のさえずり環境音』鳥の鳴声が心を癒してくれます。疲れ", "富士山と夕陽『山中湖より』癒しBGMで疲れた心身の回復・リラックス効果・睡眠時など眠れな", "満月の夜・秋の虫の音（鈴虫・こおろぎ・キリギリス）α波で自律神経の乱れを整え、心身の疲", "絶景夜景！【4K高画質】大阪・和歌山・神戸・奈良・広島・岐阜・石川夜景／美しい風景とジャ", "環境音・夏の音『風鈴・日暮ゼミ・川のせせらぎ音』ASMR・Healing videos and natural sounds", "癒し【環境音・ASMR】夜の京都で雨宿り（雨音・雷音・フクロウ・虫・カエル・足音など）3時間", "癒しBGMと海中の生き物たち／疲れた心身の回復、リラックス、勉強中や作業用、眠れない夜に", "癒し系【環境音・ASMR】風鈴の音／お部屋が涼しく感じます", "癒し映像『焚火の炎』／炭火の柔らかな炎がぬくもりを感じさせてくれます", "引導睡眠 進入深度優質睡眠身心放鬆delta腦波催眠音樂", "高僧破解大家对冥想的误解，让你一分钟学会冥想-1"];
    $urls = ["http://media.yoosoul.com/sv/413a017-17d0ebef257/413a017-17d0ebef257.m4v", "http://media.yoosoul.com/sv/a0bb551-17d0ebef21b/a0bb551-17d0ebef21b.m4v", "http://media.yoosoul.com/sv/22e81eb0-17d0ebef27c/22e81eb0-17d0ebef27c.m4v", "http://media.yoosoul.com/sv/388a50f7-17d0ebef283/388a50f7-17d0ebef283.m4v", "http://media.yoosoul.com/sv/43249211-17d0ebef286/43249211-17d0ebef286.m4v", "http://media.yoosoul.com/sv/1b470198-17d0ebf2035/1b470198-17d0ebf2035.m4v", "http://media.yoosoul.com/sv/3c820a7c-17d0ebf3aa9/3c820a7c-17d0ebf3aa9.m4v", "http://media.yoosoul.com/sv/2c085692-17d0ec010fb/2c085692-17d0ec010fb.m4v", "http://media.yoosoul.com/sv/13559c3-17d0ec1cc12/13559c3-17d0ec1cc12.m4v", "http://media.yoosoul.com/sv/5ed771bb-17d0ec23e2c/5ed771bb-17d0ec23e2c.m4v", "http://media.yoosoul.com/sv/5bc59dc-17d0ec29368/5bc59dc-17d0ec29368.m4v", "http://media.yoosoul.com/sv/15805de7-17d0ee35e80/15805de7-17d0ee35e80.mp4", "http://media.yoosoul.com/sv/2dcd5bcb-17d0ee35e7c/2dcd5bcb-17d0ee35e7c.mp4", "http://media.yoosoul.com/sv/294f6edc-17d0ee35e85/294f6edc-17d0ee35e85.mp4", "http://media.yoosoul.com/sv/b038ecb-17d0ee35e7c/b038ecb-17d0ee35e7c.mp4", "http://media.yoosoul.com/sv/56539a8c-17d0ee35e8a/56539a8c-17d0ee35e8a.mp4", "http://media.yoosoul.com/sv/52b6286c-17d0efac5e1/52b6286c-17d0efac5e1.mp4", "http://media.yoosoul.com/sv/2957c4c5-17d0f05fe5c/2957c4c5-17d0f05fe5c.mp4", "http://media.yoosoul.com/sv/5e22eed6-17d0f06467a/5e22eed6-17d0f06467a.mp4", "http://media.yoosoul.com/sv/5a091f07-17d0f083ed2/5a091f07-17d0f083ed2.mp4", "http://media.yoosoul.com/sv/49bdf36f-17d0f094c6c/49bdf36f-17d0f094c6c.mp4", "http://media.yoosoul.com/sv/2ad1cb19-17d0f095abb/2ad1cb19-17d0f095abb.mp4", "http://media.yoosoul.com/sv/17b42ddd-17d0f0c4d72/17b42ddd-17d0f0c4d72.mp4", "http://media.yoosoul.com/sv/22ab179d-17d0f295e09/22ab179d-17d0f295e09.mp4", "http://media.yoosoul.com/sv/5db965da-17d0f2b02bf/5db965da-17d0f2b02bf.mp4", "http://media.yoosoul.com/sv/f9db8f4-17d0f2bdc6c/f9db8f4-17d0f2bdc6c.mp4", "http://media.yoosoul.com/sv/2d26cb8d-17d0f30a06d/2d26cb8d-17d0f30a06d.mp4", "http://media.yoosoul.com/sv/619b4f1-17d0f341713/619b4f1-17d0f341713.mp4", "http://media.yoosoul.com/sv/14a86ea2-17d0f3586df/14a86ea2-17d0f3586df.mp4", "http://media.yoosoul.com/sv/458b0287-17d0f361a74/458b0287-17d0f361a74.mp4", "http://media.yoosoul.com/sv/46174355-17d0f36986b/46174355-17d0f36986b.mp4", "http://media.yoosoul.com/sv/1f133f10-17d0f39dc55/1f133f10-17d0f39dc55.mp4", "http://media.yoosoul.com/sv/117be90c-17d0f39f8b4/117be90c-17d0f39f8b4.mp4", "http://media.yoosoul.com/sv/4bdfcdad-17d0f3eb110/4bdfcdad-17d0f3eb110.mp4", "http://media.yoosoul.com/sv/3d38a69a-17d0f3f40c5/3d38a69a-17d0f3f40c5.mp4", "http://media.yoosoul.com/sv/3e82965a-17d0f4187d6/3e82965a-17d0f4187d6.mp4", "http://media.yoosoul.com/sv/1e456fb6-17d0f459bde/1e456fb6-17d0f459bde.mp4", "http://media.yoosoul.com/sv/2a8c9b91-17d23b1f099/2a8c9b91-17d23b1f099.m4v", "http://media.yoosoul.com/sv/53dbc59e-17d23b283f1/53dbc59e-17d23b283f1.m4v"];

    $times = ["3604", "11600", "320", "887", "3600", "3751", "3739", "11544", "781", "852", "4273", "11371", "30196", "11025", "10980", "11114", "5481", "162", "15503", "12150", "10806", "10825", "11544", "5076", "12858", "4233", "12055", "1360", "4396", "2372", "3852", "3933", "3739", "13168", "12101", "11695", "4266", "3600", "108"];

    $description = ["冥想引导", "专注音乐", "冥想引导", "呼吸练习", "睡眠引导", "治愈之声", "ASMR治愈之声", "ASMR治愈之声", "冥想音乐", "睡眠引导", "冥想音乐", "治愈之声", "治愈之声", "放松效果α波", "ASMR治愈之声", "ASMR治愈之声", "ASMR静谧星空", "ASMR放松音乐", "ASMR风铃庭院音乐", "ASMR放松身心恢复精力", "自然音乐", "ASMR治愈之声", "ASMR治愈之声", "噼啪燃烧的篝火", "Bossa Nova", "清爽的秋日", "治愈之声", "ASMR鸟之鸣", "鸟之鸣", "治愈之声", "调节精神焦虑", "夜游", "ASMR治愈之声", "ASMR治愈之声", "ASMR放松一下", "ASMR清爽的感觉", "感受温度", "睡眠引导", "冥想引导"];



    $playlists = ["减压_特色", "专注_默认", "每日修行_打卡", "每日修行_打卡", "睡眠_默认", "减压_其他", "减压_其他", "减压_其他", "减压_特色", "每日修行_打卡", "减压_特色", "减压_其他", "减压_其他", "减压_其他", "减压_默认", "减压_其他", "减压_其他", "减压_其他", "睡眠_特色", "睡眠_特色", "减压_其他", "减压_其他", "减压_其他", "睡眠_特色", "减压_其他", "减压_其他", "减压_其他", "减压_其他", "减压_其他", "减压_其他", "减压_其他", "睡眠_特色", "减压_其他", "减压_其他", "睡眠_特色", "减压_其他", "睡眠_特色", "睡眠_特色", "减压_特色"];

    for ($i = 0; $i <= count($names) - 1; $i++) {
        $videos = [
            'total_time'  => (int)$times[$i],
            'type'        => 'video',
            'url'         => $urls[$i],
            'origin_name' => $ori_names[$i],
        ];

        $video = Video::create($videos);
        $play_list = PlayList::where('name', $playlists[$i])->first();
        $resource = [
            'description'  => $description[$i],
            'play_list_id' => $play_list->id,
            'title'        => $names[$i],
            'total_time'   => (int)$times[$i],
            'media_id'     => $video->id,
        ];
        Resource::create($resource);
    }
});
