<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
//************************FUNKCJE CZYSZCZACE*********************
function sort_val($array, $key)
{
	foreach($array as $k => $v)
	{
		$b[] = strtolower($v[$key]);
	}
	arsort($b);
	foreach($b as $k => $v)
	{
		$c[] = $array[$k];
	}
	return $c;
}

//****************************WYPISANIE RAPORTU BITWY**************
//*****************OBLICZANIE CALKOWITEGO HP ARMIA/POTWORY*******************
function tb_raport($potwory_walka = null, $armia_walka = null, $kolejka = 0){
	$hp_potwory = 0;
	$raport_to_send = [];
	if((empty($potwory_walka)) || (empty($armia_walka))){
		echo "Error bledna wartosc";
		exit();
	} else {
		$potwory_walka = sort_val($potwory_walka, 'atak_sila');
		foreach($potwory_walka as $keys)
		{
			$hp_potwory+=$keys['zycie_walka'];
		}
		$hp_armia = 0;
		$armia_walka = sort_val($armia_walka, 'atak_sila');
		$licz_armia = count($armia_walka);
		if($licz_armia > 0)
		{
			foreach($armia_walka as $keys)
			{
				$hp_armia+=$keys['zycie_walka'];
			}
		}
	}
	while((0 < $hp_armia) && (0 < $hp_potwory)){
		$bonsu = 0;
		$atakowana_jednostka = 0;
		$atak = 0;
		$licznik_armia = 0;
		$licznik_potwory = 0;
		//----- ATAK GRACZ ATAKUJE-----
		if($kolejka > 0){
			
			//*****CZYSZCZENIE I PONOWNE UZUPELNIENIE ARMII SORTOWANIE MALEJACO**********
			$czysc = $armia_walka;
			unset($armia_walka);
			$armia_walka = [];
			foreach($czysc as $key){
				$armia_walka[]=$key;
			}
			unset($czysc);
			$ile_armia = count($armia_walka);
			if($ile_armia > 1){
				$armia_walka = sort_val($armia_walka, 'atak_sila');
			}
			//***************************************************************************
			$licznik_ataku_armia = 0;
			foreach($armia_walka as $keys){
				$licznik_ataku_armia+=$keys['odbyty_atak'];
			}
			if($licznik_ataku_armia > 0){
				while(($licznik_armia < count($armia_walka)) && ($atak == 0)){
					if($armia_walka[$licznik_armia]['odbyty_atak'] == 1){
						$atak = $armia_walka[$licznik_armia]['atak_sila'];
						//wypisanie aktualnie atakujacych jednostek
						$each_row_to_send = new stdClass;
						$each_row_to_send->action = true;
						$each_row_to_send->lvlLeft = $armia_walka[$licznik_armia]['lvl'];
						$each_row_to_send->nazwaLeft = $armia_walka[$licznik_armia]['nazwa'];
						$each_row_to_send->iloscLeft = $armia_walka[$licznik_armia]['ile_jednostek'];
						$each_row_to_send->stratyLeft = 0;
						$each_row_to_send->deathLeft = false;
						foreach($potwory_walka as $p_licznik => $p_klucz)
						{
							$sprawdzany_bonus = 0;
							for($j = 1; $j <= 3; $j++)
							{
								$typ_potwory = "typ".$j;
								if(isset($p_klucz[$typ_potwory]))
								{
									for($i = 1; $i <= 3; $i++)
									{
										$typ_armia = "bonus_komu".$i;
										$bonu_armia ="bonus_ile".$i;
										if(isset($armia_walka[$licznik_armia][$typ_armia]))
										{
											if($p_klucz[$typ_potwory] == $armia_walka[$licznik_armia][$typ_armia])
											{
												$sprawdzany_bonus+=$armia_walka[$licznik_armia][$bonu_armia];
											}
										}
									}
								}
							}
							if(($bonsu < $sprawdzany_bonus)&&($atak < $p_klucz['zycie_walka']))
							{
								$bonsu = $sprawdzany_bonus;
								$atakowana_jednostka = $p_licznik;
							}
						}
						//-----ATAK Z BONUSEM-----
						if($bonsu > 0)
						{
							$each_row_to_send->lvlRight = $potwory_walka[$atakowana_jednostka]['lvl'];
							$each_row_to_send->nazwaRight = $potwory_walka[$atakowana_jednostka]['nazwa'];
							$atak_z_bonem = $armia_walka[$licznik_armia]['ile_jednostek'] * (($armia_walka[$licznik_armia]['sila']/100) * (100 + $armia_walka[$licznik_armia]['bonus_ataku'] + $bonsu));
							$obr2_potwory = $potwory_walka[$atakowana_jednostka]['zycie_walka'];
							$potwory_walka[$atakowana_jednostka]['zycie_walka'] -= $atak_z_bonem;
							$armia_walka[$licznik_armia]['odbyty_atak'] = 0;
							if($potwory_walka[$atakowana_jednostka]['zycie_walka'] <= 0)
							{
								$each_row_to_send->damage = $obr2_potwory;
								$each_row_to_send->deathRight = true;
								$each_row_to_send->stratyRight = $potwory_walka[$atakowana_jednostka]['ile_jednostek'];
								$each_row_to_send->iloscRight = 0;
								unset($potwory_walka[$atakowana_jednostka]);
							}
							else
							{
								$each_row_to_send->damage = $atak_z_bonem;
								$each_row_to_send->deathRight = false;
								$each_row_to_send->stratyRight = $potwory_walka[$atakowana_jednostka]['ile_jednostek']-($potwory_walka[$atakowana_jednostka]['ile_jednostek'] - ($potwory_walka[$atakowana_jednostka]['ile_jednostek'] - (ceil($potwory_walka[$atakowana_jednostka]['zycie_walka'] / $potwory_walka[$atakowana_jednostka]['zycie']))));
								$each_row_to_send->iloscRight = ceil($potwory_walka[$atakowana_jednostka]['zycie_walka'] / $potwory_walka[$atakowana_jednostka]['zycie']);
								$potwory_walka[$atakowana_jednostka]['ile_jednostek'] = ceil($potwory_walka[$atakowana_jednostka]['zycie_walka'] / $potwory_walka[$atakowana_jednostka]['zycie']);
								$potwory_walka[$atakowana_jednostka]['atak_sila'] = $potwory_walka[$atakowana_jednostka]['ile_jednostek'] * $potwory_walka[$atakowana_jednostka]['sila'];
							}
						}
						//-----ATAK BEZ BONUSU----
						else
						{
							$pulta = 1;
								if(($armia_walka[$licznik_armia]['typ'] == 'Machina') || ($armia_walka[$licznik_armia]['typ1'] == 'Machina') || ($armia_walka[$licznik_armia]['typ2'] == 'Machina') || ($armia_walka[$licznik_armia]['typ3'] == 'Machina'))
								{
									$pulta = 20;
								}
							$atak_bez_bona = $armia_walka[$licznik_armia]['ile_jednostek'] * ((($armia_walka[$licznik_armia]['sila']/100)/$pulta) * (100 + $armia_walka[$licznik_armia]['bonus_ataku']));
							$max_atak_potwora = 0; //sprawdz atak
							$max_index_potwora = 0; //ostatnie wychodzace id
							$najwieksze_hp = 0;//hp test sprawdzanie
							$id_hp = 0;
							$id_ap = -1;
							foreach($potwory_walka as $keys => $values)
							{
								$atak_podstawa = $values['ile_jednostek'] * $values['sila'];
								if(($atak_bez_bona < $values['zycie_walka']) && ($max_atak_potwora < $atak_podstawa))
								{ 
									$max_atak_potwora = $atak_podstawa;
									$id_ap = $keys;
								}
								elseif(($atak_bez_bona > $values['zycie_walka']) && ($najwieksze_hp < $values['zycie_walka']))
								{
									$najwieksze_hp = $values['zycie_walka'];
									$id_hp = $keys;
								}
							}
							if($id_ap != -1)
							{
								$max_index_potwora = $id_ap;
							}
							else
							{
								$max_index_potwora = $id_hp;
							}
							$licznik_potw = count($potwory_walka);
							for($i = 0; $i < $licznik_potw; $i++)
							{
								for($j = 1; $j <= 3; $j++)
								{
									$bonus_komu = "bonus_komu".$j;
									$bonus_ile = "bonus_ile".$j;
									if(isset($potwory_walka[$i][$bonus_komu]))
									{
										for($k = 1; $k <= 3; $k++)
										{
											$typ_armia = "typ".$k;
											if($potwory_walka[$i][$bonus_komu] == $armia_walka[$licznik_armia][$typ_armia])
											{
												$atak1 = $potwory_walka[$i]['ile_jednostek'] * (($potwory_walka[$i]['sila']/100)*(100 + $potwory_walka[$i][$bonus_ile]));
												if(($max_atak_potwora < $atak1) && ($potwory_walka[$i]['zycie_walka'] > $atak_bez_bona))
												{
													$max_atak_potwora = $atak1;
													$max_index_potwora = $i;
												}
											}
										}
									}
								}
							}
							$each_row_to_send->lvlRight = $potwory_walka[$max_index_potwora]['lvl'];
							$each_row_to_send->nazwaRight = $potwory_walka[$max_index_potwora]['nazwa'];
							$obr_potwory = $potwory_walka[$max_index_potwora]['zycie_walka'];
							$potwory_walka[$max_index_potwora]['zycie_walka'] -= $atak_bez_bona;
							$armia_walka[$licznik_armia]['odbyty_atak'] = 0;
							if($potwory_walka[$max_index_potwora]['zycie_walka'] <= 0)
							{
								$each_row_to_send->damage = $obr_potwory;
								$each_row_to_send->deathRight = true;
								$each_row_to_send->stratyRight = $potwory_walka[$max_index_potwora]['ile_jednostek'];
								$each_row_to_send->iloscRight = 0;
								unset($potwory_walka[$max_index_potwora]);
							}
							else
							{
								$each_row_to_send->damage = $atak_bez_bona;
								$each_row_to_send->deathRight = false;
								$each_row_to_send->stratyRight = $potwory_walka[$max_index_potwora]['ile_jednostek']-($potwory_walka[$max_index_potwora]['ile_jednostek'] - ($potwory_walka[$max_index_potwora]['ile_jednostek'] - (ceil($potwory_walka[$max_index_potwora]['zycie_walka'] / $potwory_walka[$max_index_potwora]['zycie']))));
								$each_row_to_send->iloscRight = ceil($potwory_walka[$max_index_potwora]['zycie_walka'] / $potwory_walka[$max_index_potwora]['zycie']);
								$potwory_walka[$max_index_potwora]['ile_jednostek'] = ceil($potwory_walka[$max_index_potwora]['zycie_walka'] / $potwory_walka[$max_index_potwora]['zycie']);
								$potwory_walka[$max_index_potwora]['atak_sila'] = $potwory_walka[$max_index_potwora]['ile_jednostek'] * $potwory_walka[$max_index_potwora]['sila'];
							}
						}
						array_push($raport_to_send, $each_row_to_send);
						break;
					}
					else
					{
						$licznik_armia++;
					}
				}
			}
			$kolejka = 0;
			$licznik_armia = 0;
			$atak = 0;
		}
		//-----ATAK POTWOR ATAKUJE----------------------------------------------------------------------------------------------------
		else {
			//*****CZYSZCZENIE I PONOWNE UZUPELNIENIE POTWOROW SORTOWANIE MALEJACO*******
			$czysc = $potwory_walka;
			unset($potwory_walka);
			$potwory_walka = [];
			foreach($czysc as $key)
			{
				$potwory_walka[]=$key;
			}
			unset($czysc);
			$ile_potwory = count($potwory_walka);
			if($ile_potwory > 1)
			{
			$potwory_walka = sort_val($potwory_walka, 'atak_sila');
			}
			//***************************************************************************
			$licznik_ataku_armia = 0;
			foreach($armia_walka as $keys)
			{
				$licznik_ataku_armia+=$keys['odbyty_atak'];
			}
			$licznik_ataku_potwory = 0;
			foreach($potwory_walka as $keys)
			{
				$licznik_ataku_potwory+=$keys['odbyty_atak'];
			}
			if($licznik_ataku_potwory > 0)
			{
				while(($licznik_potwory < count($potwory_walka)) && ($atak == 0))
				{
					if($potwory_walka[$licznik_potwory]['odbyty_atak'] == 1)
					{
						$atak = $potwory_walka[$licznik_potwory]['atak_sila'];
						//wypisanie aktualnie atakujacych jednostek
						$each_row_to_send = new stdClass;
						$each_row_to_send->action = false;
						$each_row_to_send->lvlRight = $potwory_walka[$licznik_potwory]['lvl'];
						$each_row_to_send->nazwaRight = $potwory_walka[$licznik_potwory]['nazwa'];
						$each_row_to_send->iloscRight = $potwory_walka[$licznik_potwory]['ile_jednostek'];
						$each_row_to_send->stratyRight = 0;
						$each_row_to_send->deathRight = false;

						foreach($armia_walka as $a_licznik => $a_klucz)
						{
							$sprawdzany_bonus = 0;
							for($j = 1; $j <= 3; $j++)
							{
								$typ_potwory = "typ".$j;
								if(isset($a_klucz[$typ_potwory]))
								{
									for($i = 1; $i <= 3; $i++)
									{
										$typ_armia = "bonus_komu".$i;
										$bonu_armia ="bonus_ile".$i;
										if(isset($potwory_walka[$licznik_potwory][$typ_armia]))
										{
											if($a_klucz[$typ_potwory] == $potwory_walka[$licznik_potwory][$typ_armia])
											{
												$sprawdzany_bonus+=$potwory_walka[$licznik_potwory][$bonu_armia];
											}
										}
									}
								}
							}
							if(($bonsu < $sprawdzany_bonus)&&($atak < $a_klucz['zycie_walka']))
							{
								$bonsu = $sprawdzany_bonus;
								$atakowana_jednostka = $a_licznik;
							}
						}
						//-----ATAK Z BONUSEM-----
						if($bonsu > 0)
						{
							$each_row_to_send->lvlLeft = $armia_walka[$atakowana_jednostka]['lvl'];
							$each_row_to_send->nazwaLeft = $armia_walka[$atakowana_jednostka]['nazwa'];
							$atak_z_bonem = $potwory_walka[$licznik_potwory]['ile_jednostek'] * (($potwory_walka[$licznik_potwory]['sila']/100) * (100 + $bonsu));
							$obr_armia = $armia_walka[$atakowana_jednostka]['zycie_walka'];
							$armia_walka[$atakowana_jednostka]['zycie_walka'] -= $atak_z_bonem;
							$potwory_walka[$licznik_potwory]['odbyty_atak'] = 0;
							if($armia_walka[$atakowana_jednostka]['zycie_walka'] <= 0)
							{
								$each_row_to_send->damage = $obr_armia;
								$each_row_to_send->deathLeft = true;
								$each_row_to_send->stratyLeft = $armia_walka[$atakowana_jednostka]['ile_jednostek'];
								$each_row_to_send->iloscLeft = 0;
								unset($armia_walka[$atakowana_jednostka]);
							}
							else
							{
								$each_row_to_send->damage = $atak_z_bonem;
								$each_row_to_send->deathLeft = false;
								$each_row_to_send->stratyLeft = $armia_walka[$atakowana_jednostka]['ile_jednostek']-($armia_walka[$atakowana_jednostka]['ile_jednostek'] - ($armia_walka[$atakowana_jednostka]['ile_jednostek'] - (ceil($armia_walka[$atakowana_jednostka]['zycie_walka'] / (($armia_walka[$atakowana_jednostka]['zycia']/100)*(100+$armia_walka[$atakowana_jednostka]['bonus_zycia']))))));
								$each_row_to_send->iloscLeft = ceil($armia_walka[$atakowana_jednostka]['zycie_walka'] / (($armia_walka[$atakowana_jednostka]['zycia']/100)*(100+$armia_walka[$atakowana_jednostka]['bonus_zycia'])));
								$armia_walka[$atakowana_jednostka]['ile_jednostek'] = ceil($armia_walka[$atakowana_jednostka]['zycie_walka'] / (($armia_walka[$atakowana_jednostka]['zycia']/100)*(100+$armia_walka[$atakowana_jednostka]['bonus_zycia'])));
								$armia_walka[$atakowana_jednostka]['atak_sila'] = $armia_walka[$atakowana_jednostka]['ile_jednostek'] * (($armia_walka[$atakowana_jednostka]['sila']/100)*(100+$armia_walka[$atakowana_jednostka]['bonus_ataku']));
							}
						}
						else//-----ATAK BEZ BONUSU----
						{
							$max_atak_potwora = 0;
							$max_index_potwora = 0;
							foreach($armia_walka as $keys => $values)
							{
								$pulta = 1;
								if(($values['typ'] == 'Machina') || ($values['typ1'] == 'Machina') || ($values['typ2'] == 'Machina') || ($values['typ3'] == 'Machina'))
								{
									$pulta = 20;
								}
								$atak_podstawa = $values['ile_jednostek'] * ((($values['sila']/100)/$pulta)*(100+$values['bonus_ataku']));
								if($max_atak_potwora < $atak_podstawa)
								{
									$max_atak_potwora = $atak_podstawa;
									$max_index_potwora = $keys;
								}
							}
							$licznik_potw = count($armia_walka);
							for($i = 0; $i < $licznik_potw; $i++)
							{
								for($j = 1; $j <= 3; $j++)
								{
									$bonus_komu = "bonus_komu".$j;
									$bonus_ile = "bonus_ile".$j;
									if(isset($armia_walka[$i][$bonus_komu]))
									{
										for($k = 1; $k <= 3; $k++)
										{
											$typ_armia = "typ".$k;
											if($armia_walka[$i][$bonus_komu] == $potwory_walka[$licznik_potwory][$typ_armia])
											{
												$atak1 = $armia_walka[$i]['ile_jednostek'] * (($armia_walka[$i]['sila']/100)*(100 + $armia_walka[$i]['bonus_ataku'] + $armia_walka[$i][$bonus_ile]));
												if($max_atak_potwora < $atak1)
												{
													$max_atak_potwora = $atak1;
													$max_index_potwora = $i;
												}
											}
										}
									}
								}
							}
							$each_row_to_send->lvlLeft = $armia_walka[$max_index_potwora]['lvl'];
							$each_row_to_send->nazwaLeft = $armia_walka[$max_index_potwora]['nazwa'];
							$atak_bez_bona = $potwory_walka[$licznik_potwory]['ile_jednostek'] * $potwory_walka[$licznik_potwory]['sila'];
							$obr2_armia = $armia_walka[$max_index_potwora]['zycie_walka'];
							$armia_walka[$max_index_potwora]['zycie_walka'] -= $atak_bez_bona;
							$potwory_walka[$licznik_potwory]['odbyty_atak'] = 0;
							if($armia_walka[$max_index_potwora]['zycie_walka'] <= 0)
							{
								$each_row_to_send->damage = $obr2_armia;
								$each_row_to_send->deathLeft = true;
								$each_row_to_send->stratyLeft = $armia_walka[$max_index_potwora]['ile_jednostek'];
								$each_row_to_send->iloscLeft = 0;
								unset($armia_walka[$max_index_potwora]);
							}
							else
							{
								$each_row_to_send->damage = $atak_bez_bona;
								$each_row_to_send->deathLeft = false;
								$each_row_to_send->stratyLeft = $armia_walka[$max_index_potwora]['ile_jednostek']-($armia_walka[$max_index_potwora]['ile_jednostek'] - ($armia_walka[$max_index_potwora]['ile_jednostek'] - (ceil($armia_walka[$max_index_potwora]['zycie_walka'] / (($armia_walka[$max_index_potwora]['zycia']/100)*(100+$armia_walka[$max_index_potwora]['bonus_zycia']))))));
								$each_row_to_send->iloscLeft = ceil($armia_walka[$max_index_potwora]['zycie_walka'] / (($armia_walka[$max_index_potwora]['zycia']/100)*(100+$armia_walka[$max_index_potwora]['bonus_zycia'])));
								$armia_walka[$max_index_potwora]['atak_sila'] = $armia_walka[$max_index_potwora]['ile_jednostek'] * (($armia_walka[$max_index_potwora]['sila']/100)*(100+$armia_walka[$max_index_potwora]['bonus_ataku']));
							}
						}
						array_push($raport_to_send, $each_row_to_send);
						break;
					}
					else
					{
						$licznik_potwory++;
					}
				}
			}
			$kolejka = 1;
			$licznik_potwory = 0;
			$atak = 0;
		}
		//-----OBLICZANIE ILOSCI HP PO ATAKU-----
		$hp_potwory = 0;
		$licz_potwory = count($potwory_walka);
		if($licz_potwory > 0)
		{
			foreach($potwory_walka as $keys)
			{
				$hp_potwory+=$keys['zycie_walka'];
			}
		}

		$hp_armia = 0;
		$licz_armia = count($armia_walka);
		if($licz_armia > 0)
		{
			foreach($armia_walka as $keys)
			{
				$hp_armia+=$keys['zycie_walka'];
			}
		}
		//----SPRAWDZANIE LICZNIKA ATAKU----
		$licznik_ataku_armia = 0;
		foreach($armia_walka as $keys)
		{
			$licznik_ataku_armia+=$keys['odbyty_atak'];
		}
		$licznik_ataku_potwory = 0;
		foreach($potwory_walka as $keys)
		{
			$licznik_ataku_potwory+=$keys['odbyty_atak'];
		}
		//-----RESET LICZNIKA ATAKU-----
		if(($licznik_ataku_armia == 0) && ($licznik_ataku_potwory == 0))
		{
			foreach($armia_walka as $key => $value)
			{
				$armia_walka[$key]['odbyty_atak'] = 1;
			}
			foreach($potwory_walka as $key => $value)
			{
				$potwory_walka[$key]['odbyty_atak'] = 1;
			}
		}
	}
	return $raport_to_send;
}
?>