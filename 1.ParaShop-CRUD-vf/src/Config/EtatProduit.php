<?php

namespace App\Config;

//this class is defined to perform the enum type
//Be careful ! This only works in php 8.1 >
//If this caused any problems make sure to check your php -version
// with love farouk <3
enum EtatProduit: string
{
    case New = 'New';
    case UsedNEW = 'Used-New';
    case UsedGood = 'Used-Good';
}