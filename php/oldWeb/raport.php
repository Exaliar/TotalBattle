<?php
session_start();
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
function kolor($lvl)
{
	switch ($lvl)
	{
		case 0:
		{
			 return "#444444";
			 break;
		}
		case 1:
		{
			 return "#C0C0C0";
			 break;
		}
		case 2:
		{
			 return "#008000";
			 break;
		}
		case 3:
		{
			 return "#0000FF";
			 break;
		}
		case 4:
		{
			 return "#800080";
			 break;
		}
		case 5:
		{
			 return "#FF6600";
			 break;
		}
		case 6:
		{
			 return "#CC0000";
			 break;
		}
		case 7:
		{
			 return "#FFCC00";
			 break;
		}
		default:
		{
			 return "lightgray";
			 break;
		}
	}
}
//****************************WYPISANIE RAPORTU BITWY**************
//*****************OBLICZANIE CALKOWITEGO HP ARMIA/POTWORY*******************
if((isset($_SESSION['potwory_walka'][0]['zycie_walka'])) && (isset($_SESSION['armia_walka'][0]['zycie_walka']))){
	$hp_potwory = 0;
	$potwory_walka = $_SESSION['potwory_walka'];
	$potwory_walka = sort_val($potwory_walka, 'atak_sila');
	foreach($potwory_walka as $keys)
	{
		$hp_potwory+=$keys['zycie_walka'];
	}
	$hp_armia = 0;
	$armia_walka = $_SESSION['armia_walka'];
	$armia_walka = sort_val($armia_walka, 'atak_sila');
	$licz_armia = count($armia_walka);
	if($licz_armia > 0)
	{
		foreach($armia_walka as $keys)
		{
			$hp_armia+=$keys['zycie_walka'];
		}
	}
	//echo "HP Potwory: $hp_potwory HP Armia: $hp_armia"; // do usuniecia
	//Pierwszy warunek HP
	//0 = potwory, 1 = gracz --- KTO PIERWSZY ATAKUJE
	$kolejka = 0;
	if(isset($_POST['gracz']))
	{
		if($_POST['gracz'] == true)
		$kolejka = 1;
	}
	$nr_ataku = 1;
	echo '<h3>Raport</h3>';
	echo '<table id="tabela">';
	echo "<tbody>";
	echo '<tr style="background-color: orange">';
	echo '<th style="width: 30px">Lp.</th>';
	echo '<th style="width: 150px; text-align: center;">Gracz</th>';
	echo '<th style="width: 50px; text-align: center;">Atak</th>';
	echo '<th style="width: 150px; text-align: center;">Potwor</th>';
	echo '<th style="width: 200px; text-align: center;">Opis</th>';
	echo '</tr>';
	while((0 < $hp_armia) && (0 < $hp_potwory))
	{
		$bonsu = 0;
		$atakowana_jednostka = 0;
		$atak = 0;
		$licznik_armia = 0;
		$licznik_potwory = 0;
		//----- ATAK GRACZ ATAKUJE-----
		if($kolejka > 0)
		{
			//*****CZYSZCZENIE I PONOWNE UZUPELNIENIE ARMII SORTOWANIE MALEJACO**********
			$czysc = $armia_walka;
			unset($armia_walka);
			$armia_walka = [];
			foreach($czysc as $key)
			{
				$armia_walka[]=$key;
			}
			unset($czysc);
			$ile_armia = count($armia_walka);
			if($ile_armia > 1)
			{
				$armia_walka = sort_val($armia_walka, 'atak_sila');
			}
			//***************************************************************************
			$licznik_ataku_armia = 0;
			foreach($armia_walka as $keys)
			{
				$licznik_ataku_armia+=$keys['odbyty_atak'];
			}
			if($licznik_ataku_armia > 0)
			{
				while(($licznik_armia < count($armia_walka)) && ($atak == 0))
				{
					if($armia_walka[$licznik_armia]['odbyty_atak'] == 1)
					{
						$atak = $armia_walka[$licznik_armia]['atak_sila'];
						//wypisanie aktualnie atakujacych jednostek
						echo '<tr>';
						echo '<td>';
						echo $nr_ataku.". ";
						echo '</td>';
						$nr_ataku++;
						echo '<td style="text-align: center; background-color: '.kolor($armia_walka[$licznik_armia]['lvl']).'">'.$armia_walka[$licznik_armia]['nazwa']." ".$armia_walka[$licznik_armia]['lvl']."<br/>".$armia_walka[$licznik_armia]['ile_jednostek'].'szt.</td><td style="text-align: center; background-color: #86ff77"> --> </td>';
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
							echo '<td style="text-align: center; background-color: '.kolor($potwory_walka[$atakowana_jednostka]['lvl']).'">';
							echo $potwory_walka[$atakowana_jednostka]['nazwa']."<br/>".$potwory_walka[$atakowana_jednostka]['ile_jednostek']."szt.</td>";
							$atak_z_bonem = $armia_walka[$licznik_armia]['ile_jednostek'] * (($armia_walka[$licznik_armia]['sila']/100) * (100 + $armia_walka[$licznik_armia]['bonus_ataku'] + $bonsu));
							echo "<td>Cios za: ";
							$obr2_potwory = $potwory_walka[$atakowana_jednostka]['zycie_walka'];
							$potwory_walka[$atakowana_jednostka]['zycie_walka'] -= $atak_z_bonem;
							$armia_walka[$licznik_armia]['odbyty_atak'] = 0;
							if($potwory_walka[$atakowana_jednostka]['zycie_walka'] <= 0)
							{
								echo $obr2_potwory;
								echo "<br/>Zginelo: ".$potwory_walka[$atakowana_jednostka]['ile_jednostek']."szt.";
								unset($potwory_walka[$atakowana_jednostka]);
							}
							else
							{
								echo $atak_z_bonem;
								echo "<br/>Zginelo: ".($potwory_walka[$atakowana_jednostka]['ile_jednostek']-($potwory_walka[$atakowana_jednostka]['ile_jednostek'] - ($potwory_walka[$atakowana_jednostka]['ile_jednostek'] - (ceil($potwory_walka[$atakowana_jednostka]['zycie_walka'] / $potwory_walka[$atakowana_jednostka]['zycie'])))))."szt.";
								$potwory_walka[$atakowana_jednostka]['ile_jednostek'] = ceil($potwory_walka[$atakowana_jednostka]['zycie_walka'] / $potwory_walka[$atakowana_jednostka]['zycie']);
								$potwory_walka[$atakowana_jednostka]['atak_sila'] = $potwory_walka[$atakowana_jednostka]['ile_jednostek'] * $potwory_walka[$atakowana_jednostka]['sila'];
							}
							echo "</td>";
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
							//echo $licznik_potwory." "; //do usuniecia
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
												//echo $atak1." "; //do usuniecia
												//echo $i." "; //do usuniecia
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
							echo '<td style="text-align: center; background-color: '.kolor($potwory_walka[$max_index_potwora]['lvl']).'">';
							echo $potwory_walka[$max_index_potwora]['nazwa']."<br/>".$potwory_walka[$max_index_potwora]['ile_jednostek']."szt.</td>";
							//echo $atak_bez_bona; //do usuniecia
							echo "<td>Cios za: ";
							$obr_potwory = $potwory_walka[$max_index_potwora]['zycie_walka'];
							$potwory_walka[$max_index_potwora]['zycie_walka'] -= $atak_bez_bona;
							$armia_walka[$licznik_armia]['odbyty_atak'] = 0;
							if($potwory_walka[$max_index_potwora]['zycie_walka'] <= 0)
							{
								echo $obr_potwory;
								echo "<br/>Zginelo: ".$potwory_walka[$max_index_potwora]['ile_jednostek']."szt.";
								unset($potwory_walka[$max_index_potwora]);
							}
							else
							{
								echo $atak_bez_bona;
								echo "<br>Zginelo: ".($potwory_walka[$max_index_potwora]['ile_jednostek']-($potwory_walka[$max_index_potwora]['ile_jednostek'] - ($potwory_walka[$max_index_potwora]['ile_jednostek'] - (ceil($potwory_walka[$max_index_potwora]['zycie_walka'] / $potwory_walka[$max_index_potwora]['zycie'])))))."szt.";
								$potwory_walka[$max_index_potwora]['ile_jednostek'] = ceil($potwory_walka[$max_index_potwora]['zycie_walka'] / $potwory_walka[$max_index_potwora]['zycie']);
								$potwory_walka[$max_index_potwora]['atak_sila'] = $potwory_walka[$max_index_potwora]['ile_jednostek'] * $potwory_walka[$max_index_potwora]['sila'];
							}
							echo "</td>";
						}
						break;
					}
					else
					{
						$licznik_armia++;
					}
				}
			}
			echo '</tr>';
			$kolejka = 0;
			$licznik_armia = 0;
			$atak = 0;
		}
		//-----ATAK POTWOR ATAKUJE----------------------------------------------------------------------------------------------------
		else
		{
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
						echo '<tr>';
						echo '<td>';
						echo $nr_ataku.". ";
						echo '</td>';
						$nr_ataku++;
						$atak_html = '<td style="text-align: center; background-color: #ff7777"> <-- </td><td style="text-align: center; background-color: '.kolor($potwory_walka[$licznik_potwory]['lvl']).'">'.$potwory_walka[$licznik_potwory]['nazwa']."<br/>".$potwory_walka[$licznik_potwory]['ile_jednostek']."szt.</td>";
						
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
							echo '<td style="text-align: center; background-color: '.kolor($armia_walka[$atakowana_jednostka]['lvl']).'">'.$armia_walka[$atakowana_jednostka]['nazwa']." ".$armia_walka[$atakowana_jednostka]['lvl']."<br/>".$armia_walka[$atakowana_jednostka]['ile_jednostek']."szt.</td>";
							echo $atak_html;
							$atak_z_bonem = $potwory_walka[$licznik_potwory]['ile_jednostek'] * (($potwory_walka[$licznik_potwory]['sila']/100) * (100 + $bonsu));
							echo "<td>Cios za: ";
							$obr_armia = $armia_walka[$atakowana_jednostka]['zycie_walka'];
							$armia_walka[$atakowana_jednostka]['zycie_walka'] -= $atak_z_bonem;
							$potwory_walka[$licznik_potwory]['odbyty_atak'] = 0;
							if($armia_walka[$atakowana_jednostka]['zycie_walka'] <= 0)
							{
								echo $obr_armia;
								echo "<br/>Zginelo: ".$armia_walka[$atakowana_jednostka]['ile_jednostek']."szt.";
								unset($armia_walka[$atakowana_jednostka]);
							}
							else
							{
								echo $atak_z_bonem;
								echo "<br/>Zginelo: ".($armia_walka[$atakowana_jednostka]['ile_jednostek']-($armia_walka[$atakowana_jednostka]['ile_jednostek'] - ($armia_walka[$atakowana_jednostka]['ile_jednostek'] - (ceil($armia_walka[$atakowana_jednostka]['zycie_walka'] / (($armia_walka[$atakowana_jednostka]['zycia']/100)*(100+$armia_walka[$atakowana_jednostka]['bonus_zycia'])))))))."szt.";
								$armia_walka[$atakowana_jednostka]['ile_jednostek'] = ceil($armia_walka[$atakowana_jednostka]['zycie_walka'] / (($armia_walka[$atakowana_jednostka]['zycia']/100)*(100+$armia_walka[$atakowana_jednostka]['bonus_zycia'])));
								$armia_walka[$atakowana_jednostka]['atak_sila'] = $armia_walka[$atakowana_jednostka]['ile_jednostek'] * (($armia_walka[$atakowana_jednostka]['sila']/100)*(100+$armia_walka[$atakowana_jednostka]['bonus_ataku']));
							}
							echo "</td>";
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
							echo '<td style="text-align: center; background-color: '.kolor($armia_walka[$max_index_potwora]['lvl']).'">'.$armia_walka[$max_index_potwora]['nazwa']." ".$armia_walka[$max_index_potwora]['lvl']."<br/>".$armia_walka[$max_index_potwora]['ile_jednostek']."szt.</td>";
							echo $atak_html;
							$atak_bez_bona = $potwory_walka[$licznik_potwory]['ile_jednostek'] * $potwory_walka[$licznik_potwory]['sila'];
							echo "<td>Cios za: ";
							$obr2_armia = $armia_walka[$max_index_potwora]['zycie_walka'];
							$armia_walka[$max_index_potwora]['zycie_walka'] -= $atak_bez_bona;
							$potwory_walka[$licznik_potwory]['odbyty_atak'] = 0;
							if($armia_walka[$max_index_potwora]['zycie_walka'] <= 0)
							{
								echo $obr2_armia;
								echo "<br/>Zginelo: ".$armia_walka[$max_index_potwora]['ile_jednostek']."szt.";
								unset($armia_walka[$max_index_potwora]);
							}
							else
							{
								echo $atak_bez_bona;
								echo "<br/>Zginelo: ".($armia_walka[$max_index_potwora]['ile_jednostek']-($armia_walka[$max_index_potwora]['ile_jednostek'] - ($armia_walka[$max_index_potwora]['ile_jednostek'] - (ceil($armia_walka[$max_index_potwora]['zycie_walka'] / (($armia_walka[$max_index_potwora]['zycia']/100)*(100+$armia_walka[$max_index_potwora]['bonus_zycia'])))))))."szt.";
								$armia_walka[$max_index_potwora]['ile_jednostek'] = ceil($armia_walka[$max_index_potwora]['zycie_walka'] / (($armia_walka[$max_index_potwora]['zycia']/100)*(100+$armia_walka[$max_index_potwora]['bonus_zycia'])));
								$armia_walka[$max_index_potwora]['atak_sila'] = $armia_walka[$max_index_potwora]['ile_jednostek'] * (($armia_walka[$max_index_potwora]['sila']/100)*(100+$armia_walka[$max_index_potwora]['bonus_ataku']));
							}
							echo "</td>";
						}
						break;
					}
					else
					{
						$licznik_potwory++;
					}
				}
			}
			echo '</tr>';
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
	echo "</tbody>";
	echo '</table>';
	echo "<br><br>";
}
?>