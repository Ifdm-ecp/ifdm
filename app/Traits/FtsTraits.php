<?php
namespace App\Traits;



Class FtsTraits
{
	 static function CIN($ty)
	 {
	 	if($ty<=200)
		{
			return '- 0.1%-1% Corrosion Inhibitor';
		}else if($ty>200){
			return '- 0.2%-3% Iodized Salt <br> - 0.5%-5% Formic Acid';
		}
	 }


	 static function CFe($hematite, $magnetite, $clays,$ty,$pyrite,$siderite,$pyrrhotite,$zeolites, $chamosite)
	 {
	 	if($hematite+$magnetite)<5 || $chamosite>5&&($ty>225)&&($ty<300)
		    disp ('      - 10% Acetic Acid')
		       
		    if (Clays>5)
		        disp ('      - 5% NH4Cl')
		    end

		end
	 }
}