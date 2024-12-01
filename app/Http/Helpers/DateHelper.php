<?php

namespace App\Http\Helpers;

use Carbon\Carbon;

class DateHelper
{
	public static function formatDateReadable(?string $date): ?string
	{
		return $date ? Carbon::parse($date)->format('d M Y, h:i A') : null;
	}
}
