<?php
	$postageconfig = array (
		"default"  => array (
			"新疆维吾尔自治区" => "10",
			"西藏自治区"    => "10",
			"内蒙古自治区"   => "10",
			"青海省"      => "10",
			"宁夏回族自治区"  => "10",
			"甘肃省"      => "10",
			"新疆"       => "10",
			"西藏"       => "10",
			"内蒙古"      => "10",
			"宁夏"       => "10",
		),
		"新疆维吾尔自治区" => "3",
		"新疆"       => "3",
		"西藏自治区"    => "3",
		"西藏"       => "3",
		"内蒙古自治区"   => "3",
		"内蒙古"      => "3",
		"青海省"      => "3",
		"宁夏回族自治区"  => "3",
		"宁夏"       => "3",
		"甘肃省"      => "3",
	);
	/**
	 * @param $supplier
	 * @param $delivery
	 * @param $postageinfo array
	 * @return int
	 * @throws XN_Exception
	 */
	function getPostage($supplier, $delivery, $postageinfo)
	{
		global $postageconfig;
		$postage    = 0;
		$allpostage = 0;
		$allmerge   = 0;
		if ($supplier == "" || $delivery == "")
		{
			return $allpostage;
		}
		$supplierinfo = XN_Content::load($supplier, "suppliers");
		$province     = $supplierinfo->my->province;

		if ($province == $delivery)
		{
			return $allpostage;
		}
		if (isset($postageconfig[$province]))
		{
			$postage = $postageconfig[$province];
		}
		if (isset($postageconfig[$province][$delivery]))
		{
			$postage = $postageconfig[$province][$delivery];
		}
		elseif (isset($postageconfig["default"][$delivery]))
		{
			$postage = $postageconfig["default"][$delivery];
		}

		if (is_array($postageinfo) && count($postageinfo) > 0)
		{
			foreach ($postageinfo as $value)
			{
				if (intval($value["merge"]) != 1)
				{
					$allmerge += $postage * intval($value["quantity"]);
				}
				elseif ($allpostage < $postage)
				{
					$allpostage = $postage;
				}
			}
		}

		return $allpostage + $allmerge;
	}