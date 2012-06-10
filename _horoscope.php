<?php


function horo_feed($brth_time)
{

		$brth_array = getdate($brth_time);
		$brth_day = substr($brth_time, 8, 2);
		$brth_mon = substr($brth_time, 5, 2);
		
			


		if( (($brth_day >= 21) && ($brth_mon == 3)) || (($brth_day <= 20) && ($brth_mon == 4)) )
		{
				$hr_sign = 1;
				$url_en = 'http://horoscopes.astrology.com/dailyaries.html';
				$sign_en = 'Aries (21.03 - 20.04)';

		}

		elseif( (($brth_day >= 21) && ($brth_mon == 4)) || (($brth_day <= 21) && ($brth_mon == 5)) )
		{
				$hr_sign = 2;
				$url_en = 'http://horoscopes.astrology.com/dailytaurus.html';
				$sign_en = 'Taurus (21.04 - 20.05)';

		}

		elseif( (($brth_day >= 22) && ($brth_mon == 5)) || (($brth_day <= 21) && ($brth_mon == 6)) )
		{
				$hr_sign = 3;
				$url_en = 'http://horoscopes.astrology.com/dailygemini.html';
				$sign_en = 'Gemini (21.05 - 21.06)';

		}

		elseif( (($brth_day >= 22) && ($brth_mon == 6)) || (($brth_day <= 23) && ($brth_mon == 7)) )
		{
				$hr_sign = 4;
				$url_en = 'http://horoscopes.astrology.com/dailycancer.html';
				$sign_en = 'Cancer (22.06 - 22.07)';

		}

		elseif( (($brth_day >= 24) && ($brth_mon == 7)) || (($brth_day <= 23) && ($brth_mon == 8)) )
		{
				$hr_sign = 5;
				$url_en = 'http://horoscopes.astrology.com/dailyleo.html';
				$sign_en = 'Leo (23.07 - 23.08)';

		}

		elseif( (($brth_day >= 24) && ($brth_mon == 8)) || (($brth_day <= 23) && ($brth_mon == 9)) )
		{
				$hr_sign = 6;
				$url_en = 'http://horoscopes.astrology.com/dailyvirgo.html';
				$sign_en = 'Virgo (24.08 - 23.09)';

		}

		elseif( (($brth_day >= 24) && ($brth_mon == 9)) || (($brth_day <= 23) && ($brth_mon == 10)) )
		{
				$hr_sign = 7;
				$url_en = 'http://horoscopes.astrology.com/dailylibra.html';
				$sign_en = 'Libra (24.09 - 23.10)';

		}

		elseif( (($brth_day >= 24) && ($brth_mon == 10)) || (($brth_day <= 22) && ($brth_mon == 11)) )
		{
				$hr_sign = 8;
				$url_en = 'http://horoscopes.astrology.com/dailyscorpio.html';
				$sign_en = 'Scorpio (24.10 - 22.11)';

		}

		elseif( (($brth_day >= 23) && ($brth_mon == 11)) || (($brth_day <= 21) && ($brth_mon == 12)) )
		{
				$hr_sign = 9;
				$url_en = 'http://horoscopes.astrology.com/dailysagittarius.html';
				$sign_en = 'Sagittarius (23.11 - 21.12)';

		}

		elseif( (($brth_day >= 22) && ($brth_mon == 12)) || (($brth_day <= 20) && ($brth_mon == 1)) )
		{
				$hr_sign = 10;
				$url_en = 'http://horoscopes.astrology.com/dailycapricorn.html';
				$sign_en = 'Capricorn (22.12 - 20.01)';

		}

		elseif( (($brth_day >= 21) && ($brth_mon == 1)) || (($brth_day <= 19) && ($brth_mon == 2)) )
		{
				$hr_sign = 11;
				$url_en = 'http://horoscopes.astrology.com/dailyaquarius.html';
				$sign_en = 'Aquarius (21.01 - 20.02)';

		}

		else
		{
				$hr_sign = 12;
				$url_en = 'http://horoscopes.astrology.com/dailypisces.html';
				$sign_en = 'Pisces (21.02 - 20.03)';

		}
		$show = 0;


	
if (SE_Language::select($user) == 1){	
		$horo_html = file_get_contents($url_en);

		preg_match('#"intelliTxt">(.*)#', $horo_html, $source);
		$source =& $source[1];
		$source = "<b>$sign_en</b><br>$source";
		return $source;
}		
}
?>