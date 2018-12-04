<?php

if (!defined('ABSPATH')) { exit; }

if (!class_exists('DHLPWC_Model_Meta_Order_Option_Preference')) :

class DHLPWC_Model_Meta_Order_Option_Preference extends DHLPWC_Model_Meta_Abstract
{

    // Required delivery method option
    const OPTION_PS = 'PS'; // Delivery to the specified DHL Parcelshop or DHL Parcelstation
    const OPTION_DOOR = 'DOOR'; // Delivery to the address of the recipient
    const OPTION_BP = 'BP'; // Mailbox delivery
    const OPTION_H = 'H'; // Hold for collection (Terminal)

    // Additional delivery option
    const OPTION_COD_CASH = 'COD_CASH'; // Cash on delivery. Payment method cash.
    const OPTION_EXP = 'EXP'; // Expresser
    const OPTION_BOUW = 'BOUW'; // Delivery to construction site
    const OPTION_REFERENCE2 = 'REFERENCE2'; // Reference
    const OPTION_EXW = 'EXW'; // Ex Works
    const OPTION_EA = 'EA'; // Increased liability
    const OPTION_EVE = 'EVE'; // Evening delivery
    const OPTION_RECAP = 'RECAP'; // Recap
    const OPTION_COD_CHECK = 'COD_CHECK'; // Cash on delivery. Payment method check.
    const OPTION_INS = 'INS'; // All risks insurance
    const OPTION_REFERENCE = 'REFERENCE'; // Reference
    const OPTION_HANDT = 'HANDT'; // Signature on delivery
    const OPTION_NBB = 'NBB'; // No neighbour delivery
    const OPTION_ADD_RETURN_LABEL = 'ADD_RETURN_LABEL'; // Print extra label for return shipment
    const OPTION_SSN = 'SSN'; // Undisclosed sender
    const OPTION_PERS_NOTE = 'PERS_NOTE'; // E-mail to receiver
    const OPTION_SDD = 'SDD'; // Same-day delivery
    const OPTION_S = 'S'; // Saturday delivery
    const OPTION_IS_BULKY = 'IS_BULKY'; // Piece is bulky

    // TODO Temp
    const INPUT_NUMBER = 'number';
    const INPUT_TEXT = 'text';
    const INPUT_ADDRESS = 'address';

    public $key;
    public $input;
    public $input_type;

}

endif;
